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
 *	@file CorreccionDomicilioFiscalController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 30-07-2016
 *
 *  @class CorreccionDomicilioFiscalController
 *	@brief Clase CorreccionDomicilioFiscalController del lado del contribuyente backend.
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


 	namespace backend\controllers\aaee\correcciondomicilio;


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
	use yii\helpers\ArrayHelper;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\enviaremail\PlantillaEmail;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscalSearch;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscalForm;

	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de Correccion de Domicilio.
	 * Solicitud que se realizara del lado del contribuyente (frontend). Se mostrara una vista
	 * previa de la solicitud realizada por el contribuyente y se le indicara al contribuyente
	 * que confirme la operacion o retorne a la vista inicial donde cargo la informacion para su
	 * ajuste. Cuando el contribuyente confirme su intencion de crear la solicitud, es cuando
	 * se guardara en base de datos.
	 */
	class CorreccionDomicilioFiscalController extends Controller
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
		const CONFIG = 86;


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

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					$idContribuyente = $_SESSION['idContribuyente'];
					$searchCorreccion = New CorreccionDomicilioFiscalSearch($idContribuyente);

					// Se verifica que el contribuyente sea de tipo naturaleza "Juridico".
					if ( $searchCorreccion->esUnContribuyenteJuridico() ) {

						// Se determina si ya esta inscrito como contribuyente de Actividad Economica.
						//if ( $searchCorreccion->estaInscritoActividadEconomica() ) {

							// Se determina si ya existe una solicitud pendiente.
							if ( !$searchCorreccion->yaPoseeSolicitudSimiliarPendiente() ) {

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
								// El contribuyente ya posee una solicitud similar, y la misma esta pendiente.
								return $this->redirect(['error-operacion', 'cod' => 945]);
							}
						//} else {
							// El contribuyente no esta inscrito como contribuyente de Actividad Economica.
						//	return $this->redirect(['error-operacion', 'cod' => 404]);
						//}
					} else {
						// El contribuyente no es JURIDICO.
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
		 * de correccion de domicilio fiscal. Tambien gestiona la ejecucion de las reglas
		 * de validacion del formulario.
		 * @return view
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				$model = New CorreccionDomicilioFiscalForm();
				$formName = $model->formName();
				$model->scenario = self::SCENARIO_FRONTEND;

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

		      	if ( $model->load($postData) ) {
		      		if ( $model->validate() ) {

	      				// Validacion correcta.
	      				if ( isset($postData['btn-create']) ) {
	      					if ( $postData['btn-create'] == 1 ) {

	      						// Mostrar vista previa.
	      						$datosRecibido = $postData[$formName];
	      						return $this->render('@frontend/views/aaee/correccion-domicilio-fiscal/pre-view-create', [
	      																	'model' => $model,
	      																	'datosRecibido' => $datosRecibido,
	      							]);
	      					}
	      				} elseif ( isset($postData['btn-confirm-create']) ) {
	      					if ( $postData['btn-confirm-create'] == 2 ) {
	      						$result = self::actionBeginSave($model, $postData);
	      						self::actionAnularSession(['begin']);
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

		      	// Se muestra el form de la solicitud.
		      	// Datos generales del contribuyente.
		      	$searchCorreccion = New CorreccionDomicilioFiscalSearch($idContribuyente);
		      	$datos = $searchCorreccion->getDatosContribuyente();
		  		if ( isset($datos) ) {
		  			$subCaption = Yii::t('frontend', 'Info of Taxpayer');
		  			return $this->render('@frontend/views/aaee/correccion-domicilio-fiscal/_create', [
					  											'model' => $model,
					  											'datos' => $datos,
					  											'subCaption' => $subCaption,
					  					]);
		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 404]);
		  		}
			}
		}




		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $model modelo de CorreccionDomicilioFiscalForm.
		 * @param array $postEnviado post enviado desde el formulario.
		 * @return boolean retorna true si se realizan todas las operacions de
		 * insercion y actualizacion con exitos o false en caso contrario.
		 */
		private function actionBeginSave($model, $postEnviado)
		{
			$result = false;
			$nroSolicitud = 0;

			if ( isset($_SESSION['idContribuyente']) ) {
				if ( isset($_SESSION['conf']) ) {
					$conf = $_SESSION['conf'];

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

						$result = self::actionCreateCorreccionDomicilio($this->_conexion,
																	    $this->_conn,
																	    $model,
																	    $conf);
// die(var_dump());
						if ( $result ) {
							$result = self::actionUpdateDomicilioFiscal($this->_conexion,
																	  $this->_conn,
																	  $model,
																	  $conf);
							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($this->_conexion, $this->_conn, $model, $conf);
								if ( $result ) {
									$result = self::actionEnviarEmail($model, $conf);
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
		 * @param  model $model modelo de CorreccionDomicilioFiscalForm.
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
		 * @param  ConexionController $conexionLocal instancia de la lcase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de CorreccionDomicilioFiscalForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna un true si guardo el registro, false en caso contrario.
		 */
		private static function actionCreateCorreccionDomicilio($conexionLocal, $connLocal, $model, $conf)
		{
			$result = false;
			$estatus = 0;
			// $user = isset($model->usuario) ? $model->usuario : null;
			$user = Yii::$app->identidad->getUsuario();
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
	      			$model->origen = 'LAN';
	      			// $model->attributes es array {
	      			// 							[attribute] => valor
	      			// 						}
					$arregloDatos = $model->attributes;

					$arregloDatos['estatus'] = $estatus;
					$arregloDatos['user_funcionario'] = $userFuncionario;
					$arregloDatos['fecha_hora_proceso'] = $fechaHoraProceso;

					$model->estatus = $estatus;
					$model->user_funcionario = $userFuncionario;

					$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);
				}
			}
			return $result;
		}




		/**
		 * Metodo que ejecuta la actualizacion del domicilio del contribuyente, aplica
		 * solo en aquellos casos donde la aprobacion de la solicitud sea directa.
		 * @param  ConexionController $conexionLocal instancia de la lcase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de CorreccionDomicilioFiscalForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna true si se ejecuta la actualizacion, sino false.
		 */
		private static function actionUpdateDomicilioFiscal($conexionLocal, $connLocal, $model, $conf)
		{
			$result = false;
			if ( $conf['nivel_aprobacion'] == 1 ) {
				$arregloCondicion = ['id_contribuyente' => $model->id_contribuyente];
				$arregloDatos = ['domicilio_fiscal' => $model->domicilio_fiscal_new];

				$tabla = ContribuyenteBase::tableName();

				$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion);
			} else {
				$result = true;
			}
			return $result;
		}




		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  ConexionController  $conexionLocal instancia de la clase ConexionController
		 * @param  connection  $connLocal instancia de connection.
		 * @param  model $model modelo de InscripcionSucursalForm.
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
		 * @param  model $model modelo de la instancia CorrecionDomicilioFiscalForm.
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
		 * @return Boolean Retorna un true si envio el correo o false en caso
		 * contrario.
		 */
		private function actionEnviarEmail($model, $conf)
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
	    			$modelSearch = New CorreccionDomicilioFiscalSearch($_SESSION['idContribuyente']);
	    			$findModel = $modelSearch->findSolicitudCorreccionDomicilio($id);
	    			if ( isset($findModel) ) {
	    				return self::actionShowSolicitud($findModel, $modelSearch);
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
    	private function actionShowSolicitud($findModel, $modelSearch)
    	{
    		if ( isset($findModel) && isset($modelSearch) ) {
				$opciones = [
					'quit' => '/aaee/correcciondomicilio/correccion-domicilio-fiscal/quit',
				];
				return $this->render('@frontend/views/aaee/correccion-domicilio-fiscal/_view', [
																'codigo' => 100,
																'model' => $findModel,
																'modelSearch' => $modelSearch,
																'opciones' => $opciones,
					]);
			} else {
				throw new NotFoundHttpException('No se encontro el registro');
			}
    	}





    	/**
    	 * Metodo que indica si los valores presentes en el formulario son validos.
    	 * Se evalua si los campos son validos a traves del modelo. Esto campos se encuentra
    	 * en el modelo de inscripcion de actividad economica.
    	 * @param $postData, post enviado desde el formulario.
    	 * @return returna boolean que indica si los campos en el formulario contienen valores validos.
    	 * true indica que todo esta bien, false todo lo contrario.
    	 */
    	private static function actionValidateRegistroMercantil($postData)
    	{
    		$modelActEcon = new InscripcionActividadEconomicaForm();
    		$nombreForm = $modelActEcon->formName();
    		foreach ( $postData[$nombreForm] as $key => $value ) {
    			if ( !isset($postData[$nombreForm]['fecha']) ) {
    				return false;
    			} elseif ( !isset($postData[$nombreForm]['num_reg']) ) {
    				return false;
    			} elseif ( !isset($postData[$nombreForm]['reg_mercantil']) ) {
    				return false;
    			}
    		}
    		return true;
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
					];
		}

	}
?>