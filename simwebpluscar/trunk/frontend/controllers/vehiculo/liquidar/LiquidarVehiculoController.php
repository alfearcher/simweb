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


 	namespace frontend\controllers\vehiculo\liquidar;


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


	session_start();


	/***/
	class LiquidarVehiculoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;




		public function actionIndex()
		{
			$_SESSION['begin'] = 1;
			$this->redirect(['listar-vehiculo']);
		}




		/***/
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
				// if ( isset($postData['btn-begin']) ) {
					// if ( $postData['btn-begin'] == 3 ) {
					// 	if ( isset($postData['chkIdImpuesto']) ) {
					// 		$chkSeleccion = $postData['chkIdImpuesto'];

					// 		// Se crea una lista de vehiculos activos.
					// 		$model = New LiquidarVehiculoForm($idContribuyente);
					// 		$provider = $model->getDataProviderVehiculo($chkSeleccion);

					// 		$url = Url::to(['index-create']);
					// 		$caption = Yii::t('frontend', 'Liquidar Vehiculo(s)');
					// 		$subCaption = Yii::t('frontend', $caption . '. Vehiculo(s) seleccinado(s)');
					// 		return $this->render('/vehiculo/liquidar/lista-vehiculo-liquidacion',[
					// 										'caption' => $caption,
					// 										'subCaption' => $subCaption,
					// 										'dataProvider' => $provider,
					// 										'url' => $url,
					// 					]);

					// 	} else {
					// 		$controlSeleccion = Yii::t('frontend', 'No ha seleccionado ningun vehiculo');
					// 	}
					// }
				// }

				// Se crea una lista de vehiculos activos.
				$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
				$provider = $searchLiquidacion->getDataProviderVehiculo();

				if ( $provider !== null ) {
					$caption = Yii::t('frontend', 'Lista de Vehiculos');
					$subCaption = Yii::t('frontend', $caption .'. Seleccione el o los vehiculo(s) para liquidar el impuesto');
					return $this->render('/vehiculo/liquidar/_list',[
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

						// $model = New LiquidarVehiculoForm($idContribuyente);
						// $formName = $model->formName();

						// $datos = $postData[$formName];
						// if ( count($datos) > 0 ) {
						// 	foreach ( $datos as $key => $value ) {
						// 		$modelMultiplex[$key] = New LiquidarVehiculoForm($idContribuyente);
						// 	}
						// 	Model::loadMultiple($modelMultiplex, $postData);
						// 	$result = Model::validateMultiple($modelMultiplex);
						// }

						// if ( Model::loadMultiple($modelMultiplex, $postData)  && Yii::$app->request->isAjax ) {
						// 	Yii::$app->response->format = Response::FORMAT_JSON;
						// 	return ActiveForm::validateMultiple($modelMultiplex);
				  //     	}

// die(var_dump($modelMultiplex));
// 				      	if ( $result ) {
// die('valido');
// 				      	} else {
// 				      		$chkSeleccion = ArrayHelper::map($datos, 'id_impuesto', 'id_impuesto');

// die(var_dump($chkSeleccion));
// 				      		$searchLiquidacion = New LiquidarVehiculoSearch($idContribuyente);
// 							$vehiculos = $searchLiquidacion->getListaVehiculo($chkSeleccion);
// 							foreach ( $chkSeleccion as $key => $value ) {
// 								$lapso[$key] = $searchLiquidacion->getListaLapsoPendiente($value);
// 							}

// 				      		$opciones = [
// 								'back' => '/vehiculo/liquidar/liquidar-vehiculo/index',
// 							];
// 							$caption = Yii::t('frontend', 'Liquidacion de Vehiculo(s)');
// 							$subCaption = Yii::t('frontend', $caption .'. Indique los parametros para liquidar el impuesto');
// 							return $this->render('/vehiculo/liquidar/lista-vehiculo-liquidacion',[
// 															'caption' => $caption,
// 															'subCaption' => $subCaption,
// 															'models' => $modelMultiplex,
// 															'opciones' => $opciones,
// 															'lapso' => $lapso,
// 										]);
// 				      	}

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
					return $this->render('/vehiculo/liquidar/lista-vehiculo-liquidacion',[
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








		/**
		 * Metodo que inicia el modulo de lqiquidacion.
		 * @return [type] [description]
		 */
		public function actionIndexCreate()
		{

			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];

				$request = Yii::$app->request;
				$postData = $request->post();


// die(var_dump($postData));


				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						return $this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {
						return $this->redirect(['listar-vehiculo']);
					}

				} elseif ( isset($postData['btn-confirm-seleccion']) ) {
					if ( $postData['btn-confirm-seleccion'] == 5 ) {
						// Vista previa.
						// Se liquida los vehiculos seleccionados y se muetras una vista previa de la liquidacion
						// de cada vehiculo.

						$detalles = [];
						$chkSeleccion = $postData['chkIdImpuesto'];
						if ( count($chkSeleccion) > 0 ) {
							$formModel = New LiquidarVehiculoForm($idContribuyente);

							// Lo siguiente sera un arreglo de gridview de los resumenes de las liquidaciones
							// de los diferentes objetos seleccionados.
							$gridHtml = [];
							$grid = [];

							foreach ( $chkSeleccion as $key => $value ) {
								$liquidar[$value] = New Liquidar($idContribuyente, $value);
								$ultimoLapsoLiquidado[$key] = $liquidar[$value]->getUltimoLapsoLiquidado();

								// Lapsos liquidados. Por vehiculo
								$detalles[$value] = $liquidar[$value]->iniciarProcesoLiquidacion();


								// Se genera el proveedro de datos. ArrayDataProvider
								$provider = $formModel->getDataProviderDetalleLiquidacion($detalles[$value]);
								//$grid[$value] = $formModel->generarGridViewResumenLiquidacionIndividual($provider, $value);

								$infoVehiculo = 'Placa: ' . $postData['placa'][$value] . ' - Marca: ' . $postData['marca'][$value] .
								                ' - Modelo: ' . $postData['modelo'][$value] . ' - Color: ' . $postData['color'][$value];
								$subCaption = Yii::t('frontend', $infoVehiculo);
								$gridHtml[$value] = $this->renderPartial('/vehiculo/liquidar/resumen-individual-liquidacion',[
																				'dataProvider' => $provider,
																				'subCaption' => $subCaption,
															]);

								foreach ( $detalles[$value] as $i => $campos ) {

									$models[$value][$i] = New PagoDetalle();
									$models[$value][$i][$campo] = $detalles[$value][$i][$campo];

									// $models[$value][]['id_impuesto'] = $detalles[$value][]['id_impuesto'];
									// $models[$value][]['impuesto'] = $detalles[$value][]['impuesto'];
									// $models[$value][]['ano_impositivo'] = $detalles[$value][]['ano_impositivo'];
									// $models[$value][]['trimestre'] = $detalles[$value][]['trimestre'];
									// $models[$value][]['monto'] = $detalles[$value]['monto'];
									// $models[$value][]['recargo'] = $detalles[$value]['recargo'];
									// $models[$value][]['interes'] = $detalles[$value]['interes'];
									// $models[$value][]['descuento'] = $detalles[$value]['descuento'];
									// $models[$value][]['referencia'] = $detalles[$value]['referencia'];
									// $models[$value][]['pago'] = $detalles[$value]['pago'];
									// $models[$value][]['fecha_emision'] = $detalles[$value]['fecha_emision'];
									// $models[$value][]['fecha_pago'] = $detalles[$value]['fecha_pago'];
									// $models[$value][]['feccha_vcto'] = $detalles[$value]['feccha_vcto'];
									// $models[$value][]['monto_reconocimiento'] = $detalles[$value]['monto_reconocimiento'];
									// $models[$value][]['exigibilidad_pago'] = $detalles[$value]['exigibilidad_pago'];
									// $models[$value][]['fecha_desde'] = $detalles[$value]['fecha_desde'];
									// $models[$value][]['fecha_hasta'] = $detalles[$value]['fecha_hasta'];

								}

							}


die(var_dump($model));

							$caption = Yii::t('frontend', 'Liquidacion de Vehiculo');
							$subCaption = Yii::t('frontend', $caption . '. Resumen por Vehiculo');
							return $this->render('/vehiculo/liquidar/pre-view-liquidacion',[
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'gridHtml' => $gridHtml,
																	'models' => $models,
									]);

						}




						// $dataKey = 0;
						// $dataKey = (int)$postData['data-key'];

						// // data provider de lo seleccionado.
						// foreach ( $detalles as $key => $value ) {
						// 	if ( $key <= $dataKey ) {
						// 		$detalleSeleccion[$key] = $detalles[$key];
						// 	}
						// }

						// if ( count($detalleSeleccion) > 0 ) {

						// 	$provider = New ArrayDataProvider([
						// 					'allModels' => $detalleSeleccion,
						// 					'pagination' => false,
						// 		]);

						// 	$caption = Yii::t('frontend', 'Confirme seleccion de lapsos');
						// 	$subCaption = Yii::t('frontend', 'Confirmar. Detalle de la liquidacion');
						// 	return $this->render('/aaee/liquidar/pre-view-liquidacion',[
						// 											'caption' => $caption,
						// 											'subCaption' => $subCaption,
						// 											'dataProvider' => $provider,
						// 			]);
						// }
					}

				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 3 ) {

						$chkSeleccion = $postData['chkLapso'];
						$liquidar = New Liquidar($idContribuyente);

						// Lapsos liquidados.
						$detalles = $liquidar->iniciarProcesoLiquidacion();

						foreach ( $chkSeleccion as $key => $value ) {
							$models[$key] = New PagoDetalle();
							foreach ( $models[$key]->attributes as $i => $valor ) {
								if ( isset($detalles[$key][$i]) ) {
									$models[$key]->$i = $detalles[$key][$i];
								}
							}
						}

						if ( count($models) > 0 ) {
							$result = self::actionBeginSave($models, $idContribuyente);
							self::actionAnularSession(['begin']);
							if ( $result ) {
								$this->_transaccion->commit();
								$this->_conn->close();
								// Mostrar resultado.
								return self::actionMostrarLiquidacionGuardada($models);

							} else {
								$this->_transaccion->rollBack();
								$this->_conn->close();
								$this->redirect(['error-operacion', 'id' => 920]);
							}
						}

					}
				}


				// Lo siguiente recibe un segun parametro, "tipo liquidacion", este parametro es
				// opcional y por defecto se setea a "ESTIMADA".
				// $liquidar = New Liquidar($idContribuyente);
				// $ultimoLapsoLiquidado = $liquidar->getUltimoLapsoLiquidado();

				// // Lapsos liquidados.
				// $detalles = $liquidar->iniciarProcesoLiquidacion();

				// if ( count($detalles) > 0 ) {
				// 	$provider = $liquidar->getDataProviderDetalle();
				// 	$fechaInicio = $liquidar->getFechaInicioActividad();

				// 	// Vista inicial.
				// 	if ( count($detalles) > 0 ) {
				// 		$caption = Yii::t('frontend', 'Detalle de la Liquidacion');
				// 		$subCaption = Yii::t('frontend', 'Informacion relevante');

				// 		return $this->render('/aaee/liquidar/_view-detalle',[
				// 										'dataProvider' => $provider,
				// 										'caption' => $caption,
				// 										'subCaption' => $subCaption,
				// 										'fechaInicio' => $fechaInicio,
				// 										'ultimoLapsoLiquidado' => $ultimoLapsoLiquidado,

				// 			]);
				// 	}

				// } else {
				// 	// No se encontraron detalles pendientes.
				// 	$r = $liquidar->getErrors();
				// 	return $this->render('/aaee/liquidar/warning',['mensajes' => $r]);
				// }

			} else {
				// No esta definida la session del contribuyente.
				return $this->redirect(['quit']);
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

			if ( $models[0]['id_pago'] > 0 ) {

				$findModel = self::actionInfoPlanilla((int)$models[0]['id_pago'])->asArray()->one();

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



		/***/
		public function actionMostrarLiquidacionGuardada($models)
		{
			$findModel = self::actionInfoPlanilla($models[0]['id_pago']);
			$detalles = $findModel->asArray()->one();

			if ( count($detalles) > 0 ) {

				$planilla = (int)$detalles['pagos']['planilla'];
				$planillaSearch = New PlanillaSearch($planilla);
				$dataProvider = $planillaSearch->getProviderPlanilla(0);
				$url = Url::to(['generar-pdf']);
				$caption = Yii::t('frontend', 'Detalle de la planilla ' . $planilla);
				$subCaption = Yii::t('frontend', 'Lapsos liquiaddos');

				return $this->render('/aaee/liquidar/view-liquidacion-resultante',[
															'caption' => $caption,
															'subCaption' => $subCaption,
															'dataProvider' => $dataProvider,
															'planilla' => $planilla,
															'url' => $url,
						]);
			} else {
				// No se encontraro detalles liquidados.

			}
		}






		/***/
		private function actionInfoPlanilla($idPago)
		{
			return $findModel = PagoDetalle::find()->alias('D')
		                                   		   ->where('D.id_pago =:id_pago',[':id_pago'=>$idPago])
		                                           ->joinWith('pagos P', true, 'INNER JOIN');

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