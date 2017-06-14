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
 *	@file ContribuyenteGeneralController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-06-2017
 *
 *  @class ContribuyenteGeneralController
 *	@brief Clase que gestiona la consulta de los contribuyentes existentes
 *  por pantalla o impreso.
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


 	namespace backend\controllers\reporte\contribuyente\general;


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
	use backend\models\reporte\contribuyente\general\ContribuyenteGeneralSearch;



	session_start();


	/**
	 * Clase que permite gestinar la generacion de reportes sobre los contribuyentes
	 * existentes. Los reporte seran emitidos por pantalla e impreso (pdf).
	 */
	class ContribuyenteGeneralController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario


		public function actionIndex()
		{
			$_SESSION['begin'] = 1;
			$this->redirect(['mostrar-form-consulta-contribuyente']);
		}



		/**
		 * Metodo que inicia el modulo de lqiquidacion.
		 * @return [type] [description]
		 */
		public function actionMostrarFormConsultaContribuyente()
		{
			$usuario = Yii::$app->identidad->getUsuario();
			$model = New ContribuyenteGeneralSearch();
			$formName = $model->formName();

			if ( $model->estaAutorizado($usuario) ) {

				$request = Yii::$app->request;
				$postData = $request->bodyParams;

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						$this->redirect(['index']);
					}
				}

//die(var_dump($postData));
				$caption = Yii::t('backend', 'Busqueda de Contribuyentes');
				$subCaption = Yii::t('backend', 'Parametros de consulta');

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load($postData) ) {
		      		if ( $model->validate() ) {

						$subCaption = Yii::t('backend', 'Resultado de la Consulta');
		      			$dataProvider = $model->getDataProvider();
		      			return $this->render('/reporte/contribuyente/general/contribuyente-existente-reporte', [
		      										'dataProvider' => $dataProvider,
		      										'caption' => $caption,
		      										'subCaption' => $subCaption,

		      		  		]);
		      		}
		      	}

		      	// Lista de tipo naturaleza
		      	$listaTipoNaturaleza = $model->getListaTipoNaturaleza();

		      	// Lista de Condicion del registro
		      	$listaCondicion = $model->getListaCondicionContribuyente();

		      	// Se muestra el formulario de busqueda.
		      	return $this->render('/reporte/contribuyente/general/contribuyente-general-consulta-form', [
		      										'model' => $model,
		      										'caption' => $caption,
		      										'subCaption' => $subCaption,
		      										'listaTipoNaturaleza' => $listaTipoNaturaleza,
		      										'listaCondicion' => $listaCondicion,
		      		  ]);

			} else {
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}











		/***/
		public function actionGenerarPdf()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($postData['planilla']) ) {
				$planilla = $postData['planilla'];
				$pdf = New PlanillaPdfController($planilla);
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