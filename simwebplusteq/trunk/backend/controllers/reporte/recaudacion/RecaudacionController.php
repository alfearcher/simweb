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
 *	@file RecaudacionController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-06-2017
 *
 *  @class RecaudacionController
 *	@brief Clase que gestiona la consulta de la recaudacion de ingresos
 *  por codigos presupuestario
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


 	namespace backend\controllers\reporte\recaudacion;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\reporte\recaudacion\detallada\RecaudacionDetalladaSearch;
	use backend\models\reporte\recaudacion\general\RecaudacionGeneralSearch;
	use backend\models\reporte\recaudacion\RecaudacionBusquedaForm;
	use backend\models\usuario\AutorizacionUsuario;


	session_start();


	/**
	 * Clase que permite gestionar la consulta de la recaudacion por codigos presupuestario,
	 * se ingresa el rengo de fecha de la consulta y se selcciona el tipo de recaudacion.
	 * Esto genera los registros segun lo solicitado para insertarlos en una entidad temporal
	 * para uego disponer de esta informacion ( ya sea por pantalla o reporte impreso).
	 */
	class RecaudacionController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario



		/**
		 * Metodo inicio de la clase.
		 * @return none
		 */
		public function actionIndex()
		{
			$autorizacion = New AutorizacionUsuario();
			if ( $autorizacion->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']) ) {
				$varSessions = self::actionGetListaSessions();
				self::actionAnularSession($varSessions);
				$_SESSION['begin'] = 1;
				$this->redirect(['mostrar-form-consulta-recaudacion']);
			} else {
				// Su perfil no esta autorizado.
				// El usuario no esta autorizado.
            	$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/**
		 * Metodo que permite mostrar un formulario de consulta.
		 * @return none
		 */
		public function actionMostrarFormConsultaRecaudacion()
		{
			$usuario = Yii::$app->identidad->getUsuario();
			$model = New RecaudacionBusquedaForm();
			$formName = $model->formName();

			if ( isset($_SESSION['begin']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( $request->post('btn-quit') !== null ) {
					if ( $request->post('btn-quit') == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( $request->post('btn-back') !== null ) {
					if ( $request->post('btn-back') == 1 ) {
						$this->redirect(['index']);
					}
				}

				$caption = Yii::t('backend', 'Consulta de Recaudación de Ingresos');
				$subCaption = Yii::t('backend', 'Parametros de consulta');

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}


		      	if ( $model->load($postData) ) {
		      		if ( $model->validate() ) {

		      			if ( strtoupper(trim($model->tipo_recaudacion)) == 'DETALLADA' ) {

			      			$recaudacionSearch = New RecaudacionDetalladaSearch($model->fecha_desde, $model->fecha_hasta, $usuario);
			      			// Lo siguiente realiza la consulta y carga la entidad temporal con los registros
			      			// resultantes. Utilizando los parametros fechas y el usuario.
			      			$recaudacionSearch->iniciarReporteDetalle();

			      			// Grupo de codigos presupuestarios existente en la data recolectada.
			      			$arregloCodigo = $recaudacionSearch->findDetalleAgrupadoByCodigo();

			      			// Deuda actual y morosa.
			      			$lapsos = [1, 2, 3];

			      			foreach ( $lapsos as $key => $value ) {
			      				$htmlRecaudacion[$value] = self::actionViewDetalleRecaudacionByLapso($arregloCodigo, $value, $recaudacionSearch);

			      				// Toda la data recolectada por lapso. Data por deuda morosa y deuda actual.
			      				$recaudacion[$value] = $recaudacionSearch->findSumatoriaDetalleByPlanillaCodigoLapso(0, $value);
			      			}

			      			// Unir las consultas en una sola.
			      			$results = array_merge($recaudacion[1], $recaudacion[2]);

			      			// Retorna un arreglo con la totalizacion por atributos.
			      			$totalRecaudado = $recaudacionSearch->totalizarResultado($results);
			      			//$htmlTotalRecaudado = self::actionViewTotalRecaudado($totalRecaudado);

			      			//$data = $recaudacionSearch->armarData($totalRecaudado);
			      			//$dataProvider = $recaudacionSearch->getDataProvider($data);

			      			$totalChequeRecuperado = $recaudacionSearch->totalizarChequeRecuperado($results);
			      			$totalNotaDebito = $recaudacionSearch->totalizarNotaDebito($recaudacion[3]);

			      			$htmlTotalRecaudado = $this->renderPartial('/reporte/recaudacion/detallada/total-resumen-general', [
			      																'totalRecaudado' => $totalRecaudado,
			      																'results' => $results,
			      																'totalChequeRecuperado' => $totalChequeRecuperado,
			      																'totalNotaDebito' => $totalNotaDebito,
			      									]);

			      			$subCaption = Yii::t('backend', 'Reporte de Recaudación de Ingresos Municipales');
			      			$subCaptionConsulta = Yii::t('backend', 'Rango de Consulta ') . $model->fecha_desde . ' - ' . $model->fecha_hasta;

			      			$this->layout = 'layoutbase';
			      			return $this->render('/reporte/recaudacion/detallada/reporte-recaudacion-detallada-master', [
			      																'model' => $model,
						      													'caption' => $caption,
						      													'subCaption' => $subCaption,
						      													'subCaptionConsulta' => $subCaptionConsulta,
						      													'htmlRecaudacion' => $htmlRecaudacion,
						      													'lapsos' => $lapsos,
						      													'htmlTotalRecaudado' => $htmlTotalRecaudado,
			      					]);

			      		} elseif ( strtoupper(trim($model->tipo_recaudacion)) == 'GENERAL' ) {

			      			$results = [];
			      			$totalGeneral = 0;
			      			$totalDeposito = 0;
			      			$totalNotaDebito = 0;
			      			$htmlSubTotalNivel = [];

			      			$recaudacionSearch = New RecaudacionGeneralSearch($model->fecha_desde, $model->fecha_hasta, $usuario);

			      			// Se determina si no hubo error
			      			$errores = $recaudacionSearch->getError();
			      			if ( count($errores) == 0 ) {
			      				// Totalizacion de las notas de debitos
			      				$totalNotaDebito = $recaudacionSearch->totalizarMontoNegativo();

				      			// Se obtiene una lista de los niveles presupuestario.
				      			$niveles = $recaudacionSearch->getListaNivelContable();
				      			if ( count($niveles) > 0 ) {
				      				foreach ( $niveles as $key => $nivel ) {
				      					$data = $recaudacionSearch->filtarDataSegunNivel($nivel->nivel_contable);
				      					if ( count($data) > 0 ) {
				      						$totalDeposito += (float)array_sum(array_column($data, 'monto'));
				      						$dataProvider = $recaudacionSearch->getDataProvider($data);
				      						$htmlSubTotalNivel[] = $this->renderPartial('/reporte/recaudacion/general/reporte-recaudacion-general-por-nivel-presupuestario',[
																												'model' => $data,
																												'dataProvider' => $dataProvider,
																		]);
				      					}
				      				}
				      				// Monto recuperado por cheques devueltos.
					      			$cheque = $recaudacionSearch->getDeterminarMontoPorChequeRecuperado();
				      				$totalGeneral = $totalDeposito + $totalNotaDebito;
				      				return $this->render('/reporte/recaudacion/general/reporte-recaudacion-general-maestro',[
																					'model' => $model,
																					'totalGeneral' => $totalGeneral,
																					'totalDeposito' => $totalDeposito,
																					'totalNotaDebito' => $totalNotaDebito,
																					'htmlSubTotalNivel' => $htmlSubTotalNivel,
																					'cheque' => $cheque,
											]);
				      			}
				      		} else {
				      			// Mostrar mensaje de error.

				      		}
			      		}
		      		}
		      	}

		      	// Lista del tipo de recaudacion
		      	$listaRecaudacion = $model->getListaTipoRecaudacion();

		      	return $this->render('/reporte/recaudacion/recaudacion-consulta-form', [
		      										'model' => $model,
		      										'caption' => $caption,
		      										'subCaption' => $subCaption,
		      										'listaRecaudacion' => $listaRecaudacion,
		      		]);



			} else {
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}







		/**
		 * Metodo que genera la vistas del detalle de la recaudacion por codigo presupuetario-lapso
		 * @param array $arregloCodigo arraeglo de codigo presupuestario
		 * @param integer $lapso indicativo del tipo de deuda:
		 * - 1 => deuda actual
		 * - 2 => deuda morosa
		 * - 3 => monto negativo.
		 * @param RecaudacionGeneralSearch $recaudacionSearch instancia de la clase.
		 * @return view con las columnas y detalles de los montos.
		 */
		public function actionViewDetalleRecaudacionByLapso($arregloCodigo, $lapso, $recaudacionSearch)
		{
			$htmlRecaudacion = [];
			$totalLapso = [
				'monto' => 0,
				'recargo' => 0,
				'interes' => 0,
				'descuento' => 0,
				'monto_reconocimiento' => 0
			];

			foreach ( $arregloCodigo as $codigo ) {
				$results = [];

				// Resultado de la consulta sobre la data recolectada.
				// Suma agrupado por planilla segun un codigo presupuestario y lapso (deuda actual o morosa).
				// Lista de planilla.
				$results = $recaudacionSearch->findSumatoriaDetalleByPlanillaCodigoLapso($codigo['codigo'], $lapso);

				// Totalizacion, en este caso totaliza por codigo presupuestario.
				$totalizar = $recaudacionSearch->totalizarResultado($results);

				$totalLapso['monto'] += $totalizar['monto'];
				$totalLapso['recargo'] += $totalizar['recargo'];
				$totalLapso['interes'] += $totalizar['interes'];
				$totalLapso['descuento'] += $totalizar['descuento'];
				$totalLapso['monto_reconocimiento'] += $totalizar['monto_reconocimiento'];

				// Impuesto - ( Descuento + Recon/Ret)
				$totalCodigo = $totalizar['monto'] - ( $totalizar['descuento'] + $totalizar['monto_reconocimiento'] );

				if ( count($results) > 0 ) {
					$dataProvider = $recaudacionSearch->getDataProvider($results);
					if ( $lapso == 3 ) {
						$htmlRecaudacion[] = $this->renderPartial('/reporte/recaudacion/detallada/reporte-recaudacion-detallada-nota-debito', [
							      										'dataProvider' => $dataProvider,
							      										'model' => $results,
							      										'lapso' => $lapso,
							      										'totalizar' => $totalizar,
							      										'totalCodigo' => $totalCodigo,
						]);

					} else {
						$htmlRecaudacion[] = $this->renderPartial('/reporte/recaudacion/detallada/reporte-recaudacion-detallada-item', [
							      										'dataProvider' => $dataProvider,
							      										'model' => $results,
							      										'lapso' => $lapso,
							      										'totalizar' => $totalizar,
							      										'totalCodigo' => $totalCodigo,
						]);
					}
				}
			}
			$htmlRecaudacion[] = self::actionViewTotalByLapso($totalLapso, $lapso);

			return $htmlRecaudacion;
		}



		/**
		 * Metodo que retorna la vista con la totalizacion por lapso (Año Actual, Año Anteriores)
		 * @param array $totalLapso arreglo con los totales por atributos
		 * @param integer $lapso entero que indica el laspo
		 * 1 => Año Actual
		 * 2 => Años Anteriores
		 * @return view
		 */
		public function actionViewTotalByLapso($totalLapso, $lapso)
		{
			return $htmlTotalLapso = $this->renderPartial('/reporte/recaudacion/detallada/total-recaudacion-detallada-por-lapso', [
																		'lapso' => $lapso,
																		'totalLapso' => $totalLapso,
							]);
		}



		/***/
		public function actionViewTotalRecaudado($totalRecaudado)
		{
			return $htmlTotalRecaudado = $this->renderPartial('/reporte/recaudacion/detallada/total-resumen-general', [
																		'totalRecaudado' => $totalRecaudado,
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