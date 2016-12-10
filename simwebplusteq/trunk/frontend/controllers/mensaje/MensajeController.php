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
 *	@file MensajeController.php
 *
 *	@author Manuel Alejandro Zapata Canelon
 *
 *	@date 04/01/2016
 *
 *  @class MensajeController
 *	@brief Clase MensajeController, controller de mensaje.
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


 namespace frontend\controllers\mensaje;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;


	/**
	 *
	 */
	class MensajeController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario



    	public function actionMensaje($cuerpoMensaje = '', $tipoModal = true)
    	{
    		return $this->render('/mensaje/mensaje-modal', ['cuerpoMensaje' => $cuerpoMensaje, 'tipoModal' => $tipoModal]);
    	}

	}
?>