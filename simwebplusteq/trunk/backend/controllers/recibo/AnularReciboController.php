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
 *	@brief Clase AnularReciboController del lado del contribuyente backend.
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


 	namespace backend\controllers\recibo;


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
	use backend\models\recibo\recibo\AnularReciboSearch;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use yii\helpers\ArrayHelper;


	session_start();		// Iniciando session

	/***/
	class AnularReciboController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;


		const CONFIG = 112;	// Anulacion de recibo


		/**
		 * Metodo que inicia el modulo
		 * @return
		 */
		public function actionIndex()
		{
			self::actionAnularSession(['recibo', 'begin']);
			if ( isset($_SESSION['idContribuyente']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();

				$idContribuyente = $_SESSION['idContribuyente'];
				$model = New AnularReciboForm();
				$formName = $model->formName();
				$model->load($postData);

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
										$_SESSION['begin'] = 1;
										$chkRecibo = $postData['chkRecibo'];
										$depositos =  $model->findListaDeposito($chkRecibo);

										$result = self::actionBeginSave($depositos);
										if ( $result ) {
											$this->_transaccion->commit();
											$this->_conn->close();
											return self::actionViewSolicitud($depositos);
										} else {
											$this->_transaccion->rollBack();
											$this->_conn->close();
										}
									}
								}

							}
						}
					}
				}

				$model->id_contribuyente = $idContribuyente;
				$model->estatus = 0;
				$dataProvider = $model->searchListaDeposito();
				return $this->render('@frontend/views/recibo/anulacion/_list',[
										'model' => $model,
										'caption' => 'Lista de Recibos Pendientes',
										'dataProvider' => $dataProvider,
					]);

			} else {
				$this->redirect(['error-operacion', 'cod' => 932]);
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
			$result = false;
			if ( isset($_SESSION['recibo']) ) {

				$recibo = (int)$_SESSION['recibo'];
				$idContribuyente =  $_SESSION['idContribuyente'];
				self::actionAnularSession(['recibo']);

				if ( $recibo > 0 ) {

					$model = New AnularReciboForm();
					$model->id_contribuyente = $idContribuyente;
					$model->recibo = $recibo;
					$model->estatus = 0;

					$dataProvider = $model->searchDepositoPlanilla();
					$deposito = $model->findDeposito();

					if ( $deposito['estatus'] == 0 ) {
						if ( $dataProvider->getTotalCount() > 0 ) {
							$_SESSION['begin'] = 1;
							return $this->render('@frontend/views/recibo/anulacion/recibo-seleccionado',[
														'model' => $model,
														'deposito' => $deposito,
														'dataProvider' => $dataProvider,
														'caption' => 'Recibo seleccionado ' . $recibo,
								]);
						} else {
							// No se encontraron las planillas asociadas en la tabla respectiva.

						}
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
						if ( isset($_SESSION['begin']) ) {
							$deposito = $model->findDeposito();

							$result = self::actionBeginSave($deposito);
							if ( $result ) {
								$this->_transaccion->commit();
								$this->_conn->close();
								self::actionAnularSession(['begin']);
								return self::actionViewSolicitud($deposito);

							} else {
								$this->_transaccion->rollBack();
								$this->_conn->close();
							}
						}
					}
				}
			}


		}




		/**
		 * Metodo que incia el proceso de guardar el recibo y las planillas asociadas
		 * @param  Deposito $depositos modelo de la clase "Deposito" con datos.
		 * @return boolean retorna true si guarda satisfactoriamente, flase en caso
		 * contrario.
		 */
		private function actionBeginSave($depositos)
		{
			$result = false;
			$nroSolicitud = 0;

			if ( isset($_SESSION['begin']) ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					$idContribuyente = $_SESSION['idContribuyente'];
					$this->_conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->_conn = $this->_conexion->initConectar('db');
	      			$this->_conn->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();

					$modelParametro = New ParametroSolicitud(self::CONFIG);
					// Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
					// del parametro y el valor del elemento es el contenido del campo en base de datos.
					$conf = $modelParametro->getParametroSolicitud([
														'id_config_solicitud',
														'tipo_solicitud',
														'impuesto',
														'nivel_aprobacion'
												]);


					$model = New AnularReciboForm();
					if ( is_array($depositos) ) {
						foreach ( $depositos as $deposito ) {

							$model->recibo = $deposito->recibo;
							$model->id_contribuyente = $idContribuyente;
							$model->estatus = 0;

							$nroSolicitud = self::actionCreateSolicitud($conf);

							if ( $nroSolicitud > 0 ) {
								$model->nro_solicitud = $nroSolicitud;
								$result = self::actionCreateAnularRecibo($model, $deposito, $conf);
								if ( $result ) {
									$result = self::actionAnularRecibo($this->_conexion, $this->_conn,
																	   $deposito, $conf);
								}
							}
							if ( !$result ) { break; }
						}

					} else {
						$model->recibo = $depositos->recibo;
						$model->id_contribuyente = $idContribuyente;
						$model->estatus = 0;

						$nroSolicitud = self::actionCreateSolicitud($conf);

						if ( $nroSolicitud > 0 ) {
							$model->nro_solicitud = $nroSolicitud;
							$result = self::actionCreateAnularRecibo($model, $depositos, $conf);
							if ( $result ) {
								$result = self::actionAnularRecibo($this->_conexion, $this->_conn,
																   $depositos, $conf);
							}
						}

					}

					//$result = self::actionEnviarEmail($model, $postEnviado);
					//$result = true;

				} else {
					// No esta defino el contribuyente.
					$this->redirect(['error-operacion', 'cod' => 932]);
				}
			}
			return $result;
		}





		/**
		 * Metodo que guarda el registro respectivo en la entidad "solicitudes-contribuyente".
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna true si guardo correctamente o false sino guardo.
		 */
		private function actionCreateSolicitud($conf)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$user = Yii::$app->identidad->getUsuario();
			$nroSolicitud = 0;
			$modelSolicitud = New SolicitudesContribuyenteForm();
			$tabla = $modelSolicitud->tableName();
			$idContribuyente = $_SESSION['idContribuyente'];

			$nroSolicitud = 0;

			if ( count($conf) > 0 ) {
				// Valores que se pasan al modelo:
				// id-config-solicitud.
				// impuesto.
				// tipo-solicitud.
				// nivel-aprobacion
				$modelSolicitud->attributes = $conf;

				if ( $conf['nivel_aprobacion'] == 1 ) {
					$estatus = 1;
					$userFuncionario = $user;
					$fechaHoraProceso = date('Y-m-d H:i:s');
				}

				$modelSolicitud->id_contribuyente = $idContribuyente;
				$modelSolicitud->id_impuesto = 0;
				$modelSolicitud->usuario = $user;
				$modelSolicitud->fecha_hora_creacion = date('Y-m-d H:i:s');
				$modelSolicitud->inactivo = 0;
				$modelSolicitud->estatus = $estatus;
				$modelSolicitud->nro_control = 0;
				$modelSolicitud->user_funcionario = $userFuncionario;
				$modelSolicitud->fecha_hora_proceso = $fechaHoraProceso;
				$modelSolicitud->causa = 0;
				$modelSolicitud->observacion = '';

				// Arreglo de datos del modelo para guardar los datos.
				$arregloDatos = $modelSolicitud->attributes;

				if ( $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDatos) ) {
					$nroSolicitud = $this->_conn->getLastInsertID();
				}
			}

			return $nroSolicitud;
		}




		/**
		 * Metodo que guarda el detalle de la solicitud por anulacion de recibo.
		 * @param  AnularReciboForm $model modelo del tipo clase "AnularReciboForm".
		 * @param  Deposito $deposito modelo del tipo clase "Deposito".
		 * @param  array $conf arreglo que contiene los parametros principales de
		 * configuracion de la solicitud.
		 * @return boolean retorna true si guarda satisfactoriamente, false en caso
		 * contrario.
		 */
		private function actionCreateAnularRecibo($model, $deposito, $conf)
		{
			$result = false;
			$tabla = $model->tableName();
			$model->recibo = $deposito->recibo;
			$model->id_contribuyente = $deposito->id_contribuyente;
			$user = '';
			$model->usuario = Yii::$app->identidad->getUsuario();
			$model->estatus = 0;
			$model->fecha_hora = date('Y-m-d H:i:s');
			$model->user_funcionario = '';
			$model->fecha_hora_proceso = '0000-00-00 00:00:00';

			$arregloDatos = $model->attributes;
			if ( $conf['nivel_aprobacion'] == 1 ) {
				$model->estatus = 1;
				$model->user_funcionario = Yii::$app->identidad->getUsuario();
				$model->fecha_hora_proceso = date('Y-m-d H:i:s');
			}

			return $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);

		}



		/***/
		private function actionAnularRecibo($conexion, $conn, $deposito, $conf)
		{
			$result = true;
			if ( $conf['nivel_aprobacion'] == 1 ) {
				$result = false;
				$model = New AnularReciboSearch($deposito->recibo);
				$result = $model->anularRecibo($conexion, $conn);
			}

			return $result;
		}



		/***/
		public function actionViewSolicitud($depositos)
		{
			$model = New AnularReciboForm();

			if ( is_array($depositos) ) {
				foreach ( $depositos as $deposito ) {
					$listaRecibo[] = $deposito->recibo;
				}
			} else {
				$listaRecibo[] = $depositos->recibo;
			}

			$dataProvider = $model->searchSolicitud($listaRecibo);

			if ( $dataProvider->getTotalCount() > 0 ) {
				return $this->render('@frontend/views/recibo/anulacion/_view',[
								'dataProvider' => $dataProvider,
								'caption' => 'Solicitud',
								'codigo' => 100,
					]);
			} else {
				throw new NotFoundHttpException('No se encontro el registro');
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
							'conf',
							'begin',
					];

		}

	}
?>