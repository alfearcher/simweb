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
 *	@file LiquidarActividadEconomicaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 14-11-2016
 *
 *  @class LiquidarActividadEconomicaController
 *	@brief Clase que gestiona el proceso de liquidacion de Actividad Economica con
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


 	namespace frontend\controllers\aaee\liquidar;


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
	use common\models\planilla\PagoDetalle;
	use common\models\planilla\Pago;
	use common\models\planilla\NumeroPlanillaSearch;
	use common\models\planilla\PlanillaSearch;
	use yii\data\ArrayDataProvider;
	use common\models\contribuyente\ContribuyenteBase;
	use common\models\aaee\lapso\SeleccionLapsoForm;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBaseSearch;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	use backend\models\aaee\liquidar\LiquidarDefinitivaSearch;
	use backend\models\aaee\liquidar\LiquidarDefinitivaForm;


	session_start();


	/***/
	class LiquidarActividadEconomicaDefinitivaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;




		public function actionIndex()
		{
			$_SESSION['begin'] = 1;
			$this->redirect(['index-lapso']);
		}




		/**
		 * Metodo que inicia el modulo de lqiquidacion.
		 * @return [type] [description]
		 */
		public function actionIndexLapso()
		{

			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				if ( ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($idContribuyente) == 'JURIDICO' ) {

					$request = Yii::$app->request;
					$postData = $request->post();

					$mensajes = '';
					if ( isset($postData['btn-quit']) ) {
						if ( $postData['btn-quit'] == 1 ) {
							return $this->redirect(['quit']);
						}
					} elseif ( isset($postData['btn-create']) ) {
						if ( $postData['btn-create'] == 3 ) {
							$modelLiq = New LiquidarDefinitivaForm();
							$formName = $modelLiq->formName();
							$modelLiq->load($postData, $formName);

							// Mostrar vista previa
							$sumaPago = (float)$postData['total-pago'];
							$sumaImpuesto = (float)$postData['total-impuesto'];
							$totalDiferencia = $postData['total-diferencia'];
							$m = str_replace('.', '', $totalDiferencia);
							$m = str_replace(',', '.', $m);
							if ( $m > 0 ) {
								$modelLiq['monto'] = $m;

								$caption = Yii::t('frontend', 'Confirme. Liqudacion de la definitiva ' . $modelLiq->ano_impositivo . ' - ' . $modelLiq->trimestre);
				      			$subCaption = Yii::t('frontend', 'Monto a Liquidar');
								return $this->render('@frontend/views/aaee/liquidar/definitiva/pre-view-liquidacion-definitiva',[
																						'model' => $modelLiq,
																						'sumaImpuesto' => $sumaImpuesto,
																						'sumaPago' => $sumaPago,
																						'caption' => $caption,
																						'subCaption' => $subCaption,
																						'idContribuyente' => $idContribuyente,
																						'totalDiferencia' => $totalDiferencia,
									]);

							} else {
								return $this->render('@frontend/views/aaee/liquidar/definitiva/_operacion-no-valida');
							}
						}

					} elseif ( isset($postData['btn-confirm-create']) ) {
						if ( $postData['btn-confirm-create'] == 5 ) {

							$modelLiq = New LiquidarDefinitivaForm();
							$formName = $modelLiq->formName();
							$modelLiq->load($postData, $formName);
							$modelLiq['monto'] = number_format((float)$modelLiq['monto'], 2, '.', '');

							if ( $postData['id_contribuyente'] == $idContribuyente ) {
								self::actionAnularSession(['begin']);
								$result = self::actionBeginSave($modelLiq, $idContribuyente);
								if ( $result ) {
									$this->_transaccion->commit();
									$this->_conn->close();
									return self::actionMostrarLiquidacionGuardada($modelLiq);
								} else {
									$this->_transaccion->rollBack();
									$this->_conn->close();
									$this->redirect(['error-operacion', 'cod'=> 920]);
								}
							}
						}

					}


					$model = New SeleccionLapsoForm();
					$formName = $model->formName();

					if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validate($model);
			      	}

			      	if ( $model->load($postData) ) {
			      		if ( $model->validate() ) {
			      			$liquidarSearch = New LiquidarDefinitivaSearch($model->id_contribuyente,
			      										 				   $model->ano_impositivo,
			      										 				   $model->exigibilidad_periodo);
			      			$mensajes = $liquidarSearch->validarEvento();
			      			if ( count($mensajes) == 0 ) {
			      				// Todo bien

			      				$url = Url::to(['liquidar-definitiva']);
			      				$dataDeclaracion = $liquidarSearch->datosDeclaracionImpuesto();
			      				$provider = $liquidarSearch->getArrayDataProviderDeclaracionImpuesto();
			      				$caption = Yii::t('frontend', 'Resumen del Calculo. Liqudacion de la definitiva ' . $model->ano_impositivo . ' - ' . $model->exigibilidad_periodo);
			      				$subCaption = Yii::t('frontend', 'Resumen de la declaracion');

			      				$sumaDeclarado = $liquidarSearch->sumaDeclarado($dataDeclaracion);
			      				$sumaImpuesto = $liquidarSearch->sumaImpuesto($dataDeclaracion);

			      				$resumenPago = $liquidarSearch->getResumenPagos();
			      				$providerPago = $liquidarSearch->getArrayDataProviderResumenPago();
			      				$sumaPago = $liquidarSearch->sumaPago($resumenPago);


			      				$modelLiq = New LiquidarDefinitivaForm();
			      				$modelLiq->id_pago = 0;
			      				$modelLiq->impuesto = 1;
			      				$modelLiq->id_impuesto = $dataDeclaracion[0]['id_impuesto'];
			      				$modelLiq->ano_impositivo = $dataDeclaracion[0]['ano_impositivo'];
			      				$modelLiq->trimestre = 1;
			      				$modelLiq->monto = 0;
			      				$modelLiq->descuento = 0;
			      				$modelLiq->recargo = 0;
			      				$modelLiq->interes = 0;
			      				$modelLiq->fecha_emision = date('Y-m-d');
			      				$modelLiq->fecha_vcto = $liquidarSearch->getFechaVcto(date('Y-m-d'));
			      				$modelLiq->pago = 0;
			      				$modelLiq->fecha_pago = '0000-00-00';
			      				$modelLiq->referencia = 1;
			      				$modelLiq->descripcion = 'LIQUIDACION DEFINITIVA ' . $model->ano_impositivo . ' - ' . $model->exigibilidad_periodo;   ;
			      				$modelLiq->monto_reconocimiento = 0;
			      				$modelLiq->exigibilidad_pago = 1;
			      				$modelLiq->fecha_desde = '0000-00-00';
			      				$modelLiq->fecha_hasta = '0000-00-00';

			      				return $this->render('@frontend/views/aaee/liquidar/definitiva/resumen-declaracion-pago',[
			      														'dataDeclaracion' => $dataDeclaracion,
			      														'dataProvider' => $provider,
			      														'caption' => $caption,
			      														'subCaption' => $subCaption,
			      														'sumaDeclarado' => $sumaDeclarado,
			      														'sumaImpuesto' => $sumaImpuesto,
			      														'url' => $url,
			      														'resumenPago' => $resumenPago,
			      														'sumaPago' => $sumaPago,
			      														'dataProviderPago' => $providerPago,
			      														'model' => $modelLiq,
			      						]);

			      			}

			      		}
			      	}


			      	$searchSustitutiva = New SustitutivaBaseSearch($idContribuyente);
		      		$findModel = $searchSustitutiva->findContribuyente();

		      		if ( count($findModel) > 0 ) {
			      		$errorMensaje = '';
				      	$listaAño = $searchSustitutiva->getListaAnoRegistrado(2, true);
						if ( count($listaAño) == 0 ) {
							$errorListaAño = Yii::t('frontend', 'No se encontraron RUBROS AUTORIZADOS cargados ');
							$errorMensaje = ( trim($errorMensaje) !== '' ) ? $errorMensaje = $errorMensaje . '. ' . $errorListaAño : $errorListaAño;
						}

						$rutaLista = "/aaee/liquidar/liquidar-actividad-economica-definitiva/lista-periodo";

				      	$url = Url::to(['index-lapso']);
				      	$caption = Yii::t('frontend', 'Liquidacion declaracion definitiva');
				      	$subCaption = Yii::t('frontend', 'Seleccionar lapso');
				      	return $this->render('@frontend/views/aaee/liquidar/definitiva/_create',[
							      											'model' => $model,
							      											'findModel' => $findModel,
							      											'caption' => $caption,
							      											'subCaption' => $subCaption,
							      											'url' => $url,
							      											'rutaLista' => $rutaLista,
							      											'listaAño' => $listaAño,
							      											'btnBack' => 0,
							      											'errorMensaje' => $errorMensaje,
							      											'mensajes' => $mensajes,
				      					]);
				    } else {
				    	// No se encontro al contribuyente

				    }


				} else {
					return $this->redirect(['error-operacion', 'cod' => 934]);
				}

			} else {
				// No esta definida la session del contribuyente.
				return $this->redirect(['quit']);
			}
		}



		/**
		 * Metodo que permite obtener los periodos que existe en un año especifico,
		 * los periodos representan el nivel de exigibilidad (cantidad de periodo)
		 * que se debe cumplir en un año. Cada periodo representa un lapso de tiempo
		 * especifico. Esquema:
		 * 1 - Anual.
		 * 2 - Semestral.
		 * 3 - Cuatrimestre.
		 * 4 - Trimestral.
		 * 6 - Bimensual.
		 * 12 - Mensual.
		 * 99 - No definido.
		 * @return view
		 */
		public function actionListaPeriodo()
		{
			$añoImpositivo = 0;
			$request = Yii::$app->request;

			// Se capta el año impositivo de act-econ.
			$añoImpositivo = $request->get('id');

			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;
			if ( $idContribuyente > 0 ) {
				$searchRamo = New DeclaracionBaseSearch($idContribuyente);

				// Se espera recibir un arreglo con los atributos de la entidad respectiva.
				$exigibilidad = $searchRamo->getExigibilidadSegunAnoImpositivo($añoImpositivo);
				if ( count($exigibilidad) > 0 ) {
					return $searchRamo->getViewListaExigibilidad($exigibilidad);
				}
			}
			return "<option> - </option>";
		}




		/**
		 * Metodo que inicia el proceos para guardar la liquidacion
		 * @param PagoDetall $model arreglo de modelo de la clase LiquidacionDefinitivaForm().
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @return boolean retorna true o false.
		 */
		private function actionBeginSave($model, $idContribuyente)
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

			if ( $model['id_pago'] == 0 ) {

				$idPago = self::actionGuardarPago($this->_conexion, $this->_conn, $idContribuyente);
				if ( $idPago > 0 ) {

					$model['id_pago'] = $idPago;
					$result = self::actionGuardarDetalle($model, $this->_conexion, $this->_conn);
				}
			}

			return $result;

		}




		/**
		 * Metodo que guarda los detalle de la liquidacion
		 * @param PagoDetalle $models arreglo de modelo de la clase LiquidacionDefinitivaForm().
		 * @param ConexionController $conexion [description]
		 * @param  [type] $conn     [description]
		 * @return boolean retorna true o false.
		 */
		private function actionGuardarDetalle($model, $conexion, $conn)
		{
			$result = false;
			if ( count($model) > 0 ) {
				$tabla = $model->tableName();

				if ( $model['id_pago'] > 0 ) {
					$result = $conexion->guardarRegistro($conn, $tabla, $model->attributes);
					if ( !$result ) { break; }
				} else {
					break;
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
		public function actionMostrarLiquidacionGuardada($model)
		{
			$findModel = self::actionInfoPlanilla($model['id_pago']);
			$detalles = $findModel->asArray()->one();

			if ( count($detalles) > 0 ) {

				$planilla = (int)$detalles['pagos']['planilla'];
				$planillaSearch = New PlanillaSearch($planilla);
				$dataProvider = $planillaSearch->getProviderPlanilla(0);
				$url = Url::to(['generar-pdf']);
				$caption = Yii::t('frontend', 'Detalle de la planilla ' . $planilla);
				$subCaption = Yii::t('frontend', 'Lapsos liquiaddos');

				return $this->render('@frontend/views/aaee/liquidar/view-liquidacion-resultante',[
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
							'begin',
					];

		}





	}
?>