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
 *	@file NegacionSolicitudController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-06-2016
 *
 *  @class NegacionSolicitudController
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


 	namespace backend\controllers\solicitud\negacion;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use backend\models\solicitud\negacion\NegacionSolicitudForm;


	//session_start();		// Iniciando session

	/**
	 *
	 */
	class NegacionSolicitudController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario


		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$model = New NegacionSolicitudForm();
			$request = Yii::$app->request;

			$postData = $request->post();

	  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
	      	}

	      	if ( $model->load($postData) ) {
	      	 	if ( $model->validate() ) {

	      	 	}
	  		}

	  		// Se obtiene una lista de causas de negacion de solicitudes para mostrarlo
	  		// en un combo-lista, esto se obtuvo con el ArrayHelper.
	  		$lista = $model->listaCausasNegacion();

  			return $this->render('/solicitud/negacion/negacion-solicitud-form.php', [
  														'model' => $model,
  														'listaCausas' => $lista,
  														'caption' => 'dddd',
  					]);

		}












    	/**
    	 * [actionQuit description]
    	 * @return [type] [description]
    	 */
    	public function actionQuit()
    	{
    		unset($_SESSION['idInscripcion']);
    		return $this->render('/aaee/inscripcion-sucursal/quit');
    	}





    	/**
    	 * [gestionarMensajesLocales description]
    	 * @param  [type] $mensajeLocal [description]
    	 * @return [type]               [description]
    	 */
    	public function gestionarMensajesLocales($mensajeLocal)
    	{
    		if ( trim($mensajeLocal) != '' ) {
    			return MensajeController::actionMensaje($mensajeLocal);
    		}
    	}

	}
?>