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
 *	@file PropagandaGeneralController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 08-07-2017
 *
 *  @class PropagandaGeneralController
 *	@brief Clase que gestiona la consulta de los contribuyentes existentes
 *  con sus respectivas propagnadas por pantalla o impreso.
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


 	namespace backend\controllers\reporte\propaganda\general;


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
	use backend\models\reporte\contribuyente\general\ContribuyenteObjetoBusquedaForm;
	use backend\models\reporte\contribuyente\general\ContribuyenteGeneralSearch;
	use backend\models\buscargeneral\BuscarGeneral;
	use backend\models\reporte\propaganda\general\PropagandaReporteSearch;


	session_start();


	/**
	 * Clase que permite gestinar la generacion de reportes sobre los contribuyentes
	 * existentes y sus propagnadas. Los reporte seran emitidos por pantalla e impreso (pdf).
	 */
	class PropagandaGeneralController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario


		public function actionIndex()
		{
			$varSessions = self::actionGetListaSessions();
			self::actionAnularSession($varSessions);
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
			$propagandaSearch = New PropagandaReporteSearch();

			$formName = $propagandaSearch->formName();

			if ( $propagandaSearch->estaAutorizado($usuario) ) {

				$request = Yii::$app->request;

				if ( $request->post('btn-quit') !== null ) {
					if ( $request->post('btn-quit') == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( $request->post('btn-back') !== null ) {
					if ( $request->post('btn-back') == 1 ) {
						$this->redirect(['index']);
					}
				}

				if ( $request->isGet ) {
					$postData = isset($_SESSION['postData']) ? $_SESSION['postData'] : [];
					$propagandaSearch->load($postData);
				} else {
					$postData = $request->post() !== null ? $request->post() : $_SESSION['postData'];
					$_SESSION['postData'] = $postData;
				}


				if ( isset($postData['page']) ) {
					$postData = $_SESSION['postData'];
					$propagandaSearch->load($postData);
				}

				$caption = Yii::t('backend', 'Busqueda de Propagandas');
				$subCaption = Yii::t('backend', 'Parametros de consulta');
				$captionObjeto = Yii::t('backend', 'Condicion de las propagandas');

				if ( $propagandaSearch->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($propagandaSearch);
		      	}

		      	if ( $propagandaSearch->load($postData) ) {
		      		if ( $propagandaSearch->validate() ) {
						$subCaption = Yii::t('backend', 'Resultado de la Consulta');

		      			$dataProvider = $propagandaSearch->getDataProvider(Yii::$app->request->queryParams);
		      			return $this->render('/reporte/propaganda/general/propaganda-existente-reporte',[
														'model' => $propagandaSearch,
														'dataProvider' => $dataProvider,
														'caption' => $caption,
														'subCaption' => $subCaption,
								]);
		      		}
		      	}

		      	// Lista de Condicion del registro
		      	$contribuyenteSearch = New ContribuyenteGeneralSearch();
		      	$listaCondicionContribuyente = $contribuyenteSearch->getListaCondicionContribuyente();
		      	array_pop($listaCondicionContribuyente);


		      	// Se muestra el formulario de busqueda.
		      	return $this->render('/reporte/contribuyente/general/contribuyente-objeto-busqueda-form', [
						      										'model' => $propagandaSearch,
						      										'caption' => $caption,
						      										'subCaption' => $subCaption,
						      										'listaCondicionContribuyente' => $listaCondicionContribuyente,
						      										'captionObjeto' => $captionObjeto,
		      		  ]);

			} else {
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/**
		 * Metodo que permite obtener una vista modal de los datos basicos de la propaganda
		 * @return view
		 */
		public function actionViewPropaganda()
		{
			$request = Yii::$app->request;
			$getData = $request->get();

			$idImpuesto = 0;
			$idImpuesto = $getData['p'];

			// Se busca los datos de la propagnada seleccionada.
			$propagandaSearch = New PropagandaReporteSearch();

			$model = $propagandaSearch->findPropaganda($idImpuesto);

			return $this->renderAjax('@frontend/views/propaganda/listado/view-propaganda',[
														'model' => $model,
				]);

		}




		/**
		 * Metodo que permite renderizar una vista con la informacion preliminar de
		 * la licencia segun el numero de solicitud de la misma.
		 * @return View
		 */
		public function actionViewContribuyenteModal()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();

			// Identificador del contribuyente
			$id = $postGet['id'];

			return $this->renderAjax('@backend/views/buscar-general/view', [
			            	'model' => $this->findModel($id),
			       ]);
		}




		/**
		 * Metodo que exporta el contenido de la consulta a formato excel
		 * @return view
		 */
		public function actionExportarExcel()
		{
			$postData = isset($_SESSION['postData']) ? $_SESSION['postData'] : [];
			$propagandaSearch = New PropagandaReporteSearch();
			$propagandaSearch->load($postData);

			$dataProvider = $propagandaSearch->getDataProvider($postData, true);
			$model = $dataProvider->getModels();

			$propagandaSearch->exportarExcel($model);

		}



		/**
		 * Metodo salida del modulo.
		 * @return view
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return Yii::$app->getResponse()->redirect(array('/menu/vertical'));
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


		/**
    	 * [findModel description]
    	 * @param  [type] $idContribuyente [description]
    	 * @return [type]                  [description]
    	 */
    	protected function findModel($idContribuyente)
    	{
  			$model = BuscarGeneral::find()->alias('B')
  			                              ->joinWith('afiliacion A', true, 'LEFT JOIN')
  			                              ->where('B.id_contribuyente =:id_contribuyente',
  			                          					[':id_contribuyente' => $idContribuyente])
  			                              ->one();

  			if ( $model !== null ) {
  				return $model;
  			} else {
  				throw new NotFoundHttpException('The requested page does not exist.');
  			}
    	}


	}
?>