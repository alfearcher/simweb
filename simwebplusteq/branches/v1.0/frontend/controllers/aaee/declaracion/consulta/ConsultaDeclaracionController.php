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
 *	@file ConsultaDeclaracionController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-10-2016
 *
 *  @class ConsultaDeclaracionController
 *	@brief Clase ConsultaDeclaracionController del lado del contribuyente frontend.
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


 	namespace frontend\controllers\aaee\declaracion\consulta;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use yii\base\Model;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBaseForm;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBaseSearch;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	use common\controllers\pdf\boletin\BoletinController;
	use backend\models\aaee\historico\declaracion\HistoricoDeclaracionSearch;
	use common\controllers\pdf\declaracion\DeclaracionController;



	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de Declaraciones
	 * Solicitud que se realizara del lado del contribuyente (frontend). Se mostrara una vista
	 * previa de la solicitud realizada por el contribuyente y se le indicara al contribuyente
	 * que confirme la operacion o retorne a la vista inicial donde cargo la informacion para su
	 * ajuste. Cuando el contribuyente confirme su intencion de crear la solicitud, es cuando
	 * se guardara en base de datos.
	 */
	class ConsultaDeclaracionController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		const SCENARIO_SEARCH = 'search';
		const SCENARIO_SEARCH_TIPO = 'search_tipo';



		/***/
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['lapso', 'tipo']);
			if ( isset($_SESSION['idContribuyente']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();
				$errorMensaje = '';
				$errorListaTipoDeclaracion = '';

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$model = New SustitutivaBaseForm();

				$formName = $model->formName();
				$model->scenario = self::SCENARIO_SEARCH_TIPO;

				$caption = Yii::t('frontend', 'Consulta. Seleccionar Tipo Declaracion.');
				$subCaption = Yii::t('frontend', 'Seleccione el tipo de declaracion');

		      	// Datos generales del contribuyente.
		      	$searchSustitutiva = New SustitutivaBaseSearch($idContribuyente);
		      	$findModel = $searchSustitutiva->findContribuyente();

				if ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {
						$model->scenario = self::SCENARIO_SEARCH_TIPO;
						$model->load($postData);

						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {
			    				$_SESSION['tipo'] = $model->tipo_declaracion;
								$this->redirect(['index-consulta']);

							}
						}
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		  		if ( isset($findModel) ) {
					// Se busca la lista de los tipos declaraciones. Solo estimada y definitiva.
					$listaTipoDeclaracion = $searchSustitutiva->getListaTipoDeclaracion([1,2]);
					if ( count($listaTipoDeclaracion) == 0 ) {
						$errorListaTipoDeclaracion = Yii::t('frontend', 'No se encontraron los TIPOS DE DECLARACION');
						$errorMensaje = ( trim($errorMensaje) !== '' ) ? $errorMensaje = $errorMensaje . '. ' . $errorListaTipoDeclaracion : $errorListaTipoDeclaracion;
					}
					$rutaLista = "/aaee/declaracion/consulta/consulta-declaracion/lista-periodo";
					return $this->render('/aaee/declaracion/consulta/_lista-tipo',
																		[
																			'model' => $model,
																			'caption' => $caption,
																			'subCaption' => $subCaption,
																			'findModel' => $findModel,
																			'listaTipoDeclaracion' => $listaTipoDeclaracion,
																			'errorMensaje' => $errorMensaje,
																		]);

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			} else {
				// No esta definido el contribuyente.
		  		$this->redirect(['error-operacion', 'cod' => 932]);
			}
		}




		/***/
		public function actionIndexConsulta()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['lapso']);
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['tipo']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$tipoDeclaracion = $_SESSION['tipo'];

				$request = Yii::$app->request;
				$postData = $request->post();
				$errorMensaje = '';
				$errorListaAño = '';
				$msjControls = [];

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$model = New SustitutivaBaseForm();

				$formName = $model->formName();
				$model->scenario = self::SCENARIO_SEARCH;

		      	// Datos generales del contribuyente.
		      	$searchSustitutiva = New SustitutivaBaseSearch($idContribuyente);
		      	$findModel = $searchSustitutiva->findContribuyente();

		      	$tipoDeclaracionDescripcion = $searchSustitutiva->getListaTipoDeclaracion([$tipoDeclaracion]);
		      	$descripcion = 'Declaracion ' . $tipoDeclaracionDescripcion[$tipoDeclaracion];

		      	$caption = Yii::t('frontend', 'Consulta Declaracion') . '. ' . Yii::t('backend', $descripcion);
				$subCaption = Yii::t('frontend', 'Seleccione el periodo fiscal') . '. ' . Yii::t('backend', $descripcion);

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$model->scenario = self::SCENARIO_SEARCH;
						$postData = [];			// Inicializa el post.
						$model->load($postData);
						$this->redirect(['index']);
					} elseif ( $postData['btn-back-form'] == 1 ) {
						$postData = [];			// Inicializa el post.
						$model->load($postData);
						$this->redirect(['index']);
					}

				} elseif ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {
						$model->scenario = self::SCENARIO_SEARCH;
						$model->load($postData);
						$model->tipo_declaracion = $tipoDeclaracion;

						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {

			    				$msjControls = [];

		    					if ( count($msjControls) == 0 ) {
				   					$_SESSION['lapso'] = [
				   								'a' => $model->ano_impositivo,
				   								'p' => $model->exigibilidad_periodo,
				   								'tipo' => $tipoDeclaracion,
				   								'descripcion' => $descripcion,
				   					];
				   					$this->redirect(['show-declaracion']);
				   				} else {
				   					$errorMensaje = json_encode($msjControls);
				   				}
							}
						}
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		  		if ( isset($findModel) ) {
					// Se busca la lista de años que se mostraran en al combo de años.
					// Solo se considerara los año anteriores al actual para la declaracion definitiva.
					$listaAño = $searchSustitutiva->getListaAnoRegistrado($tipoDeclaracion, true);
					if ( count($listaAño) == 0 ) {
						$errorListaAño = Yii::t('frontend', 'No se encontraron RUBROS AUTORIZADOS cargados ');
						$errorMensaje = ( trim($errorMensaje) !== '' ) ? $errorMensaje = $errorMensaje . '. ' . $errorListaAño : $errorListaAño;
					}
					$url = '';//Url::to(['index-create']);
					$rutaLista = "/aaee/declaracion/consulta/consulta-declaracion/lista-periodo";
					return $this->render('/aaee/declaracion/consulta/_create',
																[
																	'model' => $model,
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'findModel' => $findModel,
																	'listaAño' => $listaAño,
																	'url' => $url,
																	'rutaLista' => $rutaLista,
																	'searchSustitutiva' => $searchSustitutiva,
																	'errorMensaje' => $errorMensaje,
																]);

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
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





		/***/
		public function actionShowDeclaracion()
		{
			if ( isset($_SESSION['lapso']) && isset($_SESSION['idContribuyente']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$lapso = $_SESSION['lapso'];

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}

				} elseif ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 1 ) {
						$this->redirect(['index-consulta']);
					}

				} elseif ( isset($postData['btn-boletin']) ) {
					if ( $postData['btn-boletin'] == 1 ) {

						$this->redirect(['generar-boletin-estimada']);

					} elseif ( $postData['btn-boletin'] == 2 ) {

					}
				}

				$declaracionSearch = New DeclaracionBaseSearch($idContribuyente);

				$dataProvider = $declaracionSearch->getDataProviderRubrosRegistrados($lapso['a'], $lapso['p']);

				$dataProviderHistorico = $declaracionSearch->getDataProviderHistoricoDeclaracionSegunLapso($lapso['a'], $lapso['p']);

				$opciones = [
					'quit' => '/aaee/declaracion/consulta/consulta-declaracion/quit',
				];
				if ( $lapso['tipo'] == 1 ) {
					$urlBoletin = 'generar-boletin-estimada';
				} elseif ( $lapso['tipo'] == 2 ) {
					$urlBoletin = 'generar-boletin-definitiva';
				}

				$caption = $lapso['descripcion'] . ' ' . $lapso['a'] . ' - ' . $lapso['p'];
				return $this->render('/aaee/declaracion/consulta/_declaracion', [
															'lapso' => $lapso,
															'dataProvider' => $dataProvider,
															'caption' => $caption,
															'opciones' => $opciones,
															'dataProviderHistorico' => $dataProviderHistorico,
															'urlBoletin' => $urlBoletin,
					]);
			}
		}





		/**
		 * Metodo que permite renderizar el certificado de declaracion, el renderizado
		 * es un pdf.
		 * @return renderiza un pdf.
		 */
		public function actionGenerarCertificado()
		{

			$idContribuyente = $_SESSION['idContribuyente'];
			$lapso = $_SESSION['lapso'];
			$a = $lapso['a'];
			$p = $lapso['p'];
			$request = Yii::$app->request;

			$idEnviado = 0;
			if ( $request->isGet ) {
				if ( $request->get('id') !== null ) {
					$idEnviado = $request->get('id');			// el identificador del historico.
					$idContEnviado = $request->get('idC');		// el identificador del contribuyente.
				}

				if ( $idEnviado > 0 && $idContEnviado == $idContribuyente ) {

					// Controlador para emitir el comprobante de declaracion.
					$declaracion = New DeclaracionController($idContribuyente, $a, $p);
					$declaracion->actionGenerarCertificadoDeclaracionSegunHistorico($idEnviado);

				} else {
					throw new NotFoundHttpException(Yii::t('frontend', 'Numero de control no valido'));
				}

			} else {
				throw new NotFoundHttpException(Yii::t('frontend', 'Solicitud no valida'));
			}

		}



	    /**
		 * Metodo para responser a la solicitud de generacion de la declaracion estimada.
		 * Lo que aparecera sera un pdf con el resumen de la declaracion.
		 * @return view
		 */
		public function actionGenerarComprobante()
		{

			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;
			$lapso = $_SESSION['lapso'];
			$a = $lapso['a'];
			$p = $lapso['p'];
			$request = Yii::$app->request;

			$idEnviado = 0;
			if ( $request->isGet ) {
				if ( $request->get('id') !== null ) {
					$idEnviado = $request->get('id');			// el identificador del historico.
					$idContEnviado = $request->get('idC');		// el identificador del contribuyente.
				}

				if ( $idEnviado > 0 && $idContEnviado == $idContribuyente ) {

					// Controlador para emitir el comprobante de declaracion.
					$declaracion = New DeclaracionController($idContribuyente, $a, $p);
					$declaracion->actionGenerarComprobanteSegunHistorico($idEnviado);

				} else {
					throw new NotFoundHttpException(Yii::t('frontend', 'Numero de control no valido'));
				}

			} else {
				throw new NotFoundHttpException(Yii::t('frontend', 'Solicitud no valida'));
			}

		}



		/***/
		public function actionGenerarBoletinEstimada()
		{

			$id = $_SESSION['idContribuyente'];
			$lapso = $_SESSION['lapso'];
			$a = $lapso['a'];
			$p = $lapso['p'];

			$boletin = New BoletinController($id, $a, $p);
			return $boletin->generarBoletinEstimada();
		}




		/***/
		public function actionGenerarBoletinDefinitiva()
		{

			$id = $_SESSION['idContribuyente'];
			$lapso = $_SESSION['lapso'];
			$a = $lapso['a'];
			$p = $lapso['p'];

			$boletin = New BoletinController($id, $a, $p);
			return $boletin->generarBoletinDefinitiva();
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
							'lapso'
					];

		}

	}
?>