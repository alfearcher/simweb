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
	use backend\models\funcionario\solicitud\SolicitudAsignadaSearch;
	use backend\models\funcionario\solicitud\SolicitudAsignadaForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\impuesto\ImpuestoForm;

	/**
	 *	Clase principal del formulario.
	 */
	class SolicitudAsignadaController extends Controller
	{

	   	public $layout = 'layout-main';				//	Layout principal del formulario.

		public $conn;
		public $conexion;
		public $transaccion;



		/***/
		public function actionIndex2()
		{
			$model = New SolicitudAsignadaSearch();
			$lista = $model->getTipoSolicitudAsignada('jperez');

			$caption = Yii::t('backend', 'Lists of Request');
			$subCaption = Yii::t('backend', 'Lists of Request');
			$dataProvider = $model->getDataProviderSolicitudContribuyente($lista);
			return $this->render('/funcionario/solicitud-asignada/lista-solicitud-elaborada', [
																'model' => $model,
																'dataProvider' => $dataProvider,
																'caption' => $caption,
																'subCaption' => $subCaption,
				]);
		}




		/***/
		public function actionIndex()
		{
			$request = Yii::$app->request;
			$page = isset($request->queryParams['page']) ? $request->queryParams['page'] : null;

			// Modelo del formulario de busqueda de las solicitudes.
			$model = New SolicitudAsignadaForm();

			if ( $page == null ) {

				$postData = $request->post();

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ( $model->load($postData) ) {
					if ( $model->validate() ) {
						if ( isset($postData['btn-search-request']) ) {
							return self::actionBuscarSolicitudesContribuyente($model);
						}
					}
				}

				// Modelo adicionales para la busqueda de los funcionarios.
				$modelImpuesto = New ImpuestoForm();

				// Se define la lista de item para el combo de impuestos.
				$listaImpuesto = $modelImpuesto->getListaImpuesto(0, [1,2]);

				$m = New SolicitudAsignadaSearch();
				$r1 = $m->findImpuestoSegunFuncionario(Yii::$app->user->identity->id_funcionario);
//ie(var_dump($r1->asArray()->one()));

				$caption = Yii::t('backend', 'Search Request');
				return $this->render('/funcionario/solicitud-asignada/busqueda-solicitud-form', [
																				'model' => $model,
																				'modelImpuesto' => $modelImpuesto,
																				'caption' => $caption,
																				'listaImpuesto' => $listaImpuesto,

					]);
			} elseif ( $page > 0 && isset($request->queryParams['page']) ) {
				$model->load($request->queryParams);
				return self::actionBuscarSolicitudesContribuyente($model);
			}
		}




		/***/
		public function actionVerificarEnvio()
		{
			$request = Yii::$app->request;
			$postData = $request->post();
die(var_dump($postData));
		}





		/**
		 * [actionBuscarSolicitudesContribuyente description]
		 * @param  [type] $model [description]
		 * @return [type]        [description]
		 */
		public function actionBuscarSolicitudesContribuyente($model)
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			$url = Url::to(['verificar-envio']);
			$modelSolicitud = New SolicitudAsignadaSearch();
			$modelSolicitud->attributes = $model->attributes;

			$userLocal = Yii::$app->user->identity->username;
			$lista = $modelSolicitud->getTipoSolicitudAsignada('jperez');

			$caption = Yii::t('backend', 'Lists of Request');
			$subCaption = Yii::t('backend', 'Lists of Request');

			$dataProvider = $modelSolicitud->getDataProviderSolicitudContribuyente($lista);

			return $this->render('/funcionario/solicitud-asignada/lista-solicitudes-elaboradas', [
																'model' => $modelSolicitud,
																'dataProvider' => $dataProvider,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'url' => $url,
																'listado' => 10,
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
			return $this->render('/funcionario/quit');
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
							''
					];
		}

	}
?>