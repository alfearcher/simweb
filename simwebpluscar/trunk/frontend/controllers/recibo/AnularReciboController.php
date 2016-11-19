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
 *	@file AnularReciboController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-10-2016
 *
 *  @class AnularReciboController
 *	@brief Clase AnularReciboController del lado del contribuyente frontend.
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
	use common\conexion\ConexionController;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\recibo\recibo\AnularReciboForm;
	use backend\models\recibo\deposito\Deposito;




	session_start();		// Iniciando session

	/***/
	class AnularReciboController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;



		/**
		 * Metodo que inicia el modulo
		 * @return
		 */
		public function actionIndex1()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['begin']);
			if ( isset($_SESSION['idContribuyente']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['quit']) ) {
					if ( $postData['quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}


				$model = New AnularReciboForm();
				$formName = $model->formName();


				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	$idContribuyente = $_SESSION['idContribuyente'];
		      	$findModel = ContribuyenteBase::findOne($idContribuyente);

		      	if ( $model->load($postData) ) {

					if ( isset($postData['btn-delete-batch']) ) {
						if ( $postData['btn-delete-batch'] == 2 ) {
							if ( $model->validate(['id_contribuyente', 'estatus']) ) {

			      			}
						}
					} elseif ( isset($postData['btn-delete-one']) ) {
						if ( $postData['btn-delete-one'] == 3 ) {
							if ( $model->validate(['recibo', 'id_contribuyente', 'estatus']) ) {

			      			}
			      		}
					}
				}

				if ( isset($postData['btn-search-recibo']) ) {
					if ( $postData['btn-search-recibo'] == 5 ) {
						$this->render('/recibo/anulacion/_view',[
											'findModel' => $findModel,

							]);
					}
				}


				if ( count($findModel) > 0 ) {
					$dataProvider = $model->searchListaDeposito();
					return $this->render('/recibo/anulacion/_list',[
													'model' => $deposito,
													'caption' => 'Lista de Recibos Pendientes',
													'dataProvider' => $dataProvider,
						]);
				}


			}
		}



		/***/
		public function actionIndex()
		{
			self::actionAnularSession(['recibo']);
			if ( isset($_SESSION['idContribuyente']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();

				$idContribuyente = $_SESSION['idContribuyente'];
				$model = New AnularReciboForm();
				$formName = $model->formName();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( isset($postData[$formName]) ) {
					// Envio desde el listado
					$datos = $postData[$formName];
					if ( (int)$datos['id_contribuyente'] == (int)$idContribuyente ) {
						if ( isset($postData['recibo']) ) {
							// Se solicito la consulta de un recibo particular.
							$_SESSION['recibo'] = $postData['recibo'];
							$this->redirect(['mostrar-recibo-seleccionado']);

						} elseif ( isset($postData['btn-delete-batch']) ) {

							// Se solicita la anulacion de un grupo de recibos.

							if ( $postData['btn-delete-batch'] == 2 ) {

								// Se verifica que esten seleccionados los recibos.
								if ( isset($postData['chkRecibo']) ) {
									if ( count($postData['chkRecibo']) > 0 ) {
die(var_dump($postData['chkRecibo']));
									}
								}

							}
						}
					}
				}


				$model->id_contribuyente = $idContribuyente;
				$model->estatus = 0;

				$dataProvider = $model->searchListaDeposito();
				return $this->render('/recibo/anulacion/_list',[
										'model' => $model,
										'caption' => 'Lista de Recibos Pendientes',
										'dataProvider' => $dataProvider,
					]);

			}
		}




		/**
		 * Metodo que busca las planillas seleccionadas del recibo para renderizar una
		 * vista con un resumen de la informacion del recibo.
		 * @return view.
		 */
		public function actionMostrarReciboSeleccionado()
		{

			$request = Yii::$app->request;

			if ( isset($_SESSION['recibo']) ) {

				$recibo = (int)$_SESSION['recibo'];
				$idContribuyente =  $_SESSION['idContribuyente'];
				self::actionAnularSession(['recibo']);

				if ( $recibo > 0 ) {

					$model = New AnularReciboForm();
					$model->id_contribuyente = $idContribuyente;
					$model->recibo = $recibo;
					$model->estatus = 0;

					$dataProvider = $model->searchDepositoPlanilla($recibo);

					$deposito = Deposito::find()->where('recibo =:recibo',[':recibo' => $recibo])
										        ->joinWith('condicion C', true)
										        ->one();


					if ( $deposito['estatus'] == 0 ) {

						return $this->render('/recibo/anulacion/recibo-seleccionado',[
												'model' => $model,
												'deposito' => $deposito,
												'dataProvider' => $dataProvider,
												'caption' => 'Recibo seleccionado ' . $recibo,
							]);
					}
				}
			} else {
				$request = Yii::$app->request;
				$postData = $request->post();
				$model = New AnularReciboForm();
				$formName = $model->formName();

				$model->load($postData);

				if ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						// Regreso al listado.
						return $this->redirect(['index']);
					}

				} elseif ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						// Salida de la opcion
						$this->redirect(['quit']);
					}

				} elseif ( isset($postData['btn-delete-one']) ) {
					if ( $postData['btn-delete-one'] ) {
						// Anular este recibo.

					}
				}
			}


		}






		/**
		 * Metodo que inicia la carga de las planillas y la creacion del recibo
		 * @return
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
				} elseif ( isset($postData['btn-reset']) ) {
					if ( $postData['btn-reset'] == 9 ) {
						self::actionAnularSession(['planillaSeleccionadas']);
					}
				}

				// Datos generales del contribuyente.
		      	$searchRecibo = New ReciboSearch($idContribuyente);
		      	$dataProvider = $searchRecibo->getDataProviderDeuda();

		      	$providerPlanillaSeleccionada = $searchRecibo->initDataPrivider();

				$model = New DepositoForm();
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
				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 5 ) {

						if ( $model->load($postData) ) {
							if ( $model->validate() ) {

								$result = self::actionBeginSave($model, $postData);
								if ( $result ) {
									$this->_transaccion->commit();
									$this->_conn->close();
									self::actionAnularSession(['begin', 'planillaSeleccionadas']);
									return self::actionView($model);

								} else {
									$this->_transaccion->rollBack();
									$this->_conn->close();
									$this->redirect(['error-operacion', 'cod'=> 920]);

		  						}

							}
						}
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
		 * Metodo que incia el proceso de guardar el recibo y las planillas asociadas
		 * @param  DepositoForm $model modelo de la entidad "DepositoForm".
		 * @param  array $postEnviado post enviado desde la vista previa.
		 * @return boolean retorna true si guarda satisfactoriamente, flase en caso
		 * contrario.
		 */
		private function actionBeginSave($model, $postEnviado)
		{
			$result = false;
			$recibo = 0;

			if ( isset($_SESSION['idContribuyente']) ) {

					$this->_conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->_conn = $this->_conexion->initConectar('db');
	      			$this->_conn->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();

					$recibo = self::actionCreateDeposito($model);
					if ( $recibo > 0 ) {

						$model->recibo = $recibo;

						// Se pasa a guardar las planillas.
						$result = self::actionCreateDepositoPlanilla($model, $postEnviado);

						if ( $result ) {
							//$result = self::actionEnviarEmail($model, $postEnviado);
							$result = true;
						}
					} else {
						// No genero el recibo

					}

			} else {
				// No esta defino el contribuyente.
				$this->redirect(['error-operacion', 'cod' => 932]);
			}
			return $result;
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