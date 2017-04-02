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
    use backend\models\recibo\tipodeposito\TipoDepositoSearch;
    use backend\models\recibo\depositodetalle\VaucheDetalleUsuarioForm;
    use backend\models\recibo\prereferencia\PreReferenciaPlanillaForm;
    use backend\models\recibo\pago\individual\SerialReferenciaForm;
    use backend\models\recibo\prereferencia\ReferenciaPlanillaUsuarioForm;




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
// die(var_dump($bloquearFormaPago));
						$result = self::actionInicializarTemporal($model->recibo);
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

				$result = self::actionInicializarTemporal();

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
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];

        		$request = Yii::$app->request;
        		$postData = $request->post();
        		$postGet = $request->get();
        		$htmlFormaPago = null;

        		if ( isset($postData['btn-back']) ) {
        			if ( $postData['btn-back'] == 1 ) {
        				$this->redirect(['mostrar-form-consulta']);
        			}
        		}

        		if ( isset($postData['btn-pre-referencia']) ) {
        			if ( $postData['btn-pre-referencia'] == 2 ) {
        				$this->redirect(['seleccionar-cuenta-recaudadora']);
        			}
        		}

        		// Se define el formulario a utilizar
        		if ( isset($postData['btn-cheque']) ) {
        			if ( $postData['btn-cheque'] == 1 ) {
        				$forma = (int)$postData['btn-cheque'];
        				self::actionAnularSession(['guardo']);
        			}
        		} elseif ( isset($postData['btn-deposito']) ) {
        			if ( $postData['btn-deposito'] == 2 ) {
        				$forma = (int)$postData['btn-deposito'];
        				self::actionAnularSession(['guardo']);
        			}
        		} elseif ( isset($postData['btn-efectivo']) ) {
        			if ( $postData['btn-efectivo'] == 3 ) {
        				$forma = (int)$postData['btn-efectivo'];
        				self::actionAnularSession(['guardo']);
        			}
        		} elseif ( isset($postData['btn-tarjetas']) ) {
        			if ( $postData['btn-tarjetas'] == 4 ) {
        				$forma = (int)$postData['btn-tarjetas'];
        				self::actionAnularSession(['guardo']);
        			}
        		} else {
        			$forma = 0;
        		}

        		$model = New DepositoDetalleUsuarioForm();
        		$formName = $model->formName();

        		if ( isset($postData['btn-add-forma']) ) {
        			$forma = $postData['btn-add-forma'];

        			// Se define el scenario para la validacion.
        			self::defineScenario($forma, $model);

        			if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validate($model);
					}

					if ( $model->validate() ) {
						// Se guarda
						if ( !isset($_SESSION['guardo']) ) {
							if ( (int)$forma !== 3 ) {

								$result = self::actionAgregarFormaPago($postData);
								if ( $result ) {
									return self::actionArmarFormulario(0, $model, ['insert']);
								} else {
									return self::actionArmarFormulario($forma, $model, ['ERROR']);
								}
							} else {
								if ( self::actionExisteFormaPago($recibo, 3) ) {
									$result = self::actionActualizarMontoEfectivo($postData);
								} else {
									$result = self::actionAgregarFormaPago($postData);
								}
								if ( $result ) {
									return self::actionArmarFormulario(0, $model, []);
								} else {
									return self::actionArmarFormulario($forma, $model, ['ERROR']);
								}
							}
						} else {
							return self::actionArmarFormulario(0, $model, []);
						}

					} else {
						return self::actionArmarFormulario($forma, $model, [], $postData);
					}

        		} else {
        			return self::actionArmarFormulario($forma, $model, []);
        		}
        	} else {
        		// Recibo no valido

        	}
        }



        /**
         * Metodo que renderiza una vista que permite la seleccion de la cuenta recaudadora
         * que se utilizara en el proceso de pre-referencia. La vista costa de dos conbo-lista.
         * Se presentan 3 botones.
         * @return view
         */
        public function actionSeleccionarCuentaRecaudadora()
        {
        	self::actionAnularSession(['datosBanco']);
        	$recibo = isset($_SESSION['recibo']) ? (int)$_SESSION['recibo'] : 0;
        	if ( $recibo > 0 ) {
        		$request = Yii::$app->request;
        		$postData = $request->post();

        		if ( isset($postData['btn-back']) ) {
        			if ( $postData['btn-back'] == 2 ) {
        				$this->redirect(['registrar-formas-pago']);
        			}
        		}

        		if ( isset($postData['btn-quit']) ) {
        			if ( $postData['btn-quit'] == 2 ) {
        				$this->redirect(['quit']);
        			}
        		}

        		$model = New PreReferenciaPlanillaForm();
        		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				$searchBanco = New BancoSearch();

        		if ( $model->load($postData) ) {

        			if ( $model->validate() ) {
        				// Se busca datos del banco con el post recibido.
        				$banco = $searchBanco->findBanco($model->id_banco);
        				$tipoCuenta = $postData['tipo-cuenta'];
        				$datosBanco = $banco->toArray();
        				$datosBanco['cuenta_recaudadora'] = $model->cuenta_recaudadora;
        				$datosBanco['tipo_cuenta'] = $postData['tipo-cuenta'];

        				$_SESSION['datosBanco'] = $datosBanco;
        				$this->redirect(['pre-referencia']);
        			}
        		}

        		// Listado de bancos relacionados a cuentas recaudadoras.
        		$listaBanco = $searchBanco->getListaBancoRelacionadaCuentaReceptora();

        		$caption = Yii::t('backend', 'Registro de Pre-Referencias Bancarias') . '. ' . Yii::t('backend', 'Recibo Nro. ') . $recibo;
        		$subCaption = Yii::t('backend', 'Elabore la referencia bancaria');
        		return $this->render('/recibo/pago/individual/seleccionar-cuenta-recaudadora-form',[
        										'model' => $model,
        										'caption' => $caption,
        										'subCaption' => $subCaption,
        										'listaBanco' => $listaBanco,
        			]);
        	}
        }







        /***/
        public function actionPreReferencia()
        {
        	$recibo = isset($_SESSION['recibo']) ? (int)$_SESSION['recibo'] : 0;
        	if ( $recibo > 0 ) {

        		$request = Yii::$app->request;
	        	$postGet = $request->get();
	        	$postData = $request->post();
//die(var_dump($postData));

        		if ( isset($postData['btn-back']) ) {
	        		if ( $postData['btn-back'] == 2 ) {
	        			$this->redirect(['seleccionar-cuenta-recaudadora']);
	        		}
	        	}

	        	$datosBanco = isset($_SESSION['datosBanco']) ? $_SESSION['datosBanco'] : [];

//die(var_dump($datosBanco));

	        	$modelSerial = New SerialReferenciaForm();

	        	$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
	        	$dataProviders = $pagoReciboSearch->getDataProviders();

        		$datosRecibo = $_SESSION['datosRecibo'];

        		$model = New PreReferenciaPlanillaForm();
        		$model->fecha_pago = ( $datosRecibo[0]['estatus'] == 1 ? $datosRecibo[0]['fecha'] : date('d-m-Y') );
        		$model->id_banco = $datosBanco['id_banco'];
        		$model->cuenta_recaudadora = $datosBanco['cuenta_recaudadora'];

        		if ( $modelSerial->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($modelSerial);
				}

				if ( $modelSerial->load($postData) ) {
					if ( $modelSerial->validate() ) {
						// guardar serial
						$modelReferenciaUsuario = New ReferenciaPlanillaUsuarioForm();
die(var_dump($postData));

					}
				}

				$htmlSerialForm = null;
				if ( $datosBanco['tipo_cuenta'] == 'NO ES CUENTA RECAUDADORA' ) {
	        		$htmlSerialForm = $this->renderPartial('/recibo/pago/individual/agregar-serial-form',[
	        																			'modelSerial' => $modelSerial,
	        							]);
	        	}

        		$url = Url::to(['pre-referencia']);
        		$caption = Yii::t('backend', 'Registro de Pre-Referencias Bancarias') . '. ' . Yii::t('backend', 'Recibo Nro. ') . $recibo;
        		$subCaption = Yii::t('backend', 'Elabore la referencia bancaria');
        		return $this->render('/recibo/pago/individual/_pre-referencia',[
			        										'model' => $model,
			        										'caption' => $caption,
			        										'subCaption' => $subCaption,
			        										'url' => $url,
			        										'datosRecibo' => $datosRecibo,
			        										'datosBanco' => $datosBanco,
			        										'dataProviders' => $dataProviders,
			        										'htmlSerialForm' => $htmlSerialForm,
        				]);
        	}
        }




        /**
         * Metodo que permite guardar la referencia en una entidad temporal
         * @param ReferenciaPlanillaUsuarioForm $modelReferenciaUsuario instancia de la clase.
         * @return boolean
         */
        private function actionGuardarReferenciaTemporal($modelReferenciaUsuario)
        {
        	$result = false;
        	self::setConexion();
        	$this->_transaccion = $this->_conn->beginTransaction();
        	$this->_conn->open();

        	$tabla = $modelReferenciaUsuario->tableName();

        	return $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $modelReferenciaUsuario->attributes);
        }




        /**
         * Metodo que permite setear el valor de los atributos del modelo con los dstos
         * del post enviado desde el formulario.
         * @param ReferenciaPlanillaUsuarioForm $modelReferenciaUsuario instancia de la clase.
         * @param array $postEnviado arreglo del post enviado.
         * @return ReferenciaPlanillaUsuarioForm
         */
        private function actionLoadModeloReferencia($modelReferenciaUsuario, $postEnviado)
        {
        	$modelReferenciaUsuario->recibo = $postEnviado['recibo'];
        	$modelReferenciaUsuario->fecha = $postEnviado['fecha'];
        	$modelReferenciaUsuario->monto_recibo = $postEnviado['monto'];
        	$modelReferenciaUsuario->planilla = $postEnviado['planilla'];
        	$modelReferenciaUsuario->monto_planilla = $postEnviado['fecha'];
        	$modelReferenciaUsuario->id_contribuyente = $postEnviado['fecha'];
        	$modelReferenciaUsuario->fecha_edocuenta = $postEnviado['fecha'];
        	$modelReferenciaUsuario->serial_edocuenta = $postEnviado['fecha'];
        	$modelReferenciaUsuario->debito = $postEnviado['fecha'];
        	$modelReferenciaUsuario->credito = $postEnviado['fecha'];
        	$modelReferenciaUsuario->estatus = 0;
        	$modelReferenciaUsuario->observacion = $postEnviado['fecha'];
        	$modelReferenciaUsuario->usuario = Yii::$app->identidad->getUsuario();

        }


        /***/
        public function actionListarCuentaRecaudadora()
        {
        	$request = Yii::$app->request;
        	$postGet = $request->get();
        	$postData = $request->post();

        	$searchBanco = New BancoSearch();
        	$id = isset($postGet['id']) ? (int)$postGet['id'] : 0;
        	return $searchBanco->generarViewListaCuentaRecaudadora($id);

        }





        /***/
        public function actionDeterminarCuentaRecaudadora()
        {
        	$request = Yii::$app->request;
        	$postGet = $request->get();
        	$postData = $request->post();

        	$listaCuentasRecaudadoras = Yii::$app->ente->getCuentaRecaudadora();
        	if ( isset($postGet['cuenta']) && isset($postGet['id-banco']) ) {
        		if ( in_array($postGet['cuenta'], $listaCuentasRecaudadoras) ) {
        			echo "CUENTA RECAUDADORA";
        		} else {
        			echo "NO ES CUENTA RECAUDADORA";
        		}
        	} else {
        		echo "NO ES CUENTA RECAUDADORA";
        	}
        }





        /**
         * Metodo que suprime un registro de la forma de pago. Redirecciona
         * a el formulario principal mostrando las formas de pago registradas.
         * @return view
         */
        public function actionSuprimirFormaPago()
        {
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];
        		$htmlFormaPago = null;

		      	$request = Yii::$app->request;
		      	if ( $request->isGet ) {

        			$postGet = $request->get();
        			$arregloCondicion = [
        				'linea' => $postGet['l'],
        			];

        			self::setConexion();
        			$this->_transaccion = $this->_conn->beginTransaction();
        			$this->_conn->open();

        			$modelDepositoDetalle = New DepositoDetalleUsuarioForm();
	        		$result = self::actionSuprimirDetalleTemporal($modelDepositoDetalle, $arregloCondicion);
        			if ( $postGet['forma'] == 2 ) {
	        			if ( $result ) {
	        				$modelVauche = New VaucheDetalleUsuarioForm();
	        				$result = self::actionSuprimirDetalleTemporal($modelVauche, $arregloCondicion);
	        			}
	        		}
        			if ( $result ) {
        				$this->_transaccion->commit();
        			} else {
        				$this->_transaccion->rollBack();
        			}
					$this->_conn->close();

		      		//$result = self::actionSuprimir($arregloCondicion);
        		}

		      	$this->redirect(['registrar-formas-pago']);
		    }
        }





        /**
         * Metodo que suprime un registro de los detalles del vauche.
         * @return boolean.
         */
        public function actionSuprimirDetalleVauche()
        {
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];

		      	$request = Yii::$app->request;
		      	if ( $request->isGet ) {

		      		$postGet = $request->get();
		      		if ( (int)$postGet['recibo'] == (int)$recibo ) {

	        			$arregloCondicion = [
	        				'id_vauche' => $postGet['id'],
	        			];

	        			self::setConexion();
	        			$this->_transaccion = $this->_conn->beginTransaction();
	        			$this->_conn->open();

	        			$pagoReciboSearch = New PagoReciboIndividualSearch($postGet['recibo']);
	        			$modelVauche = $pagoReciboSearch->findEspecificoDetalleVauche($postGet['id']);
		        		$result = self::actionSuprimirDetalleTemporal($modelVauche, $arregloCondicion);

	        			if ( $result ) {
	        				$result = self::actionActualizarMontoDeposito($modelVauche, 'restar');
	        			}

	        			if ( $result) {
	        				$this->_transaccion->commit();
	        			} else {
	        				$this->_transaccion->rollBack();
	        			}
						$this->_conn->close();
					}
        		}
        		$this->redirect(['update', 'l' => (int)$postGet['l']]);

		    }
        }





        /***/
        public function actionFindEspecificoRegistroTemporal($linea)
        {
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];
				$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);

				// Retorna registro encontrado, instancia de la clase DepositoDetalleUsuarioForm
				return $registers = $pagoReciboSearch->findEspecificoDepositoDetalleUsuarioTemp($linea);
			}
			return null;
        }





        /***/
        public function actionUpdate()
        {
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];

		      	$request = Yii::$app->request;
		      	if ( $request->isGet ) {
        			$postGet = $request->get();

        			// Se busca el registro selccionado. Clase DepositoDetalleUsuarioForm
		      		$registers = self::actionFindEspecificoRegistroTemporal((int)$postGet['l']);

		      		if ( $registers ) {
		      			$forma = $registers->id_forma;

		      			$model = New DepositoDetalleUsuarioForm();
        				$formName = $model->formName();

        				// Se define el scenario para la validacion.
	        			self::defineScenario($forma, $model);

	        			$model->attributes = $registers->toArray();

	        			return self::actionArmarFormulario($forma, $model, []);

		      		}
        		} else {
        			$postData = $request->post();
        			if ( isset($postData['btn-add-forma']) ) {
	        			$forma = $postData['btn-add-forma'];

	        			$model = New DepositoDetalleUsuarioForm();
        				$formName = $model->formName();

	        			// Se define el scenario para la validacion.
	        			self::defineScenario($forma, $model);

	        			if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
							Yii::$app->response->format = Response::FORMAT_JSON;
							return ActiveForm::validate($model);
						}

						if ( $model->validate() ) {
							// Se guarda
							$arregloCondicion = [
								'linea' => $model->linea,
							];

							$model->fecha = date('Y-m-d', strtotime($model->fecha));
							$model->monto = str_replace(',','.', $model->monto);

							$result = self::actionBeginActualizarFormaPagoTemp($model, $arregloCondicion, $model->attributes);
							if ( $result ) {
								return self::actionArmarFormulario(0, $model, []);
							} else {
								return self::actionArmarFormulario($forma, $model, ['ERROR']);
							}
						} else {
							return self::actionArmarFormulario($forma, $model, [], $postData);
						}

	        		} else {
	        			return self::actionArmarFormulario($forma, $model, []);
	        		}
        		}
		    }

		    $this->redirect(['registrar-formas-pago']);

        }



        /**
         * Metodo que arma el formulario genral.
         * @param  [type] $forma       [description]
         * @param  [type] $model       [description]
         * @param  [type] $operacion   [description]
         * @param  [type] $postEnviado [description]
         * @return [type]              [description]
         */
        public function actionArmarFormulario($forma, $model, $operacion, $postEnviado = null)
        {
        	if ( isset($_SESSION['recibo']) ) {
        		$recibo = $_SESSION['recibo'];
        		$htmlFormaPago = null;

				// $model = New DepositoDetalleUsuarioForm();
				// $formName = $model->formName();

				// Se define el scenario para la validacion.
    			self::defineScenario($forma, $model);

    			$htmlFormaPago = self::actionShowViewFormaPago($forma, $model, $postEnviado, $operacion);
    			$htmlResumenReciboFormaPago = self::actionViewResumenReciboFormaPago();
		      	$htmlFormaPagoContabilizada = self::actionShowViewFormaPagoContabilizada();

		      	$captionRecibo = Yii::t('backend', 'Recibo Nro') . '. ' . $recibo;
		      	$caption = Yii::t('backend', 'Registrar Formas de Pago');
		      	$rutaAyuda = Url::to(['ruta-ayuda']);
		      	return $this->render('/recibo/pago/individual/_registrar-formas-pago', [
		      								'caption' => $caption,
		      								'captionRecibo' => $captionRecibo,
		      								'htmlFormaPago' => $htmlFormaPago,
		      								'htmlResumenReciboFormaPago' => $htmlResumenReciboFormaPago,
		      								'htmlFormaPagoContabilizada' => $htmlFormaPagoContabilizada,
		      								'operacion' => $operacion,
		      								'rutaAyuda' => $rutaAyuda,
		      			]);
        	}
        }




        /***/
        public function actionViewResumenReciboFormaPago()
        {
			$recibo = $_SESSION['recibo'];
        	$usuario = Yii::$app->identidad->getUsuario();
        	$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);

        	$datosRecibo = $pagoReciboSearch->getDeposito();

			$_SESSION['datosRecibo'] = $datosRecibo;
	      	$formasPago = FormaPago::find()->all();
	      	$listaForma = ArrayHelper::map($formasPago, 'id_forma', 'descripcion');

	      	$montoAgregado = $pagoReciboSearch->getTotalFormaPagoAgregado($usuario);

	      	$montoSobrante = $datosRecibo[0]['monto'] - $montoAgregado;
	      	$captionRecibo = Yii::t('backend', 'Recibo Nro') . '. ' . $recibo;
	      	$caption = Yii::t('backend', 'Registrar Formas de Pago');
	      	return $this->renderPartial('/recibo/pago/individual/resumen-recibo-forma-pago', [
			      								'caption' => $caption,
			      								'captionRecibo' => $captionRecibo,
			      								'datosRecibo' => $datosRecibo,
			      								'listaForma' => $listaForma,
			      								'montoSobrante' => $montoSobrante,
			      			]);
        }





        /**
         * Metodo que renderiza una vista con la informacion de las formas de
         * pagos registradas (grid view) con la totalizacion de los registros.
         * @return retorna view.
         */
        public function actionShowViewFormaPagoContabilizada()
        {
        	$recibo = $_SESSION['recibo'];
        	$usuario = Yii::$app->identidad->getUsuario();
        	$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
        	$dataProvider = $pagoReciboSearch->getDataProviderRegistroTemp($usuario);

        	$montoAgregado = $pagoReciboSearch->getTotalFormaPagoAgregado($usuario);

        	return $this->renderPartial('/recibo/pago/individual/forma-pago-contabilizada', [
			      								'montoAgregado' => $montoAgregado,
			      								'dataProvider' => $dataProvider,
			      		]);
        }




        /**
         * Metodo que setea el scenario para el modelo.
         * @param integer $forma identificador de la forma de pago.
         * @param DepositoDetalleUsuarioForm $model instancia de la clase.
         * @return DepositoDetalleUsuarioForm.
         */
        private function defineScenario($forma, $model)
        {
        	if ( $forma == 1 ) {
	        	$model->scenario = self::SCENARIO_CHEQUE;
			} elseif ( $forma == 2 ) {
	        	$model->scenario = self::SCENARIO_DEPOSITO;
	        } elseif ( $forma == 3 ) {
	        	$model->scenario = self::SCENARIO_EFECTIVO;
	        } elseif ( $forma == 4 ) {
	        	$model->scenario = self::SCENARIO_TARJETA;
	        }

	        return $model;
        }



        /**
         * Metodo que renderiza una vista con los datos a cargar la forma de pago.
         * @param integer $forma identificador de la forma de pago.
         * @param DepositoDetalleUsuarioForm $model instancia de la clase.
         * @param array $postData post enviado desde el formulario.
         * @param array $operacion indica un arreglo de mensajes a ser mostrados.
         * @return retorna una view.
         */
        public function actionShowViewFormaPago($forma, $model, $postData = null, $operacion = [])
        {
        	$recibo = $_SESSION['recibo'];
        	$usuario = Yii::$app->identidad->getUsuario();

        	if ( $forma == 1 ) {

        		if ( $postData !== null ) {
        			$model->load($postData);
        			//$model->validate();

        		} else {
	        		$model->recibo = $recibo;
	        		$model->id_forma = $forma;
	        		$model->deposito = 0;
	        		$model->conciliado = 0;
	        		$model->estatus = 0;
	        		$model->codigo_banco = 0;
	        		$model->cuenta_deposito = '';
	        		$model->usuario = $usuario;
	        		$model->id_banco = 0;
	        		$model->fecha = date('Y-m-d');
	        	}
        		return $this->renderPartial('/recibo/pago/individual/forma-cheque', [
        										'model' => $model,
        										'caption' => 'Cheque',
        										'operacion' => $operacion,
        		]);

        	} elseif ( $forma == 2 ) {

        		if ( $postData !== null ) {
        			$model->load($postData);
        		} else {
	        		$model->recibo = $recibo;
	        		$model->id_forma = $forma;
	        		$model->cuenta = '';
	        		$model->cheque = '';
	        		$model->conciliado = 0;
	        		$model->estatus = 0;
	        		$model->codigo_banco = 0;
	        		$model->cuenta_deposito = '';
	        		$model->usuario = $usuario;
					$model->id_banco = 0;
				}

    			$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
				$dataProvider = $pagoReciboSearch->getDataProviderRegistroVaucheTemp($usuario, $model->linea);

        		return $this->renderPartial('/recibo/pago/individual/forma-deposito', [
        										'model' => $model,
        										'caption' => 'Deposito',
        										'operacion' => $operacion,
        										'dataProvider' => $dataProvider,

        		]);

        	} elseif ( $forma == 3 ) {

        		if ( $postData !== null ) {
        			$model->load($postData);
        		} else {
	        		$model->recibo = $recibo;
	        		$model->id_forma = $forma;
	        		$model->cuenta = '';
	        		$model->cheque = '';
	        		$model->conciliado = 0;
	        		$model->estatus = 0;
	        		$model->codigo_banco = 0;
	        		$model->cuenta_deposito = '';
	        		$model->usuario = $usuario;
					$model->id_banco = 0;
				}
        		return $this->renderPartial('/recibo/pago/individual/forma-efectivo', [
        										'model' => $model,
        										'caption' => 'Efectivo',
        										'operacion' => $operacion,
        		]);

        	} elseif ( $forma == 4 ) {

        		$searchBanco = New BancoSearch();
        		$listaBanco = $searchBanco->getListaBanco();

        		$searchTipoTarjeta = New TipoTarjetaSearch();
        		$listaTipoTarjeta = $searchTipoTarjeta->getListaTipoTarjetaDescripcion();

        		if ( $postData !== null ) {
        			$model->load($postData);
        		} else {
	        		$model->recibo = $recibo;
	        		$model->id_forma = $forma;
	        		$model->deposito = '';
	        		$model->cheque = '';
	        		$model->conciliado = 0;
	        		$model->estatus = 0;
	        		$model->codigo_banco = 0;
	        		$model->cuenta_deposito = '';
	        		$model->usuario = $usuario;
					$model->id_banco = 0;
					$model->fecha = date('Y-m-d');
				}
        		return $this->renderPartial('/recibo/pago/individual/forma-tarjeta', [
        										'model' => $model,
        										'caption' => 'Tarjeta',
        										'listaBanco' => $listaBanco,
        										'listaTipoTarjeta' => $listaTipoTarjeta,
        										'operacion' => $operacion,
        		]);
        	} else {
        		return null;
        	}
        }




        /**
         * Metodo que determina si una forma de pago ya esta registrada.
         * @param integer $recibo identificador del recibo.
         * @param integer $idForma identificador de la forma de pago.
         * @return boolean.
         */
        public function actionExisteFormaPago($recibo, $idForma)
        {
        	$result = false;

			$usuario = Yii::$app->identidad->getUsuario();
    		$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
    		$registers = $pagoReciboSearch->findFormaPago($idForma, $usuario);
    		if ( count($registers) > 0 ) {
    			$result = true;
    		}

    		return $result;
        }




        /**
         * Metodo que permite actualizar el monto de una forma de pago.
         * La actualizacion del monto por forma de pago "efectivo", se realiza debido
         * a que no se puede incluir el registro si ya existe una forma de pago de tipo
         * "efectivo", lo que se hace es actualizar dicho monto.
         * @param array $postEnviado post enviado desde el formulario sin formateo.
         * @return boolean.
         */
        public function actionActualizarMontoEfectivo($postEnviado)
        {
        	$result = false;
        	$model = New DepositoDetalleUsuarioForm();
			$formName = $model->formName();

        	if ( $postEnviado[$formName]['id_forma'] == 3 ) {

				$recibo = $postEnviado[$formName]['recibo'];
				$usuario = Yii::$app->identidad->getUsuario();
        		$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
        		$registers = $pagoReciboSearch->findFormaPago((int)$postEnviado[$formName]['id_forma'], $usuario);

        		if ( count($registers) > 0 ) {
        			$montoEnviado = str_replace(',', '.', $postEnviado[$formName]['monto']);
        			$monto = $montoEnviado + $registers[0]['monto'];
        			$linea = $registers[0]['linea'];

        			$model->load($postEnviado);

        			$model->fecha = date('Y-m-d', strtotime($postEnviado[$formName]['fecha']));
	 			    $model->conciliado = 0;
	 			    $model->cuenta = '';
	 			    $model->cheque = '';
	 			    $model->estatus = 0;
	 			    $model->deposito = 0;
	 			    $model->codigo_banco = 0;
	 			    $model->cuenta_deposito = '';

	 			    $arregloCondicion = [
	 			    	'linea' => $linea,
	 			    ];

	 			    $arregloDatos = [
	 			    	'monto' => $monto,
	 			    ];

	 			    $result = self::actionBeginActualizarFormaPagoTemp($model, $arregloCondicion, $arregloDatos);

        		}
        	}

        	return $result;
        }




        /**
         * Metodo que inserta un registro en la entidad temporal.
         * @param array $postEnviado post enviado desde el formulario.
         * @return boolean.
         */
        public function actionAgregarFormaPago($postEnviado)
        {
        	$result = false;
        	$model = New DepositoDetalleUsuarioForm();
        	$formName = $model->formName();

        	self::setConexion();
 			$this->_conn->open();
 			$this->_transaccion = $this->_conn->beginTransaction();

 			if ( $postEnviado[$formName]['id_forma'] == 1 ) {

 				$model->scenario = self::SCENARIO_CHEQUE;
				$model->load($postEnviado);
				$model->fecha = date('Y-m-d', strtotime($postEnviado[$formName]['fecha']));
 			    $model->conciliado = 0;
 			    $model->estatus = 0;
 			    $model->deposito = 0;
 			    $model->codigo_banco = 0;
 			    $model->cuenta_deposito = '';
 			    $model->id_banco = 0;

 			} elseif ( $postEnviado[$formName]['id_forma'] == 2 ) {

 				$model->scenario = self::SCENARIO_DEPOSITO;
 				$model->load($postEnviado);
 				$model->fecha = date('Y-m-d', strtotime($postEnviado[$formName]['fecha']));
 			    $model->conciliado = 0;
 			    $model->cuenta = '';
 			    $model->cheque = '';
 			    $model->estatus = 0;
 			    $model->codigo_banco = 0;
 			    $model->cuenta_deposito = '';
 			    $model->id_banco = 0;
 			    $model->monto = 0;

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
 			    $model->id_banco = 0;

 			} elseif ( $postEnviado[$formName]['id_forma'] == 4 ) {

 				$model->scenario = self::SCENARIO_TARJETA;
 				$model->load($postEnviado);
 				$model->fecha = date('Y-m-d', strtotime($postEnviado[$formName]['fecha']));
 			    $model->conciliado = 0;
 			    $model->estatus = 0;
 			    $model->deposito = 0;
 			    $model->codigo_banco = 0;
 			    $model->cuenta_deposito = '';
 			    $model->id_banco = 0;
 			}

 			$model->monto = str_replace(',','.', $model->monto);

 			$result = self::actionBeginSaveFormaPagoTemp($model);
 			if ( $result ) {
 				$_SESSION['guardo'] = 1;
 				$this->_transaccion->commit();
 			} else {
 				$this->_transaccion->rollBack();
 			}

 			$this->_conn->close();
 			return $result;
        }





        /**
         * Metodo que realiza la insercion en la entidad respectiva.
         * @param VaucheDetalleUsuarioForm|DepositioDetalleUsuarioForm $model modelo de la clase.
         * @return boolean retorna true si ejecuta la operacion satisfactorimente, false en caso
         * contrario.
         */
        private function actionBeginSaveFormaPagoTemp($model)
        {
        	$d = $model->attributes;
        	$tabla = $model->tableName();

        	return $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);

        }




        /**
         * Metodo que actualiza el regitro seleccionado
         * @param DepositioDetalleUsuarioForm $model modelo de la clase.
         * @param array $arregloCondicion arreglo que posee el where condicion de la actualizacion
         * Estructura del arreglos:
         * 	[
         *  	'campo' => valor del campo,
         *  ]
         * @param array $arregloDatos arreglo de datos que seran actualizados.
         * @return boolean retorna true si ejecuta la operacion satisfactorimente, false en caso
         * contrario.
         */
        private function actionBeginActualizarFormaPagoTemp($model, $arregloCondicion, $arregloDatos)
        {
        	self::setConexion();
        	$this->_conn->open();

        	$model->fecha = date('Y-m-d', strtotime($model->fecha));
        	//$arregloDatos = $model->attributes;
        	$tabla = $model->tableName();
        	$this->_transaccion = $this->_conn->beginTransaction();

        	$result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);
        	if ( $result ) {
        		$_SESSION['guardo'] = 1;
 				$this->_transaccion->commit();
 			} else {
 				$this->_transaccion->rollBack();
 			}
			$this->_conn->close();

			return $result;
        }




        /**
         * Metodo que permite inicializar la tabla temporal utilizada para la carga de las formas
         * de pagos utilizadas para pagar un recibo. Proceso que realiza el usuario en el modulo
         * de la caja.
         * @param array $arregloCondicion arreglo de datos con la estructura:
         *  [
         *  	campo => valor de campo,
         *  ]
         * @return boolean retorna true si ejecuta la operacion satisfactoriamente o false en caso
         * contrario.
         */
        private function actionSuprimirRegistroTemporal($arregloCondicion)
        {
        	$result = false;
        	$model = New DepositoDetalleUsuarioForm();
        	$tabla = $model->tableName();

        	self::setConexion();
        	$this->_conn->open();
 			$this->_transaccion = $this->_conn->beginTransaction();

 			$result = $this->_conexion->eliminarRegistro($this->_conn, $tabla, $arregloCondicion);

 			if ( $result ) {
 				$this->_transaccion->commit();
 			} else {
 				$this->_transaccion->rollBack();
 			}
			$this->_conn->close();

			return $result;
        }







        /**
         * Metodo que suprime regiatros de las entidades temporales.
         * @param VaucheDetalleUsuarioForm $model instancia de la clase.
         * @param array $arregloCondicion arreglo del where condition
         * @return boolean.
         */
        private function actionSuprimirDetalleTemporal($model, $arregloCondicion)
        {
        	$result = false;
        	$tabla = $model->tableName();

 			return $result = $this->_conexion->eliminarRegistro($this->_conn, $tabla, $arregloCondicion);
        }






        /**
         * Metodo que permite ejecutar la inicializacion de la tabla temporal
         * @param integer $recibo numero de recibo
         * @return boolean
         */
        public function actionInicializarTemporal($recibo = 0)
        {
        	$procesoExitoso = false;
        	$result = [];
        	$modelDepositoDetalle = New DepositoDetalleUsuarioForm();
        	$modelVauche = New VaucheDetalleUsuarioForm();

        	self::setConexion();
        	$this->_conn->open();
        	$this->_transaccion = $this->_conn->beginTransaction();

        	$arregloCondicion = [
    			'usuario' => Yii::$app->identidad->getUsuario(),
    		];

    		$result[] = self::actionSuprimirDetalleTemporal($modelVauche, $arregloCondicion);
        	$result[] = self::actionSuprimirDetalleTemporal($modelDepositoDetalle, $arregloCondicion);

        	if ( $recibo > 0 ) {
        		$arregloCondicion = [
        			'recibo' => $recibo,
        		];
        		$result[] = self::actionSuprimirDetalleTemporal($modelVauche, $arregloCondicion);
        		$result[] = self::actionSuprimirDetalleTemporal($modelDepositoDetalle, $arregloCondicion);
        	}

        	$cancel = false;
        	foreach ( $result as $key => $value ) {
        		if ( $value === false ) {
        			$cancel = true;
        		}
        	}

        	if ( !$cancel ) {
        		$this->_transaccion->commit();
        		$procesoExitoso = true;
        	} else {
        		$this->_transaccion->rollBack();
        	}
        	$this->_conn->close();

        	return $procesoExitoso;
        }




        /**
         * Metodo que permite suprimir registros de la tabla temporal que se utiliza
         * para registrar las formas de pagos de un recibo.
         * @param array $arregloCondicion arreglo de datos con la estructura:
         *  [
         *  	campo => valor de campo,
         *  ]
         * @return boolean retorna true si ejecuta la operacion satisfactoriamente o
         * false en caso contrario.
         */
        private function actionSuprimir($arregloCondicion)
        {
        	return $result = self::actionSuprimirRegistroTemporal($arregloCondicion);
        }





        /**
         * Metodo que renderiza una vista para cargar los detalles del vauche. Estos
         * detalle son los datos por concepto de efectivo y por cheques que posea el
         * vauche del deposito. Además el metodo inserta los registros por cada cheque.
         * Se intenta que si existe efectivo se sume al monto existente.
         * @return view
         */
        public function actionViewAgregarDetalleDeposito()
        {
			$recibo = isset($_SESSION['recibo']) ? (int)$_SESSION['recibo'] : 0;

			$usuario = Yii::$app->identidad->getUsuario();
			$searchTipoDeposito = New TipoDepositoSearch();
			$listaTipoDeposito = $searchTipoDeposito->getListaTipoDeposito();

        	$request = Yii::$app->request;
        	$modelVauche = New VaucheDetalleUsuarioForm();
        	$formName = $modelVauche->formName();

        	if ( $request->isGet ) {
        		// Viene de seleccionar el numero de deposito para cargar los detalles
        		// del mismo.

        		if ( (int)$request->get('recibo') === $recibo ) {
        			$modelVauche->linea = (int)$request->get('linea');
        			$modelVauche->recibo = (int)$request->get('recibo');
        			$modelVauche->deposito = (int)$request->get('deposito');
        			$modelVauche->usuario = $usuario;

		        	return $this->renderAjax('/recibo/pago/individual/agregar-detalle-deposito-form', [
		        													'modelVauche' => $modelVauche,
		        													'listaTipoDeposito' => $listaTipoDeposito,
		        													'url' => Url::to(['view-agregar-detalle-deposito']),
		        		]);
		        }
        	} else {

        		$postData = $request->post();

        		if ( $modelVauche->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($modelVauche);
				}

				if ( $modelVauche->load($postData) ) {

					if ( $modelVauche->validate() ) {
						// Se guarda el detalle del deposito.

						self::setConexion();
						$this->_conn->open();
						$this->_transaccion = $this->_conn->beginTransaction();

						$result = self::actionBeginAgregarDetalleDeposito($modelVauche, $postData);
						if ( $result ) {
							// Se actualiza el maestro del vaucher.
							$result = self::actionActualizarMontoDeposito($modelVauche, 'sumar');
							if ( $result ) {
								$this->_transaccion->commit();
							} else {
								$this->_transaccion->rollBack();
							}

						} else {
							$this->_transaccion->rollBack();
						}
						$this->_conn->close();
					}
				}
        	}
        	$this->redirect(['registrar-formas-pago']);
        }




        /***/
        public function actionViewAgregarSerialForm()
        {
			$recibo = isset($_SESSION['recibo']) ? (int)$_SESSION['recibo'] : 0;

			$usuario = Yii::$app->identidad->getUsuario();
			$modelSerial = New SerialReferenciaForm();
			$formName = $modelSerial->formName();

        	$request = Yii::$app->request;

        	if ( $request->isGet ) {
        		// Viene de seleccionar el numero de deposito para cargar los detalles
        		// del mismo.
	        	return $this->renderAjax('/recibo/pago/individual/agregar-serial-form', [
	        													'modelSerial' => $modelSerial,
	        													'url' => Url::to(['view-agregar-serial-form']),
	        			]);

        	} else {

        		$postData = $request->post();

        		if ( $modelSerial->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($modelSerial);
				}

				if ( $modelSerial->load($postData) ) {

					if ( $modelSerial->validate() ) {
						// Se guarda el detalle del deposito.

						// self::setConexion();
						// $this->_conn->open();
						// $this->_transaccion = $this->_conn->beginTransaction();

						// $result = self::actionBeginAgregarDetalleDeposito($modelSerial, $postData);
						// if ( $result ) {
						// 	// Se actualiza el maestro del vaucher.
						// 	$result = self::actionActualizarMontoDeposito($modelSerial, 'sumar');
						// 	if ( $result ) {
						// 		$this->_transaccion->commit();
						// 	} else {
						// 		$this->_transaccion->rollBack();
						// 	}

						// } else {
						// 	$this->_transaccion->rollBack();
						// }
						// $this->_conn->close();

					}
				}
        	}
        }







        /**
         * Metodo que inicia el proceso para guardar el detalle del vauche
         * @param VaucheDepositoDetalleUsuarioForm $model instancia de la clase.
         * @param array $postEnviado post enviado desde el formulario.
         * @return boolean.
         */
        private function actionBeginAgregarDetalleDeposito($model, $postEnviado)
        {
        	$result = false;
        	$tabla = $model->tableName();
        	$model->monto = str_replace(',', '.', $model->monto);
        	return $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);

        }





        /**
         * [actionActualizarMontoDeposito description]
         * @param VaucheDetalleUsuarioForm $model
         * @param string $operacion 'sumar' o 'restar'
         * @return boolean
         */
        private function actionActualizarMontoDeposito($model, $operacion)
        {
        	$result = false;
        	$monto = 0;
        	$recibo = isset($model->recibo) ? $model->recibo : 0;
			$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
			$model->monto = str_replace(',', '.', $model->monto);

			$monto = $pagoReciboSearch->contabilizarVaucheDetalleDepositoUsuario($model->usuario, $model->deposito);
			if ( $monto >= 0 ) {
				$modelUsuario = New DepositoDetalleUsuarioForm();
				$tabla = $modelUsuario->tableName();

				$arregloCondicion = [
					'recibo' => $model->recibo,
					'deposito' => $model->deposito,
					'usuario' => $model->usuario,
				];

				if ( $operacion == 'sumar' ) {
					$arregloDatos = [
						'monto' => $monto + $model->monto,
					];
				} elseif ( $operacion == 'restar' ) {
					$arregloDatos = [
						'monto' => $monto - $model->monto,
					];
				}

				$result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);
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



		/***/
		public function actionPrueba()
		{
			$request = Yii::$app->request;
			$postData = $request->get();

die(var_dump($postData));
		}



	}
?>