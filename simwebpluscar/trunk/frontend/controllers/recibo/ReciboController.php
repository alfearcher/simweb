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
	use common\models\planilla\PlanillaSearch;
	use backend\models\recibo\deposito\DepositoForm;
	use backend\models\recibo\depositoplanilla\DepositoPlanillaForm;



	session_start();		// Iniciando session

	/***/
	class ReciboController extends Controller
	{
		public $layout = 'layoutbase';				//	Layout principal del formulario






		/***/
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['begin']);
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

		      	// Datos generales del contribuyente.
		      	$searchRecibo = New ReciboSearch($idContribuyente);
		      	$findModel = $searchRecibo->findContribuyente();
		      	$dataProvider = $searchRecibo->getDataProviderDeuda();

		      	$providerPlanillaSeleccionada = $searchRecibo->initDataPrivider();

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

			  			$totalSeleccionado = self::actionTotalSeleccionado($providerPlanillaSeleccionada);
			  			$model->totalSeleccionado = $totalSeleccionado;

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





		/***/
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
				} elseif ( isset($postData['btn-reset']) ) {
					if ( $postData['btn-reset'] == 9 ) {
						self::actionAnularSession(['planillaSeleccionadas']);
					}
				}


				// Datos generales del contribuyente.
		      	$searchRecibo = New ReciboSearch($idContribuyente);
		      	$dataProvider = $searchRecibo->getDataProviderDeuda();

		      	$providerPlanillaSeleccionada = $searchRecibo->initDataPrivider();

				$model = New ReciboForm();
				$formName = $model->formName();

				$caption = Yii::t('frontend', 'Recibo de Pago. Crear');
				$subCaption = Yii::t('frontend', 'SubTitulo');

				if ( isset($postData['btn-add-seleccion']) ) {
					if ( $postData['btn-add-seleccion'] == 3 ) {
						// Seleccion de las deudas de periodos.
						$providerPlanillaSeleccionada = self::actionAjustarListaPlanillaSeleccionada($postData);

					} elseif ( $postData['btn-add-seleccion'] == 5 ) {
						// Seleccion de las deudas por tasa.
						$providerPlanillaSeleccionada = self::actionAjustarListaPlanillaSeleccionada($postData);

					}
				} elseif ( isset($postData['btn-create']) ) {
					if ( $postData['btn-create'] == 1 ) {
						// Se muestra un pre-view de la informacion seleccionada.
						$_SESSION['postEnviado'] = $postData;
						$this->redirect(['mostrar-vista-previa']);

					}
				} elseif ( isset($postData['btn-create-confirm']) ) {
					if ( $postData['btn-create-confirm'] == 5 ) {

					}
				} elseif ( isset($postData['btn-back']) ) {
					$providerPlanillaSeleccionada = $searchRecibo->getDataProviderAgruparDeudaPorPlanilla($_SESSION['planillaSeleccionadas']);
				}

		      	$findModel = $searchRecibo->findContribuyente();

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

			  			$totalSeleccionado = self::actionTotalSeleccionado($providerPlanillaSeleccionada);
			  			$model->totalSeleccionado = $totalSeleccionado;

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





		/**
		 * Metodo que contabiliza el total del monto por las planillas seleccionadas.
		 * Se utiliza para la contabilizacion un provider de tipo ArrayDataProvider.
		 * @param  ArrayDataProvider $provider array data provider de las planillas que ya
		 * fueron seleccionadas.
		 * @return double retorna un monto de lo contabilizado.
		 */
		private function actionTotalSeleccionado($provider)
		{
			$total = 0;
			if ( count($provider) > 0 ) {
	  			foreach ( $provider->allModels as $item ) {
	  				$total = $item['t'] + $total;
	  			}
	  		}
	  		return $total;
		}




		/***/
		public function actionMostrarVistaPrevia()
		{
			if ( isset($_SESSION['begin']) && isset($_SESSION['idContribuyente']) && isset($_SESSION['postEnviado']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$searchRecibo = New ReciboSearch($idContribuyente);
				$model = New ReciboForm();

				$caption = 'Pre-View';
				$postEnviado = $_SESSION['postEnviado'];
				self::actionAnularSession(['postEnviado']);

				$chkPlanillas = $postEnviado['chkPlanillaSeleccionadas'];

				$dataProvider = $searchRecibo->getDataProviderAgruparDeudaPorPlanilla($chkPlanillas);

				$model->totalSeleccionado = self::actionTotalSeleccionado($dataProvider);
				return $this->render('/recibo/pre-view-recibo-create-form', [
													'dataProvider' => $dataProvider,
													'model' => $model,
													'idContribuyente' => $idContribuyente,
													'caption' => $caption,
						]);
			}
		}




		public function actionPrueba()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

die(var_dump($postData));

		}



		/***/
		private function actionAjustarListaPlanillaSeleccionada($postEnviado)
		{
			$providerPlanillaSeleccionada = null;
			$idContribuyente = $_SESSION['idContribuyente'];
			$chkPlanillas = [];
			if ( $idContribuyente == $postEnviado['id_contribuyente'] ) {

				if ( $postEnviado['btn-add-seleccion'] == 3 ) {
					// Se obtienen las planillas seleccionadas.
					$listaPlanillas = $postEnviado['chkSeleccionDeuda'];

					$planillaInicial = $postEnviado['planillaInicial'];
					$planillaFinal = $postEnviado['planillaFinal'];

					foreach ( $listaPlanillas as $planilla ) {
						array_push($chkPlanillas, $planilla);
						if ( $planilla == $planillaFinal ) {
							break;
						}
					}

				} elseif ( $postEnviado['btn-add-seleccion'] == 5 ) {
					// Se obtienen las planillas seleccionadas.
					$chkPlanillas = $postEnviado['chkSeleccionDeuda'];
				}

				if ( count($chkPlanillas) > 0 ) {
					// Suma de la deuda.
					$sumaDeuda = $postEnviado['suma'];

					// Se genera el provider para mostrar las planillas seleccionadas.
					// Se utilizaran las planillas para generar el provider.
					$planillaSeleccionadas = isset($_SESSION['planillaSeleccionadas']) ? $_SESSION['planillaSeleccionadas'] : [];
					if ( count($planillaSeleccionadas) == 0 ) {

						//Indica que es la primera seleccion. No existe seleccion anterior.
						if ( count($chkPlanillas) > 0 ) {
							$planillaSeleccionadas = $chkPlanillas;
							$_SESSION['planillaSeleccionadas'] = $chkPlanillas;
						}

					} else {

						self::actionAnularSession(['planillaSeleccionadas']);

						// Se verifica que cualquiera de las planillas no este en al array anterior.
						if ( count($chkPlanillas) > 0 ) {
							foreach ( $chkPlanillas as $planilla ) {
								if ( !in_array($planilla, $planillaSeleccionadas ) ) {

									// Se agrega el elemento al array de planillas.
									array_push($planillaSeleccionadas, $planilla);
								}
							}
						}

						$_SESSION['planillaSeleccionadas'] = $planillaSeleccionadas;

					}
					if ( count($planillaSeleccionadas) > 0 ) {
						// Se manda a crear el provider con las planillas.
						$searchRecibo = New ReciboSearch($idContribuyente);
						$providerPlanillaSeleccionada = $searchRecibo->getDataProviderAgruparDeudaPorPlanilla($planillaSeleccionadas);

					}
				} else {
					// No se encontraron planillas seleccionadas.

				}

			} else {
				// El contribuyente del request no concuerda con el de la session..

			}

			return $providerPlanillaSeleccionada;
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
						$_SESSION['begin'] = 1;
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
									return $html = self::actionGetViewDeudaPorObjetoEspecifico($searchRecibo, (int)$getData['i'], (int)$getData['idO'], $getData['objeto']);
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
			if ( isset($_SESSION['begin']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$caption = Yii::t('frontend', 'Deuda segun tipo');
				$provider = $searchRecibo->getDataProviderEnPeriodo($impuesto);
				return $this->renderAjax('/recibo/_deuda_en_periodo', [
													'caption' => $caption,
													'dataProvider' => $provider,
													'idContribuyente' => $idContribuyente,
					]);
			}
		}




		/***/
		public function actionGetViewDeudaTasa($searchRecibo, $impuesto)
		{
			if ( isset($_SESSION['begin']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$caption = Yii::t('frontend', 'Deuda - Detalle');
				//$provider = $searchRecibo->getDataProviderDeudaDetalle($impuesto);

				// Setear las planillas ya seleccionadas.
				$planillaSeleccionadas = isset($_SESSION['planillaSeleccionadas']) ? $_SESSION['planillaSeleccionadas'] : [];
				$searchRecibo->setPlanillas($planillaSeleccionadas);

				$provider = $searchRecibo->getDataProviderDeudaPorObjetoPlanilla($impuesto, 0, '=');
				return $this->renderAjax('/recibo/_deuda_detalle_planilla_tasa', [
													'caption' => $caption,
													'dataProvider' => $provider,
													'periodoMayorCero' => false,
													'primeraPlanilla' => 0,
													'idContribuyente' => $idContribuyente,

					]);
			}
		}




		/***/
		public function actionGetViewDeudaActividadEconomica($searchRecibo)
		{
			if ( isset($_SESSION['begin']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$caption = Yii::t('frontend', 'Deuda - Detalle: Actividad Economica');
				//$provider = $searchRecibo->getDataProviderDeudaDetalleActEcon();

				// Setear las planillas ya seleccionadas.
				$planillaSeleccionadas = isset($_SESSION['planillaSeleccionadas']) ? $_SESSION['planillaSeleccionadas'] : [];
				$searchRecibo->setPlanillas($planillaSeleccionadas);

				$provider = $searchRecibo->getDataProviderDeudaPorObjetoPlanilla(1, 0, '>');

				// Se obtiene la primera planilla del provider.
				$primeraPlanilla = array_keys($provider->allModels)[0];

				return $this->renderAjax('/recibo/_deuda_detalle_planilla', [
													'caption' => $caption,
													'dataProvider' => $provider,
													'periodoMayorCero' => true,
													'primeraPlanilla' => $primeraPlanilla,
													'idContribuyente' => $idContribuyente,
					]);
			}
		}



		/***/
		public function actionGetViewDeudaPorObjeto($searchRecibo, $impuesto)
		{
			if ( isset($_SESSION['begin']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];

				// Setear las planillas ya seleccionadas.
				$planillaSeleccionadas = isset($_SESSION['planillaSeleccionadas']) ? $_SESSION['planillaSeleccionadas'] : [];
				$searchRecibo->setPlanillas($planillaSeleccionadas);

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
													'idContribuyente' => $idContribuyente,
					]);
			}
		}




		/***/
		public function actionGetViewDeudaPorObjetoEspecifico($searchRecibo, $impuesto, $idImpuesto, $objetoDescripcion)
		{
			if ( isset($_SESSION['begin']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				//$provider = $searchRecibo->getDataProviderDeudaDetalle($impuesto, $idImpuesto);

				// Setear las planillas ya seleccionadas.
				$planillaSeleccionadas = isset($_SESSION['planillaSeleccionadas']) ? $_SESSION['planillaSeleccionadas'] : [];
				$searchRecibo->setPlanillas($planillaSeleccionadas);

				$provider = $searchRecibo->getDataProviderDeudaPorObjetoPlanilla($impuesto, $idImpuesto, '>');
				$caption = Yii::t('frontend', 'Deuda - Detalle: ') . ' Id: ' . $idImpuesto  . ' Descripcion: ' . $objetoDescripcion;

				// Se obtiene la primera planilla del provider.
				$primeraPlanilla = array_keys($provider->allModels)[0];

				return $this->renderAjax('/recibo/_deuda_detalle_planilla', [
													'caption' => $caption,
													'dataProvider' => $provider,
													'periodoMayorCero' => true,
													'primeraPlanilla' => $primeraPlanilla,
													'idContribuyente' => $idContribuyente,
					]);
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