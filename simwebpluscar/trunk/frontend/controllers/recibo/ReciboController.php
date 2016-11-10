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
	use backend\models\impuesto\Impuesto;



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


				$model = New ReciboForm();

				$formName = $model->formName();

				$caption = Yii::t('frontend', 'Recibo de Pago. Crear');
				$subCaption = Yii::t('frontend', 'SubTitulo');

				// Vista donde se guarda la consulta de la deuda segun parametros.
				$html = null;

		      	// Datos generales del contribuyente.
		      	$searchRecibo = New ReciboSearch($idContribuyente);
		      	$findModel = $searchRecibo->findContribuyente();
		      	$dataProvider = $searchRecibo->getDataProviderDeuda();

				if ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {
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
		  			if ( count($dataProvider) > 0 ) {
			  			foreach ( $dataProvider->allModels as $item ) {
			  				$total = $item['deuda'] + $total;
			  			}

			  			$providerPlanillaSeleccionada = $searchRecibo->initDataPrivider();
						return $this->render('/recibo/recibo-create-form',
																[
																	'model' => $model,
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'findModel' => $findModel,
																	'dataProvider' => $dataProvider,
																	'total' => $total,
																	'providerPlanillaSeleccionada' => $providerPlanillaSeleccionada,

																]);
					} else {
						// No presenta deuda pendiente
						$this->redirect(['error-operacion', 'cod' => 501]);
					}

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}




		public function actionPrueba()
		{
			$request = Yii::$app->request;


		}






		/***/
		public function actionBuscarDeudaDetalle()
		{
			$request = Yii::$app->request;
			$getData = $request->get();

			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			if ( isset($getData['view']) ) {
				$idC = $getData['idC'];
				if ( $idC == $idContribuyente ) {
					$searchRecibo = New ReciboSearch($idContribuyente);

					if ( $getData['view'] == 1 ) {			//Request desde deuda general.
						return $html = self::actionGetViewDeudaEnPeriodo($searchRecibo, (int)$getData['i']);

					} elseif ( $getData['view'] == 2 ) {	// Request desde deuda por tipo.
						if ( isset($getData['tipo']) ) {

							if ( $getData['tipo'] == 'periodo>0' ) {
								if ( $getData['i'] == 1 ) {

									// Se buscan todas las planillas.
									return $html = self::actionGetViewDeudaActividadEconomica($searchRecibo);

								} elseif ( $getData['i'] == 2 || $getData['i'] == 3 || $getData['i'] == 12 ) {

									// Se buscan los objetos con sus deudas.
									return $html = self::actionGetViewDeudaPorObjeto($searchRecibo, (int)$getData['i']);
								}

							} elseif ( $getData['tipo'] == 'periodo=0' ) {

								// Se buscan todas la planilla que cumplan con esta condicion
								return $html = self::actionGetViewDeudaTasa($searchRecibo, (int)$getData['i']);
							}
						} else {
							// Peticion no valida.

						}
					} elseif ( $getData['view'] == 3 ) {	// Request desde deuda por objeto.
						if ( $getData['tipo'] == 'periodo>0' ) {
							if ( $getData['i'] == 1 ) {

								// Se buscan todas las planillas.
								return $html = self::actionGetViewDeudaActividadEconomica($searchRecibo);

							} elseif ( $getData['i'] == 2 || $getData['i'] == 3 || $getData['i'] == 12 ) {
								if ( isset($getData['idO']) ) {

									// Se busca las deudas detalladas de un objeto especifico.
									return $html = self::actionGetViewDeudaPorObjetoEspecifico($searchRecibo, (int)$getData['i'], (int)$getData['idO']);
								}
							}
						}
					}
				} else {
					// El contribuyente solicitante no coincide con la session.
					$this->redirect(['error-operacion', 'cod' => 938]);
				}
			}

			return null;

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




		/***/
		public function actionGetViewDeudaTasa($searchRecibo, $impuesto)
		{
			$idSeleccionado = [];
			$caption = Yii::t('frontend', 'Deuda - Detalle');
			//$provider = $searchRecibo->getDataProviderDeudaDetalle($impuesto);
			$provider = $searchRecibo->getDataProviderDeudaPorObjetoPlanilla($impuesto, 0, '=');
			return $this->renderAjax('/recibo/_deuda_detalle_planilla', [
												'caption' => $caption,
												'dataProvider' => $provider,

				]);
		}




		/***/
		public function actionGetViewDeudaActividadEconomica($searchRecibo)
		{
			$idSeleccionado = [];
			$caption = Yii::t('frontend', 'Deuda - Detalle: Actividad Economica');
			//$provider = $searchRecibo->getDataProviderDeudaDetalleActEcon();
			$provider = $searchRecibo->getDataProviderDeudaPorObjetoPlanilla(1, 0, '>');
			return $this->renderAjax('/recibo/_deuda_detalle_planilla', [
												'caption' => $caption,
												'dataProvider' => $provider,
				]);
		}



		/***/
		public function actionGetViewDeudaPorObjeto($searchRecibo, $impuesto)
		{
			$provider = $searchRecibo->getDataProviderPorListaObjeto($impuesto);
			if ( $impuesto == 2 ) {
				$labelObjeto = Yii::t('frontend', 'direccion');
			} elseif ($impuesto == 3 ) {
				$labelObjeto = Yii::t('frontend', 'placa');
			}
			$i = Impuesto::findOne($impuesto);
			$caption = Yii::t('frontend', 'Deuda - Por: ' . $i->descripcion);
			return $this->renderAjax('/recibo/_deuda_por_objeto', [
												'caption' => $caption,
												'dataProvider' => $provider,
												'labelObjeto' => $labelObjeto,
				]);
		}




		/***/
		public function actionGetViewDeudaPorObjetoEspecifico($searchRecibo, $impuesto, $idImpuesto)
		{
			$idSeleccionado = [];
			//$provider = $searchRecibo->getDataProviderDeudaDetalle($impuesto, $idImpuesto);
			$provider = $searchRecibo->getDataProviderDeudaPorObjetoPlanilla($impuesto, $idImpuesto, '>');
			$caption = Yii::t('frontend', 'Deuda - Detalle: ');
			return $this->renderAjax('/recibo/_deuda_detalle_planilla', [
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