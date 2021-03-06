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
    use backend\models\recibo\pago\individual\SerialReferenciaUsuarioSearch;
    use backend\models\recibo\prereferencia\ReferenciaPlanillaUsuarioForm;
    use backend\models\recibo\txt\RegistroTxtSearch;
    use backend\models\recibo\txt\RegistroTxtReciboSearch;
    use common\models\referencia\GenerarReferenciaBancaria;
    use common\models\planilla\PlanillaSearch;
    use common\models\distribucion\presupuesto\GenerarPlanillaPresupuesto;
    use backend\models\recibo\pago\individual\PagoReciboIndividual;
    use common\controllers\pdf\deposito\ReciboRafagaController;
    use common\controllers\pdf\deposito\DepositoController;
    use backend\models\recibo\depositodetalle\DepositoDetalleSearch;
    use backend\models\utilidad\banco\BancoCuentaReceptora;
    use backend\models\usuario\AutorizacionUsuario;


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
            $varSessions = self::actionGetListaSessions();
            self::actionAnularSession($varSessions);
            $autorizacion = New AutorizacionUsuario();
            if ( $autorizacion->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']) ) {
                $_SESSION['begin'] = 1;
                $this->redirect(['mostrar-form-consulta']);
            } else {
                // Su perfil no esta autorizado.
                // El usuario no esta autorizado.
                $this->redirect(['error-operacion', 'cod' => 700]);
            }
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
        	self::actionAnularSession(['datosRecibo', 'recibo', 'postEnviado']);
            $model = New BusquedaReciboForm();
            if ( isset($_SESSION['begin']) ) {

                $request = Yii::$app->request;
                $postData = $request->post();

                // Permite controlar la activacion o no del boton para la rafaga del recibo de pago.
                $desactivarBotonRafaga = true;

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
                        $varSession = self::actionGetListaSessions();
                        self::actionAnularSession($varSession);
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

                if ( isset($postData['btn-rafaga-print']) ) {
                    if ( $postData['btn-rafaga-print'] == 2 ) {
                        $recibo = isset($_SESSION['reciboRafaga']) ? (int)$_SESSION['reciboRafaga'] : 0;
                        $this->redirect(['mostrar-form-rafaga-print', 'recibo' => $recibo]);
                    }
                }

				if ( $model->load($postData) ) {

					if ( $model->validate() ) {

                        $estatusRecibo = 0;
                        $_SESSION['reciboRafaga'] = $model->recibo;

						// Se verifica que el recibo cumpla las reglas de negocio establecidas.
						$pagoReciboSearch = New PagoReciboIndividualSearch($model->recibo);
						$mensajes = $pagoReciboSearch->validarEvento();
						if ( count($mensajes) == 0 ) {
							$urlFormaPagos = Url::to(['registrar-formas-pago']);
							$bloquearFormaPago = false;
							$htmlMensaje = null;
							$_SESSION['recibo'] = $model->recibo;

						} else {
                            $modelRecibo = New BusquedaReciboForm();
                            $modelRecibo->recibo = $model->recibo;
                            $modelRecibo->id_contribuyente = 0;
                            $modelRecibo->nro_control = 0;
							$htmlMensaje = $this->renderPartial('/recibo/pago/individual/warnings',[
																	'mensajes' => $mensajes,
											]);
						}

						// Arreglo de los provider del recibo y el de las planillas.
						$dataProviders = $pagoReciboSearch->getDataProviders();

                        if ( count($dataProviders[0]->getModels()) > 0 ) {
                            // $dataProviders[0]->getModels(), es el modelo de la entidad "depositos".
                            // $dataProviders[1]->getModels(), es el modelo de la entidad "depositos-planillas"
                            $estatusRecibo = isset($dataProviders[0]->getModels()[0]->toArray()['estatus']) ? (int)$dataProviders[0]->getModels()[0]->toArray()['estatus'] : 0;
                            $modelRecibo = $dataProviders[0]->getModels()[0];
                        }
                        if ( $estatusRecibo == 1 ) {

                            // Se verifica que todas la planillas contenidas en el recibo esten en condicion
                            // de pagadas.
                            $planillasModels = $dataProviders[1]->getModels();
                            foreach ( $planillasModels as $key => $mod ) {
                                if ( (int)$mod->estatus == 1 ) {
                                    $desactivarBotonRafaga = false;
                                } else {
                                    $desactivarBotonRafaga = true;
                                    break;
                                }
                            }
                        }

                        if ( $desactivarBotonRafaga ) {
                            self::actionAnularSession(['reciboRafaga']);
                        }

						$totales = $pagoReciboSearch->getTotalesReciboPlanilla($dataProviders);

						$result = self::actionInicializarTemporal($model->recibo);
						$htmlRecibo = $this->renderPartial('/recibo/pago/individual/_recibo-encontrado',[
																'dataProviderRecibo' => $dataProviders[0],
																'dataProviderReciboPlanilla' => $dataProviders[1],
																'totales' => $totales,
																'htmlMensaje' => $htmlMensaje,
																'urlFormaPagos' => $urlFormaPagos,
																'bloquearFormaPago' => $bloquearFormaPago,
                                                                'desactivarBotonRafaga' => $desactivarBotonRafaga,
                                                                'modelRecibo' => $modelRecibo,

											]);

						$caption = Yii::t('backend', 'Pago de Recibo');
						$subCaption = Yii::t('backend', 'Datos del Recibo');
						return $this->render('/recibo/pago/individual/recibo', [
													'model' => $model,
													'htmlRecibo' => $htmlRecibo,
													'caption' => $caption,
													'subCaption' => $subCaption,
													'bloquearFormaPago' => $bloquearFormaPago,
                                                    'desactivarBotonRafaga' => $desactivarBotonRafaga,
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
                $this->redirect(['error-operacion', 'cod' => 700]);
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
        	self::actionAnularSession(['datosBanco', 'postEnviado']);
        	$recibo = isset($_SESSION['recibo']) ? (int)$_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();

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

        				self::actionSetearBancoCuentaReceptoraEnDetallePago($model->id_banco, $model->cuenta_recaudadora);
        				$modelSerial = New SerialReferenciaForm();
        				self::actionInicializarEntidadTemporal($recibo, $usuario, $modelSerial);
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

        		$usuario = Yii::$app->identidad->getUsuario();
        		$request = Yii::$app->request;
	        	$postGet = $request->get();
	        	$postData = $request->post();

	        	$htmlSerialForm = null;		// Formulario para cargar los seriales manuales.

	        	// Se determina la cantidad de vauches registrados en las formas de pago.
				$cantidadDeposito = (int)self::actionCantidadDepositoRegistrado($recibo);

				$modelSerial = New SerialReferenciaForm();
				$model = New PreReferenciaPlanillaForm();

        		if ( isset($postData['btn-back']) ) {
	        		if ( $postData['btn-back'] == 2 ) {
	        			self::actionAnularSession(['fecha_pago']);
	        			$this->redirect(['seleccionar-cuenta-recaudadora']);

	        		} elseif ( $postData['btn-back'] == 9 ) {

	        		}
	        	} elseif ( isset($postData['btn-find-referencia']) ) {
	        		if ( $postData['btn-find-referencia'] == 3 ) {

	        			$formName = $model->formName();
	        			if ( !isset($_SESSION['fecha_pago']) ) {
	        				$_SESSION['fecha_pago'] = $model->fecha_pago;
	        			} else {
	        				if ( $_SESSION['fecha_pago'] !== date('Y-m-d', strtotime($postData[$formName]['fecha_pago'])) ) {
	        					$result = self::actionInicializarEntidadTemporal($recibo, $usuario, $modelSerial);
	        				}
	        			}

	        			$model->load($postData);
	        			$model->fecha_pago = date('Y-m-d', strtotime($postData[$formName]['fecha_pago']));
	        			$_SESSION['fecha_pago'] = $model->fecha_pago;

	        			// Se busca las referencias que se encuentran en el registro-txt de las planillas
	        			// pagadas en banco. Pero que no esten relacionada a ninguna pre-referencia anterior
	        			// Se tomaran aquellos registros asociados a la fecha de pago.
	        			$cuentaRecaudadora = $postData['cuenta_recaudadora'];
	        			$htmlSerialForm = self::actionViewHtmlPlanillaSinReferencia($model->fecha_pago, $cuentaRecaudadora);

	        		}
	        	} elseif ( isset($postData['btn-add-planilla']) ) {
	        		if ( $postData['btn-add-planilla'] == 4 ) {

	        			$cuentaRecaudadora = $postData['cuenta_recaudadora'];
	        			$chkIdRegistro = $postData['chkIdRegistro'];
	        			$fechaPago = $postData['fecha_pago'];

	        			$model->fecha_pago = date('Y-m-d', strtotime($postData['fecha_pago']));
	        			self::actionAgregarPlanillaComoSerial($chkIdRegistro, $fechaPago, $cuentaRecaudadora);
	        		}
	        	} elseif ( isset($postData['btn-add-deposito']) ) {
	        		if ( $postData['btn-add-deposito'] == 6 ) {

	        			$cuentaRecaudadora = $postData['cuenta_recaudadora'];
	        			$formName = $model->formName();
	        			$model->load($postData);
	        			$model->fecha_pago = date('Y-m-d', strtotime($postData[$formName]['fecha_pago']));

	        			self::actionAgregarDepositoComoSerial($recibo, $usuario, $model->fecha_pago, $cuentaRecaudadora);
	        		}
	        	} elseif ( isset($postData['btn-generar-pre-referencia']) ) {
	        		if ( $postData['btn-generar-pre-referencia'] == 7 ) {

	        			if ( self::actionVerificarReferenciaBancaria($postData['cuenta_recaudadora']) ) {
	        				$_SESSION['postEnviado'] = $postData;
	        				$this->redirect(['armar-resumen-pago']);
	        			} else {
	        				// Vista que indique que la referencia bancaria falló
	        				$errorMensaje = Yii::t('backend', 'Las pre-referencias bancarias no se realizarón correctamente');
	        				return $this->render('/recibo/pago/individual/error-pago', [
	        													'errorMensaje' => $errorMensaje,
	        						]);
	        			}
	        		}

	        	} elseif ( isset($postData['btn-quit']) ) {
	        		if ( $postData['btn-quit'] == 9 ) {
	        			$this->redirect(['quit']);
	        		}
	        	}



	        	$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
	        	$dataProviders = $pagoReciboSearch->getDataProviders();

	        	// Modelo del dataProvider relacionado a las planillas existentes en el recibo.
	        	// DepositoPLanilla
	        	$models = $dataProviders[1]->getModels();
	        	$totalPlanilla = self::actionTotalizarMontoDocumento($models, 'monto');

	        	$datosBanco = isset($_SESSION['datosBanco']) ? $_SESSION['datosBanco'] : [];
	        	$datosRecibo = isset($_SESSION['datosRecibo']) ? $_SESSION['datosRecibo'] : [];

	        	if ( !isset($_SESSION['fecha_pago']) ) {
        			$model->fecha_pago = ( $datosRecibo[0]['estatus'] == 1 ? $datosRecibo[0]['fecha'] : date('d-m-Y') );
        		} else {
        			$model->fecha_pago = $_SESSION['fecha_pago'];
        		}

        		$model->id_banco = $datosBanco['id_banco'];
        		$model->cuenta_recaudadora = $datosBanco['cuenta_recaudadora'];

        		if ( $modelSerial->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($modelSerial);
				}

				if ( isset($postData['btn-add-serial']) ) {
					if ( $postData['btn-add-serial'] == 5 ) {
						if ( $modelSerial->load($postData) ) {
							$modelSerial->fecha_edocuenta = date('Y-m-d', strtotime($modelSerial->fecha_edocuenta));
							$modelSerial->monto_edocuenta = str_replace('.', '', $modelSerial->monto_edocuenta);
							$modelSerial->monto_edocuenta = str_replace(',', '.', $modelSerial->monto_edocuenta);
							if ( $modelSerial->validate() ) {
								// guardar serial
								$modelSerial->observacion = self::actionSetObservacionSerialManual($datosBanco['cuenta_recaudadora']);
								self::actionGuardarSerialTemporal($modelSerial);
							}
						}
					}
				}

				if ( $datosBanco['tipo_cuenta'] == 'NO ES CUENTA RECAUDADORA' ) {
					$modelSerial->recibo = $recibo;
					$modelSerial->usuario = Yii::$app->identidad->getUsuario();
	        		$htmlSerialForm = $this->renderPartial('/recibo/pago/individual/agregar-serial-form',[
	        																		'modelSerial' => $modelSerial,
	        							]);
	        	}

	        	// Vista con los seriales-referencias agregados.
	        	$htmlSerialAgregado = self::actionViewHtmlSerialAgregado();

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
			        										'htmlSerialAgregado' => $htmlSerialAgregado,
			        										'totalPlanilla' => $totalPlanilla,
			        										'cantidadDeposito' => $cantidadDeposito,
        				]);
        	}
        }




        /**
         * Metodo que ejecuta la generacion de las pre-referencias bancarias, si no se detecta ningún error
         * y las referencia se ejecutan, se devolvera true, de lo contario false.
         * @param string $cuentaRecaudadora numero de cuenta (cuenta recaudadora seleccionada por el usuario)
         * @return boolean.
         */
        public function actionVerificarReferenciaBancaria($cuentaRecaudadora)
        {
        	$recibo = isset($_SESSION['recibo']) ?  $_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();
        	if ( $recibo > 0 ) {
        		$serialSearch = New SerialReferenciaUsuarioSearch($recibo, $usuario);
        		$modelSerial = $serialSearch->findSeriales();

        		$generarReferencia = New GenerarReferenciaBancaria($recibo, $modelSerial, $cuentaRecaudadora);
        		$referencia = $generarReferencia->iniciarReferencia();
        		if ( count($referencia) > 0 && count($generarReferencia->getError()) == 0 ) {
        			return true;
        		}
        	}

        	return false;
        }




        /**
         * Metodo que permite el seteo de los atributos:
         * - codigo-banco
         * - cuenta-deposito.
         * en la entidad temporal donde se guarda los detalles de las formas de
         * pagos del recibo.
         * @param integer $idBanco identificador del banco en la entidad "bancos"
         * @param string $cuentaRecaudadora numero de la cuenta receptora del pago.
         * @return boolean.
         */
        private function actionSetearBancoCuentaReceptoraEnDetallePago($idBanco, $cuentaRecaudadora)
        {
        	$recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
        	$result = false;
        	if ( $recibo > 0 ) {
        		self::setConexion();
        		$this->_conn->open();
        		$this->_transaccion = $this->_conn->beginTransaction();

        		$tabla = DepositoDetalleUsuarioForm::tableName();
        		$arregloCondicion = [
        			'recibo' => $recibo,
        		];
        		$arregloDato = [
        			'codigo_banco' => $idBanco,
        			'cuenta_deposito' => $cuentaRecaudadora,
        		];

        		$result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDato, $arregloCondicion);
        		if ( $result ) {
        			$this->_transaccion->commit();
        		} else {
        			$this->_transaccion->rollBack();
        		}
        		$this->_conn->close();
        	}
        	return $result;

        }





        /**
         * [actionArmarResumenPago description]
         * @return [type] [description]
         */
        public function actionArmarResumenPago()
        {
        	$recibo = isset($_SESSION['recibo']) ?  $_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();
        	if ( $recibo > 0 ) {
        		$request = Yii::$app->request;
        		$postGet = $request->get();
        		$postData = $request->post();

				if ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						$this->redirect(['pre-referencia']);

					} elseif ( $postData['btn-back'] == 9 ) {
						$this->redirect(['pre-referencia']);
					}
				}

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				if ( isset($postData['btn-guardar-pago']) ) {
					if ( $postData['btn-guardar-pago'] == 9 ) {
						// Se guarda el pago.
						$pago = New PagoReciboIndividual($recibo, date('Y-m-d'));
        				$result = $pago->iniciarPagoRecibo();

        				if ( $result ) {
        					// mostrar pago guardado
        					$this->redirect(['pago-guardado', 'recibo' => $recibo]);

        				} elseif ( !$result ) {
        					$errorMensaje = Yii::t('backend', 'La operación no se ejecuto satisfactoriamente');
        					return $this->render('/recibo/pago/individual/error-pago', [
        													'errorMensaje' => $errorMensaje,
        						]);
        				}
					}
				}

        		$postEnviado = isset($_SESSION['postEnviado']) ? $_SESSION['postEnviado'] : [];

        		if ( (int)$postEnviado['recibo'] == (int)$recibo ) {

        			$urlFormaPagos = '';
        			$bloquearFormaPago = false;
        			// Recibo y las planilas

					$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);
					$htmlMensaje = null;

					// Arreglo de los provider del recibo y el de las planillas.
					$dataProviders = $pagoReciboSearch->getDataProviders();

					$totales = $pagoReciboSearch->getTotalesReciboPlanilla($dataProviders);

					$htmlRecibo = $this->renderPartial('/recibo/pago/individual/datos-recibo',[
															'dataProviderRecibo' => $dataProviders[0],
															'dataProviderReciboPlanilla' => $dataProviders[1],
															'totales' => $totales,

										]);

					$dataProvider = $pagoReciboSearch->getDataProviderRegistroTemp($usuario);
        			$montoAgregado = $pagoReciboSearch->getTotalFormaPagoAgregado($usuario);

        			$htmlFormaPago = $this->renderPartial('/recibo/pago/individual/resumen-forma-pago', [
			      								'montoAgregado' => $montoAgregado,
			      								'dataProvider' => $dataProvider,
			      						]);


        			$datosBanco = $_SESSION['datosBanco'];
        			$htmlCuentaRecaudadora = $this->renderPartial('/recibo/pago/individual/resumen-cuenta-recaudadora',[
        													'datosBanco' => $datosBanco,
        					]);


					$caption = Yii::t('backend', 'Resumen de pago. Recibo Nro.') . $recibo ;
					return $this->render('/recibo/pago/individual/resumen-pago-form',[
															'caption' => $caption,
															'htmlRecibo' => $htmlRecibo,
															'htmlFormaPago' => $htmlFormaPago,
															'htmlCuentaRecaudadora' => $htmlCuentaRecaudadora,
							]);
        		}
        	}
        }




        /**
         * Metodo que renderiza una vista con la informacion del recibo.
         * @return view
         */
        public function actionPagoGuardado()
        {
        	$recibo = isset($_SESSION['recibo']) ?  $_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();
        	$request = Yii::$app->request;

        	$postData = $request->post();
        	if ( isset($postData['btn-pagar-otro']) ) {
        		if ( $postData['btn-pagar-otro'] == 9 ) {
        			$this->redirect(['index']);
        		}
        	} elseif ( isset($postData['btn-quit']) ) {
        		if ($postData['btn-quit'] == 1 ) {
        			$this->redirect(['quit']);
        		}
        	} elseif ( isset($postData['btn-rafaga-print']) ) {
                if ( $postData['btn-rafaga-print'] == 2 ) {
                    $recibo = isset($_SESSION['reciboRafaga']) ? $_SESSION['reciboRafaga'] : 0;
                    $this->redirect(['mostrar-form-rafaga-print', 'recibo' => $recibo]);
                }
            }

        	if ( $recibo > 0 ) {
        		$varSession = self::actionGetListaSessions();
				self::actionAnularSession($varSession);
				self::actionInicializarTemporal($recibo);

        		$postGet = $request->get();
        		if ( (int)$recibo == (int)$postGet['recibo'] ) {

                    $_SESSION['reciboRafaga'] = (int)$recibo;
        			$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);

					// Arreglo de los provider del recibo y el de las planillas.
					$dataProviders = $pagoReciboSearch->getDataProviders();

					$totales = $pagoReciboSearch->getTotalesReciboPlanilla($dataProviders);

					$htmlRecibo = $this->renderPartial('/recibo/pago/individual/datos-recibo',[
															'dataProviderRecibo' => $dataProviders[0],
															'dataProviderReciboPlanilla' => $dataProviders[1],
															'totales' => $totales,

										]);


                    // Datos de la formas de pago.
                    $detalleSearch = New DepositoDetalleSearch(null, null);
                    $dataProviderDetalle = $detalleSearch->getDataProviderDepositoDetalle($recibo);
                    $htmlDepositoDetalle = $this->renderPartial('@backend/views/recibo/deposito-detalle/deposito-detalle-forma-pago',[
                                                                            'dataProviderDetalle' => $dataProviderDetalle,
                                            ]);


                    // Datos de la cuenta recaudadora.
                    $detalleModel = $dataProviderDetalle->getModels()[0];
                    $cuenta = $detalleModel['cuenta_deposito'];

        			$datosBanco = self::datoBancoCuentaReceptora($cuenta);
        			$htmlCuentaRecaudadora = $this->renderPartial('/recibo/pago/individual/resumen-cuenta-recaudadora',[
        													'datosBanco' => $datosBanco,
        					                   ]);


                    $model = New BusquedaReciboForm();
                    $model->recibo = $recibo;
                    $model->id_contribuyente = $dataProviders[0]->getModels()[0]->toArray()['id_contribuyente'];
                    $model->nro_control = $dataProviders[0]->getModels()[0]->toArray()['nro_control'];


                    $desactivarBotonRafaga = false;
					$caption = Yii::t('backend', 'Resumen de pago guardado. Recibo Nro. ') . $recibo ;
					return $this->render('/recibo/pago/individual/resumen-pago-efectuado-form',[
															'caption' => $caption,
															'htmlRecibo' => $htmlRecibo,
															'htmlFormaPago' =>  $htmlDepositoDetalle,
															'htmlCuentaRecaudadora' => $htmlCuentaRecaudadora,
                                                            'desactivarBotonRafaga' => $desactivarBotonRafaga,
                                                            'modelRecibo' => $model,
                                                            'codigo' => 100,
							]);
        		} else {
                    // La informacion del recibo a pagar no coincide con la enviada.
                    $this->redirect(['error-operacion', 'cod' => 724]);
                }
        	} else {
        		$this->redirect(['index']);
        	}
        }



        /**
         * Metodo para mostrar el formulario qye permitira la impresion de la rafaga
         * correspondiente del recibo. La vista sera en formato modal.
         * @return view
         */
        public function actionMostrarFormRafagaPrint($recibo)
        {
            $request = Yii::$app->request;
            $getData = $request->get();

            $recibo = isset($getData['recibo']) ? (int)$getData['recibo'] : 0;

            if ( $recibo == (int)$getData['recibo'] && $recibo > 0 ) {

                self::actionAnularSession(['reciboRafaga']);
                // $reciboRafaga = New ReciboRafagaController($recibo);
                // $mensajes = $reciboRafaga->actionGenerarRafagaReciboPdf();

                // $htmlMensaje = $this->renderPartial('/recibo/pago/individual/warnings',[
                //                                             'mensajes' => $mensajes,
                //                             ]);

                if ( (int)$getData['id_contribuyente'] > 0 ) {
                    // Controlador que gestiona la generacion del pdf.
                    $depositoPdf = New DepositoController($recibo, (int)$getData['id_contribuyente'], (int)$getData['nro']);
                    return $depositoPdf->actionGenerarReciboPdf();

                } else {
                    $mensajes = [Yii::t('backend','El Id del contribuyente no esta definido para el recibo ') . $recibo];
                    $htmlMensaje = $this->renderPartial('/recibo/pago/individual/warnings',[
                                                             'mensajes' => $mensajes,
                                             ]);
                    return $this->render('/recibo/pago/error/error',[
                                                    'htmlMensaje' => $htmlMensaje,
                            ]);
                }
            }
        }



        /**
         * Metodo que iniciar el proceso de relacionar las planillas con los seriales (referencias bancarias).
         * @param array $postEnviado post enviado
         * @return array
         */
        public function actionArmarReferenciaBancaria($postEnviado)
        {
        	$cuentaRecaudadora = $postEnviado['cuenta_recaudadora'];
        	$result = false;
        	$referencia = null;
        	$recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();

    		$serialSearch = New SerialReferenciaUsuarioSearch($recibo, $usuario);
    		$dataProvider = $serialSearch->getDataProvider();

    		// Retorna un arreglo con los datos del modelo, sino encuentra nada
    		// el arreglo llega vacion
    		$models = $dataProvider->getModels();

    		$generar = New GenerarReferenciaBancaria($recibo, $models, self::actionSetObservacionSerialManual($cuentaRecaudadora));
    		$referencia = $generar->iniciarReferencia();
    		if ( count($generar->getError()) == 0 ) {
    			return $referencia;
    		} else {
    			return $referencia = [];
    		}

        }






        /**
         * Metodo que determina la cantidad de registro del tipo deposito (vauche)
         * @return integer.
         */
        private function actionCantidadDepositoRegistrado($recibo)
        {
        	return $registers = DepositoDetalleUsuarioForm::find()->where('recibo =:recibo',
        								 	 									[':recibo' => $recibo])
        								 						  ->andWhere('id_forma =:id_forma',
        								 						  				[':id_forma' => 2])
        	                             						  ->count();
        }





        /**
         * Metodo que permite agregar los numeros de depositos (vauches) como seriales de
         * preferencias.
         * @param integre $recibo numero de recibo que se esta procesando.
         * @param string $usuario nombre del usuario que esta realizando la operacion.
         * @param string $fechaPago fecha de pago
         * @return boolean.
         */
        public function actionAgregarDepositoComoSerial($recibo, $usuario, $fechaPago, $cuentaRecaudadora)
        {
        	$registers = DepositoDetalleUsuarioForm::find()->where('recibo =:recibo',
        								 	 							[':recibo' => $recibo])
        								 				   ->andWhere('id_forma =:id_forma',
        								 						  		[':id_forma' => 2])
        								 				   ->asArray()
        	                         				       ->all();
        	if ( count($registers) > 0 ) {

        		$modelSerial = New SerialReferenciaForm();
        		foreach ( $registers as $register ) {

        			// Se busca el numero de deposito en los seriles existente para no repetirlo.
        			$resultado = $modelSerial->find()->where('serial =:serial',
        														[':serial' => $register['deposito']])
        										     ->exists();
        			if ( !$resultado ) {

        				$modelSerial->recibo = $recibo;
			        	$modelSerial->serial = $register['deposito'];
			        	$modelSerial->fecha_edocuenta = $register['fecha'];
			        	$modelSerial->monto_edocuenta = $register['monto'];
			        	$modelSerial->estatus = 0;
			        	$modelSerial->observacion = self::actionSetObservacionSerialManual($cuentaRecaudadora);
			        	$modelSerial->usuario = $usuario;

			        	$result = self::actionGuardarSerialTemporal($modelSerial);
        			}
				}
        	}

        	$this->redirect(['pre-referencia']);
        }






        /**
         * Metodo que permite inicializar una entidad temporal
         * @param integer $recibo numero de recibo.
         * @param string $usuario nombre del usuario actual,
         * @param model $model instancia de la clase.
         * @return boolean
         */
        private function actionInicializarEntidadTemporal($recibo, $usuario, $model)
        {
        	$results = null;
        	$cancel = false;

        	self::setConexion();
        	$this->_conn->open();
        	$this->_transaccion = $this->_conn->beginTransaction();

        	$arregloCondicion = ['recibo' => $recibo];
			$results[] = self::actionSuprimirDetalleTemporal($model, $arregloCondicion);
			$arregloCondicion = ['usuario' => $usuario];
			$results[] = self::actionSuprimirDetalleTemporal($model, $arregloCondicion);

			foreach ( $results as $key => $value ) {
				if ( !$value ) {
					$cancel = true;
					break;
				}
			}

			if ( !$cancel ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return $cancel;
        }





        /**
         * Metodo que renderiza una vista con un grid que muestra
         * @param string $fechaPago fecha de pago del txt.
         * @return view
         */
        public function actionViewHtmlPlanillaSinReferencia($fechaPago, $cuentaRecaudadora)
        {
        	$txtSearch = New RegistroTxtReciboSearch();
        	$txtSearch->setFechaPago($fechaPago);
        	$dataProvider = $txtSearch->getDataProviderPlanillaSinRferenciaByFecha();
        	$models = $dataProvider->getModels();

        	$totalizar = self::actionTotalizarMontoDocumento($models, 'monto_recibo');

        	return $this->renderPartial('/recibo/pago/individual/lista-planilla-sin-referencia', [
        														'dataProvider' => $dataProvider,
        														'totalizar' => $totalizar,
        														'fechaPago' => $fechaPago,
        														'cuentaRecaudadora' => $cuentaRecaudadora,
        		]);
        }




        /**
         * Metodo que permite setear el valor de la observacion de la pre-referencia.
         * @param string $nroCuentaRecaudadora numero de la cuenta recaudadora.
         * @return string
         */
        public function actionSetObservacionSerialManual($nroCuentaRecaudadora)
        {
        	return 'SERIAL MANUAL, Cuenta Recaudadora: ' . $nroCuentaRecaudadora;
        }





        /**
         * Metodo que guardar una planilla como serial de pre-referencia, seleccionado
         * previamente por el usuario. Esta planilla viene desde el listado de Referencias
         * Bancarias.
         * @param array $chkIdRegistro arreglo de identificadores de la entidad "registros-txt".
         * @param string $fechaPago fecha de pago relacionada a los identificadores.
         * @return boolean retorna true si de guardado
         */
        public function actionAgregarPlanillaComoSerial($chkIdRegistro = [], $fechaPago, $cuentaRecaudadora)
        {
        	$rsult = false;
        	$recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();

        	$txtSearch = New RegistroTxtReciboSearch();
        	foreach ( $chkIdRegistro as $key => $value ) {

	        	$register = $txtSearch->findRegistroTxtById($value)->toArray();

	        	if ( count($register) > 0 ) {

		        	$modelSerial = New SerialReferenciaForm();

		        	$modelSerial->recibo = $recibo;
		        	$modelSerial->serial = $register['recibo'];
		        	$modelSerial->fecha_edocuenta = $register['fecha_pago'];
		        	$modelSerial->monto_edocuenta = $register['monto_recibo'];
		        	$modelSerial->estatus = 0;
		        	$modelSerial->observacion = self::actionSetObservacionSerialManual($cuentaRecaudadora);
		        	$modelSerial->usuario = $usuario;

		        	$result = self::actionGuardarSerialTemporal($modelSerial);
		        }
        	}

        	$this->redirect(['pre-referencia']);
        }





        /**
         * Metodo que permite renderizar una vista que muestra los seriales para las pre-referencias
         * agregadas por el usuario.
         * @return view
         */
        public function actionViewHtmlSerialAgregado()
        {
        	$recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
        	$usuario = Yii::$app->identidad->getUsuario();
    		$serialSearch = New SerialReferenciaUsuarioSearch($recibo, $usuario);
    		$dataProvider = $serialSearch->getDataProvider();

    		// Totalizar los montos de los seriales agregados.
    		$totalizar = 0;

    		// Retorna un arreglo con los datos del modelo, sino encuentra nada
    		// el arreglo llega vacion
    		$models = $dataProvider->getModels();

    		$totalizar = self::actionTotalizarMontoDocumento($models, 'monto_edocuenta');
    		return $this->renderPartial('/recibo/pago/individual/serial-agregado-form',[
    													'dataProvider' => $dataProvider,
    													'totalizar' => $totalizar,
    			]);
        }




        /**
         * Metodo que permite la totalizacion de un valore perteneciente a un modelo.
         * Dicha variable se pasa como argumento ($nombreCampo)
         * @param Model $model modelo de un dataProvider.
         * @param string $nombreCampo nombre del atributo que se totalizara.
         * @return double.
         */
        private function actionTotalizarMontoDocumento($model, $nombreCampo)
        {
        	$totalizado = 0;
        	foreach ( $model as $item ) {
        		$totalizado = $totalizado + $item->$nombreCampo;
        	}
        	return $totalizado;
        }





        /**
         * Metodo que permite suprimir un registro de los seriales agregados.
         * Esto seon los seriales (pre-referencia) agregados por el usuario.
         * @return boolean.
         */
        public function actionSuprimirSerialAgregado()
        {
        	$result = false;
        	$request = Yii::$app->request;
        	$postGet = $request->get();

        	$idSerial = isset($postGet['id']) ? $postGet['id'] : 0;

        	if ( $idSerial > 0 ) {
	        	$recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
	        	$usuario = Yii::$app->identidad->getUsuario();
	    		$serialSearch = New SerialReferenciaUsuarioSearch($recibo, $usuario);

	        	self::setConexion();
				$this->_transaccion = $this->_conn->beginTransaction();
				$this->_conn->open();

				$result = $serialSearch->suprimirSerialById($idSerial, $this->_conn, $this->_conexion);
				if ( $result ) {
					$this->_transaccion->commit();
				} else {
					$this->_transaccion->rollBack();
				}
				$this->_conn->close();
			}
			$this->redirect(['pre-referencia']);
        }






        /**
         * Metodo que permite guardar la referencia en una entidad temporal
         * @param SerialReferenciaUsuario $model instancia de la clase.
         * @return boolean
         */
        private function actionGuardarSerialTemporal($model)
        {
        	$result = false;
        	self::setConexion();
        	$this->_transaccion = $this->_conn->beginTransaction();
        	$this->_conn->open();

        	$tabla = $model->tableName();

        	$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);
        	if ( $result ) {
        		$this->_transaccion->commit();
        	} else {
        		$this->_transaccion->rollBack();
        	}
        	$this->_conn->close();
        	return $result;
        }





        /**
         * Metodo que renderiza un listado dde cuentas recaudadoras, segun el identificador
         * del banco seleccionado. La lista aparecera en la vista.
         * @return view
         */
        public function actionListarCuentaRecaudadora()
        {
        	$request = Yii::$app->request;
        	$postGet = $request->get();
        	$postData = $request->post();

        	$searchBanco = New BancoSearch();
        	$id = isset($postGet['id']) ? (int)$postGet['id'] : 0;
        	return $searchBanco->generarViewListaCuentaRecaudadora($id);

        }





        /**
         * Metodo que renderiza un mensaje indicando si un numero de cuemta determinado
         * corresponde a la cuenta recaudadora de la Alcaldia.
         * @return string
         */
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
         * Metodo que realiza la consulta sobre las cuentas recaudadoras que estan
         * registradas.
         * @return BancoCuentaReceptora
         */
        protected function findCuentaReceptora($cuenta = '')
        {
            if ( trim($cuenta) !== '' ) {
                return BancoCuentaReceptora::find()->alias('R')
                                               ->joinWith('banco B', true, 'INNER JOIN')
                                               ->where('R.cuenta =:cuenta', [':cuenta' => $cuenta])
                                               ->asArray()
                                               ->one();
            } else {
                return BancoCuentaReceptora::find()->alias('R')
                                                   ->joinWith('banco B', true, 'INNER JOIN')
                                                   ->asArray()
                                                   ->all();
            }
        }



        /**
         * Metodo para crear un arreglo con la estructura:
         * {
         *     'nombre' => nombre del banco
         *     'cuenta_recaudadora' => cuenta recpetora
         *     'tipo_cuenta' => define si la cuenta es CUENTA RECAUDADORA O NO ES CUENTA RECAUDADORA
         * }
         * y lo retorna.
         * @param string $cuenta numero de la cuenta receptora (string largo de enteros).
         * @return array
         */
        protected function datoBancoCuentaReceptora($cuenta)
        {
            $listaCuentasRecaudadoras = Yii::$app->ente->getCuentaRecaudadora();
            $datoBanco = [];
            $registers = self::findCuentaReceptora($cuenta);
            if ( count($registers) > 0 ) {
                if ( in_array($registers['cuenta'], $listaCuentasRecaudadoras) ) {
                    $tipo = 'CUENTA RECAUDADORA';
                } else {
                    $tipo = 'NO ES CUENTA RECAUDADORA';
                }
                $datoBanco = [
                    'nombre' => $registers['banco']['nombre'],
                    'cuenta_recaudadora' => $registers['cuenta'],
                    'tipo_cuenta' => $tipo,
                ];
            }
            return $datoBanco;
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
        	$recibo = isset($_SESSION['recibo']) ? $_SESSION['recibo'] : 0;
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
         * @param model $model instancia de la clase.
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
        	$modelSerial = New SerialReferenciaForm();

        	self::setConexion();
        	$this->_conn->open();
        	$this->_transaccion = $this->_conn->beginTransaction();

        	$arregloCondicion = [
    			'usuario' => Yii::$app->identidad->getUsuario(),
    		];

    		$result[] = self::actionSuprimirDetalleTemporal($modelVauche, $arregloCondicion);
        	$result[] = self::actionSuprimirDetalleTemporal($modelDepositoDetalle, $arregloCondicion);
        	$result[] = self::actionSuprimirDetalleTemporal($modelSerial, $arregloCondicion);

        	if ( $recibo > 0 ) {
        		$arregloCondicion = [
        			'recibo' => $recibo,
        		];
        		$result[] = self::actionSuprimirDetalleTemporal($modelVauche, $arregloCondicion);
        		$result[] = self::actionSuprimirDetalleTemporal($modelDepositoDetalle, $arregloCondicion);
        		$result[] = self::actionSuprimirDetalleTemporal($modelSerial, $arregloCondicion);
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
         * Metodo que permite renderizar una vista de los detalles de la planilla
         * que se encuentran en la solicitud.
         * @return View Retorna una vista que contiene un grid con los detalles de la
         * planilla.
         */
        public function actionViewPlanilla()
        {
            $request = Yii::$app->request;
            $getData = $request->get();

            $planilla = $getData['p'];
            $planillaSearch = New PlanillaSearch($planilla);
            $dataProvider = $planillaSearch->getArrayDataProviderPlanilla();

            // Se determina si la peticion viene de un listado que contiene mas de una
            // pagina de registros. Esto sucede cuando los detalles de un listado contienen
            // mas de los manejados para una pagina en la vista.
            if ( isset($request->queryParams['page']) ) {
                $planillaSearch->load($request->queryParams);
            }
            $url = Url::to(['generar-pdf']);
            return $this->renderAjax('@backend/views/planilla/planilla-detalle', [
                                            'dataProvider' => $dataProvider,
                                            'caption' => 'Planilla: ' . $planilla,
                                            'p' => $planilla,
            ]);
        }





        /**
		 * Metodo salida del modulo.
		 * @return view
		 */
		public function actionQuit()
		{
			self::actionInicializarTemporal();
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return Yii::$app->getResponse()->redirect(array('/menu/vertical'));
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
						'postEnviado',
						'datosRecibo',
						'datosBanco',
                        'reciboRafaga',
					];
		}


	}
?>