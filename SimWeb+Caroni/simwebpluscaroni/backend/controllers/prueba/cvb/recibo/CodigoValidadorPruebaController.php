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
 *	@file CodigoValidadorPruebaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 05-02-2017
 *
 *  @class CodigoValidadorPruebaController
 *	@brief Clase que gestiona
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

 	namespace backend\controllers\prueba\cvb\recibo;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\prueba\cvb\recibo\CodigoValidadorPruebaForm;
	use backend\models\recibo\deposito\Deposito;
	use common\models\historico\cvbrecibo\GenerarValidadorReciboTresDigito;


	session_start();


	/***/
	class CodigoValidadorPruebaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario




		/***/
		public function actionIndex()
		{
			$this->redirect(['formulario']);
		}





		/***/
		public function actionFormulario()
		{

			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}

			$model = New CodigoValidadorPruebaForm();
			$formName = $model->formName();

			if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
	      	}

	      	$cvb = '';
	      	if ( $model->load($postData) ){

	      		if ( $model->validate() ) {

	      			if ( isset($postData['btn-cvb']) ) {

	      				if ( $postData['btn-cvb'] == 1 ) {

	      					$deposito = New Deposito();
	      					$deposito->recibo = $model->recibo;
	      					$deposito->fecha = $model->fecha;
	      					$deposito->monto = $model->monto;

	      					$generar = New GenerarValidadorReciboTresDigito($deposito);
	      					$cvb = $generar->getCodigoValidadorRecibo();

	      					// Renderiza resultado con valores de entrada mas el
	      					// resultado del algoritmo.
	      					return $this->render('/prueba/cvb/recibo/prueba-cvb-recibo',[
	      								'model' => $model,
	      								'cvb' => $cvb,
	      							]);
	      				}
	      			}
	      		}
	      	}

	      	return $this->render('/prueba/cvb/recibo/prueba-cvb-recibo',[
	      								'model' => $model,
	      								'cvb' => $cvb,
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