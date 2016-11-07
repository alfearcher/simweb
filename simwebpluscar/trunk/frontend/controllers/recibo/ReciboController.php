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
 *	@file ReciboController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-10-2016
 *
 *  @class ReciboController
 *	@brief Clase ReciboController del lado del contribuyente frontend.
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


 	namespace frontend\controllers\recibo;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\recibo\recibo\ReciboSearch;
	use backend\models\recibo\recibo\ReciboForm;



	session_start();		// Iniciando session

	/***/
	class ReciboController extends Controller
	{
		public $layout = 'layoutbase';				//	Layout principal del formulario






		/***/
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['lapso', 'tipo']);
			if ( isset($_SESSION['idContribuyente']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				// Vista donde se guarda la consulta de la deuda segun parametros.
				$html = null;

// die(var_dump($postData));


				$model = New ReciboForm();

				$formName = $model->formName();
				//$model->scenario = self::SCENARIO_SEARCH_TIPO;

				$caption = Yii::t('frontend', 'Recibo de Pago. Crear');
				$subCaption = Yii::t('frontend', 'SubTitulo');

		      	// Datos generales del contribuyente.
		      	$searchRecibo = New ReciboSearch($idContribuyente);
		      	$findModel = $searchRecibo->findContribuyente();
		      	$dataProvider = $searchRecibo->getDataProviderDeuda();

		      	// Es indicativo que se selecciono una deuda.
		      	if ( isset($postData['id']) ) {
					$html = self::actionGetViewDeuda($searchRecibo, $postData['id']);
				}


				if ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {
						//$model->scenario = self::SCENARIO_SEARCH_TIPO;
						$model->load($postData);

						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {

							}
						}
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	$total = 0;
		  		if ( isset($findModel) ) {
		  			foreach ( $dataProvider->allModels as $item ) {
		  				$total = $item['deuda'] + $total;
		  			}

		  			//$url = Url::to(['buscar-deuda']);
					return $this->render('/recibo/recibo-create-form',
															[
																'model' => $model,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'findModel' => $findModel,
																'dataProvider' => $dataProvider,
																'total' => $total,
																//'url' => $url,
																'html' => $html,

															]);

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}



		/***/
		public function actionBuscarDeuda()
		{
			$request = Yii::$app->request;

//die(var_dump($request->post()));
// 			if ( $request->isGet ) {
// 				$getData = $request->get();
// 				if ( $getData['n'] == 1 ) {
// 					$s = $request->getCsrfToken();
// die(var_dump($s));
			// 	}
			// }
			//
			return $this->renderAjax('/recibo/prueba');
		}




		/***/
		public function actionGetViewDeuda($searchRecibo, $postJson)
		{
			$html = null;

			// Lo siguiente crea un objeto json.
			$jsonObj = json_decode($postJson);

			if ( $jsonObj->{'view'} == 1 ) {
				$html = self::actionGetViewDeudaEnPeriodo($searchRecibo, $jsonObj->{'i'});
			} elseif ( $jsonObj->{'view'} == 2 ) {

			} elseif ( $jsonObj->{'view'} == 3 ) {

			}

			return $html;
		}



		/***/
		public function actionGetViewDeudaEnPeriodo($searchRecibo, $impuesto)
		{
			$caption = Yii::t('frontend', 'Deuda segun tipo');
			$provider = $searchRecibo->getDataProviderEnPeriodo($impuesto);
			return $this->renderAjax('/recibo/_deuda_en_periodo', [
												'caption' => $caption,
												'dataProvider' => $provider,
				]);
		}




    	/**
		 * Metodo salida del modulo.
		 * @return view
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/menu/menu-vertical');
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
							'lapso'
					];

		}

	}
?>