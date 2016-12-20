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
 *	@file LiquidarVehiculoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-11-2016
 *
 *  @class LiquidarVehiculoController
 *	@brief Clase que gestiona el proceso de liquidacion de Vehiculos con
 *  periodos mayores a cero.
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


 	namespace backend\controllers\vehiculo\liquidar;


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
	use common\controllers\pdf\planilla\PlanillaPdfController;
	use backend\models\vehiculo\liquidar\Liquidar;
	use backend\models\vehiculo\liquidar\LiquidarVehiculoForm;
	use backend\models\vehiculo\liquidar\LiquidarVehiculoSearch;
	use common\models\planilla\PagoDetalle;
	use common\models\planilla\Pago;
	use common\models\planilla\NumeroPlanillaSearch;
	use common\models\planilla\PlanillaSearch;
	use yii\data\ArrayDataProvider;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\base\Model;
	use yii\helpers\ArrayHelper;
	use backend\models\recibo\depositoplanilla\DepositoPlanillaSearch;
	use frontend\controllers\planilla\ConsultaController;
	use frontend\controllers\planilla\PlanillaConsultaController;

	session_start();


	/***/
	class LiquidarVehiculoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		private $_objeto;




		/**
		 * Metodo que inicia el proceso
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$_SESSION['begin'] = 1;
			$this->redirect(['listar-vehiculo']);
		}




		/**
		 * Metodo que busca y renderiza una vista con una lista de los vehiculos activos.
		 * @return view retorna una vista con una lista de vehiculo.
		 */
		public function actionListarVehiculo()
		{
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				$chkSeleccion = [];
				$controlSeleccion = '';

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						return $this->redirect(['quit']);
					}
				}

				if ( $request->get('id') == 1 ) {
					$controlSeleccion = Yii::t('frontend', 'No ha seleccionado ningun vehiculo');
				}
				$url = Url::to(['indicar-liquidacion']);

				// Se crea una lista de vehiculos activos.
				$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
				$provider = $searchLiquidacion->getDataProviderVehiculo();

				if ( $provider !== null ) {
					$caption = Yii::t('frontend', 'Lista de Vehiculos');
					$subCaption = Yii::t('frontend', $caption .'. Seleccione el o los vehiculo(s) para liquidar el impuesto');
					return $this->render('@frontend/views/vehiculo/liquidar/_list',[
													'caption' => $caption,
													'subCaption' => $subCaption,
													'dataProvider' => $provider,
													'controlSeleccion' => $controlSeleccion,
													'url' => $url,
								]);
				} else {
					return $this->redirect(['error-operacion', 'cod' => 505]);

				}

			} else {
				return $this->redirect(['quit']);
			}
		}





		/***/
		public function actionIndicarLiquidacion()
		{
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				$chkSeleccion = [];

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						return $this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-begin']) ) {
					if ( $postData['btn-begin'] == 3 ) {

						if ( !isset($postData['chkIdImpuesto']) ) {

							// Se inicia de nuevo el modulo. No se ha seleccionado ningun vehiculo.
							return $this->redirect(['listar-vehiculo', 'id' => 1]);

						}

					}

				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 5 ) {

						$model = New LiquidarVehiculoForm($idContribuyente);
						$formName = $model->formName();

						$datos = $postData[$formName];
						if ( count($datos) > 0 ) {
							foreach ( $datos as $key => $value ) {
								$modelMultiplex[$key] = New LiquidarVehiculoForm($idContribuyente);
							}
							Model::loadMultiple($modelMultiplex, $postData);
							$result = Model::validateMultiple($modelMultiplex);
						}

						if ( Model::loadMultiple($modelMultiplex, $postData)  && Yii::$app->request->isAjax ) {
							Yii::$app->response->format = Response::FORMAT_JSON;
							return ActiveForm::validateMultiple($modelMultiplex);
				      	}


				      	if ( $result ) {		// Validacion correcta
				      		$gridHtml = [];

				      		// Se realiza el proceso de liquidacion por objeto y hasta el lapso final seleccionado.
				      		foreach ( $datos as $key => $vehiculo ) {
				      			$chkIdImpuesto[] = $vehiculo['id_impuesto'];

				      			$liquidar[$vehiculo['id_impuesto']] = New Liquidar($idContribuyente, $vehiculo['id_impuesto']);

				      			// Lapsos liquidados. Por vehiculo
				      			// Se toma el lapso final y se convierte en un arreglo con los indices 'ano_impositivo' y 'periodo'
				      			// para enviarlo como parametro a la liquidacion. Esto permite fijar el "hasta donde" se quiere liquiadr.
				      			$rango = explode('-', $vehiculo['lapso']);
				      			if ( count($rango) > 0 ) {
					      			$lapsoFinal = [
					      				'ano_impositivo' => $rango[0],
					      				'periodo' => $rango[1],
					      			];
					      		} else {
					      			$lapsoFinal = [];
					      		}

								$detalles[$vehiculo['id_impuesto']] = $liquidar[$vehiculo['id_impuesto']]->iniciarProcesoLiquidacion($lapsoFinal);

								// Se genera el proveedor de datos. ArrayDataProvider
								$provider = $model->getDataProviderDetalleLiquidacion($detalles[$vehiculo['id_impuesto']]);

								// Genera una vista individual de las liquidaciones de cada objeto.Muestra un grid con dichas
								// liquidaciones por lapsos.
								$infoVehiculo = 'Placa: ' . $vehiculo['placa'] . ' - Marca: ' . $vehiculo['marca'] .
								                ' - Modelo: ' . $vehiculo['modelo'] . ' - Color: ' . $vehiculo['color'];
								$subCaption = Yii::t('frontend', $infoVehiculo);
								$gridHtml[$vehiculo['id_impuesto']] = $this->renderPartial('@frontend/views/vehiculo/liquidar/resumen-individual-liquidacion',[
																								'dataProvider' => $provider,
																								'subCaption' => $subCaption,
																								'guardo' => '',
																								'label' => 'label label-default',
																			]);
				      		}

				      		// Se crea un modelo PagoDetalle con todos los registros liquidados por objetos.
				      		$models = self::actionCreateModelPago($detalles);

				      		// Se muetra el resumen de lo liquidado.
				      		$url = Url::to(['confirmar-save']);
				      		$caption = Yii::t('frontend', 'Liquidacion de Vehiculo');
							$subCaption = Yii::t('frontend', $caption . '. Resumen por Vehiculo');
							return $this->render('@frontend/views/vehiculo/liquidar/pre-view-liquidacion',[
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'gridHtml' => $gridHtml,
																	'url' => $url,
																	'models' => $models,
											]);


				      	} else {


				      		$chkSeleccion = ArrayHelper::map($datos, 'id_impuesto', 'id_impuesto');
				      		$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
							$vehiculos = $searchLiquidacion->getListaVehiculo($chkSeleccion);
							foreach ( $chkSeleccion as $key => $value ) {
								$lapso[] = $searchLiquidacion->getListaLapsoPendiente($value);
							}

				      		$opciones = [
								'back' => '/vehiculo/liquidar/liquidar-vehiculo/index',
							];
							$caption = Yii::t('frontend', 'Liquidacion de Vehiculo(s)');
							$subCaption = Yii::t('frontend', $caption .'. Indique los parametros para liquidar el impuesto');
							return $this->render('@frontend/views/vehiculo/liquidar/lista-vehiculo-liquidacion',[
															'caption' => $caption,
															'subCaption' => $subCaption,
															'models' => $modelMultiplex,
															'opciones' => $opciones,
															'lapso' => $lapso,
										]);
				      	}

					}

				} elseif ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						return $this->redirect(['listar-vehiculo']);
					}
				}

				$chkSeleccion = isset($postData['chkIdImpuesto']) ? $postData['chkIdImpuesto'] : [];
				$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
				$vehiculos = $searchLiquidacion->getListaVehiculo($chkSeleccion);

				if ( count($vehiculos) > 0 ) {

					foreach ( $vehiculos as $i => $vehiculo ) {
						$lapso[$i] = $searchLiquidacion->getListaLapsoPendiente((int)$vehiculo['id_vehiculo']);
						$models[$i] = New LiquidarVehiculoForm($idContribuyente);
						$models[$i]['id_impuesto'] = $vehiculo['id_vehiculo'];
						$models[$i]['id_contribuyente'] = $idContribuyente;
						$models[$i]['placa'] = $vehiculo['placa'];
						$models[$i]['marca'] = $vehiculo['marca'];
						$models[$i]['modelo'] = $vehiculo['modelo'];
						$models[$i]['color'] = $vehiculo['color'];
						$models[$i]['lapso'] = $lapso[$i];
					}

					$opciones = [
						'back' => '/vehiculo/liquidar/liquidar-vehiculo/index',
					];
					$caption = Yii::t('frontend', 'Liquidacion de Vehiculo(s)');
					$subCaption = Yii::t('frontend', $caption .'. Indique los parametros para liquidar el impuesto');
					return $this->render('@frontend/views/vehiculo/liquidar/lista-vehiculo-liquidacion',[
													'caption' => $caption,
													'subCaption' => $subCaption,
													'models' => $models,
													'opciones' => $opciones,
													'lapso' => $lapso,
								]);
				} else {
					return $this->redirect(['error-operacion', 'cod' => 505]);

				}

			} else {
				return $this->redirect(['quit']);
			}
		}





		/***/
		protected function actionCreateModelPago($detalles)
		{
			foreach ( $detalles as $i => $detalle ) {
				foreach ( $detalle as $key => $value ) {

					$models[$i][$key] = New PagoDetalle();
					$models[$i][$key]['id_pago'] = $value['id_pago'];
					$models[$i][$key]['id_impuesto'] = $value['id_impuesto'];
					$models[$i][$key]['impuesto'] = $value['impuesto'];
					$models[$i][$key]['ano_impositivo'] = $value['ano_impositivo'];
					$models[$i][$key]['trimestre'] = $value['trimestre'];
					$models[$i][$key]['monto'] = $value['monto'];
					$models[$i][$key]['recargo'] = $value['recargo'];
					$models[$i][$key]['interes'] = $value['interes'];
					$models[$i][$key]['descuento'] = $value['descuento'];
					$models[$i][$key]['referencia'] = $value['referencia'];
					$models[$i][$key]['pago'] = $value['pago'];
					$models[$i][$key]['fecha_emision'] = $value['fecha_emision'];
					$models[$i][$key]['fecha_pago'] = $value['fecha_pago'];
					$models[$i][$key]['fecha_vcto'] = $value['fecha_vcto'];
					$models[$i][$key]['descripcion'] = $value['descripcion'];
					$models[$i][$key]['monto_reconocimiento'] = $value['monto_reconocimiento'];
					$models[$i][$key]['exigibilidad_pago'] = $value['exigibilidad_pago'];
					$models[$i][$key]['fecha_desde'] = $value['fecha_desde'];
					$models[$i][$key]['fecha_hasta'] = $value['fecha_hasta'];
				}
			}

			return $models;

		}



		/***/
		public function actionConfirmarSave()
		{
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];

				$request = Yii::$app->request;
				$postData = $request->post();


				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						return $this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						return $this->redirect(['listar-vehiculo']);
					}

				} elseif ( isset($postData['btn-confirm-save']) ) {
					if ( $postData['btn-confirm-save'] == 7 ) {

						$model = New PagoDetalle();
						$formName = $model->formName();

						// Liquidacion agrupada por objetos.
						$datos = $postData[$formName];

						$models = self::actionCreateModelPago($datos);

						$result = false;
						foreach ( $models as $i => $model ) {

							$result = self::actionBeginSave($model, $idContribuyente);
							if ( $result ) {
								$this->_transaccion->commit();
								$this->_conn->close();

								$this->_objeto[$i] = [
											'mensaje' => 'Guardo',
											'model' => $model,
								];
							} else {
								$this->_transaccion->commit();
								$this->_conn->close();
								$this->_objeto[$i] = [
											'mensaje' => 'No Guardo',
											'model' => $model,
								];
							}

						}
						self::actionAnularSession(['begin']);
						return self::actionMostrarLiquidacionGuardada($this->_objeto);

					}

				}

			} else {
				$this->redirect(['quit']);
			}
		}




		/**
		 * Metodo que inicia el proceos para guardar la liquidacion
		 * @param PagoDetall $models arreglo de modelo de la clase PagoDetalle().
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return boolean retorna true o false.
		 */
		private function actionBeginSave($models, $idContribuyente)
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

			// Se vuelve a verificar si la planilla no esta asociada a un recibo pendiente
			// o pagada.
			if ( $models[0]['id_pago'] > 0 ) {
				$modelPago = Pago::findOne($models[0]['id_pago']);

				$puedo = false;
				$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
				$puedo = $searchLiquidacion->puedoSeleccionarPlanilla($modelPago->planilla);
				if ( !$puedo ) {
					foreach ( $models as $model ) {
						$model['id_pago'] = 0;
					}
				}
			}


			if ( $models[0]['id_pago'] > 0 ) {

				$findModel = $searchLiquidacion->infoPlanilla((int)$models[0]['id_pago'])->asArray()->one();

				// Se verifica que la planilla donde se guardaran los detalle este disponible.
				// Sino es asi se genrara otra planilla.
				if ( $findModel['pago'] == 0 ) {

					$result = self::actionGuardarDetalle($models, $this->_conexion, $this->_conn);

				} else {

					// Se genera otra planilla para los detalles de la liquiadcion.
					$idPago = self::actionGuardarPago($this->_conexion, $this->_conn, $idContribuyente);
					if ( $idPago > 0 ) {
						foreach ( $models as $model ) {
							$model['id_pago'] = $idPago;
						}

						$result = self::actionGuardarDetalle($models, $this->_conexion, $this->_conn);
					}
				}

			} elseif ( $models[0]['id_pago'] == 0 ) {

				$idPago = self::actionGuardarPago($this->_conexion, $this->_conn, $idContribuyente);
				if ( $idPago > 0 ) {
					foreach ( $models as $model ) {
						$model['id_pago'] = $idPago;
					}

					$result = self::actionGuardarDetalle($models, $this->_conexion, $this->_conn);
				}
			}

			return $result;

		}





		/**
		 * Metodo que guarda los detalle de la liquidacion
		 * @param PagoDetalle $models arreglo de modelo de la clase PagoDetella().
		 * @param ConexionController $conexion [description]
		 * @param  [type] $conn     [description]
		 * @return boolean retorna true o false.
		 */
		private function actionGuardarDetalle($models, $conexion, $conn)
		{
			$result = false;
			if ( count($models) > 0 ) {
				$tabla = $models[0]->tableName();

				foreach ( $models as $model ) {
					if ( $model['id_pago'] > 0 ) {
						$result = $conexion->guardarRegistro($conn, $tabla, $model->attributes);
						if ( !$result ) { break; }
					} else {
						break;
					}
				}
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



		/**
		 * Metodo que busca los detalle de la liquidaciones guardadas y renderiza una vista
		 * con la informacion de las liquidaciones de cada vehiculo seleccionado. Para aquellos
		 * registros que no se pudieron guardar se mostraran solo los registros generados en este
		 * proceso.
		 * $objeto, contiene la informacion de los registros guardados y no guardado, este
		 * variable es un arreglo donde el indice ($key), es el identificador del objeto (vehiculo)
		 * y el contenido de dicho elemento es el modelo guardado con los registros seleccionados.
		 * Estructura de $objeto es:
		 * array => {
		 * 		[identificador del objeto] => [
		 *  		['modelo'] => modelo guardado
		 *    		['mensaje'] => 'Guardado' o 'No Guardado'
		 *      ]
		 * }
		 * @return View retorna una vista con la informacion resumen de la liquidaciones.
		 */
		public function actionMostrarLiquidacionGuardada($objetos)
		{
			if ( isset($_SESSION['idContribuyente']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];

				if ( count($objetos) > 0 ) {
					$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
					foreach ( $objetos as $key => $models ) {

						$label = 'label label-default';
						$resultado = (string)$models['mensaje'];
						if ( strtoupper($resultado) == 'GUARDO' ) {
							$label = 'label label-success';
						} elseif ( strtoupper($resultado) == 'NO GUARDO') {
							$label = 'label label-danger';
						}
						// Se genera el proveedor de datos. ArrayDataProvider
						$provider = $searchLiquidacion->getDataProviderDetalleLiquidacion($models['model']);

						$modelVehiculo = $searchLiquidacion->getListaVehiculo([$key]);
						$infoVehiculo = ' Placa: ' . $modelVehiculo[0]['placa'] . ' - Marca: ' . $modelVehiculo[0]['marca'] .
							            ' - Modelo: ' . $modelVehiculo[0]['modelo'] . ' - Color: ' . $modelVehiculo[0]['color'];
						$subCaption = Yii::t('frontend', $infoVehiculo);
						$gridHtml[$key] = $this->renderPartial('@frontend/views/vehiculo/liquidar/resumen-individual-liquidacion',[
																							'dataProvider' => $provider,
																							'subCaption' => $subCaption,
																							'guardo' => $resultado,
																							'label' => $label,
																		]);

					}

					$url = Url::to(['consultor-planilla']);
					$caption = Yii::t('frontend', 'Resumen de la operacion');
					$subCaption = Yii::t('frontend', 'Registros');
					return $this->render('@frontend/views/vehiculo/liquidar/resumen-general',[
													'codigo' => 100,
													'caption' => $caption,
													'subCaption' => $subCaption,
													'gridHtml' => $gridHtml,
													'url' => $url,
						]);
				}
			}
		}




		/***/
		public function actionConsultorPlanilla()
		{
// die(var_dump(Yii::$app->controller->id));
			$consultor = New PlanillaConsultaController();
			$consultor->actionIndex();
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




		/***/
		public function actionGenerarPdf()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($postData['planilla']) ) {
				$planilla = $postData['planilla'];
				$pdf = New PlanillaPdfController($planilla);
				$pdf->actionGenerarPlanillaPdf();

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
							'postData',
							'conf',
							'begin',
					];

		}





	}
?>