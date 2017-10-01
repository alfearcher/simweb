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


 	namespace backend\controllers\planilla;


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
	use backend\models\usuario\AutorizacionUsuario;


	if ( !isset($_SESSION) ) {
		session_start();
	}


	/***/
	class PlanillaConsultaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario




		/**
		 * Metodo que inicia, retorna una vista con un listado de los impuesto
		 * que tiene registradas deudas, por parte del contribuyente.
		 * @return view
		 */
		public function actionIndex()
		{
			$autorizacion = New AutorizacionUsuario();
			//$r = $autorizacion->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']);
			$r = true;
			if ( $r ) {
				if ( isset($_SESSION['idContribuyente']) ) {

					$idContribuyente = $_SESSION['idContribuyente'];

					$request = Yii::$app->request;
					$postData = $request->post();

					if ( isset($postData['btn-quit']) ) {
						if ( $postData['btn-quit'] == 1 ) {
							$this->redirect(['quit']);
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

					return $this->render('@frontend/views/planilla/consulta/_view-consulta',[
													'model' => $model,
													'caption' => $caption,
													'subCaption' => $subCaption,
													'listaImpuesto' => $listaImpuesto,
													'collapseDeuda' => $collapseDeuda,
													'url' => Url::to(['generar-pdf']),
							]);


				} else {
					// No esta definida la session del contribuyente.
					$this->redirect(['quit']);
				}
			} else {
				// Su perfil no esta autorizado.
				// El usuario no esta autorizado.
            	$this->redirect(['error-operacion', 'cod' => 700]);
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
			$obj = true;

			if ( isset($postData['p']) ) {
				$planilla = $postData['p'];
				$objeto = $postData['o'];

				if ( $postData['o'] == "false" ) {
					$pdf = New PlanillaPdfController($planilla, false);
				} else {
					$pdf = New PlanillaPdfController($planilla, true);
				}
				$pdf->actionGenerarPlanillaPdf();

			}

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
							'postData',
							'conf',
							'begin',
					];

		}





	}
?>