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
 *	@file CodigoValidadorBancarioListadoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 05-02-2017
 *
 *  @class CodigoValidadorBancarioListadoController
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
	use backend\models\prueba\cvb\recibo\CodigoValidadorBancarioListado;


	session_start();


	/***/
	class CodigoValidadorBancarioListadoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario




		/***/
		public function actionIndex()
		{
			$this->redirect(['listado']);
		}



		/***/
		public function actionListado()
		{
			$search = New CodigoValidadorBancarioListado();
			$dataProvider = $search->search(Yii::$app->request->bodyParams);

// die(var_dump(Yii::$app->request->bodyParams));
			$caption = Yii::t('frontend', 'Casos de Usos - Recibos/Codigos Validador Bancario.');
			return $this->render('/prueba/cvb/recibo/listado-recibo-cvb',[
										'model' => $search,
										'dataProvider' => $dataProvider,
										'caption' => $caption,
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
					];

		}





	}
?>