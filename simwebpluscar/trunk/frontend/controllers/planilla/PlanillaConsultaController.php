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
	use common\models\planilla\PlanillaSearch;
	use common\controllers\pdf\planilla\PlanillaPdfController;


	session_start();


	/***/
	class PlanillaConsultaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario





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


				$model = New PlanillaConsultaForm();
				$model->load($postData);

				$formName = $model->formName();

				if ( isset($postData['btn-search-planillas']) ) {
					if ( $postData['btn-search-planillas'] == 5 ) {

					}
				} elseif ( isset($postData['btn-search-objeto']) ) {
					if ( $postData['btn-search-objeto'] == 3 ) {

					}
				}

				$model->id_contribuyente = $idContribuyente;
				$deudaSearch = New DeudaSearch($idContribuyente);

				// Crea un arreglo de impuestos que indica donde existen deudas del contribuyente.
				$listaImpuesto = $deudaSearch->getImpuestoConDeuda();

				$collapseDeuda = $model->generarCollapseDeuda();

				$caption = Yii::t('frontend', 'Consulta de Planilla(s)');
				$subCaption = Yii::t('frontend', 'Seleccione el Impuesto');

				return $this->render('/planilla/consulta/_view-consulta',[
												'model' => $model,
												'caption' => $caption,
												'subCaption' => $subCaption,
												'listaImpuesto' => $listaImpuesto,
												'collapseDeuda' => $collapseDeuda,
												'url' => Url::to(['generar-pdf']),
						]);


			} else {
				// No esta definida la session del contribuyente.
			}
		}




		/**
		 * Metodo que permite renderizar una vista de los detalles de la planilla
		 * que se encuentran en la solicitud.
		 * @return View Retorna una vista que contiene un grid con los detalles de la
		 * planilla.
		 */
		public function actionViewPlanilla()
		{
			$request = Yii::$app->request;
			$getData = $request->get();

			$planilla = $getData['p'];
			$planillaSearch = New PlanillaSearch($planilla);
			$dataProvider = $planillaSearch->getArrayDataProviderPlanilla();

			// Se determina si la peticion viene de un listado que contiene mas de una
			// pagina de registros. Esto sucede cuando los detalles de un listado contienen
			// mas de los manejados para una pagina en la vista.
			if ( isset($request->queryParams['page']) ) {
				$planillaSearch->load($request->queryParams);
			}
			$url = Url::to(['generar-pdf']);
			return $this->renderAjax('@backend/views/planilla/planilla-detalle', [
								 			'dataProvider' => $dataProvider,
								 			'caption' => 'Planilla: ' . $planilla,
								 			'p' => $planilla,
			]);
		}




		/***/
		public function actionGenerarPdf()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($postData['p']) ) {
				$planilla = $postData['p'];
				$pdf = New PlanillaPdfController($planilla);
				$pdf->actionGenerarPlanillaPdf();

			}

		}






	}
?>