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
 *	@file ListadoPropagandaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 17-01-2017
 *
 *  @class ListadoPropagandaController
 *	@brief Clase
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


 	namespace frontend\controllers\propaganda\listado;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use yii\base\Exception;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\propaganda\listado\ListadoPropagandaForm;



	session_start();		// Iniciando session

	/**
	 *
	 */
	class ListadoPropagandaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario





		public function actionIndex()
		{
			$this->redirect(['listar']);
		}





		/***/
		public function actionListar()
		{
			if ( isset($_SESSION['idContribuyente']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request->queryParams;

				if ( count(Yii::$app->request->post()) > 0 ) {
					if ( Yii::$app->request->post()['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$listadoPropaganda = New ListadoPropagandaForm($idContribuyente);

				$dataProvider = $listadoPropaganda->search($request);

				return $this->render('@frontend/views/propaganda/listado/listado-propaganda',[
												'listadoPropaganda' => $listadoPropaganda,
												'dataProvider' => $dataProvider,
					]);

			} else {
				// Contribuyente no definido.
				$this->redirect(['error-operacion', 'cod' => 930]);
			}
		}





		/***/
		public function actionViewPropaganda()
		{
			$request = Yii::$app->request;
			$getData = $request->get();

			$idImpuesto = 0;
			$idImpuesto = $getData['p'];

			// Se busca los datos de la propagnada seleccionada.
			$listadoPropaganda = New ListadoPropagandaForm($_SESSION['idContribuyente']);

			$model = $listadoPropaganda->findPropaganda($idImpuesto);

			return $this->renderAjax('@frontend/views/propaganda/listado/view-propaganda',[
														'model' => $model,
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