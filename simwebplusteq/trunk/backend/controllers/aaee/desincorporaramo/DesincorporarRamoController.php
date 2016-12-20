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
 *	@file DesincorporarRamoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 01-09-2016
 *
 *  @class DesincorporarRamoController
 *	@brief Clase DesincorporarRamoController del lado del contribuyente backend.
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


 	namespace backend\controllers\aaee\desincorporaramo;


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
	use backend\models\aaee\desincorporaramo\DesincorporarRamoSearch;
	use backend\models\aaee\desincorporaramo\DesincorporarRamoForm;
	use backend\models\aaee\rubro\Rubro;
	use backend\models\aaee\actecon\ActEconForm;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use backend\models\aaee\acteconingreso\ActEconIngreso;

	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de Desincorporacion de Ramos
	 * Solicitud que se realizara del lado del contribuyente (frontend). Se mostrara una vista
	 * previa de la solicitud realizada por el contribuyente y se le indicara al contribuyente
	 * que confirme la operacion o retorne a la vista inicial donde cargo la informacion para su
	 * ajuste. Cuando el contribuyente confirme su intencion de crear la solicitud, es cuando
	 * se guardara en base de datos.
	 */
	class DesincorporarRamoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';
		const SCENARIO_SEARCH = 'search';
		const SCENARIO_DEFAULT = 'default';

		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 103;


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'conf']);
			$request = Yii::$app->request;
			$getData = $request->get();

			$postData = $request->post();
			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					return $this->redirect(['check', 'id' => $id]);
				} else {
					// No esta definido el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 404]);
				}
			}
		}





		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionCheck($id)
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'conf', 'postSearch']);

			// identificador de la configuracion de la solicitud.
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					$idContribuyente = $_SESSION['idContribuyente'];
					$searchRamo = New DesincorporarRamoSearch($idContribuyente);

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
		 * de anexo de ramos.
		 * @return view
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$errorMensaje = '';
				$msjControls = '';
				self::actionAnularSession(['postSearch']);
				$btnSearchCategory = 0;			// Indica se se activa o no.
				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$model = New DesincorporarRamoForm();
				$formName = $model->formName();
				$model->scenario = self::SCENARIO_SEARCH;

				$caption = Yii::t('frontend', 'Remove Categories');

				// Se muestra el form de la solicitud.
		      	// Datos generales del contribuyente.
		      	$searchRamo = New DesincorporarRamoSearch($idContribuyente);
		      	$findModel = $searchRamo->findContribuyente();

				if ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {

						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {

			    				$msjControls = $searchRamo->validarEvento($model->ano_impositivo, $model->periodo);

			    				if ( count($msjControls) == 0 ) {
			    					$_SESSION['postSearch'] = $postData;
			    					return $this->redirect(['rubro-registrados']);

			    				} else {
			    					$errorMensaje = json_encode($msjControls);
			    				}
							}
						}
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		  		if ( isset($findModel) ) {
		  			$caption = $caption . '. ' . Yii::t('frontend', 'Select Fiscal Lapse');
		  			$listaAño = $searchRamo->getListaAnoRegistrado();
		  			return $this->render('@frontend/views/aaee/desincorpora-ramo/_create', [
			  											'model' => $model,
			  											'findModel' => $findModel,
			  											'listaAño' => $listaAño,
			  											'caption' => $caption,
			  											'errorMensaje' => $errorMensaje,
					  					]);

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}






		/**
		 * Metodo que muetra una vista de los rubros registrados segun el
		 * lapso seleccionado.
		 * @return view
		 */
		public function actionRubroRegistrados()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			$errorChk = '';
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$postSearch = isset($_SESSION['postSearch']) ? $_SESSION['postSearch'] : [];

				$request = Yii::$app->request;
				$postData = $request->post();
				if ( count($postData) == 0 ) {
					$postData = $postSearch;
				}

				$msjControls = [];
				$errorMensaje = '';
				$chkSeleccion = [];
				$chkItems = [];
				$rubroJson = '';
				$rubroSeleccionado = '';

				$model = New DesincorporarRamoForm();
				$formName = $model->formName();
				$model->scenario = self::SCENARIO_FRONTEND;

				$caption = Yii::t('frontend', 'Remove Categories');

				// Se muestra el form de la solicitud.
		      	// Datos generales del contribuyente.
		      	$idContribuyente = $_SESSION['idContribuyente'];
		      	$searchRamo = New DesincorporarRamoSearch($idContribuyente);
		      	$findModel = $searchRamo->findContribuyente();

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 1 ) {
						return $this->redirect(['index-create']);

					} elseif ( $postData['btn-back-form'] == 9 ) {
						if ( isset($postData['chkRubroSeleccionado']) ) {
							$chkRubros = $searchRamo->getConvertirJson($postData['chkRubroSeleccionado']);
							//$chkRubros = $postData['chkRubroSeleccionado'];


							foreach ( $chkRubros as $jsonRubro ) {
								$rubroJson[] = json_decode($jsonRubro);
							}

							// El arreglo de objetos json $rubroJson se obtiene
							foreach ( $rubroJson as $rubro ) {
								$items[$rubro->{'id_rubro'}] = $rubro->{'id_rubro'};
							}

							$rubroSeleccionado = json_encode($items);
						}
					}
				} elseif ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}

				} elseif ( isset($postData['btn-create']) ) {
					if ( $postData['btn-create'] == 5 ) {
						$model->scenario = self::SCENARIO_FRONTEND;
						$model->load($postData);
						if ( isset($postData['chkRubroSeleccionado']) ) {
							$añoImpositivo = $model->ano_impositivo;
							$periodo = $model->periodo;

							// Lo siguiente es una estructura json. Por no tener un atributo
							// clave se envia una convinacion de atributos que representan
							// la clave de la entidad.
							$chkSeleccion1 = $postData['chkRubroSeleccionado'];

							$chkSeleccion = $searchRamo->getConvertirJson($chkSeleccion1);

							if ( count($chkSeleccion) > 0 ) {
								foreach ( $chkSeleccion as $key => $value ) {
									$chkItems[] = json_decode($value, true);

								}
							}

							$itemsRubro = [];
							if ( count($chkItems) ) {
								foreach ( $chkItems as $key => $value ) {
									$itemsRubro[] = $value['id_rubro'];
								}
							}

							// Total de item seleccionado.
							$totalChk = (int)count($postData['chkRubroSeleccionado']);

							// Total de item en el grid.
							$totalItem = $postData[$formName]['totalItem'];

							$msjControls = $searchRamo->validarSeleccion($itemsRubro, $totalItem, $añoImpositivo, $periodo);

							if ( count($msjControls) == 0 ) {
					      		$dataProviderRubroRemove = $searchRamo->getDataProviderRubroDesincorporar($model->ano_impositivo,
					      																				  $model->periodo,
					      																				  $chkSeleccion);
				      			return $this->render('@frontend/views/aaee/desincorpora-ramo/pre-view-create', [
			      											'model' => $model,
			      											'dataProvider' => $dataProviderRubroRemove,
			      				]);
			      			} else {
			      				$errorMensaje = json_encode($msjControls);
			      			}

						} else {
							$msjControls[] = Yii::t('frontend', 'Dont exist select');
							$errorMensaje = json_encode($msjControls);
							$errorChk = Yii::t('frontend', 'Dont exist select');
						}
					}
				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 6 ) {
						$model->scenario = self::SCENARIO_FRONTEND;
						$postData = $request->post();
						$model->load($postData);

						$chkSeleccionJson = $searchRamo->getConvertirJson($postData['chkRubroSeleccionado']);

						$result = self::actionBeginSave($model, $postData, $chkSeleccionJson);
  						self::actionAnularSession(['begin', 'postSearch']);
  						if ( $result ) {
							$this->_transaccion->commit();
							return self::actionView($model->nro_solicitud);
						} else {
							$this->_transaccion->rollBack();
							$this->redirect(['error-operacion', 'cod'=> 920]);

  						}
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}


		  		if ( isset($findModel) ) {
		  			$model->load($postData);

		  			$añoImpositivo = $model->ano_impositivo;
					$periodo = $model->periodo;

					$dataProviderRubro = $searchRamo->getDataProviderRubrosRegistrados($añoImpositivo, $periodo);

					// Total de items ene el grid.
					$totalItem = $dataProviderRubro->getCount();

					$mActEconIngreso = $dataProviderRubro->getModels();
					$fd = '0000-00-00';
					$fh = '0000-00-00';
					foreach ( $mActEconIngreso as $m ) {
						$fd = $m['periodo_fiscal_desde'];
						$fh = $m['periodo_fiscal_hasta'];
					}

					$model->periodo_fiscal_desde = $fd;
					$model->periodo_fiscal_hasta = $fh;

					$opciones = [
						'back' => '/aaee/desincorporaramo/desincorporar-ramo/index-create',
					];
					$caption = $caption . '. ' . Yii::t('frontend', 'Categories Registered') . ' ' . $model->ano_impositivo . ' - ' . $model->periodo;
					return $this->render('@frontend/views/aaee/desincorpora-ramo/_view-ramo', [
  													'model' => $model,
  													'findModel' => $findModel,
  													'dataProviderRubro' => $dataProviderRubro,
  													'caption' => $caption,
  													'opciones' =>$opciones,
  													'errorChk' => $errorChk,
  													'totalItem' => $totalItem,
  													'errorMensaje' => $errorMensaje,
  													'chkSeleccion' => $chkSeleccion,
  													'rubroSeleccionado' => $rubroSeleccionado,
		  					]);

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}






		/**
		 * Metodo que permite obtener los periodos que existe en un año especifico,
		 * los periodos representan el nivel de exigibilidad (cantidad de periodo)
		 * que se debe cumplir en un año. Cada periodo representa un lapso de tiempo
		 * especifico. Esquema:
		 * 1 - Anual.
		 * 2 - Semestral.
		 * 3 - Cuatrimestre.
		 * 4 - Trimestral.
		 * 6 - Bimensual.
		 * 12 - Mensual.
		 * 99 - No definido.
		 * @return view
		 */
		public function actionListaPeriodo()
		{
			$añoImpositivo = 0;
			$request = Yii::$app->request;

			// Se capta el año impositivo de act-econ.
			$añoImpositivo = $request->get('id');

			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;
			if ( $idContribuyente > 0 ) {
				$searchRamo = New DesincorporarRamoSearch($idContribuyente);

				// Se espera recibir un arreglo con los atributos de la entidad respectiva.
				$exigibilidad = $searchRamo->getExigibilidadSegunAnoImpositivo($añoImpositivo);
				if ( count($exigibilidad) > 0 ) {
					return $searchRamo->getViewListaExigibilidad($exigibilidad);
				}
			}
			return "<option> - </option>";
		}




		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $model modelo de DesincorporarRamoForm.
		 * @param array $postEnviado post enviado desde el formulario.
		 * @return boolean retorna true si se realizan todas las operacions de
		 * insercion y actualizacion con exitos o false en caso contrario.
		 */
		private function actionBeginSave($model, $postEnviado, $chkSeleccionJson)
		{
			$result = false;
			$nroSolicitud = 0;

			if ( isset($_SESSION['idContribuyente']) ) {
				if ( isset($_SESSION['conf']) ) {
					$conf = $_SESSION['conf'];

					// Lo siguiente contiene una estructura json para la clave concatenada
					// de la seleccion realizada.
					//$chkSeleccion = $postEnviado['chkRubroSeleccionado'];

					$chkSeleccion = $chkSeleccionJson;

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

						$result = self::actionCreateDesincorporarRamo($this->_conexion,
															   		  $this->_conn,
															   		  $model,
																	  $conf,
																	  $chkSeleccion);

						if ( $result ) {
							if ( $conf['nivel_aprobacion'] == 1 ) {
								$result = self::actionInactivarRamo($this->_conexion,
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
			//$user = isset($model->usuario) ? $model->usuario : null;
			$user = Yii::$app->identidad->getUsuario();
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
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de DesincorporarRamoForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @param  array $chkSeleccionJson arreglo de datos donde elindice del arreglo es un entero y
	     * el valor de cada elemento es una estructura de datos tipo json.
		 * @param  array $chkSeleccion arreglo que contiene los identificadores de los ramos seleccionados
		 * @return boolean retorna un true si guardo el registro, false en caso contrario.
		 */
		private static function actionCreateDesincorporarRamo($conexionLocal, $connLocal, $model, $conf, $chkSeleccionJson)
		{
			$result = false;
			$cancel = false;
			$estatus = 0;
			//$user = isset($model->usuario) ? $model->usuario : null;
			$user = Yii::$app->identidad->getUsuario();
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			if ( isset($conexionLocal) && isset($connLocal) && isset($model) ) {
				if ( count($conf) > 0 ) {
					if ( $conf['nivel_aprobacion'] == 1 ) {
						$estatus = 1;
						$userFuncionario = Yii::$app->user->identity->login;
						$fechaHoraProceso = date('Y-m-d H:i:s');
					}

					$tabla = '';
	      			$tabla = $model->tableName();
	      			$model->origen = 'LAN';
	      			// $model->attributes es array {
	      			// 							[attribute] => valor
	      			// 						}
					$arregloDatos = $model->attributes;

					$arregloDatos['estatus'] = $estatus;
					$arregloDatos['user_funcionario'] = $userFuncionario;
					$arregloDatos['fecha_hora_proceso'] = $fechaHoraProceso;
					$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
					$arregloDatos['usuario'] = Yii::$app->identidad->getUsuario();
					$arregloDatos['origen'] = 'LAN';


					$model->estatus = $estatus;
					$model->user_funcionario = $userFuncionario;

					$chkRubro = [];
					foreach ( $chkSeleccionJson as $objetoJson ) {
						// Cada elemento del arreglo es un objeto json.
						$chkRubro[] = json_decode($objetoJson);
					}

					foreach ( $chkRubro as $rubro ) {
						$arregloDatos['id_impuesto'] = $rubro->{'id_impuesto'};
						$arregloDatos['id_rubro'] = $rubro->{'id_rubro'};
						$arregloDatos['periodo'] = $rubro->{'exigibilidad_periodo'};

						$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);
						if ( !$result ) {
							$cancel = true;
							break;
						}
					}
				}
			}
			return $result;
		}



	    /**
	     * Metodo que utilizando la variable $chkSeleccionJson, determina los valores de la clave
	     * concatenada id-impuesto / id-rubro / exigibilidad-periodo y ejecuta la inactivacion
	     * logica del registro. La variable $chkSeleccionJson, es un array que contiene en sus
	     * elementos los valores de clave concatenada, pero en una estructura de datos json.
	     * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de DesincorporarRamoForm.
	     * @param  array $chkSeleccionJson arreglo de datos donde elindice del arreglo es un entero y
	     * el valor de cada elemento es una estructura de datos tipo json.
	     * @return boolean retorna un true si actualiza el registro, false en caso contrario.
	     */
	    private static function actionInactivarRamo($conexionLocal, $connLocal, $model, $chkSeleccionJson)
	    {
	    	$result = false;
	    	if ( isset($_SESSION['idContribuyente']) && isset($connLocal) && isset($conexionLocal) ) {
	    		$idContribuyente = $_SESSION['idContribuyente'];
	    		if ( count($chkSeleccionJson) > 0 && $idContribuyente == $model->id_contribuyente ) {

	    			$tabla = ActEconIngreso::tableName();

	    			$chkRubro = [];
					foreach ( $chkSeleccionJson as $objetoJson ) {
						// Cada elemento del arreglo es un objeto json.
						$chkRubro[] = json_decode($objetoJson);
					}

					$arregloDatos['inactivo'] = 1;
					$arregloDatos['condicion'] = 9;

		    		foreach ( $chkRubro as $rubro ) {
		    			$arregloCondicion['id_impuesto'] = $rubro->{'id_impuesto'};
	      				$arregloCondicion['id_rubro'] = $rubro->{'id_rubro'};
	      				$arregloCondicion['exigibilidad_periodo'] = $rubro->{'exigibilidad_periodo'};
	      				$arregloCondicion['inactivo'] = 0;
	      				$arregloCondicion['bloqueado'] = 0;
	      				if ( !$conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion) ) {
							$result = false;
							break;
						} else {
							$result = true;
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
		 * @param  model $model modelo de AnexoRamoForm.
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
		 * @param  model $model modelo de la instancia AnexoRamoForm.
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
		 * @param  array $chkSeleccion arreglo que contiene los identificadores de los rubros.
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
	    			$searchRamo = New DesincorporarRamoSearch($_SESSION['idContribuyente']);
	    			$findModel = $searchRamo->findSolicitudDesincorporarRamo($id);
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
					'quit' => '/aaee/desincorporaramo/desincorporar-ramo/quit',
				];
				return $this->render('@frontend/views/aaee/desincorpora-ramo/_view', [
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
			return $this->render('/menu/menuvertical2');
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
							'postSearch',
					];
		}

	}
?>