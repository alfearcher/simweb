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
	use backend\models\utilidad\ut\UnidadTributariaForm;
	use common\models\planilla\Pago;
	use common\models\planilla\PagoDetalle;
	use common\models\ordenanza\OrdenanzaBase;
	use common\models\planilla\NumeroPlanillaSearch;
	use common\controllers\pdf\planilla\PlanillaPdfController;


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
			self::actionAnularSession(['begin', 'postEnviado', 'planilla']);
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

				$model = New LiquidarTasaForm();
die(var_dump($postData));
				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-liquidar']) ) {
					if ( $postData['btn-liquidar'] == 5 ) {
						//$model->resultado = str_replace('.', '', $model->resultado);
						//$model->resultado = str_replace(',', '.', $model->resultado);

						if ( $model->load($postData) ) {
							$model->resultado = str_replace('.', '', $postData['resultado']);
							$model->resultado = str_replace(',', '.', $model->resultado);

							if ( $model->validate(['id_impuesto', 'id_contribuyente']) ) {

								if ( $model->resultado > 0 ) {
die(var_dump($model));
									$result = self::actionBeginSave($model, $idContribuyente, $postData);
									if ( $result ) {
										$this->_transaccion->commit();
										$this->_conn->close();
										$this->redirect(['mostrar-tasa-creada', 'id' => $model->id_pago]);

									} else {
										$this->_transaccion->commit();
										$this->_conn->close();
									}
								} else {
									$model->addError('resultado', 'El monto del resultado debe ser mayor a cero (0)');
								}
							}
						}
					}
				} elseif ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 3 ) {
						$this->redirect(['index']);
					}
				}



				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}


		      	if ( $model->load($postData) ) {

		      		if ( $model->validate() ) {

		      			if ( $model->id_impuesto > 0 ) {
		      				// Mostrar formulario con la informacion de los parametros mas
		      				// los campos para ingresar un cantidad.

		      				// Unidad tributaria del año.
		      				$utDelAño = self::actionUnidadTributaria($model->ano_impositivo);

		      				// Informacion completa de la tasa que se liquidara.
		      				$tasa = self::actionTasa($model->id_impuesto);

		      				$montoEnMoneda = self::actionDeterminarMontoEnMoneda($tasa);

		      				return $this->render('/tasa/liquidar/_pre-view',[
		      											'model' => $model,
		      											'tasa' => $tasa,
		      											'utDelAño' => $utDelAño,
		      											'montoEnMoneda' => $montoEnMoneda,

		      					]);
		      			} else {
		      				// Mostrar mensaje de que no se pudo determinar el identificador de la tasa

		      			}
		      		}

		      	}

				$impuesto = New ImpuestoForm();
				$listaImpuesto = $impuesto->getListaImpuesto();

				$rutaLista = '/tasa/liquidar/liquidar-tasa/generar-lista-ano-impositivo';
				$caption = Yii::t('backend', 'Liquidar Tasas');
				$subCaption = Yii::t('backend', 'Seleccione los parametros solicitados');

				$model->id_contribuyente = $idContribuyente;
				$model->multiplicar_por = 0;
				$model->observacion = '';
				$model->resultado = 0;
				$model->id_pago = 0;
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
			$codigo = isset($request['codigo']) ? $request['codigo'] : 0;


			$tasaSearch = New LiquidarTasaSearch();
			if ( $impuesto > 0 && $año == 0 && $idCodigo == 0 && $subnivel == 0 ) {

				return $tasaSearch->generarViewListaAnoImpositivo($impuesto);

			} elseif ( $impuesto > 0 && $año > 0 && $idCodigo == 0 && $subnivel == 0 ) {

				return $tasaSearch->generarViewListaCodigoPresupuesto($impuesto, $año);

			} elseif ( $impuesto > 0 && $año > 0 && $idCodigo > 0 && $subnivel == 0 ) {

				return $tasaSearch->generarViewListaGrupoSubNivel($impuesto, $año, $idCodigo);

			} elseif ( $impuesto > 0 && $año > 0 && $idCodigo > 0 && $subnivel > 0 ) {

				return $tasaSearch->generarViewListaCodigoSubNivel($impuesto, $año, $idCodigo, $subnivel, true);

			}

		}




		/**
		 * Metodo que retorna el registro de la tasa que se utilizo en la liquidacion.
		 * Se recibe un arreglo con los atributos del registro.
		 * @param integer $idImpuesto identificacion de la entidad.
		 * @return array
		 */
		public function actionTasa($idImpuesto)
		{
			// Parametros completos de la tasa.
			$searchTasa = New LiquidarTasaSearch();
			return $tasa = $searchTasa->findTasa($idImpuesto);
		}




		/**
		 * Metodo que retorna el monto de la unidad tributaria para un año.
		 * @param integer $añoImpositivo año impositivo.
		 * @return double.
		 */
		public function actionUnidadTributaria($añoImpositivo)
		{
			$montoUt = 0;
			$ut = New UnidadTributariaForm();
			$montoUt = $ut->getUnidadTributaria((int)$añoImpositivo);
			return $montoUt;
		}


		/**
		 * Metodo que permite determinar el monto en moneda segun el parametro de calculo.
		 * Esto permite determinar la cabtidad en moneda que se debe utilizar si la tasa
		 * esta calculada en unidades tributarias, enn caso de que el calculo sea en moneda
		 * nacional o en porcentaje se debera colocar 1.
		 * @param array $tasa arreglo con el registro de la tasa utilixada en los alculos.
		 * @return double.
		 */
		public function actionDeterminarMontoEnMoneda($tasa)
		{
			$monto = 0;
			if ( $tasa['tipoRango']['tipo_rango'] == 0 ) {
				$monto = $tasa['monto'];

			} elseif ( $tasa['tipoRango']['tipo_rango'] == 1 ) {
				$monto = $tasa['monto'] * self::actionUnidadTributaria($tasa['ano_impositivo']);

			} elseif ( $tasa['tipoRango']['tipo_rango'] == 2 ) {
				$monto = round($tasa['monto'] / 100, 2);

			} else {
				$monto = 1;

			}

			return $monto;
		}


		/**
		 * Metodo que inicia el proceos para guardar la liquidacion
		 * @param PagoDetall $models arreglo de modelo de la clase PagoDetalle().
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return boolean retorna true o false.
		 */
		private function actionBeginSave($model, $idContribuyente, $postEnviado)
		{

			$result = false;
			$idPago = 0;
			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');
  			$this->_conn->open();

  			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			// Se genera otra planilla para los detalles de la liquiadcion.
			$idPago = self::actionGuardarPago($this->_conexion, $this->_conn, $idContribuyente);
			if ( $idPago > 0 ) {
				$_SESSION['idPago'] = $idPago;
				$fechaVcto = OrdenanzaBase::getFechaVencimientoSegunFecha(date('Y-m-d'));
				$observacion = $postEnviado['codigo-presupuesto-descripcion'] . ' / ' .
							   $postEnviado['codigo-descripcion'] . ' / ' .
							   $model['observacion'] . ' / factor: ' . $model['multiplicar_por'];

				$model->id_pago = $idPago;

				$pagoDetalleModel = New PagoDetalle();
				$pagoDetalleModel['id_pago'] = $idPago;
				$pagoDetalleModel['id_impuesto'] = $model['id_impuesto'];
				$pagoDetalleModel['impuesto'] = $model['impuesto'];
				$pagoDetalleModel['ano_impositivo'] = $model['ano_impositivo'];
				$pagoDetalleModel['trimestre'] = 0;
				$pagoDetalleModel['monto'] = $model['resultado'];
				$pagoDetalleModel['recargo'] = 0;
				$pagoDetalleModel['interes'] = 0;
				$pagoDetalleModel['descuento'] = 0;
				$pagoDetalleModel['referencia'] = 0;
				$pagoDetalleModel['pago'] = 0;
				$pagoDetalleModel['fecha_emision'] = date('Y-m-d');
				$pagoDetalleModel['fecha_pago'] = '0000-00-00';
				$pagoDetalleModel['fecha_vcto'] = $fechaVcto;
				$pagoDetalleModel['descripcion'] = $observacion;
				$pagoDetalleModel['monto_reconocimiento'] = 0;
				$pagoDetalleModel['exigibilidad_pago'] = 99;
				$pagoDetalleModel['fecha_desde'] = '0000-00-00';
				$pagoDetalleModel['fecha_hasta'] = '0000-00-00';

				$result = self::actionGuardarDetalle($pagoDetalleModel, $this->_conexion, $this->_conn);
			}

			return $result;

		}




		/**
		 * Metodo que guarda los detalle de la liquidacion
		 * @param PagoDetalle $model arreglo de modelo de la clase PagoDetella().
		 * @param ConexionController $conexion [description]
		 * @param  [type] $conn     [description]
		 * @return boolean retorna true o false.
		 */
		private function actionGuardarDetalle($model, $conexion, $conn)
		{
			$result = false;
			if ( count($model) > 0 ) {
				$tabla = $model->tableName();

				$result = $conexion->guardarRegistro($conn, $tabla, $model->attributes);

			}

			return $result;
		}





		/**
		 * Metodo que guarda el maetro de la planilla y ademas se genera el numero de la misma.
		 * @param  ConexionController $conexion        [description]
		 * @param  [type] $conn            [description]
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return integer retorna el identificador de la entidad "pagos".
		 */
		private function actionGuardarPago($conexion, $conn, $idContribuyente)
		{
			$idPago = 0;
			$numero = 0;
			$pago = New Pago();
			$tabla = $pago->tableName();

			$planillaSearch = New NumeroPlanillaSearch();

			$intento = 10;
			while ( $intento >= 1 ) {
				$numero = $planillaSearch->getGenerarNumeroPlanilla();
				if ( $numero > 0 ) {
					break;
				} else {
					$intento--;
				}
			}

			if ( $numero > 0 ) {
				$pago->ente = Yii::$app->ente->getEnte();
				$pago->id_contribuyente = $idContribuyente;
				$pago->planilla = $numero;
				$pago->status_pago = 0;
				$pago->notificado = 0;
				$pago->ult_act = date('Y-m-d');
				$pago->recibo = 0;
				$pago->id_moneda = 1;
				$pago->exigibilidad_deuda = 0;

				$arregloDatos = $pago->attributes;

				if ( $conexion->guardarRegistro($conn, $tabla, $arregloDatos) ) {
					$idPago = $conn->getLastInsertID();
				}
			}

			return $idPago;

		}




		/***/
		public function actionMostrarTasaCreada($id)
		{
			if ( $_SESSION['idPago'] == $id ) {
				self::actionAnularSession(['idPago']);
				$model = PagoDetalle::find()->alias('D')
				                            ->joinWith('pagos P', true, 'INNER JOIN')
				                            ->where('D.id_pago =:id_pago',[':id_pago' => $id])
				                            ->asArray()
				                            ->all();

				if ( $model !== null ) {

					$_SESSION['planilla'] = $model[0]['pagos']['planilla'];
					$searchTasa = New LiquidarTasaSearch();
					$tasa = $searchTasa->findTasa($model[0]['id_impuesto']);

					$caption = Yii::t('backend', 'Tasa liquidada');
					return $this->render('/tasa/liquidar/_view-create',[
												'model' => $model,
												'tasa' => $tasa,
												'caption' => $caption,
												'codigo' => 100,
						]);
				}
			} else {

			}
		}



		/***/
		public function actionGenerarPdf($p)
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($_SESSION['planilla']) ) {

				if ( $_SESSION['planilla'] == $p ) {
					$planilla = $p;
					$pdf = New PlanillaPdfController($planilla);
					$pdf->actionGenerarPlanillaPdf();
				} else {
					$this->redirect(['quit']);
				}
			} else {
				$this->redirect(['quit']);
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