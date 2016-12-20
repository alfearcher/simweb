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
 *	@file LicenciaGenerarController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2016
 *
 *  @class LicenciaGenerarController
 *	@brief Clase LicenciaGenerarController del lado del contribuyente backend.
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


 	namespace backend\controllers\aaee\solvencia;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\enviaremail\PlantillaEmail;
	use common\models\numerocontrol\NumeroControlSearch;
	use backend\models\aaee\historico\solvencia\HistoricoSolvenciaSearch;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use backend\models\aaee\solvencia\SolvenciaActividadEconomica;
	use common\controllers\pdf\solvencia\SolvenciaController;




	session_start();		// Iniciando session

	/**
	 * Clase principal que permite mostrar la ultima solvencia generada segun el historico
	 * y permite a traves de una vista descargar dicha solvencia.
	 */
	class SolvenciaActividadGenerarController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;



		/**
		 * Metodo que mostrara la vista donde se encuentra el ultimo registro del historico
		 * para el año actual.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin']);
			if ( $_SESSION['idContribuyente']) {
				$idContribuyente = $_SESSION['idContribuyente'];

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}


				$searchModel = New HistoricoSolvenciaSearch($idContribuyente, 1);
				$model = $searchModel->findUltimoHistoricoAnoActual();

				if ( isset($postData['btn-generar-nro-licencia']) ) {
					if ( $postData['btn-generar-nro-licencia'] == 5 ) {

					}

				} elseif ( isset($postData['btn-generar-pdf']) ) {
					if ( $postData['btn-generar-pdf'] == 4 ) {
						// Se generar el pdf de la licencia

					}

				} else {

					if ( count($model) > 0 ) {
						$_SESSION['id_historico'] = $model->id_historico;
						$_SESSION['nro_control'] = $model->nro_control;
						$solicitudSolvencia = New SolvenciaActividadEconomica();
						$search = $solicitudSolvencia->find()->where('nro_solicitud =:nro_solicitud',
																		[':nro_solicitud' => $model->nro_solicitud])
						                                     ->one();
						return $this->render('@frontend/views/aaee/solvencia/historico/historico-solvencia', [
																		'model' => $model,
																		'searchSolicitud' => $search,
								]);
					} else {
						// No existe el historico para mostrar.
						throw new NotFoundHttpException(Yii::t('frontend', 'No se encontro un historico valido'));
					}
				}

			} else {
				// NO esta definido el contribuyente.
				throw new NotFoundHttpException(Yii::t('frontend', 'No se pudo obtener la informacion de inicio de session'));
			}
		}



		/***/
		public function actionGenerarSolvenciaPdf()
		{
			$request = Yii::$app->request;
			$getData = $request->get();

			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['id_historico']) && isset($_SESSION['nro_control']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];

				if ( (int)$_SESSION['nro_control'] == (int)$getData['id'] ) {
					$idHistorico = $_SESSION['id_historico'];
					$nroControl = $_SESSION['nro_control'];
					$solvencia = New SolvenciaController($idHistorico, $idContribuyente, $nroControl);
					$solvencia->actionGenerarSolvenciaActividadEconomicaPdf();

				} else {
					// El numero de control de la request no coincide con el de la session
				}
			}
		}





		/**
		 * Metodo que permite enviar un email al contribuyente indicandole
		 * la confirmacion de la realizacion de la solicitud.
		 * @param  model $model modelo LicenciaSolicitudForm que contiene la informacion
		 * del identificador del contribuyente.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna un true si envio el correo o false en caso
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
						'id_historico',
						'nro_control',
						'begin',
					];
		}

	}
?>