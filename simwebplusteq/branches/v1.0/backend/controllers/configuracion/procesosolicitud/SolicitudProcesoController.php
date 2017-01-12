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
 *	@file ConfigurarSolicitudController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 26-02-2016
 *
 *  @class SolicitudProcesoController
 *	@brief Clase SolicitudProcesoController
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


 	namespace backend\controllers\configuracion\procesosolicitud;

 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\configuracion\procesosolicitud\SolicitudProcesoForm;
	//session_start();		// Iniciando session

	/**
	 *
	 */
	class SolicitudProcesoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		// public $connLocal;
		// public $conexion;
		// public $transaccion;


		public function actionListarProcesoSolicitud()
		{
			$dataProvider = SolicitudProcesoForm::getDataProvider();
			return $this->render('/configuracion/procesosolicitud/lista-proceso-solicitud', [
													'dataProvider' => $dataProvider,
				]);
		}


	}
?>