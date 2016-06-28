<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *  > This library is free software; you can redistribute it and/or modify it under
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *  > any later version.
 *  >
 *  > This library is distributed in the hope that it will be usefull,
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *  > for more details.
 *  >
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *  @file SolicitudAsignada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-05-2016
 *
 *  @class SolicitudAsignadaController
 *  @brief Clase
 *
 *
 *  @property
 *
 *
 *  @method
 *  rules
 *  attributeLabels
 * 	scenarios
 *
 *
 *  @inherits
 *
 */


	namespace backend\controllers\funcionario\solicitud;

 	session_start();		// Iniciando session
 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use yii\db\Exception;
	use backend\models\funcionario\solicitud\SolicitudAsignadaSearch;
	use backend\models\funcionario\solicitud\SolicitudAsignadaForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\impuesto\ImpuestoForm;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumentoSearch;
	use common\models\solicitudescontribuyente\DetalleSolicitudCreada;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanillaSearch;
	use common\models\planilla\PlanillaSearch;
	use common\models\solicitudescontribuyente\ProcesarSolicitudContribuyente;
	use common\models\solicitudescontribuyente\SolicitudesContribuyente;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use backend\models\solicitud\negacion\NegacionSolicitudForm;


	/**
	 * Clase que le permite al funcionario buscar las solicitudes asignadas a este
	 * a traves de un buscador que facilita la entrada de algunos parametros de busqueda.
	 * Una vez indicado los parametros de busqueda podra generar un listado de dichas solicitudes
	 * Este listado de solicitudes dispone de un boton que permite visualizar el detalle
	 * de la solicitud, en este formulario de detalle el funcionario podra aprobar o no la
	 * solicitud a traves de dos botones para dichas acciones.
	 * El detalle de la solicitud debe mostrar:
	 * - Detalle propio de la solicitud.
	 * - Planillas relacionadas a la solicitud al monento de crear la misma.
	 * - Documentos y o requisitos asociados.
	 */
	class SolicitudAsignadaController extends Controller
	{

	   	public $layout = 'layout-main';				//	Layout principal del formulario.

		private $_conn;
		private $_conexion;
		private $_transaccion;
		private $_exigirDocumento = false;





		/**
		 * Metodo que inicia el modulo.
		 * @return View Retorna una vista de un formularios de busqueda de solictudes
		 * a traves de diferentes parametros.
		 */
		public function actionIndex()
		{
			$request = Yii::$app->request;
			$postData = $request->post();
			$sessiones = self::actionGetListaSessions();
			self::actionAnularSession($sessiones);

			// Modelo del formulario de busqueda de las solicitudes.
			$model = New SolicitudAsignadaForm();

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}

			if ( $model->load($postData) ) {
				if ( $model->validate() ) {
					if ( isset($postData['btn-search-request']) ) {
						$_SESSION['postBusquedaInicial'] = $postData;
						return $this->redirect(['buscar-solicitudes-contribuyente']);
					}
				}
			}
			// Lo siguiente permite determinar que impuestos estan relacionados a las
			// solicitudes permisadas para el funcionario.
			$listaImpuesto = null;
			$modelSearch = New SolicitudAsignadaSearch();
			$listaImpuesto = $modelSearch->getImpuestoSegunFuncionario();

			// Modelo adicionales para la busqueda de los funcionarios.
			$modelImpuesto = New ImpuestoForm();

			// Se define la lista de item para el combo de impuestos.
			// El primer parametro se refiere a la condicion del registro 0 => activo, 1 => inactivo.
			$listaImpuesto = $modelImpuesto->getListaImpuesto(0, $listaImpuesto);

			$caption = Yii::t('backend', 'Search Request');
			return $this->render('/funcionario/solicitud-asignada/busqueda-solicitud-form', [
																			'model' => $model,
																			'modelImpuesto' => $modelImpuesto,
																			'caption' => $caption,
																			'listaImpuesto' => $listaImpuesto,

				]);
		}



		/***/
		public function actionProcesarSolicitud()
		{
			$result = false;
			$request = Yii::$app->request;
			$postData = $request->post();
//die(var_dump($postData));

			$model = New SolicitudesContribuyente();
			$formName = $model->formName();

			if ( trim($formName) !== '' ) {
				$this->_exigirDocumento = isset($postData[$formName]['exigirDocumento']) ? $postData[$formName]['exigirDocumento'] : false;
				// Se pregunta por el boton seleccionado para procesar la solicitud.
				if ( isset($postData['btn-approve-request']) ) {
					if ( $postData['btn-approve-request'] == 1 ) {
						// Se presiono el boto de aprobacion.

						// Lo siguiente controla que si existe una lista de documentos y requisitos
						// a consignar por parte del contribuyente, el funcionario lo indique con un
						// tilde (checkbox), en el formulario de procesamiento de la solicitud.
						if ( $this->_exigirDocumento == true ) {
							if ( isset($postData['chk-documento-requisito']) ) {
								if ( count($postData['chk-documento-requisito']) > 0 ) {
									$result = self::actionIniciarAprobarSolicitud($postData, $formName);
									if ( $result ) {
										return self::actionProcesoExitoso(101);
									} else {
										return self::actionErrorOperacion(920);
									}
								} else {
									$_SESSION['mensajeErrorChk'] = Yii::t('backend', 'Debe indicar el documento consignado');
									$this->redirect(['buscar-solicitud-seleccionada']);
								}
							} else {
								$_SESSION['mensajeErrorChk'] = Yii::t('backend', 'Debe indicar el documento consignado');
								$this->redirect(['buscar-solicitud-seleccionada']);
							}
						} else {
							// Se presiono el boto de aprobacion.
							$result = self::actionIniciarAprobarSolicitud($postData, $formName);
							if ( $result ) {
								return self::actionProcesoExitoso(101);
							} else {
								return self::actionErrorOperacion(920);
							}
						}
					}
				} elseif ( isset($postData['btn-reject-request']) ) {
					if ( $postData['btn-reject-request'] == 1 ) {
						// Se presiono el boto de negacion.
						// Mostrar formulario para cargar la causa y la observacion.
						self::actionAnularSession(['postData', 'nroSolicitud']);
						$_SESSION['postData'] = $postData;
						$_SESSION['nroSolicitud'] = $postData[$formName]['nro_solicitud'];
						$this->redirect(['levantar-form-negacion-solicitud']);

					}
				}

			} else {
				// No esta definida la clase de la solicitud
				return self::actionErrorOperacion(404);
			}
		}



		/***/
		public function actionLevantarFormNegacionSolicitud()
		{
			$result = false;
			$request = Yii::$app->request;
			$postData = $request->post();
			$postInicial = isset($_SESSION['postData']) ? $_SESSION['postData'] : null;

			$model = New SolicitudesContribuyente();
			$formName = $model->formName();

			$postIn = $postInicial[$formName];
			$modelSolicitud = self::actionFindSolicitudCreada($postIn['nro_solicitud']);

			if ( $modelSolicitud->nro_solicitud == $_SESSION['nroSolicitud'] ) {
				if ( $modelSolicitud->estatus == 0 && $modelSolicitud->inactivo == 0 ) {

					$modelNegacion = New NegacionSolicitudForm();

					if ( $modelNegacion->load($postData) && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validate($modelNegacion);
					}

					if ( $modelNegacion->load($postData) ) {
						if ( $modelNegacion->validate() ) {
							$result = self::actionIniciarNegarSolicitud($postData, $formName);
							if ( $result ) {
								return self::actionProcesoExitoso(102);
							} else {
								return self::actionErrorOperacion(404);
							}
						}
					}

					// Se obtiene una lista de causas de negacion de solicitudes para mostrarlo
			  		// en un combo-lista, esto se obtuvo con el ArrayHelper.
			  		$lista = $modelNegacion->listaCausasNegacion();

			  		$caption = Yii::t('backend', 'Reject request ' . $_SESSION['nroSolicitud']);
			  		$subCaption = Yii::t('backend', 'Request ' . $_SESSION['nroSolicitud']);
		  			return $this->render('/solicitud/negacion/negacion-solicitud-form', [
		  														'model' => $modelNegacion,
		  														'listaCausas' => $lista,
		  														'caption' => $caption,
		  														'subCaption' => $subCaption,
		  														'nroSolicitud' => $_SESSION['nroSolicitud'],
		  					]);
		  		} else {
		  			// El estatus de la solicitud no corresponde con el requerido para
		  			// su procesamiento.
		  			return self::actionErrorOperacion(941);
		  		}
		  	} else {
		  		// El numero de solicitud posteado no corresponde con el que se esta
		  		// procesando.
		  		return self::actionErrorOperacion(940);
		  	}
		}



		/***/
		public function actionFindSolicitudCreada($nroSolicitud)
		{
			$model = SolicitudesContribuyente::find()->where('nro_solicitud =:nro_solicitud', [':nro_solicitud' => $nroSolicitud])
			                                         ->one();
			return isset($model) ? $model : null;
		}





		/***/
		public function actionIniciarAprobarSolicitud($postData, $formName)
		{
			$result = false;
			$evento = Yii::$app->solicitud->aprobar();
			$datos = $postData[$formName];

			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');
  			$this->_conn->open();

  			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			$procesar = New ProcesarSolicitudContribuyente(
													$datos['nro_solicitud'],
													$evento,
													$this->_conn,
													$this->_conexion
												);

			$result = $procesar->aprobarSolicitud();
			if ( $result ) {
				// Ejecutar procesos asociados al evento (si existen) y enviar correo
				// comunicando al contribuyente el resultado de su solicitud.

				$result = self::actionEjecutarProcesoRelacionadoSolicitud($datos, $evento);
				// Si devuelve TRUE es que se ejecutaron correctamento los procesos relacionados
				// al evento "aprobar" de la solicitud o no existian procesos relacionados que
				// ejecutar lo que indica que no se configuraron dichos procesos.
				// Si retorna FALSE indica que no se logro ejecutar correctamente los procesos
				// relacionados al evento-solicitud. Aqui acaba el procedimiento sin guardar nada.

				if ( $result ) {
					$this->_transaccion->commit();
					$this->_conn->close();

				} else {
					$this->_transaccion->rollBack();
					$this->_conn->close();
				}
			}
			return $result;
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




		/***/
		public function actionIniciarNegarSolicitud($postData, $formName)
		{
			$result = false;
			$datos = $postData[$formName];
			$evento = Yii::$app->solicitud->negar();

			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');
  			$this->_conn->open();

  			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			$procesar = New ProcesarSolicitudContribuyente(
													$datos['nro_solicitud'],
													$evento,
													$this->_conn,
													$this->_conexion
												);
			$reult = $procesar->negarSolicitud($datos['causa'], $datos['observacion']);
			if ( $result ) {
				// Ejecutar procesos asociados al evento (si existen) y enviar correo
				// comunicando al contribuyente el resultado de su solicitud.

				$result = self::actionEjecutarProcesoRelacionadoSolicitud($datos, $evento);
				// Si devuelve TRUE es que se ejecutaron correctamento los procesos relacionados
				// al evento "aprobar" de la solicitud o no existian procesos relacionados que
				// ejecutar lo que indica que no se configuraron dichos procesos.
				// Si retorna FALSE indica que no se logro ejecutar correctamente los procesos
				// relacionados al evento-solicitud. Aqui acaba el procedimiento sin guardar nada.

				if ( $result ) {
					$this->_transaccion->commit();
					$this->_conn->close();

				} else {
					$this->_transaccion->rollBack();
					$this->_conn->close();
				}
			}
			return $result;
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
		 * Metodo que muestra la solicitud seleccionada por el funcionario.
		 * @return [type] [description]
		 */
		public function actionBuscarSolicitudSeleccionada()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($_SESSION['postSeleccionado']) ) {
				$postData = $_SESSION['postSeleccionado'];
			} else {
				$_SESSION['postSeleccionado'] = isset($postData) ? $postData : null;
			}

			$errorChk = isset($_SESSION['mensajeErrorChk']) ? $_SESSION['mensajeErrorChk'] : '';

			$exigirDocumento = false;
			$contribuyente = null;
			$caption = Yii::t('backend', 'Infomation of the request');
			$subCaption = Yii::t('backend', 'Request');
			$url = Url::to(['procesar-solicitud']);

			// Identificador de la solicitud seleccionada por el usuario.
			// nro de solicitud.
			$id = isset($postData['id']) ? $postData['id'] : null;

			if ( $id !== null ) {
				$modelSearch = New SolicitudAsignadaSearch();
				$infoSolicitud = $modelSearch->findSolicitudSeleccionada($id);

				// Se buscan los datos basicos del contribuyente.
				if ( isset($infoSolicitud->id_contribuyente) ) {
					$contribuyente = $modelSearch->getDatosBasicoContribuyenteSegunId($infoSolicitud->id_contribuyente);
					if ( count($contribuyente) > 0 ) {
						$_SESSION['idContribuyente'] = $infoSolicitud->id_contribuyente;

						// Vista detalle de la solicitud, es la informacion que se cargo.
						$detalle = New DetalleSolicitudCreada($id);
						$viewDetalle = $detalle->getDatosSolicitudCreada();

						if ( $viewDetalle !== false ) {

							// Se buscan los Documentos y Requisitos de la Solicitud.
							$modelDoc = New SolicitudDocumentoSearch($id);
							$dataProvider = $modelDoc->getDataProvider();
							if ( $dataProvider->count > 0 ) { $exigirDocumento = true; }

							// Se buscan las planillas relacionadas a la solicitud. Se refiere a las planillas
							// de impueso "tasa".
							$modelPlanilla = New SolicitudPlanillaSearch($id);
							$provider = $modelPlanilla->getArrayDataProvider();

							$dataProviderPlanilla = $provider;

							return $this->render('/funcionario/solicitud-asignada/_view', [
																	'model' => $infoSolicitud,
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'listado' => 6,
																	'url' => $url,
																	'contribuyente' => $contribuyente,
																	'viewDetalle' => $viewDetalle,
																	'dataProvider' => $dataProvider,
																	'dataProviderPlanilla' => $dataProviderPlanilla,
																	'exigirDocumento' => $exigirDocumento,
																	'errorChk' => $errorChk,


								]);
						} else {
							// No se encontraron los detalles de la solicitud.
							return self::actionErrorOperacion(946);
						}
					} else {
						// Contribuyente no definido.
						return self::actionErrorOperacion(932);
					}

				} else {
					// Contribuyente no definido.
					return self::actionErrorOperacion(932);
				}

			} else {
				// Solicitud no definida.
				return self::actionErrorOperacion(404);
			}
		}




		/**
		 * Metodo que permite realizar una consulta sobre la entidad "solicitudes-contribuyente"
		 * para localizar todos las solciitudes que coincidan con los parametros de busqueda.
		 * Esta busqueda filtra que el funcionario solo pueda ver las solicitudes que le fueron
		 * asignadaas para su procesamiento.
		 * @return View Retorna una lista de solicitudes con un boton por cada solicitud. Dicho
		 * boton permitira ver mas detalle de la solicitud.
		 */
		public function actionBuscarSolicitudesContribuyente()
		{
			$postInicial = isset($_SESSION['postBusquedaInicial']) ? $_SESSION['postBusquedaInicial'] : null;
			if ( $postInicial !== null ) {
				$model = New SolicitudAsignadaForm();     // Modelo del formulario de busqueda.
				$model->load($postInicial);

				$request = Yii::$app->request;
				$postData = isset($request->queryParams['page']) ? $request->queryParams : $postInicial;

				$url = Url::to(['buscar-solicitud-seleccionada']);
				$modelSearch = New SolicitudAsignadaSearch();
				$modelSearch->attributes = $model->attributes;

				$modelSearch->load($postData);

				$userLocal = Yii::$app->user->identity->username;

				// Lista de los identificadores de los tipos de solicitud asociado al funcionario.
				$lista = $modelSearch->getTipoSolicitudAsignada($userLocal);

				$caption = Yii::t('backend', 'Lists of Request Authorized');
				$subCaption = Yii::t('backend', 'Lists of Request Authorized');

				$dataProvider = $modelSearch->getDataProviderSolicitudContribuyente($lista);

				return $this->render('/funcionario/solicitud-asignada/lista-solicitudes-elaboradas', [
																	'model' => $modelSearch,
																	'dataProvider' => $dataProvider,
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'url' => $url,
																	'listado' => 5,
					]);
			} else {
				return self::actionQuit();
			}
		}




		/**
		 * [actionQuit description]
		 * @return [type] [description]
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/funcionario/solicitud-asignada/quit');
		}



		/**
		 * [actionAnularSession description]
		 * @param  [type] $varSessions [description]
		 * @return [type]              [description]
		 */
		public function actionAnularSession($varSessions)
		{
			Session::actionDeleteSession($varSessions);
		}



		/**
		 * [actionProcesoExitoso description]
		 * @return [type] [description]
		 */
		public function actionProcesoExitoso($codigo)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($codigo);
		}



		/**
		 * [actionErrorOperacion description]
		 * @param  [type] $codigo [description]
		 * @return [type]         [description]
		 */
		public function actionErrorOperacion($codigo)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($codigo);
		}



		/**
		 * [actionGetListaSessions description]
		 * @return [type] [description]
		 */
		public function actionGetListaSessions()
		{
			return $varSession = [
							'postData',
							'idContribuyente',
							'mensajeErrorChk',
							'nroSolicitud',
							'postBusquedaInicial',
							'postSeleccionado',
					];
		}



		/**
		 * Metodo que permite renderizar un combo de tipos de solicitudes
		 * segun el parametro impuestos.
		 * @param  Integer $i identificador del impuesto.
		 * @return Renderiza una vista con un combo de impuesto.
		 */
		public function actionListSolicitud($i)
	    {
	       // die('hola, entro a list');
	       	$userLocal = Yii::$app->user->identity->username;
	    	$model = New SolicitudAsignadaSearch();

	    	// Todas las solicitudes asignadas.
	    	$listaSolicitud = $model->getTipoSolicitudAsignada($userLocal);

	    	// Lista de solicitudes filtradas por el impuesto, es decir, las solicitudes
	    	// relacionada al impuesto $i.
	    	$lista = $model->getFiltrarSolicitudAsignadaSegunImpuesto($i, $listaSolicitud);

	        $countSolicitud = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	        									   ->andWhere(['IN', 'id_tipo_solicitud', $lista])
	        									   ->andwhere('inactivo =:inactivo', [':inactivo' => 0])
	        									   ->count();

	        //$solicitudes = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])->all();

	        $solicitudes = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	        									->andWhere(['IN', 'id_tipo_solicitud', $lista])
	        									->andwhere('inactivo =:inactivo', [':inactivo' => 0])
	        									->all();

	        if ( $countSolicitud > 0 ) {
	        	echo "<option value='0'>" . "Select..." . "</option>";
	            foreach ( $solicitudes as $solicitud ) {
	                echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }
	    }

	}
?>