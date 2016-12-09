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
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;
	use common\models\historico\cvbplanilla\GenerarValidadorPlanilla;
	use common\models\historico\cvbplanilla\HistoricoCodigoValidadorPlanillaForm;
	use common\models\deuda\DeudaSearch;
	use backend\models\planilla\consulta\PlanillaConsultaForm;


	session_start();


	/***/
	class PlanillaConsultaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		const SCENARIO_CONTRIBUYENTE = 'contribuyente';
		const SCENARIO_OBJETOS = 'objetos';





		/***/
		public function actionIndex()
		{

			if ( isset($_SESSION['idContribuyente']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {

					}
				}

// die(var_dump($postData));

				$model = New PlanillaConsultaForm();
				$model->load($postData);
// die(var_dump($model));

				$formName = $model->formName();

				$model->scenario = self::SCENARIO_CONTRIBUYENTE;

				if ( isset($postData['btn-search-planillas']) ) {
					if ( $postData['btn-search-planillas'] == 5 ) {

					}
				} elseif ( isset($postData['btn-search-objeto']) ) {
					if ( $postData['btn-search-objeto'] == 3 ) {

					}
				}

				$model->id_contribuyente = $idContribuyente;
				$deudaSearch = New DeudaSearch($idContribuyente);
				$listaImpuesto = $deudaSearch->getImpuestoConDeuda();

				$caption = Yii::t('frontend', 'Consulta de Planilla(s)');
				$subCaption = Yii::t('frontend', 'Seleccione el Impuesto');

				return $this->render('/planilla/consulta/_view-consulta',[
												'model' => $model,
												'caption' => $caption,
												'subCaption' => $subCaption,
												'listaImpuesto' => $listaImpuesto,
						]);


			} else {
				// No esta definida la session del contribuyente.
			}
		}




		public function actionPrueba()
		{
			$request = Yii::$app->request;
				$postData = $request->post();

die(var_dump($postData));
		}





		private function actionMostrarPdfPlanilla()
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