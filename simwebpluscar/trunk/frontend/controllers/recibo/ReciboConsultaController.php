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
 *	@file ReciboConsultaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-10-2016
 *
 *  @class ReciboConsultaController
 *	@brief Clase ReciboConsultaController
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
	use backend\models\impuesto\Impuesto;
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;
	use common\controllers\pdf\deposito\DepositoController;
	use backend\models\recibo\estatus\EstatusDeposito;
	use backend\models\recibo\estatus\EstatusDepositoSearch;
	use backend\models\recibo\recibo\ReciboConsultaForm;



	session_start();		// Iniciando session


	/**
	 * Clase que permite la consulta de los recibos de pagos. La consulta permitira la
	 * consulta por:
	 * - Rango de fecha de emision y condicion del recibo
	 * - Por condicion del recibo.
	 * - Por rango de fecha de emision.
	 * - Por numero de recibo directamente.
	 * La condicion del recibo se refiere al estatus del mismo, lo cual estara definido
	 * por:
	 * - Pendiente.
	 * - Pagado.
	 * - Anulado.
	 * Se permitira la generacion del recibo de pago en aquellos casos en que el recibo
	 * de pago este pendiente por pagar y su fecha de emision no exceda la fecha actual.
	 */
	class ReciboConsultaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario




		/**
		 * Metodo que inicia el modulo
		 * @return
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['postEnviado']);
			if ( isset($_SESSION['idContribuyente']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				$model = New ReciboConsultaForm();
				$formName = $model->formName();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	$idContribuyente = $_SESSION['idContribuyente'];
		      	$searchRecibo = New ReciboSearch($idContribuyente);

		      	if ( $model->load($postData) ) {

					if ( isset($postData['btn-search-params']) ) {
						if ( $postData['btn-search-params'] == 2 ) {
							if ( $model->validate(['fecha_desde', 'fecha_hasta', 'estatus']) ) {
								$_SESSION['postEnviado'] = $postData;
								$this->redirect(['search-deposito']);
			      			}
						}
					} elseif ( isset($postData['btn-search-recibo']) ) {
						if ( $postData['btn-search-recibo'] == 3 ) {
							if ( $model->validate(['recibo']) ) {
								$_SESSION['postEnviado'] = $postData;
								$this->redirect(['search-deposito']);
			      			}
			      		}
					}

				}

				$searchEstatus = New EstatusDepositoSearch();
				$lista = $searchEstatus->getListaEstatus();

				$model->id_contribuyente = $idContribuyente;
				$caption = Yii::t('frontend', 'Consulta de Recibo');
				return $this->render('/recibo/consulta/consulta-recibo-form', [
															'model' => $model,
															'lista' => $lista,
															'caption' => $caption,
						]);

			}
		}




		/***/
		public function actionSearchDeposito()
		{
			if ( isset($_SESSION['postEnviado']) ) {
				$postEnviado = $_SESSION['postEnviado'];
				//self::actionAnularSession(['postEnviado']);
			} else {
				$request = Yii::$app->request;
				$postEnviado = $request->queryParams;
			}
// die(var_dump($postEnviado));
			$model = New ReciboConsultaForm();
			$model->load($postEnviado);

			$dataProvider = $model->searchDeposito($postEnviado);
			return $this->render('/recibo/consulta/lista-recibo',[
											'caption' => 'Lista de Recibos',
											'dataProvider' => $dataProvider,
				]);
		}








		/**
		 * Metodo que renderiza la vista del pdf.
		 * @return view retorna un pdf.
		 */
		public function actionGenerarRecibo()
		{
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['recibo']) && isset($_SESSION['nro_control']) ) {

				// Controlador que gestiona la generacion del pdf.
				$depositoPdf = New DepositoController((int)$_SESSION['recibo'],
													  (int)$_SESSION['idContribuyente'],
													  (int)$_SESSION['nro_control']
													);
				return $depositoPdf->actionGenerarReciboPdf();

			} else {
				// Session no valida
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
							'planillaSeleccionadas',
					];

		}

	}
?>