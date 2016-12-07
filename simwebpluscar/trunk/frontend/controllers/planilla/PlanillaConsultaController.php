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
 *	@file PlanillaConsultaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-11-2016
 *
 *  @class PlanillaConsultaController
 *	@brief Clase que gestiona la generacion del pdf de las planillas
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


 	namespace frontend\controllers\planilla;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	// use common\models\numerocontrol\NumeroControlSearch;
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;
	use common\controllers\pdf\planilla\PlanillaPdfController;

	use mPDF;


	/***/
	class PlanillaConsultaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario



		/***/
		public function actionIndex()
		{
			$numero = 1088994;
			// $numero =1078731;
			// $numero =3216421;
			//$numero = 769965;
			 $numero = 2179852;
			// $numero = 1078731;
			// $numero = 945628;
			// $numero = 967620;
			 $numero = 963146;
			$planillaPdf = New PlanillaPdfController($numero);
			$result = $planillaPdf->actionGenerarPlanillaPdf();



		}






	}
?>