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
 *	@file InscripcionActividadEconomicaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 09-05-2016
 *
 *  @class InscripcionActividadEconomicaController
 *	@brief Clase InscripcionActividadEconomicaController, controlador del lado del frontend
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


 	namespace frontend\controllers\aaee\inscripcionactecon;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomica;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaForm;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\models\contribuyente\ContribuyenteBase;
	use common\enviaremail\PlantillaEmail;


	session_start();		// Iniciando session

	/**
	 *
	 */
	class InscripcionActividadEconomicaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $conn;
		public $conexion;
		public $transaccion;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';



		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$poseeSolicitud = false;
			$request = Yii::$app->request;
			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			$getData = $request->get();

			if ( isset($getData['id']) && $idContribuyente > 0 ) {

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

				$modelSearch = New InscripcionActividadEconomicaSearch($idContribuyente);
				$tipoNaturaleza = $modelSearch->getTipoNaturalezaDescripcionSegunID();
				if ( $tipoNaturaleza == 'JURIDICO') {
					// Se determina si el contribuyente ya posee una solicitud de este tipo, si es asi
					// se aborta la operacion de solicitud.
					$poseeSolicitud = $modelSearch->yaPoseeSolicitudSimiliar();
					if ( $poseeSolicitud ) {
						// Ya posee una solicitud de este tipo y no puede continuar.
						return $this->redirect(['error-operacion', 'cod' => 945]);
					} else {
						return $this->redirect(['index-create']);
					}
				} else {
					// Naturaleza del Contribuyente no definido o no corresponde con el tipo de solicitud.
  					return $this->redirect(['error-operacion', 'cod' => 930]);
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
			if ( isset($_SESSION['idContribuyente']) ) {
				$model = New InscripcionActividadEconomicaForm();
				$model->scenario = self::SCENARIO_FRONTEND;

		  		if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load(Yii::$app->request->post()) ) {

		      	 	if ( $model->validate() ) {
		      	 		// Todo bien la validacion es correcta.
		      	 		$_SESSION['guardar'] = 1;
		      	 		$result = self::actionBeginSave($model);
		      	 		if ( $result ) {
		      	 			$this->redirect(['proceso-exitoso']);
		      	 			//$this->redirect(['buscar-solicitud-creada']);
		      	 		} else {
		      	 			$this->redirect(['error-operacion', 'cod' => 920]);
		      	 		}
		      	 	}
		  		}

		  		$url = Url::to(['index-create']);
		  		$bloquear = false;
	  			return $this->render('/aaee/inscripcion-actividad-economica/_create', [
	  																'model' => $model,
	  																'bloquear' => $bloquear,
	  																'url' => $url,
	  				]);
	  		} else {
	  			// Contribuyente no definido.
	  			return $this->redirect(['error-operacion', 'cod' => 930]);
	  		}

		}



		/***/
		private function actionBeginSave($model)
		{
			$result = false;
			$nroSolicitud = 0;
			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['guardar'])  ) {
				if ( $_SESSION['idContribuyente'] > 0 && $_SESSION['guardar'] == 1 ) {

					$conexion = New ConexionController();

					// Instancia de conexion hacia la base de datos.
			      	$this->conn = $conexion->initConectar('db');
			      	$this->conn->open();

			      	// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
			      	// Inicio de la transaccion.
					$transaccion = $this->conn->beginTransaction();
					$nroSolicitud = self::actionCreateSolicitud($conexion, $this->conn);
					if ( $nroSolicitud > 0 ) {
						$model->nro_solicitud = $nroSolicitud;
						// Detalle de la solicitud
						$result = self::actionCreateInscripcionActEcon($model, $conexion, $this->conn);
						if ( count($conf) > 0 && $result == true ) {

							// Se define que tipo de aprobacion se debe aplicar en la solicitud.
							if ( $conf['nivel_aprobacion'] == 1 ) {

								// Solicitud de aprobacion directa. Se deben de pasar los datos
								// a las tablas principales. En este caso se actualiza los datos
								// del contribuyente con los datos anteriormente guardados.
								$result = self::actionUpdateContribuyente($model, $conexion, $this->conn);
							}

							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($model, $conexion, $this->conn);
							}
						}
						if ( $result ) {
							// Se envia el email respectivo
							self::actionEnviarEmail($model);
							$transaccion->commit();
						} else {
							$transaccion->rollBack();
						}
					}

					$this->conn->close();

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
					$userFuncionario = Yii::$app->user->identity->login;
					$fechaHoraProceso = date('Y-m-d H:i:s');
				}

				$modelSolicitud->id_contribuyente = $idContribuyente;
				$modelSolicitud->id_impuesto = 0;
				$modelSolicitud->usuario = Yii::$app->user->identity->login;
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
		private function actionCreateInscripcionActEcon($model, $conexionLocal, $connLocal)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$result = false;
			$tabla = $model->tableName();
			$idContribuyente = $_SESSION['idContribuyente'];

			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;
			if ( $conf['nivel_aprobacion'] == 1 ) {
				$estatus = 1;
				$userFuncionario = Yii::$app->user->identity->login;
				$fechaHoraProceso = date('Y-m-d H:i:s');
			}

			$model->origen = 'WEB';
			$model->fecha = date('Y-m-d', strtotime($model->fecha));
			$model->estatus = $estatus;
			$model->fecha_hora = date('Y-m-d H:i:s');
			$model->usuario = Yii::$app->user->identity->login;
			$model->user_funcionario = $userFuncionario;
			$model->fecha_hora_proceso = $fechaHoraProceso;

			// Arreglo de datos para pasarle los datos del modelo.
			$arregloDatos = $model->attributes;

			$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);

			return $result;
		}




		/***/
		private function actionUpdateContribuyente($model, $conexionLocal, $connLocal)
		{
			$result = false;
			$modelContribuyente = New ContribuyenteBase();
			$tabla = $modelContribuyente->tableName();

			// Se obtiene el array de atributos que seran actualizados.
			$atributos = $model->atributosUpDate();

			// where condicion del update.
			$arregloCondicion['id_contribuyente'] = $model->id_contribuyente;

			// arreglo de datos que se actualizaran.
			$arregloDatos = null;

			foreach ( $atributos as $atributo ) {
				if ( array_key_exists($atributo, $model->attributes) ) {
					$arregloDatos[$atributo] = $model->$atributo;
				}
			}

			if ( count($arregloDatos) > 0 ) {
				$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion);
			}

			return $result;
		}




		/***/
		public function actionEjecutaProcesoSolicitud($model, $conexionLocal, $connLocal)
		{
			$result = true;
			$resultadoProceso = [];
			$acciones = [];
			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;
			if ( count($conf) > 0 ) {
				$procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

				// Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
				// que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
				// de resultados donde el key del arrary es el nombre del proceso y el valor del elemento
				// corresponda a un reultado de la ejecucion.
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, Yii::$app->solicitud->crear(), $conexionLocal, $connLocal);

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



		/***/
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
//die(var_dump($listaDocumento));
				$email = ContribuyenteBase::getEmail($model->id_contribuyente);
die(var_dump($model->id_contribuyente));
				$enviar = New PlantillaEmail();
				$result = $enviar->plantillaEmailSolicitud($email, $descripcionSolicitud, $nroSolicitud, $listaDocumento);
			}
			return $result;
		}




		/***/
		public function actionBuscarSolicitudCreada($model)
		{
// die(var_dump($model));
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
		public function actionProcesoExitoso()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje(100);
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
							'getData',
							'postData',
							'conf',
							'guardar'
					];
		}



	}
?>