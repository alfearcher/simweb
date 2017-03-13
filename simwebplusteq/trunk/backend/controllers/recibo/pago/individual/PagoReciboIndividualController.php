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
 *	@file PagoReciboIndividualController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 12-02-2017
 *
 *  @class PagoReciboIndividualController
 *	@brief Clase
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


 	namespace backend\controllers\recibo\pago\individual;


 	use Yii;
 	use yii\helpers\ArrayHelper;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\conexion\ConexionController;
	// use backend\controllers\mensaje\MensajeController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
    use backend\models\recibo\pago\individual\BusquedaReciboForm;
    use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
    use backend\models\recibo\deposito\DepositoForm;
    use backend\models\recibo\depositodetalle\DepositoDetalleForm;
    use backend\models\recibo\depositodetalle\DepositoDetalleUsuarioForm;
    use backend\models\recibo\formapago\FormaPago;
    use backend\models\utilidad\banco\BancoSearch;
    use backend\models\utilidad\tipotarjeta\TipoTarjetaSearch;




	session_start();		// Iniciando session

	/**
	 *
	 */
	class PagoReciboIndividualController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		const SCENARIO_EFECTIVO = 'efectivo';
		const SCENARIO_CHEQUE = 'cheque';
		const SCENARIO_DEPOSITO = 'deposito';
		const SCENARIO_TARJETA = 'tarjeta';





		/**
		 * Metodo que configura las variables que permitiran la interaccion
		 * con la base de datos.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}



        /**
         * Metodo que inicia el modulo. Muestra una vista para consultar un
         * recibo.
         * @return retorna una vista donde se debe colocar el numero de recibo
         * para consultarlo.
         */
		public function actionIndex()
		{
            $this->redirect(['mostrar-form-consulta']);
		}





        /**
         * Metodo que muestra el formulario de consulta, si encuentra el recibo
         * muestra los datos del recibo y las planillas asociadas. Si el recibo es
         * valido permite registrar las formas de pago, sino bloqueara la opcion
         * del menu y del boton. Indica con una serie de mensajes las condicion
         * del recibo.
         * @return view
         */
        public function actionMostrarFormConsulta()
        {
        	self::actionAnularSession(['datosRecibo']);
            $model = New BusquedaReciboForm();
            if ( $model->usuarioAutorizado(Yii::$app->identidad->getUsuario()) ) {

                $request = Yii::$app->request;
                $postData = $request->post();

                // Permite bloquear el boton para buscar las formas de pagos del recibo.
                $bloquearFormaPago = true;

                // Mensaje que muestra las validaciones a nivel de logica de negocio que
                // no se pasa.
                $mensajes = [];
                $htmlMensaje = null;

                // url para registrar las formas de pagos, para el boton del menu desplegable.
                $urlFormaPagos = '#';

          		$formName = $model->formName();

                if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						$this->redirect(['index']);
					}
				}

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				if ( isset($postData['btn-forma-pago']) ) {
					if ( $postData['btn-forma-pago'] == 2 ) {
						$this->redirect(['registrar-formas-pago']);
					}
				}

				if ( $model->load($postData) ) {

					if ( $model->validate() ) {

						// Se verifica que el recibo cumpla las reglas de negocio establecidas.
						$pagoReciboSearch = New PagoReciboIndividualSearch($model->recibo);
						$mensajes = $pagoReciboSearch->validarEvento();

						if ( count($mensajes) == 0 ) {
							$urlFormaPagos = Url::to(['registrar-formas-pago']);
							$bloquearFormaPago = false;
							$htmlMensaje = null;
							$_SESSION['recibo'] = $model->recibo;

						} else {
							$htmlMensaje = $this->renderPartial('/recibo/pago/individual/warnings',[
																	'mensajes' => $mensajes,
											]);
						}

						// Arreglo de los provider del recibo y el de las planillas.
						$dataProviders = $pagoReciboSearch->getDataProviders();

						$totales = $pagoReciboSearch->getTotalesReciboPlanilla($dataProviders);

						$htmlRecibo = $this->renderPartial('/recibo/pago/individual/_recibo-encontrado',[
																'dataProviderRecibo' => $dataProviders[0],
																'dataProviderReciboPlanilla' => $dataProviders[1],
																'totales' => $totales,
																'htmlMensaje' => $htmlMensaje,
																'urlFormaPagos' => $urlFormaPagos,
																'bloquearFormaPago' => $bloquearFormaPago,

											]);

						$caption = Yii::t('backend', 'Pago de Recibo');
						$subCaption = Yii::t('backend', 'Datos del Recibo');
						return $this->render('/recibo/pago/individual/recibo', [
													'model' => $model,
													'htmlRecibo' => $htmlRecibo,
													'caption' => $caption,
													'subCaption' => $subCaption,
													'bloquearFormaPago' => $bloquearFormaPago,
								]);
					}
				}

				$caption = Yii::t('backend', 'Pago de Recibo');
				$subCaption = Yii::t('backend', 'Datos del Recibo');

				// Mostrar formulario de busqueda del recibo.
				return $this->render('/recibo/pago/individual/_find', [
											'model'=> $model,
											'caption' => $caption,
											'subCaption' => $subCaption,
						]);

            } else {
                // Usuario no autorizado.
            }
        }





        /**
         * Metodo que muestra una vista con el numero de recibo y el monto del mismo.
         * Muestra un boton con la lista de las formas de pagos.
         * @return view
         */
        public function actionRegistrarFormasPago()
        {
        	$depositoModel = New DepositoForm();
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];

        		$request = Yii::$app->request;
        		$postData = $request->post();
        		$postGet = $request->get();
        		//$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);

        		//$htmlFormaPago = null;

        		if ( isset($postData['btn-back']) ) {
        			if ( $postData['btn-back'] == 1 ) {
        				$this->redirect(['mostrar-form-consulta']);
        			}
        		}

        		//$datosRecibo = $pagoReciboSearch->getDeposito();

        		if ( $depositoModel->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($depositoModel);
		      	}

		      	return self::actionViewResumenRecibo();

		      	// $_SESSION['datosRecibo'] = $datosRecibo;
		      	// $formasPago = FormaPago::find()->all();
		      	// $listaForma = ArrayHelper::map($formasPago, 'id_forma', 'descripcion');

		      	// $captionRecibo = Yii::t('backend', 'Recibo Nro') . '. ' . $recibo;
		      	// $caption = Yii::t('backend', 'Registrar Formas de Pago');
		      	// return $this->render('/recibo/pago/individual/_registrar-formas-pago', [
		      	// 							// 'model' => $depositoModel,
		      	// 							'caption' => $caption,
		      	// 							'captionRecibo' => $captionRecibo,
		      	// 							'datosRecibo' => $datosRecibo,
		      	// 							'listaForma' => $listaForma,
		      	// 							'htmlFormaPago' => $htmlFormaPago,
		      	// 							'montoSobrante' => $datosRecibo[0]['monto'],
		      	// 		]);

        	} else {

        	}

        }



        /***/
        public function actionViewResumenRecibo()
        {
        	$recibo = $_SESSION['recibo'];

        	$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
        	$htmlFormaPago = null;
        	$dataProvider = $pagoReciboSearch->getDataProviderRegistroTemp(Yii::$app->identidad->getUsuario());

        	$datosRecibo = $pagoReciboSearch->getDeposito();

			$_SESSION['datosRecibo'] = $datosRecibo;
	      	$formasPago = FormaPago::find()->all();
	      	$listaForma = ArrayHelper::map($formasPago, 'id_forma', 'descripcion');

	      	$montoSobrante = $datosRecibo[0]['monto'];
	      	$captionRecibo = Yii::t('backend', 'Recibo Nro') . '. ' . $recibo;
	      	$caption = Yii::t('backend', 'Registrar Formas de Pago');
	      	return $this->render('/recibo/pago/individual/_registrar-formas-pago', [
	      								// 'model' => $depositoModel,
	      								'caption' => $caption,
	      								'captionRecibo' => $captionRecibo,
	      								'datosRecibo' => $datosRecibo,
	      								'listaForma' => $listaForma,
	      								'htmlFormaPago' => $htmlFormaPago,
	      								'montoSobrante' => $montoSobrante,
	      								'dataProvider' => $dataProvider,
	      			]);

        }




        /***/
        public function actionViewFormaPago()
        {

        	$request = Yii::$app->request;
        	$postGet = $request->get();
        	$postData = $request->post();

			$forma = isset($postGet['forma']) ? (int)$postGet['forma'] : 0;

			$model = New DepositoDetalleUsuarioForm();
			$model->id_forma = $forma;
			$model->recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
			$model->usuario = Yii::$app->identidad->getUsuario();
			$model->conciliado = 0;
			$model->estatus = 0;
			$model->codigo_banco = 0;

			if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
	      	}

	      	$formName = $model->formName();
// die(var_dump($postData));
			if ( isset($postData['btn-add-forma']) ) {
				if ( $postData['btn-add-forma'] > 0 ) {

					// $model->fecha = date('Y-m-d', strtotime($postData[$formName]['fecha']));
					// $model->scenario = self::SCENARIO_EFECTIVO;
					self::actionAgregarFormaPago($postData);
					//return self::actionViewResumenRecibo();
					$this->redirect(['view-resumen-recibo']);
				}
			} else {
	        	if ( $forma == 1 ) {
	        		$model->scenario = self::SCENARIO_CHEQUE;
	        		return $this->renderAjax('/recibo/pago/individual/forma-cheque', [
	        										'model' => $model,
	        										'caption' => 'Cheque',
	        		]);

	        	} elseif ( $forma == 2 ) {
	        		$searchBanco = New BancoSearch();
	        		$listaBanco = $searchBanco->getListaBanco();

	        		$searchTipoTarjeta = New TipoTarjetaSearch();
	        		$listaTipoTarjeta = $searchTipoTarjeta->getListaTipoTarjeta();

	        		$model->scenario = self::SCENARIO_DEPOSITO;
	        		return $this->renderAjax('/recibo/pago/individual/forma-deposito', [
	        										'model' => $model,
	        										'caption' => 'Deposito',
	        										'listaBanco' => $listaBanco,
	        										'listaTipoTarjeta' => $listaTipoTarjeta,
	        		]);

	        	} elseif ( $forma == 3 ) {

	        		$model->scenario = self::SCENARIO_EFECTIVO;
	        		return $this->renderAjax('/recibo/pago/individual/forma-efectivo', [
	        										'model' => $model,
	        										'caption' => 'Efectivo',
	        		]);

	        	} elseif ( $forma == 4 ) {
	        		$searchBanco = New BancoSearch();
	        		$listaBanco = $searchBanco->getListaBanco();

	        		$searchTipoTarjeta = New TipoTarjetaSearch();
	        		$listaTipoTarjeta = $searchTipoTarjeta->getListaTipoTarjeta();

	        		$model->scenario = self::SCENARIO_TARJETA;
	        		return $this->renderAjax('/recibo/pago/individual/forma-tarjeta', [
	        										'model' => $model,
	        										'caption' => 'Tarjeta',
	        										'listaBanco' => $listaBanco,
	        										'listaTipoTarjeta' => $listaTipoTarjeta,
	        		]);

	        	} else {
	        		return null;
	        	}
	        }

        }




        /***/
        public function actionAgregarFormaPago($postEnviado)
        {
        	$result = false;
        	$model = New DepositoDetalleUsuarioForm();
        	$formName = $model->formName();

// die(var_dump($postEnviado[$model->formName()]));
        	// $model->load($postEnviado[$model->formName()]);

        	self::setConexion();
 			$this->_conn->open();
 			$this->_transaccion = $this->_conn->beginTransaction();

 			if ( $postEnviado[$formName]['id_forma'] == 1 ) {
 				$model->scenario = self::SCENARIO_CHEQUE;
				$model->load($postEnviado);
// die(var_dump($model));
				$model->fecha = date('Y-m-d', strtotime($postEnviado[$formName]['fecha']));
 			    $model->conciliado = 0;
 			    $model->cuenta = $model->codigo_cuenta . $model->cuenta;
 			    //$model->cheque = '';
 			    $model->estatus = 0;
 			    $model->deposito = 0;
 			    $model->codigo_banco = 0;
 			    $model->cuenta_deposito = '';

// die(var_dump($model));
 			} elseif ( $postEnviado[$formName]['id_forma'] == 2 ) {
 				$model->scenario = self::SCENARIO_DEPOSITO;

 			} elseif ( $postEnviado[$formName]['id_forma'] == 3 ) {

 				$model->scenario = self::SCENARIO_EFECTIVO;
 				$model->load($postEnviado);
 				$model->fecha = date('Y-m-d', strtotime($postEnviado[$formName]['fecha']));
 			    $model->conciliado = 0;
 			    $model->cuenta = '';
 			    $model->cheque = '';
 			    $model->estatus = 0;
 			    $model->deposito = 0;
 			    $model->codigo_banco = 0;
 			    $model->cuenta_deposito = '';


// die(var_dump($model));
 			} elseif ( $postEnviado[$formName]['id_forma'] == 4 ) {
 				$model->scenario = self::SCENARIO_TARJETA;

 			}

 			$result = self::actionBeginSaveFormaPagoTemp($model);
 			if ( $result ) {
 				$this->_transaccion->commit();
 			} else {
 				$this->_transaccion->rollBack();
 			}

 			$this->_conn->close();
 			return $result;
        }



        /***/
        private function actionBeginSaveFormaPagoTemp($model)
        {
        	$d = $model->attributes;
        	$tabla = $model->tableName();
// die(var_dump($d));

        	return $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);

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
							'recibo',
							'begin',
					];

		}








	}
?>