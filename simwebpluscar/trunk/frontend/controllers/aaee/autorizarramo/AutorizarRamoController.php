<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *	> This library is free software; you can redistribute it and/or modify it under
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
 *	> any later version.
 *  >
 *	> This library is distributed in the hope that it will be usefull,
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *	> for more details.
 *  >
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *	@file CorreccionCapitalController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 30-07-2016
 *
 *  @class CorreccionCapitalController
 *	@brief Clase CorreccionCapitalController del lado del contribuyente frontend.
 *
 *
 *	@property
 *
 *
 *	@method
 *
 *
 *	@inherits
 *
 */


 	namespace frontend\controllers\aaee\autorizarramo;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\documento\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\enviaremail\PlantillaEmail;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use backend\models\aaee\autorizarramo\AutorizarRamoSearch;
	use backend\models\aaee\autorizarramo\AutorizarRamoForm;
	use backend\models\aaee\rubro\Rubro;
	use backend\models\aaee\actecon\ActEconForm;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;

	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de Auto.rizacion de Ramos
	 * Solicitud que se realizara del lado del contribuyente (frontend). Se mostrara una vista
	 * previa de la solicitud realizada por el contribuyente y se le indicara al contribuyente
	 * que confirme la operacion o retorne a la vista inicial donde cargo la informacion para su
	 * ajuste. Cuando el contribuyente confirme su intencion de crear la solicitud, es cuando
	 * se guardara en base de datos.
	 */
	class AutorizarRamoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';

		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 96;


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'conf', 'arrayIdRubros']);
			$request = Yii::$app->request;
			$getData = $request->get();

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					$idContribuyente = $_SESSION['idContribuyente'];
					$searchRamo = New AutorizarRamoSearch($idContribuyente);

					// Se verifica que el contribuyente sea la sede principal.
					if ( $searchRamo->getSedePrincipal() ) {

						// Se determina si ya existe una solicitud pendiente.
						if ( !$searchRamo->yaPoseeSolicitudSimiliarPendiente() ) {

							// Se determina si el contribuyente posee registros en las entidades
							// relacionadas a la declaracion.
							if ( !$searchRamo->tieneRecordActEcon() ) {

								$modelParametro = New ParametroSolicitud($id);
								// Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
								// del parametro y el valor del elemento es el contenido del campo en base de datos.
								$config = $modelParametro->getParametroSolicitud([
																		'id_config_solicitud',
																		'tipo_solicitud',
																		'impuesto',
																		'nivel_aprobacion'
															]);

								if ( isset($config) ) {
									$_SESSION['conf'] = $config;
									$_SESSION['begin'] = 1;
									$this->redirect(['index-create']);
								} else {
									// No se obtuvieron los parametros de la configuracion.
									return $this->redirect(['error-operacion', 'cod' => 955]);
								}
							} else {
								// Tiene registros cargados en las entidades relacionadas a la declaracion.
								// No aplica para este tipo de solicitud.
								return $this->redirect(['error-operacion', 'cod' => 404]);
							}
						} else {
							// El contribuyente ya posee una solicitud similar, y la misma esta pendiente.
							return $this->redirect(['error-operacion', 'cod' => 945]);
						}

					} else {
						// El contribuyente no es la sede principal
						return $this->redirect(['error-operacion', 'cod' => 934]);
					}
				} else {
					// No esta defino el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 932]);
				}
			} else {
				// Parametro de configuracion no coinciden.
				return $this->redirect(['error-operacion', 'cod' => 955]);
			}
		}




		/**
		 * Metodo que inicia la carga del formulario que permite realizar la solicitud
		 * de autorizacion de ramos. Tambien gestiona la ejecucion de las reglas
		 * de validacion del formulario.
		 * @return view
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			$params = '';
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				$model = New AutorizarRamoForm();
				$formName = $model->formName();
				$model->scenario = self::SCENARIO_FRONTEND;
				$validateRubroSeleccionado = false;

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$model->load($postData);
					}
				}

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

//die(var_dump($postData));

				if ( isset($postData['btn-search']) ) {
					if ( $postData['btn-search'] == 1 ) {
						$params = $postData['inputSearch'];
					}

			    } elseif ( isset($postData['btn-add-category']) ) {
			    	if ( $postData['btn-add-category'] == 2 ) {
			    		// Se obtiene el arreglo de rubros seleccionado para ser incluidos
			    		// en la lista.
			    		$arregloIdRubro = isset($postData['chkRubro']) ? $postData['chkRubro'] : [];
			    		self::actionAddRubro($arregloIdRubro);
					}

			    } elseif ( isset($postData['btn-remove-category']) ) {
			    	if ( $postData['btn-remove-category'] == 3 ) {
						$arregloIdRubro = isset($postData['chkRubroSeleccionado']) ? $postData['chkRubroSeleccionado'] : [];
						self::actionRemoveRubro($arregloIdRubro);
					}
				} else {
					$arregloRubro = isset($_SESSION['arrayIdRubros']) ? $_SESSION['arrayIdRubros'] : [];
					if ( count($arregloRubro) > 0 ) { $validateRubroSeleccionado = true; }
			   		if ( $model->load($postData) ) {
			      		if ( $model->validate() && $validateRubroSeleccionado ) {

		      				// Validacion correcta.
		      				if ( isset($postData['btn-create']) ) {
		      					if ( $postData['btn-create'] == 4 ) {

		      						$datosRecibido = $postData[$formName];

		      						$searchRamo = New AutorizarRamoSearch($idContribuyente);
		      						$dataProviderSeleccionado = $searchRamo->getDataProviderAddRubro($arregloRubro);
		      						$caption = Yii::t('frontend', 'Confirm Create. Autorizar Ramo');
		      						$subCaption = Yii::t('frontend', 'Info of Taxpayer');


		      						return $this->render('/aaee/autorizar-ramo/pre-view-create', [
		      																	'model' => $model,
		      																	'datosRecibido' => $datosRecibido,
		      																	'dataProvider' => $dataProviderSeleccionado,
		      																	'subCaption' => $subCaption,
		      																	'caption' => $caption,
		      							]);
		      					}
		      				} elseif ( isset($postData['btn-confirm-create']) ) {
		      					if ( $postData['btn-confirm-create'] == 5 ) {
//die(var_dump($model));
		      						$result = self::actionBeginSave($model, $postData);
		      						self::actionAnularSession(['begin', 'arrayIdRubros']);
		      						if ( $result ) {
										$this->_transaccion->commit();
										return self::actionView($model->nro_solicitud);
									} else {
										$this->_transaccion->rollBack();
										$this->redirect(['error-operacion', 'cod'=> 920]);

		      						}
		      					}
		      				}
				      	}
			      	}
			    }

		      	// Se muestra el form de la solicitud.
		      	// Datos generales del contribuyente.
		      	$searchRamo = New AutorizarRamoSearch($idContribuyente);
		      	$datos = $searchRamo->getDatosContribuyente();
		  		if ( isset($datos) ) {
		  			// Se buscan las sucursales. Partiendo del identificador de la sede principal
		  			// utilizando el rif de la sede principal se buscan los demas registros que
		  			// coincidan con este. Se realiza un filtrado para obtener solo los identificadores
		  			// de los registros (id-contribuyente), para luego utilizarlos en la generacion del
		  			// dataproviver.
		  			//$ids = $searchCorreccion->getIdSucursales();
		  			//if ( count($ids) > 0 ) {
		  			//	$dataProvider = $searchCorreccion->getDataProviderSucursal($ids);
		  			//}

		  			$arregloRubro = isset($_SESSION['arrayIdRubros']) ? $_SESSION['arrayIdRubros'] : [];

					$activarBotonCreate = 0;
			  		if ( count($arregloRubro) > 0 ) {
			  			$activarBotonCreate = 1;
			  			$validateRubroSeleccionado = true;
			  		}
			  		$errorRubroSeleccionado = '';
		  			if ( !$validateRubroSeleccionado ) {
		  				// Mensaje que indica que no se haincluido ningun rubro en la lista.
		  				$errorRubroSeleccionado = Yii::t('frontend', 'Category not select');
		  			}

		  			$añoInicio = $searchRamo->getAnoSegunFecha($datos['fecha_inicio']);
		  			$añoCatalogo = $searchRamo->determinarAnoCatalogoSegunAnoInicio($añoInicio);
		  			$añoVenceOrdenanza = $searchRamo->getVencimientoOrdenanza($añoCatalogo);
		  			$dataProvider = $searchRamo->getDataProvider($añoCatalogo, $params);
		  			$periodo = 1;
		  			$rangoFecha = $searchRamo->getRangoFechaDeclaracion($añoCatalogo);

		  			$fechaDesde = $rangoFecha['fechaDesde'];
		  			$fechaHasta = $rangoFecha['fechaHasta'];

		  			// Rubros seleccionados
		  			$dataProviderSeleccionado = $searchRamo->getDataProviderAddRubro($arregloRubro);

		  			$subCaption = Yii::t('frontend', 'Info of Taxpayer');
		  			return $this->render('/aaee/autorizar-ramo/_create', [
					  											'model' => $model,
					  											'datos' => $datos,
					  											'subCaption' => $subCaption,
					  											'añoCatalogo' => $añoCatalogo,
					  											'añoVenceOrdenanza' => $añoVenceOrdenanza,
					  											'dataProvider' => $dataProvider,
					  											'searchModel' => $searchRamo,
					  											'dataProviderSeleccionado' => $dataProviderSeleccionado,
					  											'periodo' => $periodo,
					  											'fechaDesde' => $fechaDesde,
						        								'fechaHasta' => $fechaHasta,
						        								'activarBotonCreate' => $activarBotonCreate,
						        								'errorRubroSeleccionado' => $errorRubroSeleccionado,

					  					]);
		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}




		/**
		 * Metodo que agrega el o los rubros seleccionados en una lista previa.
		 * @param  array $arregloIdRubro arreglo de identificadores del rubro
		 * seleccionado para incluir. El arreglo que se envia se agrega a los
		 * existentes de haberlos. El resultado final de arreglos se guarda en
		 * una variable de session.
		 * @return not
		 */
		public function actionAddRubro($arregloIdRubro)
		{
			if ( count($arregloIdRubro) > 0 ) {
    			foreach ( $arregloIdRubro as $key => $value ) {
    				if ( !isset($_SESSION['arrayIdRubros']) ) {
		    			$_SESSION['arrayIdRubros'][] = $value;
		    		} else {
		    			$arrayIdRurbros = [];
		    			$arrayIdRurbros = $_SESSION['arrayIdRubros'];
		    			if ( !in_array($value, $arrayIdRurbros) ) {
		    				$_SESSION['arrayIdRubros'][] = $value;
		    			}
		    		}
	    		}
    		}
		}




		/**
		 * Metodo que crea un arreglo nuevo de identificadores de los rubros
		 * para excluir auqellos que fueron enviados para su eliminacion. Lo
		 * se busca es suprimir del arreglo principal, aquellos identificadores
		 * que fueron enviados. Al final el nuevo arreglo se salva en una variable
		 * de session.
		 * @param  array $arregloIdRubro arreglo de identificadores de los rubros.
		 * @return not
		 */
		public function actionRemoveRubro($arregloIdRubro)
		{
			$nuevoArray = [];
			$indiceNoVa = [];
			if ( count($arregloIdRubro) > 0 ) {
				if ( isset($_SESSION['arrayIdRubros']) ) {
					// Arreglo de los identificadores existente en la lista.
					$arreglo = $_SESSION['arrayIdRubros'];

					foreach ( $arreglo as $key => $value) {
						if ( in_array($value, $arregloIdRubro) ) {
							$indiceNoVa[] = $value;
						} else {
							$nuevoArray[] = $value;
						}
					}
				}
				if ( count($indiceNoVa) > 0 ) {
					self::actionAnularSession(['arrayIdRubros']);
					$_SESSION['arrayIdRubros'] = $nuevoArray;
				}
			}
		}




		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $model modelo de CorreccionCapitalForm.
		 * @param array $postEnviado post enviado desde el formulario.
		 * @return boolean retorna true si se realizan todas las operacions de
		 * insercion y actualizacion con exitos o false en caso contrario.
		 */
		private function actionBeginSave($model, $postEnviado)
		{
			$result = false;
			$nroSolicitud = 0;
//die(var_dump($model));
			if ( isset($_SESSION['idContribuyente']) ) {
				if ( isset($_SESSION['conf']) ) {
					$conf = $_SESSION['conf'];
					$chkSeleccion = $postEnviado['chkRubroSeleccionado'];

					$this->_conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->_conn = $this->_conexion->initConectar('db');
	      			$this->_conn->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();

					$nroSolicitud = self::actionCreateSolicitud($this->_conexion,
															    $this->_conn,
															    $model,
															    $conf);
					if ( $nroSolicitud > 0 ) {
						$model->nro_solicitud = $nroSolicitud;

						$result = self::actionCreateAutorizarRamo($this->_conexion,
																	  $this->_conn,
																	  $model,
																	  $conf,
																	  $chkSeleccion);

						if ( $result ) {
							if ( $conf['nivel_aprobacion'] == 1 ) {
								$result = self::actionIniciarCicloAnoImpositivo($this->_conexion,
																		        $this->_conn,
																		        $model,
																		        $chkSeleccion);
							}

							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($this->_conexion, $this->_conn, $model, $conf);

								if ( $result ) {
									$result = self::actionEnviarEmail($model, $conf, $chkSeleccion);
									$result = true;
								}
							}
						}
					}

				} else {
					// No se obtuvieron los parametros de la configuracion.
					$this->redirect(['error-operacion', 'cod' => 955]);
				}
			} else {
				// No esta defino el contribuyente.
				$this->redirect(['error-operacion', 'cod' => 932]);
			}
			return $result;
		}




		/**
		 * Metodo que guarda el registro respectivo en la entidad "solicitudes-contribuyente".
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection.
		 * @param  model $model modelo de AutorizarRamoForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna true si guardo correctamente o false sino guardo.
		 */
		private function actionCreateSolicitud($conexionLocal, $connLocal, $model, $conf)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$user = isset($model->usuario) ? $model->usuario : null;
			$nroSolicitud = 0;
			$modelSolicitud = New SolicitudesContribuyenteForm();
			$tabla = $modelSolicitud->tableName();
			$idContribuyente = $_SESSION['idContribuyente'];

			$nroSolicitud = 0;

			if ( count($conf) > 0 ) {
				// Valores que se pasan al modelo:
				// id-config-solicitud.
				// impuesto.
				// tipo-solicitud.
				// nivel-aprobacion
				$modelSolicitud->attributes = $conf;

				if ( $conf['nivel_aprobacion'] == 1 ) {
					$estatus = 1;
					$userFuncionario = $user;
					$fechaHoraProceso = date('Y-m-d H:i:s');
				}

				$modelSolicitud->id_contribuyente = $idContribuyente;
				$modelSolicitud->id_impuesto = 0;
				$modelSolicitud->usuario = $user;
				$modelSolicitud->fecha_hora_creacion = date('Y-m-d H:i:s');
				$modelSolicitud->inactivo = 0;
				$modelSolicitud->estatus = $estatus;
				$modelSolicitud->nro_control = 0;
				$modelSolicitud->user_funcionario = $userFuncionario;
				$modelSolicitud->fecha_hora_proceso = $fechaHoraProceso;
				$modelSolicitud->causa = 0;
				$modelSolicitud->observacion = '';

				// Arreglo de datos del modelo para guardar los datos.
				$arregloDatos = $modelSolicitud->attributes;

				if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
					$nroSolicitud = $connLocal->getLastInsertID();
				}
			}

			return $nroSolicitud;
		}




		/**
		 * Metodo que guarda el registro detalle de la solicitid en la entidad
		 * "sl" respectiva.
		 * @param  ConexionController $conexionLocal instancia de la lcase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de CorreccionAutorizarRamoForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @param  array $chkSeleccion arreglo que contiene los identificadores de los contribuyentes
		 * a los cuales se se les actualizara el capital.
		 * @return boolean retorna un true si guardo el registro, false en caso contrario.
		 */
		private static function actionCreateAutorizarRamo($conexionLocal, $connLocal, $model, $conf, $chkSeleccion)
		{
			$result = false;
			$estatus = 0;
			$user = isset($model->usuario) ? $model->usuario : null;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			if ( isset($conexionLocal) && isset($connLocal) && isset($model) ) {
				if ( count($conf) > 0 ) {
					if ( $conf['nivel_aprobacion'] == 1 ) {
						$estatus = 1;
						$userFuncionario = $user;
						$fechaHoraProceso = date('Y-m-d H:i:s');
					}

					$tabla = '';
	      			$tabla = $model->tableName();

	      			// $model->attributes es array {
	      			// 							[attribute] => valor
	      			// 						}
					$arregloDatos = $model->attributes;

					$arregloDatos['estatus'] = $estatus;
					$arregloDatos['user_funcionario'] = $userFuncionario;
					$arregloDatos['fecha_hora_proceso'] = $fechaHoraProceso;

					$model->estatus = $estatus;
					$model->user_funcionario = $userFuncionario;

					foreach ( $chkSeleccion as $key => $value ) {
						$arregloDatos['id_rubro'] = $value;
						$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);
						if ( !$result ) { break; }
					}

				}
			}
			return $result;
		}


		/***/
		private function actionIniciarCicloAnoImpositivo($conexionLocal, $connLocal, $model, $chkSeleccion)
		{
			$result = false;
			$idImpuesto = 0;
			$listaIdRubro = [];
			$searchModel = New AutorizarRamoSearch($model['id_contribuyente']);

			try {
				for ( $i = $model->ano_impositivo; $i <= $model->ano_hasta ; $i++ ) {
					$idImpuesto = self::actionCreateActEcon($conexionLocal, $connLocal, $model, $i);
					if ( $idImpuesto == 0 ) {
						$result = false;
					} else {
						if ( $i == $model->ano_impositivo ) {
							$listaIdRubro = $chkSeleccion;
						} else {
							$listaIdRubro = $searchModel->getListaRubro($chkSeleccion, $i);
						}
						if ( count($listaIdRubro) > 0 ) {
							$result = self::actionCreateActEconIngresos($conexionLocal, $connLocal,
																		$model, $idImpuesto, $listaIdRubro);
						} else {
							$result = false;
						}
					}
					if ( !$result ) { break; }
				}
			} catch ( Exception $e )  {
				$result = false;
				echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			}
			return $result;
		}






		/***/
		private static function actionCreateActEcon($conexionLocal, $connLocal, $model, $añoImpositivo)
    	{
    		$idImpuesto = 0;
    		$exigDeclaracion = 0;
    		if ( isset($_SESSION['idContribuyente']) && isset($connLocal)  ) {
    			if ( $_SESSION['idContribuyente'] == $model['id_contribuyente'] ) {

    				$searchModel = New AutorizarRamoSearch($model['id_contribuyente']);
    				$exigDeclaracion = $searchModel->getExigibilidadDeclaracionSegunAnoImpositivo($añoImpositivo);
    				if ( $exigDeclaracion > 0 ) {
		    			$modelActEcon = New ActEconForm();

		    			$arregloDatos = $modelActEcon->attributes;
		    			foreach ( $arregloDatos as $key => $value ) {
		    				$arregloDatos[$key] = 0;
		    			}
			    		$arregloDatos['ente'] = Yii::$app->ente->getEnte();
			    		$arregloDatos['id_contribuyente'] = $model['id_contribuyente'];
			    		$arregloDatos['ano_impositivo'] = $añoImpositivo;
			    		$arregloDatos['exigibilidad_declaracion'] = $exigDeclaracion;

			    		// Se procede a guardar en la entidad maestra de las declaraciones.
		      			$tabla = '';
		      			$tabla = $modelActEcon->tableName();

						if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
							$idImpuesto = $connLocal->getLastInsertID();
						}
					}
		    	}
		    }
	    	return $idImpuesto;
	    }



	    /***/
	    private static function actionCreateActEconIngresos($conexionLocal, $connLocal, $model, $idImpuesto, $listaIdRubro)
	    {
	    	$result = false;
	    	if ( isset($_SESSION['idContribuyente']) && isset($connLocal) ) {
	    		if ( $idImpuesto > 0 ) {

		    		if ( count($listaIdRubro) > 0 ) {

			    		$modelActEconIngreso = New ActEconIngresoForm();
			    		$arregloDatos = $modelActEconIngreso->attributes;

			    		foreach ( $arregloDatos as $key => $value ) {
			    			$arregloDatos[$key] = 0;
			    		}
			    		$arregloDatos['id_impuesto'] = $idImpuesto;
			    		$arregloDatos['exigibilidad_periodo'] = $model->periodo;
			    		$arregloDatos['periodo_fiscal_desde'] = $model->fecha_desde;
			    		$arregloDatos['periodo_fiscal_hasta'] = $model->fecha_hasta;
			    		$arregloDatos['fecha_hora'] = $model->fecha_hora;

			    		// Se procede a guardar en la entidad maestra de las declaraciones.
		      			$tabla = '';
		      			$tabla = $modelActEconIngreso->tableName();

		      			foreach ( $listaIdRubro as $key => $value ) {
		      				$arregloDatos['id_rubro'] = $listaIdRubro[$key];
		      				if ( !$conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
								$result = false;
								break;
							} else {
								$result = true;
							}
		      			}
		      		}
	      		}
	    	}
	    	return $result;
	    }





		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  ConexionController  $conexionLocal instancia de la clase ConexionController
		 * @param  connection  $connLocal instancia de connection.
		 * @param  model $model modelo de CorreccionCapitalForm.
		 * @param  array $postEnviado post enviado por el formulario. Lo que
		 * se busca es determinar los items seleccionados como documentos y/o
		 * requisitos a consignar para guardarlos.
		 * @return boolean retorna true si guarda efectivamente o false en caso contrario.
		 */
		private static function actionCreateDocumentosConsignados($conexionLocal, $connLocal, $model, $postEnviado)
		{
			$result = false;
			if ( isset($conexionLocal) && isset($connLocal) && isset($model) && count($postEnviado) > 0 ) {
				$modelDocumento = New DocumentoConsignadoForm();
				$tabla = $modelDocumento->tableName();
				$arregloCampos = $modelDocumento->attributes();

				$datosInsert['id_doc_consignado'] = null;
				$datosInsert['id_documento'] = 0;
				$datosInsert['id_contribuyente'] = $model->id_sede_principal;
				$datosInsert['id_impuesto'] = 0;
				$datosInsert['impuesto'] = 1;
				$datosInsert['nro_solicitud'] = $model->nro_solicitud;
				$datosInsert['codigo_proceso'] = null;
				$datosInsert['fecha_hora'] = $model->fecha_hora;
				$datosInsert['usuario'] = $model->user_funcionario;
				$datosInsert['estatus'] = $model->estatus;

				// Se obtiene el arreglo de el o los items de documentos y/o reuisitos
				// seleccionados. Basicamente lo que se obtiene es el identificador (id_documento)
				// del registro.
				$arregloChkDocumeto = $postEnviado['chkDocumento'];
				if ( count($arregloChkDocumeto) > 0 ) {
					foreach ( $arregloChkDocumeto as $documento ) {
						$datosInsert['id_documento'] = $documento;
						$arregloDatos[] = $datosInsert;
					}

					$result = $conexionLocal->guardarLoteRegistros($connLocal, $tabla, $arregloCampos, $arregloDatos);
				} else {
					$result = true;
				}
			}
			return $result;
		}




		/**
		 * Metodo que se encargara de gestionar la ejecucion y resultados de los procesos relacionados
		 * a la solicitud. En este caso los proceso relacionados a la solicitud en el evento "CREAR".
		 * Se verifica si se ejecutaron los procesos y si los mismos fueron todos positivos. Con
		 * el metodo getAccion(), se determina si se ejecuto algun proceso, este metodo retorna un
		 * arreglo, si el mismo es null se asume que no habia procesos configurados para que se ejecutaran
		 * cuando la solicitud fuese creada. El metodo resultadoEjecutarProcesos(), permite determinar el
		 * resultado de cada proceso que se ejecuto.
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de conexion que permite ejecutar las acciones en base
		 * de datos.
		 * @param  model $model modelo de la instancia CorreccionCapitalForm.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna true si todo se ejecuto correctamente false en caso contrario.
		 */
		private function actionEjecutaProcesoSolicitud($conexionLocal, $connLocal, $model, $conf)
		{
			$result = true;
			$resultadoProceso = [];
			$acciones = [];
			$evento = '';
			if ( count($conf) > 0 ) {
				if ( $conf['nivel_aprobacion'] == 1 ) {
					$evento = Yii::$app->solicitud->aprobar();
				} else {
					$evento = Yii::$app->solicitud->crear();
				}


				$procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

				// Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
				// que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
				// de resultados donde el key del arrary es el nombre del proceso ejecutado y el valor
				// del elemento corresponda a un reultado de la ejecucion. La variable $model debe contener
				// el identificador del contribuyente que realizo la solicitud y el numero de solicitud.
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, $evento, $conexionLocal, $connLocal);

				// Se obtiene un array de acciones o procesos ejecutados. Sino se obtienen acciones
				// ejecutadas se asumira que no se configuraro ningun proceso para que se ejecutara
				// cuando se creara la solicitud.
				$acciones = $procesoEvento->getAccion();

				if ( count($acciones) > 0 ) {

					// Se evalua cada accion o proceso ejecutado para determinar si se realizo satisfactoriamnente.
					$resultadoProceso = $procesoEvento->resultadoEjecutarProcesos();

					if ( count($resultadoProceso) > 0 ) {
						foreach ( $resultadoProceso as $key => $value ) {
							if ( $value == false ) {
								$result = false;
								break;
							}
						}
					}
				}
			} else {
				$result = false;
			}

			return $result;

		}



		/**
		 * Metodo que permite enviar un email al contribuyente indicandole
		 * la confirmacion de la realizacion de la solicitud.
		 * @param  model $model modelo que contiene la informacion
		 * del identificador del contribuyente.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @param  array $chkSeleccion arreglo que contiene los identificadores de los contribuyentes
		 * a los cuales se se les actualizara el capital.
		 * @return boolean retorna un true si envio el correo o false en caso
		 * contrario.
		 */
		private function actionEnviarEmail($model, $conf, $chkSeleccion)
		{
			$result = false;
			$listaDocumento = '';
			if ( count($conf) > 0 ) {
				$parametroSolicitud = New ParametroSolicitud($conf['id_config_solicitud']);
				$nroSolicitud = $model->nro_solicitud;
				$descripcionSolicitud = $parametroSolicitud->getDescripcionTipoSolicitud();
				$listaDocumento = $parametroSolicitud->getDocumentoRequisitoSolicitud();

				$email = ContribuyenteBase::getEmail($model->id_contribuyente);
				try {
					$enviar = New PlantillaEmail();
					$result = $enviar->plantillaEmailSolicitud($email, $descripcionSolicitud, $nroSolicitud, $listaDocumento);
				} catch ( Exception $e ) {
					echo $e->getName();
				}
			}
			return $result;
		}


		/**
		 * Metodo que renderiza una vista con la informacion de la solicitud creada.
		 * @param  loong $id identificador de la solicitud creada.
		 * @return view retorna una vista con la informacion detalle de la solicitud.
		 * Informacion cargada por el contribuyente.
		 */
		public function actionView($id)
    	{
    		if ( isset($_SESSION['idContribuyente']) ) {
	    		if ( $id > 0 ) {
	    			$searchRamo = New AutorizarRamoSearch($_SESSION['idContribuyente']);
	    			$findModel = $searchRamo->findSolicitudAutorizarRamo($id);
	    			$dataProvider = $searchRamo->getDataProviderSolicitud($id);
	    			if ( isset($findModel) ) {
	    				return self::actionShowSolicitud($findModel, $searchRamo, $dataProvider);
	    			} else {
						throw new NotFoundHttpException('No se encontro el registro');
					}
	    		} else {
	    			throw new NotFoundHttpException('Error ' . $id);
	    		}
	    	} else {
	    		throw new NotFoundHttpException('El contribuyente no esta defino');
	    	}
    	}




    	/***/
    	private function actionShowSolicitud($findModel, $modelSearch, $dataProvider)
    	{
    		if ( isset($findModel) && isset($modelSearch) ) {
 				$model = $findModel->all();

				$opciones = [
					'quit' => '/aaee/autorizarramo/autorizar-ramo/quit',
				];
				return $this->render('/aaee/autorizar-ramo/_view', [
																'codigo' => 100,
																'model' => $model,
																'modelSearch' => $modelSearch,
																'opciones' => $opciones,
																'dataProvider' => $dataProvider,
					]);
			} else {
				throw new NotFoundHttpException('No se encontro el registro');
			}
    	}




    	/**
		 * Metodo salida del modulo.
		 * @return view
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/menu/menu-vertical');
		}



		/**
		 * Metodo que ejecuta la anulacion de las variables de session utilizados
		 * en el modulo.
		 * @param  array $varSessions arreglo con los nombres de las variables de
		 * sesion que seran anuladas.
		 * @return none.
		 */
		public function actionAnularSession($varSessions)
		{
			Session::actionDeleteSession($varSessions);
		}



		/**
		 * Metodo que renderiza una vista indicando que le proceso se ejecuto
		 * satisfactoriamente.
		 * @param  integer $cod codigo que permite obtener la descripcion del
		 * codigo de la operacion.
		 * @return view.
		 */
		public function actionProcesoExitoso($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}



		/**
		 * Metodo que renderiza una vista que indica que ocurrio un error en la
		 * ejecucion del proceso.
		 * @param  integer $cod codigo que permite obtener la descripcion del
		 * codigo de la operacion.
		 * @return view.
		 */
		public function actionErrorOperacion($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}



		/**
		 * Metodo que permite obtener un arreglo de las variables de sesion
		 * que seran utilizadas en el modulo, aqui se pueden agregar o quitar
		 * los nombres de las variables de sesion.
		 * @return array retorna un arreglo de nombres.
		 */
		public function actionGetListaSessions()
		{
			return $varSession = [
							'postData',
							'conf',
							'begin',
							'arrayIdRubros',
					];
		}

	}
?>