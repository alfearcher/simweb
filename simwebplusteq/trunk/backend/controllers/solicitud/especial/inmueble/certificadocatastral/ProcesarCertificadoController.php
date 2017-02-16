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
 *	@file ProcesarLicenciaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 28-01-2017
 *
 *  @class ProcesarLicenciaController
 *	@brief Clase ProcesarLicenciaController del lado del funcioonario, mpermite el procesamineto
 *  de las solicitudes de licencias por lote.
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


 	namespace backend\controllers\solicitud\especial\inmueble\certificadocatastral;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\enviaremail\PlantillaEmail;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use common\models\planilla\PlanillaSearch;
	use backend\models\inmueble\SlCertificadoCatastralSearch;

	use backend\models\solicitud\especial\inmueble\certificadocatastral\BusquedaSolicitudCertificadoForm;
	use backend\models\solicitud\especial\aaee\licencia\SolicitudLicenciaSearch;
	use common\models\solicitudescontribuyente\ProcesarSolicitudContribuyente;
	use common\models\inmueble\certificadocatastral\JsonCertificado;
	use common\models\aaee\licencia\GenerarLicencia;


	session_start();		// Iniciando session

	/**
	 * Clase principal que controla el proceso de atencion de las solicitudes de licencias
	 * de los contribuyentes de actividad economica. Se listan las solicitudes de licencias
	 * segun un criterio que se maenja en el modelo y aquellas solicitudes que cumplan los
	 * criterios se presentaran en un listado. Las que no cumplan las condiciones se
	 * presentaran en otro listado.
	 */
	class ProcesarCertificadoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$this->redirect(['listado-solicitud']);
		}




		/**
		 * Metodo que permite levantar una vista que permitira la busqueda de las solicitudes.
		 * @return [type] [description]
		 */
		public function actionListadoSolicitud()
		{
			$model = New BusquedaSolicitudCertificadoForm();
			$autorizado = false;

			// Se determina si el usuario esta autorixado a utilizar el modulo.
			$autorizado = $model->estaAutorizado(Yii::$app->identidad->getUsuario());

			if ( $autorizado ) {

				// Ruta donde se atendera la solicitud de busqueda.
				$url = Url::to(['mostrar-listado']);

				$_SESSION['begin'] = 1;
				$listaTipoLicencia = [
					//'NUEVA' => 'NUEVA',
					'RENOVACION' => 'RENOVACION',
				];
				$caption = Yii::t('backend', 'Busqueda de Solicitudes de Certificado Catastral');
				// Se levanta el formulario de busqueda.
				return $this->render('/solicitud/especial/aaee/licencia/busqueda-solicitud-licencia-form',[
																'model' => $model,
																'url' => $url,
																'caption' => $caption,
																'listaTipoLicencia' => $listaTipoLicencia,
					]);

			} else {
				// El usuario no esta autorizado.
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/***/
		public function actionIniciarListado()
		{
			$_SESSION['begin'] = 1;
			$this->redirect(['mostrar-listado']);
		}



		/**
		 * [actionMostrarListado description]
		 * @return [type] [description]
		 */
		public function actionMostrarListado()
		{
			if ( isset($_SESSION['begin']) ) {

				$_SESSION['begin'] = 2;
				$request = Yii::$app->request;
				$postData = $request->post();

				$model = New BusquedaSolicitudCertificadoForm();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				if ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 2 ) {
						self::actionAnularSession(['begin']);
						$this->redirect(['index']);
					}
				}


				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}



		      	if ( isset($postData['chkNroSolicitud']) ) {
		      		if ( count($postData['chkNroSolicitud']) > 0 ) {

		      			$_SESSION['postSeleccion'] = $postData;
		      			$this->redirect(['mostrar-listado-seleccionado']);
		      		}

		      	}



		      	if ( $request->isGet ) {

		      		if ( isset($_SESSION['postEnviado']) ) {
		      			$postData = $_SESSION['postEnviado'];
		      			$model->load($postData);
		      		}
		      		$dataProvider = $model->armarDataProvider(Yii::$app->request->bodyParams);
  					return $this->render('/solicitud/especial/inmueble/certificadocatastral/listado-solicitud-licencia',[
  																'model' => $model,
  																'dataProvider' => $dataProvider,
  						]);

		      	} else {

			      	if ( isset($postData['btn-search-tipo']) ) {
			      		if ( $postData['btn-search-tipo'] == 3 ) {

			      			if ( $model->load($postData) ) {
			      				if ( $model->validate() ) {

			      					$_SESSION['postEnviado'] = $postData;
			      					$dataProvider = $model->armarDataProvider(Yii::$app->request->bodyParams); 
			      					return $this->render('/solicitud/especial/inmueble/certificadocatastral/listado-solicitud-licencia',[
			      																'model' => $model,
			      																'dataProvider' => $dataProvider,
			      							]);

			      				}
			      			}

			      		}

			      	} elseif ( isset($postData['btn-search-contribuyente']) ) {
			      		if ( $postData['btn-search-contribuyente'] == 5 ) {

			      			if ( $model->load(['id_contribuyente']) ) {
			      				if ( $model->validate() ) {

			      					$_SESSION['postEnviado'] = $postData;
			      					$dataProvider = $model->armarDataProvider(Yii::$app->request->bodyParams);
			      					return $this->render('/solicitud/especial/inmueble/certificadocatastral/listado-solicitud-licencia',[
			      																'model' => $model,
			      																'dataProvider' => $dataProvider,
			      						]);
			      				}
			      			}

			      		}
			      	}

			    }


		      	// Ruta donde se atendera la solicitud de busqueda.
				$url = Url::to(['mostrar-listado']);

				$listaTipoLicencia = [
					'NUEVA' => 'NUEVA',
					'RENOVACION' => 'RENOVACION',
				];
				$caption = Yii::t('backend', 'Busqueda de Solicitudes de Licencias');
		      	// Se levanta el formulario de busqueda.
				return $this->render('/solicitud/especial/aaee/licencia/busqueda-solicitud-licencia-form',[
																'model' => $model,
																'url' => $url,
																'caption' => $caption,
																'listaTipoLicencia' => $listaTipoLicencia,
					]);


			} else {
				// No ha iniciado correctamente el modulo.
				$this->redirect(['error-operacion', 'cod' => 702]);
			}
		}






		/***/
		public function actionMostrarListadoSeleccionado()
		{
			if ( isset($_SESSION['postSeleccion']) ) {
				$postData = $_SESSION['postSeleccion'];
				self::actionAnularSession(['postSeleccion']);

			} else {
				$request = Yii::$app->request;
				$postData = $request->post();
			}

			if ( isset($postData['btn-back']) ) {
				if ( $postData['btn-back'] == 3 ) {
					$this->redirect(['mostrar-listado']);
				}
			}

			if ( isset($postData['btn-confirmar-aprobar']) ) {
				if ( $postData['btn-confirmar-aprobar'] == 9 ) {

					self::actionAnularSession(['begin', 'postSeleccion']);
					self::actionAprobarSolicitud($postData['chkNroSolicitud']);

					$this->redirect(['mostrar-solicitud-procesada']);
				}
			} else {

				$model = New BusquedaSolicitudCertificadoForm();

				// Mostrar vista previa de lo seleccionado
				$dataProvider = $model->armarDataProvider($postData['chkNroSolicitud'], true);
				return $this->render('/solicitud/especial/inmueble/certificadocatastral/pre-view-lista-seleccion-solicitud',[
														'model' => $model,
														'dataProvider' => $dataProvider,
				]);
			}

		}





		/***/
		public function actionAprobarSolicitud($chkNroSolicituds)
		{

			$evento = Yii::$app->solicitud->aprobar();
			$solicitudProcesada = [];
			$solicitudAprobada = [];

			// Se crea un ciclo para procesar el lote de solicitudes
			// Cada solicitud se tratara por separado, es decir, tendran su
			// instancia de Conexion y transaccion. Si se aborta un registro esto no
			// afectara al resto que ta se haya guardado.
			foreach ( $chkNroSolicituds as $key => $value ) {

				self::actionAnularSession(['idContribuyente']);
				$solicitud = SolicitudesContribuyente::findOne($value)->toArray();
				$_SESSION['idContribuyente'] = $solicitud['id_contribuyente'];

				$result = false; 

				$this->_conexion = New ConexionController();

	  			// Instancia de conexion hacia la base de datos.
	  			$this->_conn = $this->_conexion->initConectar('db');
	  			$this->_conn->open();

	  			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	  			// Inicio de la transaccion.
				$this->_transaccion = $this->_conn->beginTransaction();

				$procesar = New ProcesarSolicitudContribuyente(
													$value,
													$evento,
													$this->_conn,
													$this->_conexion
												);

				$result = $procesar->aprobarSolicitud();

				if ( $result == true ) {
					// Ejecutar procesos asociados al evento (si existen) y enviar correo
					// comunicando al contribuyente el resultado de su solicitud.

					$result = self::actionEjecutarProcesoRelacionadoSolicitud($solicitud, $evento);

					if ( $result ) {

						$modelInscripcion = self::findCertificadoCatastralUrbano($value, $solicitud['id_contribuyente']);
						$tableNameMaster = 'historico_certificados_catastrales';

                    	$jsonInmueble = new JsonCertificado(); 
                    	$json = $jsonInmueble->DatosJson($modelInscripcion['id_impuesto']);

                    	$arregloDatosMaster = [ 
                                           
                                            'id_impuesto' => $modelInscripcion['id_impuesto'],
                                            'nro_solicitud' => $modelInscripcion['nro_solicitud'],
                                            'fecha_hora' => $modelInscripcion['fecha_hora'],
                                            'ano_impositivo' => $modelInscripcion['ano_impositivo'],
                                            'id_contribuyente' => $modelInscripcion['id_contribuyente'],
                                            'tipo' => $modelInscripcion['tipo'],
                                            'certificado_catastral' => 'CC-'.$modelInscripcion['id_impuesto'].'-'.$modelInscripcion['id_contribuyente'] ,
                                            'nro_control' => 0,
                                            'serial_control' => 0,
                                            'inmueble_json' => $json['inmuebleJson'],
                                            'avaluo_json' => $json['avaluoJson'],
                                            'registro_json' => $json['registroJson'],
                                            'usuario' => $modelInscripcion['usuario'],
                                            'inactivo' => 0,
                                            'observacion' => 'creada',
                                            'firma_control' => $json['firmaControl'],
                                         ];
						
						if ( $this->_conexion->guardarRegistro($this->_conn, $tableNameMaster, $arregloDatosMaster) ) {
							$this->_transaccion->commit();
							$solicitudAprobada[] = $value;

							// Se envia el correo al contribuyente notificando el resultado del procesamiento de su solicitud.
							self::actionEnviarEmail($solicitud, $evento);
						} else {
							$result = false;
							$this->_transaccion->rollBack();
						}
					} else {
						$this->_transaccion->rollBack();
					}
				} else {
					$this->_transaccion->rollBack();
				}
				$this->_conn->close();			// Se cierra la conexion y transaccion


			}		// Fin del; ciclo foreach

			$_SESSION['solicitudAprobada'] = $solicitudAprobada;

		}





		/***/
		private function actionEjecutarProcesoRelacionadoSolicitud($datos, $evento)
		{
			$result = true;
			$model = New SolicitudesContribuyente();
			$model->id_contribuyente = $datos['id_contribuyente'];
			$model->nro_solicitud = $datos['nro_solicitud'];

			if ( isset($datos['id_config_solicitud']) ) {
				$procesoEvento = New SolicitudProcesoEvento($datos['id_config_solicitud']);
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, $evento, $this->_conexion, $this->_conn);
				// Se obtiene un array de acciones o procesos ejecutados.
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
				// No se pudo definir el identificador de la configuracion de la solicitud.
				$result = false;
			}
			return $result;
		}




		/**
		 * Metodo que redirecciona a la clase que permitira el envio del correo, previo obtencion de un cuerpo
		 * de mensaje que resume el resultado del procesamientro de la solicitud del contribuyente.
		 * @param  [type] $postEnviado [description]
		 * @param  String $evento Accion ejecutada sobrte la solicitud.
		 * @return [type]              [description]
		 */
		public function actionEnviarEmail($postEnviado, $evento)
		{
			$result = false;
			if ( isset($postEnviado['nro_solicitud']) ) {

				$cuerpoEmail = self::actionArmarCuerpoEmail($postEnviado['nro_solicitud'], $evento);
				if ( trim($cuerpoEmail) !== '' ) {
					// Obtuve un cuerpo de correo, ahora se manda a la clase para que lo envie al contribuyente.
					$modelSolicitud = New SolicitudesContribuyenteForm();
					$email = $modelSolicitud->getEmailContribuyente($postEnviado['id_contribuyente']);

					$plantilla = New PlantillaEmail();
					$result = $plantilla->plantillaSolicitudProcesada($email, $cuerpoEmail);

				}
			}
			return $result;
		}




		/**
		 * Metodo que arma un cuerpo de mensaje que se utilizara para cominicarle al contribuyente
		 * el resultado del procesamiento de su solicitud.
		 * @param  Long $nroSolicitud Numero de la solicitud procesada.
		 * @param  String $evento Evento resultante a la solicitud, es la accion aplicada a la solicitud.
		 * @return String Retorna un escrito que especifica los datos de la solicitud y el encabezado
		 * representa un resumen del resultado del procesamiento de la solicitud.
		 */
		private function actionArmarCuerpoEmail($nroSolicitud, $evento)
		{
			$cuerpoCorreo = null;
			$cuerpoEncabezado = null;

			if ( $evento == Yii::$app->solicitud->aprobar() ) {
				$cuerpoEncabezado = 'Estimado contribuyente su solicitud ha sido APROBADA exitosamente <br><br>' . 'Datos de la solicitud: <br><br>';

			} elseif( $evento == Yii::$app->solicitud->negar() ) {
				$cuerpoEncabezado = 'Estimado contribuyente su solicitud ha sido NEGADA <br><br>' . 'Datos de la solicitud: <br><br>';

			}

			if ( $cuerpoEncabezado !== null ) {
				$model = SolicitudesContribuyente::find()->where('nro_solicitud =:nro_solicitud',
																				[':nro_solicitud' => $nroSolicitud]
															)
													 ->joinWith('tipoSolicitud')
													 ->joinWith('impuestos')
													 ->joinWith('nivelAprobacion')
													 ->joinWith('causaNegacion')
													 ->asArray()
													 ->all();
				if ( isset($model) ) {
					// Se arma el cuerpo del email con los datos de la solicitud procesada.
					foreach ( $model as $key => $value ) {
						$cuerpo = 'Numero: '. $value['nro_solicitud'] . '<br>' .
								  'Tipo: ' . $value['tipoSolicitud']['descripcion'] . '<br>' .
								  'Impuesto: ' . $value['impuestos']['descripcion'] . '<br>' .
								  'Nivel de atencion: ' . $value['nivelAprobacion']['descripcion'] . '<br>' .
								  'Fecha/hora creacion: ' . $value['fecha_hora_creacion'] . '<br>' .
								  'Usuario: ' . $value['usuario'] . '<br>' .
								  'Id. Contribuyente: ' . $value['id_contribuyente'] . '<br>' .
								  'Fecha/Hora de atencion: ' . $value['fecha_hora_proceso'] . '<br>' .
								  'Funcionario: ' . $value['user_funcionario'] . '<br>';

						if ( $value['causaNegacion']['causa'] > 0 ) {
							$cuerpo = $cuerpo . 'Causa: ' . $value['causaNegacion']['descripcion'] . '<br>';
						}
					}
					$cuerpoCorreo = $cuerpoEncabezado . $cuerpo;
				}
			}
			return $cuerpoCorreo;
		}






		/**
		 * [actionMostrarSolicitudProcesada description]
		 * @return [type] [description]
		 */
		public function actionMostrarSolicitudProcesada()
		{
			$request = Yii::$app->request;
			$postData = $request->post();
			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}


			$solicitudAprobada = isset($_SESSION['solicitudAprobada']) ? $_SESSION['solicitudAprobada'] : null;

			if ( $solicitudAprobada !== null ) {

				$model = New BusquedaSolicitudCertificadoForm();

				$dataProvider = $model->getDataProviderHistoricoCertificado($solicitudAprobada);
				return $this->render('/solicitud/especial/inmueble/certificadocatastral/lista-historico-licencia',[
														'model' => $model,
														'dataProvider' => $dataProvider,
						]);
			} else {
				// No se detectaron registros guardados en el proceso.
				$this->redirect(['error-operacion', 'cod' => 704]);
			}
		}




    	/**
		 * Metodo que permite renderizar una vista de los detalles de la planilla
		 * que se encuentran en la solicitud.
		 * @return View Retorna una vista que contiene un grid con los detalles de la
		 * planilla.
		 */
		public function actionViewPlanilla()
		{ 
			$request = Yii::$app->request;
			$getData = $request->get();

			$planilla = $getData['p'];
			$planillaSearch = New PlanillaSearch($planilla);
			$dataProvider = $planillaSearch->getArrayDataProviderPlanilla();

			// Se determina si la peticion viene de un listado que contiene mas de una
			// pagina de registros. Esto sucede cuando los detalles de un listado contienen
			// mas de los manejados para una pagina en la vista.
			if ( isset($request->queryParams['page']) ) {
				$planillaSearch->load($request->queryParams);
			}
				return $this->renderAjax('@backend/views/planilla/planilla-detalle', [
									 			'dataProvider' => $dataProvider,
									 			'caption' => 'Planilla: ' . $planilla,
				]);
		}


		/**
         * Metodo que permite obtener un modelo de los datos de la solicitud,
         * sobre la entidad "sl-", referente al detalle de la solicitud. Es la
         * entidad donde se guardan los detalle de esta solicitud.
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findCertificadoCatastralUrbano($nro_solicitud, $id_contribuyente)
        {
            // Este find retorna el modelo de la entidad "sl-inscripciones-act-econ"
            // con datos, ya que en el metodo padre se ejecuta el ->one() que realiza
            // la consulta.
            $buscar = new SlCertificadoCatastralSearch($id_contribuyente);
            $modelFind = $buscar->findCertificado($nro_solicitud); 
            return isset($modelFind) ? $modelFind : null;
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
							'lapso',
							'id_config_solicitud',
					];
		}


	}
?>