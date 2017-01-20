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
 *	@file InscripcionPropagandaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 17-01-2017
 *
 *  @class InscripcionPropagandaController
 *	@brief Clase InscripcionPropagandaController, controlador del lado del frontend
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


 	namespace frontend\controllers\propaganda\inscripcionpropaganda;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use yii\base\Exception;
	use backend\models\propaganda\inscripcionpropaganda\InscripcionPropagandaForm;
	use backend\models\propaganda\inscripcionpropaganda\InscripcionPropagandaSearch;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\models\contribuyente\ContribuyenteBase;
	use common\enviaremail\PlantillaEmail;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanillaSearch;
	use common\models\planilla\PlanillaSearch;


	session_start();		// Iniciando session

	/**
	 *
	 */
	class InscripcionPropagandaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;
		public $envioCorreo;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';

		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 75;




		public function actionIndex()
		{
			$request = Yii::$app->request;
			self::actionAnularSession(['begin', 'nro_solicitud']);
			$this->redirect(['index-configurar', 'id' => $request->get('id')]);
		}





		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndexConfigurar()
		{
			$poseeSolicitud = false;
			$request = Yii::$app->request;
			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			$getData = $request->get();

			if ( isset($getData['id']) && $idContribuyente > 0 ) {

				if ( $getData['id'] == self::CONFIG ) {
					// identificador de la configuracion de la solicitud.
					$id = $getData['id'];
					$tipoSolicitud = 0;
					$tipoNaturaleza = '';
					$modelParametro = New ParametroSolicitud($id);
					// // Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
					// // del parametro y el valor del elemento es el contenido del campo en base de datos.
					$config = $modelParametro->getParametroSolicitud([
																'id_config_solicitud',
																'tipo_solicitud',
																'impuesto',
																'nivel_aprobacion'
																]);


					$_SESSION['conf'] = $config;

					$modelSearch = New InscripcionPropagandaSearch($idContribuyente);
					if ( $modelSearch->esUnContribuyenteJuridico() ) {

						$_SESSION['begin'] = 1;
						return $this->redirect(['index-create']);

					} else {
						// Naturaleza del Contribuyente no definido o no corresponde con el tipo de solicitud.
	  					return $this->redirect(['error-operacion', 'cod' => 930]);
					}
				} else {
					// No esta definido el identificador de la configuracion de la solicitud.
					return $this->redirect(['error-operacion', 'cod' => 940]);
				}

			} else {
				// No esta definido el identificador de la configuracion de la solicitud.
				return $this->redirect(['error-operacion', 'cod' => 940]);
			}

		}



		/***/
		public function actionIndexCreate()
		{
			$result = false;
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();

				$idContribuyente = $_SESSION['idContribuyente'];

				$model = New InscripcionPropagandaForm();
				$model->scenario = self::SCENARIO_FRONTEND;

				$searchPropaganda = New InscripcionPropagandaSearch($idContribuyente);

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}

				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 5 ) {

						$model->load($postData);

						$model->fecha_inicio = date('Y-m-d', strtotime($model->fecha_inicio));
						$model->fecha_fin = date('Y-m-d', strtotime($model->fecha_fin));

						$result = self::actionBeginSave($model, $postData);
		      	 		if ( $result ) {
		      	 			$this->_transaccion->commit();
		      	 			$this->_conn->close();
		      	 			self::actionMostrarSolicitudCreada($model);

		      	 		} else {
		      	 			$this->_transaccion->rollBack();
		      	 			$this->_conn->close();
		      	 			$this->redirect(['error-operacion', 'cod' => 920]);

		      	 		}

					}
				}

		  		if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	$caption = Yii::t('frontend', 'Inscripción de Propaganda');

		      	if ( $model->load(Yii::$app->request->post()) ) {

		      		if ( isset($postData['btn-create']) ) {

		      			if ( $postData['btn-create'] == 3 ) {

				      	 	if ( $model->validate() ) {

				      	 		if ( $model->validateInputBaseCalculo() ) {

				      	 			$listaUsoPropaganda = $searchPropaganda->getListaUsoPropaganda($model->uso_propaganda);
							  		$listaClasePropaganda = $searchPropaganda->getListaClasePropaganda($model->clase_propaganda);

							  		$listaTiempo = $searchPropaganda->getListaTiempo();
							  		$listaMedioDifusion = $searchPropaganda->getListaMewdioDifusion();
							  		$listaMedioTransporte = $searchPropaganda->getListaMewdioTransporte();
									$listaTipoPropaganda = $searchPropaganda->getListaTipoPropaganda($model->uso_propaganda,
									                                                                 $model->clase_propaganda,
									                                                                 $model->ano_impositivo,
									                                                                 $model->tipo_propaganda);

				      	 			$caption = Yii::t('frontend', 'Confirmar') . '. ' . $caption;

					      	 		// Mostrar vista previa de la solicitud para confirmar.
									return $this->render('@frontend/views/propaganda/inscripcion-propaganda/_view',[
																				'model' => $model,
																				'caption' => $caption,
																				'listaUsoPropaganda' => $listaUsoPropaganda,
															        			'listaClasePropaganda' => $listaClasePropaganda,
															        			'listaTiempo' => $listaTiempo,
															        			'listaMedioDifusion' => $listaMedioDifusion,
															        			'listaMedioTransporte' => $listaMedioTransporte,
															        			'listaTipoPropaganda' => $listaTipoPropaganda,

										]);
					      	 	}

				      	 	}
				      	}
			      	}
		  		}

		  		$idUsos = $searchPropaganda->getIdentificadorSegunAnoImpositivo("uso_propaganda", date('Y'));
		  		$idClases = $searchPropaganda->getIdentificadorSegunAnoImpositivo("clase_propaganda", date('Y'));

		  		$idUsos = ( $idUsos !== null ) ? $idUsos : [];
		  		$idClases = ( $idClases !== null ) ? $idClases : [];

		  		$listaTipoPropaganda = [];
		  		if ( isset($postData['tipo_propaganda']) ) {
		  			$idTipo = $postData['tipo_propaganda'];
		  			$clase = $postData['clase_propaganda'];
		  			$uso = $postData['uso_propaganda'];
		  			$a = date('Y');

		  			$listaTipoPropaganda = $searchPropaganda->getListaTipoPropaganda($uso, $clase, $a);
		  		}

		  		$listaUsoPropaganda = $searchPropaganda->getListaUsoPropaganda($idUsos);
		  		$listaClasePropaganda = $searchPropaganda->getListaClasePropaganda($idClases);

		  		$listaTiempo = $searchPropaganda->getListaTiempo();
		  		$listaMedioDifusion = $searchPropaganda->getListaMewdioDifusion();
		  		$listaMedioTransporte = $searchPropaganda->getListaMewdioTransporte();

		  		$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : [];
				$rutaAyuda = Yii::$app->ayuda->getRutaAyuda($conf['tipo_solicitud'], 'frontend');

				$model->id_contribuyente = $idContribuyente;

				$subCaption = Yii::t('frontend', 'Datos a Registrar de la Propaganda');
	  			return $this->render('@frontend/views/propaganda/inscripcion-propaganda/_create', [
	  																'model' => $model,
												        			'caption' => $caption,
												        			'subCaption' => $subCaption,
												        			'rutaAyuda' => $rutaAyuda,
												        			'listaUsoPropaganda' => $listaUsoPropaganda,
												        			'listaClasePropaganda' => $listaClasePropaganda,
												        			'listaTiempo' => $listaTiempo,
												        			'listaMedioDifusion' => $listaMedioDifusion,
												        			'listaMedioTransporte' => $listaMedioTransporte,
												        			'listaTipoPropaganda' => $listaTipoPropaganda,
	  				]);

	  		} else {
	  			// Contribuyente no definido.
	  			return $this->redirect(['error-operacion', 'cod' => 930]);
	  		}

		}





		/***/
		public function actionGenerarListaTipo()
		{
			$request = Yii::$app->request->get();
			$uso = isset($request['u']) ? $request['u'] : 0;
			$clase = isset($request['c']) ? $request['c'] : 0;
			$añoImpositivo = (int)date('Y');
			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			$searchPropaganda = New InscripcionPropagandaSearch($idContribuyente);
			if ( $uso > 0 && $añoImpositivo > 0 && $clase > 0 ) {

				return $searchPropaganda->generarViewListaTipoPropaganda($uso, $clase, $añoImpositivo);

			}
			return '';
		}





		/***/
		public function actionDeterminarFechaHasta()
		{
			$request = Yii::$app->request->get();
			$cantidad = isset($request['c']) ? $request['c'] : 0;		// cantidad de tiempo.
			$idTiempo = isset($request['t']) ? $request['t'] : 0;		// id tiempo
			$fecha = isset($request['f']) ? $request['f'] : 0;			// fecha de inicio

			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			if ( $cantidad > 0 && $idTiempo > 0 && $fecha !== 0 ) {

				$searchPropaganda = New InscripcionPropagandaSearch($idContribuyente);
				return $searchPropaganda->getFechaHasta($cantidad, $idTiempo, $fecha);
			}

			//return '';
		}




		/***/
		public function actionDeterminarBaseCalculo()
		{
			$request = Yii::$app->request->get();
			$tipo = isset($request['t']) ? $request['t'] : 0;		// tipo de propaganda.

			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			if ( $tipo > 0 ) {

				$searchPropaganda = New InscripcionPropagandaSearch($idContribuyente);
				return $searchPropaganda->getBaseCalculo($tipo);

			}
		}






		/***/
		private function actionBeginSave($model, $postEnviado)
		{
			$this->envioCorreo = false;
			$result = false;
			$nroSolicitud = 0;
			$idImpuesto = 0;

			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin'])  ) {
				if ( $_SESSION['idContribuyente'] > 0 && $_SESSION['begin'] == 1 ) {

					$this->_conexion = New ConexionController();

					// Instancia de conexion hacia la base de datos.
			      	$this->_conn = $this->_conexion->initConectar('db');
			      	$this->_conn->open();

			      	// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
			      	// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();
					$nroSolicitud = self::actionCreateSolicitud($this->_conexion, $this->_conn);
					if ( $nroSolicitud > 0 ) {

						$model->nro_solicitud = $nroSolicitud;

						if ( $conf['nivel_aprobacion'] == 1 ) {

							// Se guarda en la entidad principal
							$idImpuesto = self::actionCreatePropaganda($model, $this->_conexion, $this->_conn, $conf);

							if ( $idImpuesto > 0 ) {
								// Se pasa a guardar en la sl- de la solicitud
								$model->id_impuesto = $idImpuesto;
								$result = self::actionCreateInscripcionPropaganda($model, $this->_conexion, $this->_conn, $conf);
							}

						} elseif ( $conf['nivel_aprobacion'] == 2 ) {

							// Se pasa a guardar en la sl- de la solicitud
							$result = self::actionCreateInscripcionPropaganda($model, $this->_conexion, $this->_conn, $conf);

						}

						if ( $result ) {
							$result = self::actionEjecutaProcesoSolicitud($model, $this->_conexion, $this->_conn);
						}

						if ( $result ) {

							$this->envioCorreo = self::actionEnviarEmail($model);

						}

					}

				} else {
					// Operacion no ejecutada.

					return $this->redirect(['error-operacion', 'cod' => 920]);

				}
			} else {

				return $this->redirect(['error-operacion', 'cod' => 920]);
			}
			return $result;
		}



		/**
		 * Metodo que guarda el registro respectivo en la entidad "solicitudes-contribuyente".
		 * @param  Class $conexionLocal instancia de tipo ConexionController
		 * @param  [type] $connLocal     [description]
		 * @return Retorna un long, que es el numero de la solicitud generado.
		 */
		private function actionCreateSolicitud($conexionLocal, $connLocal)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$nroSolicitud = 0;
			$modelSolicitud = New SolicitudesContribuyenteForm();
			$tabla = $modelSolicitud->tableName();
			$idContribuyente = $_SESSION['idContribuyente'];

			$nroSolicitud = 0;
			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;

			if ( count($conf) > 0 ) {
				// Valores que se pasan al modelo:
				// id-config-solicitud.
				// impuesto.
				// tipo-solicitud.
				// nivel-aprobacion
				$modelSolicitud->attributes = $conf;

				if ( $conf['nivel_aprobacion'] == 1 ) {
					$estatus = 1;
					$userFuncionario = Yii::$app->identidad->getUsuario();
					$fechaHoraProceso = date('Y-m-d H:i:s');
				}

				$modelSolicitud->id_contribuyente = $idContribuyente;
				$modelSolicitud->id_impuesto = 0;
				$modelSolicitud->usuario = Yii::$app->identidad->getUsuario();
				$modelSolicitud->fecha_hora_creacion = date('Y-m-d H:i:s');
				$modelSolicitud->inactivo = 0;
				$modelSolicitud->estatus = $estatus;
				$modelSolicitud->nro_control = 0;
				$modelSolicitud->user_funcionario = $userFuncionario;
				$modelSolicitud->fecha_hora_proceso = $fechaHoraProceso;
				$modelSolicitud->causa = 0;

				// Arreglo de datos del modelo para guardar los datos.
				$arregloDatos = $modelSolicitud->attributes;

				if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
					$nroSolicitud = $connLocal->getLastInsertID();
				}
			}

			return $nroSolicitud;
		}



		/***/
		private function actionCreateInscripcionPropaganda($model, $conexionLocal, $connLocal, $conf)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$result = false;
			$tabla = $model->tableName();
			$idContribuyente = $_SESSION['idContribuyente'];

			if ( $conf['nivel_aprobacion'] == 1 ) {
				$estatus = 1;
				$userFuncionario = Yii::$app->identidad->getUsuario();
				$fechaHoraProceso = date('Y-m-d H:i:s');
			}

			$model->origen = 'WEB';
			$model->fecha_hora = date('Y-m-d H:i:s');
			$model->usuario = Yii::$app->identidad->getUsuario();

			$model->user_funcionario = $userFuncionario;
			$model->fecha_hora_proceso = $fechaHoraProceso;
			$model->estatus = $estatus;

			// Arreglo de datos para pasarle los datos del modelo.
			$arregloDatos = $model->attributes;

			$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);

			return $result;
		}




		/***/
		private function actionCreatePropaganda($model, $conexionLocal, $connLocal, $conf)
		{
			$idImpuesto = 0;
			if ( $conf['nivel_aprobacion'] == 1 ) {

				if ( isset($_SESSION['idContribuyente']) ) {
					$idContribuyente = $_SESSION['idContribuyente'];
					if ( $model->id_contribuyente == $idContribuyente ) {

						$model->user_funcionario = Yii::$app->identidad->getUsuario();
						$model->fecha_hora_proceso = date('Y-m-d H:i:s');

						$searchPropaganda = New InscripcionPropagandaSearch($idContribuyente);
						$idImpuesto = $searchPropaganda->guardarPropaganda($model, $conexionLocal, $connLocal);

					}
				}
			}

			return $idImpuesto;
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
		 * @param  model $model modelo de la instancia InscripcionSucursalForm.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna true si todo se ejecuto correctamente false en caso contrario.
		 */
		public function actionEjecutaProcesoSolicitud($model, $conexionLocal, $connLocal)
		{
			$result = true;
			$resultadoProceso = [];
			$acciones = [];
			$evento = '';
			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;
			if ( count($conf) > 0 ) {
				if ( $conf['nivel_aprobacion'] == 1 ) {
					$evento = Yii::$app->solicitud->aprobar();
				} else {
					$evento = Yii::$app->solicitud->crear();
				}

				$procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

				// Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
				// que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
				// de resultados donde el key del arrary es el nombre del proceso y el valor del elemento
				// corresponda a un reultado de la ejecucion.
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, $evento, $conexionLocal, $connLocal);

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
			}

			return $result;

		}



		/**
		 * Metodo que permite enviar un email al contribuyente indicandole
		 * la confirmacion de la realizacion de la solicitud
		 * @param  Active Record $model modelo que contiene la informacion
		 * del identificador del contribuyente.
		 * @return Boolean Retorna un true si envio el correo o false en caso
		 * contrario.
		 */
		public function actionEnviarEmail($model)
		{
			$result = false;
			$listaDocumento = '';
			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;
			if ( count($conf) > 0 ) {
				$parametroSolicitud = New ParametroSolicitud($conf['id_config_solicitud']);
				$nroSolicitud = $model->nro_solicitud;
				$descripcionSolicitud = $parametroSolicitud->getDescripcionTipoSolicitud();
				$listaDocumento = $parametroSolicitud->getDocumentoRequisitoSolicitud();

				$email = ContribuyenteBase::getEmail($model->id_contribuyente);
				try {
					$enviar = New PlantillaEmail();
					$result = $enviar->plantillaEmailSolicitud($email, $descripcionSolicitud, $nroSolicitud, $listaDocumento);
				} catch (Exception $e) {
					echo $e->getName();
				}
			}
			return $result;
		}




		/***/
		public function actionMostrarSolicitudCreada($model)
		{
			$_SESSION['nro_solicitud'] = $model->nro_solicitud;
			$this->redirect(['view-solicitud']);
		}



		/***/
		public function actionViewSolicitud()
		{
			$id = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : null;
			$nro = isset($_SESSION['nro_solicitud']) ? $_SESSION['nro_solicitud'] : null;

			$modelSearch = New InscripcionPropagandaSearch($id);
			$model = $modelSearch->findSolicitudInscripcionPropaganda($nro);


			// Se buscan las planillas relacionadas a la solicitud. Se refiere a las planillas
			// de impueso "tasa".
			$modelPlanilla = New SolicitudPlanillaSearch($nro, Yii::$app->solicitud->crear());
			$dataProvider = $modelPlanilla->getArrayDataProvider();

			$caption = Yii::t('frontend', 'Planilla(s)');
			$viewSolicitudPlanilla = $this->renderAjax('@common/views/solicitud-planilla/solicitud-planilla', [
															'caption' => $caption,
															'dataProvider' => $dataProvider,
				]);

			return $this->render('@frontend/views/propaganda/inscripcion-propaganda/_view-create', [
											'caption' => Yii::t('frontend', 'Request Nro. ' . $nro),
											'model' => $model,
											'codigo' => 100,
											'viewSolicitudPlanilla' => $viewSolicitudPlanilla,
				]);
		}




    	/**
		 * [actionQuit description]
		 * @return [type] [description]
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/menu/menu-vertical');
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
		public function actionProcesoExitoso()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return true;
			//return MensajeController::actionMensaje(100);
		}



		/**
		 * [actionErrorOperacion description]
		 * @param  [type] $codigo [description]
		 * @return [type]         [description]
		 */
		public function actionErrorOperacion($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}



		/**
		 * [actionGetListaSessions description]
		 * @return [type] [description]
		 */
		public function actionGetListaSessions()
		{
			return $varSession = [
							'postData',
							'conf',
							'nro_solicitud',
							'begin',
					];
		}



	}
?>