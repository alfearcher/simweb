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
 *	@file LiquidarTasaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2016
 *
 *  @class LiquidarTasaController
 *	@brief Clase LiquidarTasaController del lado del contribuyente backend.
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


 	namespace backend\controllers\tasa\liquidar;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\enviaremail\PlantillaEmail;
	use backend\models\tasa\liquidar\LiquidarTasaSearch;
	use backend\models\tasa\liquidar\LiquidarTasaForm;
	use backend\models\impuesto\ImpuestoForm;


	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la liquidacion de la tasa.
	 */
	class LiquidarTasaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;




		public function actionIndex()
		{
			self::actionAnularSession(['begin']);
			$_SESSION['begin'] = 1;
			$this->redirect(['index-create']);
		}



		/**
		 * Metodo que mostrara la vista donde se encuentra el ultimo registro del historico
		 * para el año actual.
		 * @return [type] [description]
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$model = New LiquidarTasaForm();

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

				$tasaSearch = New LiquidarTasaSearch();

				$impuesto = New ImpuestoForm();
				$listaImpuesto = $impuesto->getListaImpuesto();

				$rutaLista = '/tasa/liquidar/liquidar-tasa/generar-lista-ano-impositivo';
				$caption = Yii::t('backend', 'Liquidar Tasas');
				$subCaption = Yii::t('backend', 'Seleccione los parametros solicitados');

				return $this->render('/tasa/liquidar/_create',[
													'model' => $model,
													'caption' => $caption,
													'subCaption' => $subCaption,
													'listaImpuesto' => $listaImpuesto,
													'rutaLista' => $rutaLista,
					]);


			} else {
				// NO esta definido el contribuyente.
				$this->redirect(['error-operacion', 'cod' => 932]);
			}
		}





		/***/
		public function actionGenerarLista()
		{
			$request = Yii::$app->request->get();
			$impuesto = isset($request['i']) ? $request['i'] : 0;
			$año = isset($request['a']) ? $request['a'] : 0;
			$idCodigo = isset($request['idcodigo']) ? $request['idcodigo'] : 0;
			$subnivel = isset($request['subnivel']) ? $request['subnivel'] : 0;

// die(var_dump($request));

			$tasaSearch = New LiquidarTasaSearch();
			if ( $impuesto > 0 && $año == 0 && $idCodigo == 0 && $subnivel == 0 ) {

				return $tasaSearch->generarViewListaAnoImpositivo($impuesto);

			} elseif ( $impuesto > 0 && $año > 0 && $idCodigo == 0 && $subnivel == 0 ) {

				return $tasaSearch->generarViewListaCodigoPresupuesto($impuesto, $año);

			} elseif ( $impuesto > 0 && $año > 0 && $idCodigo > 0 && $subnivel == 0 ) {

				return $tasaSearch->generarViewListaGrupoSubNivel($impuesto, $año, $idCodigo);

			} elseif ( $impuesto > 0 && $año > 0 && $idCodigo > 0 && $subnivel > 0 ) {

				return $tasaSearch->generarViewListaCodigoSubNivel($impuesto, $año, $idCodigo, $subnivel);

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
					];
		}

	}
?>