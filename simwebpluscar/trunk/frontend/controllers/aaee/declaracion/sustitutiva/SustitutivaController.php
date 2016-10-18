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
 *	@file SustitutivaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 17-10-2016
 *
 *  @class SustitutivaController
 *	@brief Clase SustitutivaController del lado del contribuyente frontend.
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


 	namespace frontend\controllers\aaee\declaracion\sustitutiva;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\documento\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\enviaremail\PlantillaEmail;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	// use backend\models\aaee\anexoramo\AnexoRamoSearch;
	// use backend\models\aaee\anexoramo\AnexoRamoForm;
	use backend\models\aaee\rubro\Rubro;
	// use backend\models\aaee\actecon\ActEconForm;
	// use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use yii\base\Model;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBaseForm;
	use backend\models\aaee\declaracion\sustitutiva\SustitutivaBaseSearch;

	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de Declaraciones
	 * Solicitud. Se le mostrara los años que puede declarar la definitiva en
	 * orden cronologico. Se controlara la existencia de solicitudes similares
	 * pendientes y aprobadas.
	 */
	class SustitutivaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		const SCENARIO_ESTIMADA = 'estimada';
		const SCENARIO_DEFINITIVA = 'definitiva';
		const SCENARIO_SEARCH = 'search';
		const SCENARIO_SEARCH_TIPO = 'search_tipo';

		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 111;	// Sustitutiva


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'anoOrdenanza', 'configOrdenanza']);
			$request = Yii::$app->request;
			$getData = $request->get();

			$postData = $request->post();
			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					return $this->redirect(['check', 'id' => $id]);

				} else {
					// No esta definido el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 404]);
				}
			}
		}





		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionCheck($id)
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'conf']);

			// identificador de la configuracion de la solicitud.
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {

					$modelParametro = New ParametroSolicitud($id);
					// Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
					// del parametro y el valor del elemento es el contenido del campo en base de datos.
					$config = $modelParametro->getParametroSolicitud([
															'id_config_solicitud',
															'tipo_solicitud',
															'impuesto',
															'nivel_aprobacion'
												]);

					if ( isset($config) ) {
						$_SESSION['conf'] = $config;
						$_SESSION['begin'] = 1;
						$this->redirect(['seleccion-tipo-declaracion']);
					} else {
						// No se obtuvieron los parametros de la configuracion.
						return $this->redirect(['error-operacion', 'cod' => 955]);
					}

				} else {
					// No esta defino el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 932]);
				}
			} else {
				// Parametro de configuracion no coinciden.
				return $this->redirect(['error-operacion', 'cod' => 955]);
			}
		}




		/***/
		public function actionSeleccionTipoDeclaracion()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['lapso', 'tipo']);
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

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

				$caption = Yii::t('frontend', 'Declaracion Sustitutiva. Seleccionar Tipo Declaracion.');
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
								$this->redirect(['index-create']);

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
					$rutaLista = "/aaee/declaracion/sustitutiva/sustitutiva/lista-periodo";
					return $this->render('/aaee/declaracion/sustitutiva/_lista-tipo',
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
			}
		}





		/**
		 * Metodo que inicia la carga del formulario que permite realizar la solicitud
		 * de anexo de ramos.
		 * @return view
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['lapso']);
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf']) && isset($_SESSION['tipo']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$tipoDeclaracion = $_SESSION['tipo'];

				$request = Yii::$app->request;
				$postData = $request->post();
				$errorMensaje = '';
				$errorListaAño = '';
				$msjControls = [];
				$solicitudPendiente = Yii::t('frontend', 'Solicitud(es) Pendiente(s):');

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

		      	$caption = Yii::t('frontend', 'Declaracion Sustitutiva') . '. ' . Yii::t('backend', $descripcion);
				$subCaption = Yii::t('frontend', 'Seleccione el periodo fiscal') . '. ' . Yii::t('backend', $descripcion);

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$model->scenario = self::SCENARIO_SEARCH;
						$postData = [];			// Inicializa el post.
						$model->load($postData);
					} elseif ( $postData['btn-back-form'] == 1 ) {
						$postData = [];			// Inicializa el post.
						$model->load($postData);
						$this->redirect(['seleccion-tipo-declaracion']);
					}

				} elseif ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {
						$model->scenario = self::SCENARIO_SEARCH;
						$model->load($postData);
						$model->tipo_declaracion = $tipoDeclaracion;

						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {

			    				//$msjControls = $searchDeclaracion->validarEvento((int)$model->ano_impositivo, (int)$model->exigibilidad_periodo, 2);

		    					if ( count($msjControls) == 0 ) {
				   					$_SESSION['lapso'] = [
				   								'a' => $model->ano_impositivo,
				   								'p' => $model->exigibilidad_periodo,
				   								'tipo' => $tipoDeclaracion,
				   								'descripcion' => $descripcion,
				   					];
				   					$this->redirect(['sustitutiva-create']);
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
					$listaAño = $searchSustitutiva->getListaAnoRegistrado($tipoDeclaracion);
					if ( count($listaAño) == 0 ) {
						$errorListaAño = Yii::t('frontend', 'No se encontraron RUBROS AUTORIZADOS cargados ');
						$errorMensaje = ( trim($errorMensaje) !== '' ) ? $errorMensaje = $errorMensaje . '. ' . $errorListaAño : $errorListaAño;
					}
					$url = Url::to(['index-create']);
					$rutaLista = "/aaee/declaracion/sustitutiva/sustitutiva/lista-periodo";
					return $this->render('/aaee/declaracion/sustitutiva/_create',
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
		 * Metodo que permite mostrar el formulario para la carga de la declaracion.
		 * @return [type] [description]
		 */
		public function actionSustitutivaCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;

				$lapso = isset($_SESSION['lapso']) ? $_SESSION['lapso'] : [];

				if ( count($lapso) > 0 ) {
					$btnSearchCategory = 1;
					$postData = $request->post();

					if ( isset($postData['btn-quit']) ) {
						if ( $postData['btn-quit'] == 1 ) {
							$this->redirect(['quit']);
						}
					}

// die(var_dump($postData));
					// Lo siguiente crea un array del modelo SustitutivaBaseForm(), para la validacion
					// individual de cada uno de los input (campos) del mismo tipo. En este caso el
					// campo donde se registrara la declracion sera tantas veces como ramos (rubros) tenga
					// registrado el contribuyente.
					$modelMultiplex = [New SustitutivaBaseForm()];

					$caption = Yii::t('frontend', 'Declaracion Sustitutiva') . '. ' . $lapso['descripcion'];
					$subCaption = Yii::t('frontend', 'Declaracion Sustitutiva') . '. ' . $lapso['descripcion'];
					$formName = $modelMultiplex[0]->formName();

					// Se obtienen solo los campos.
					$datos = isset($postData[$formName]) ? $postData[$formName] : [];

					$count = ( count($datos) > 0 ) ? count($datos) : 0;
					$result = false;
					if ( $count > 0 ) {
						foreach ( $datos as $key => $value ) {
							$modelMultiplex[$key] = New SustitutivaBaseForm();
							if ( $lapso['tipo'] == 1 ) {
								$modelMultiplex[$key]->scenario = self::SCENARIO_ESTIMADA;
							} elseif ( $lapso['tipo'] == 2 ) {
								$modelMultiplex[$key]->scenario = self::SCENARIO_DEFINITIVA;
							}
						}
						Model::loadMultiple($modelMultiplex, $postData);
						$result = Model::validateMultiple($modelMultiplex);
					}

					if ( Model::loadMultiple($modelMultiplex, $postData)  && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validateMultiple($modelMultiplex);
			      	}

			      	// Datos generales del contribuyente.
			      	$searchSustitutiva = New SustitutivaBaseSearch($idContribuyente);
			      	$findModel = $searchSustitutiva->findContribuyente();

					if ( isset($postData['btn-back-form']) ) {
						if ( $postData['btn-back-form'] == 1 ) {
							$postData = [];			// Inicializa el post.
							$this->redirect(['index-create']);
						} elseif ( $postData['btn-back-form'] == 9 ) {

							$opciones = [
									'back' => '/aaee/declaracion/sustitutiva/sustitutiva/index-create',
							];
							$caption = $caption . '. ' . Yii::t('frontend', 'Rubro(s) Registrado(s)') . ' ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo;
							$subCaption = $subCaption . '. ' . Yii::t('frontend', 'Rubro(s) Registrado(s) ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo);
							return $this->render('/aaee/declaracion/sustitutiva/sustitutiva/declaracion-definitiva-form', [
		  																	'model' => $modelMultiplex,
		  																	'findModel' => $findModel,
		  																	'btnSearchCategory' => $btnSearchCategory,
		  																	'caption' => $caption,
		  																	'opciones' =>$opciones,
		  																	'subCaption' => $subCaption,



				  					]);
						}
					} elseif( isset($postData['btn-create']) ) {
						if ( $postData['btn-create'] == 3 ) {

							if ( $result ) {
								// Presentar preview.
								$opciones = [
									'back' => '/aaee/declaracion/declaracion-definitiva/index-create',
								];
								$caption = Yii::t('frontend', 'Confirm') . ' ' . $caption . '. ' . Yii::t('frontend', 'Pre View');
								$subCaption = $subCaption . '. ' . Yii::t('frontend', 'Categories Registers ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo);

								return $this->render('/aaee/declaracion/definitiva/pre-view-create', [
																	'model' => $modelMultiplex,
																	'findModel' => $findModel,
																	'caption' => $caption,
	  																'opciones' =>$opciones,
	  																'subCaption' => $subCaption,
									]);

							} else {
								$opciones = [
									'back' => '/aaee/declaracion/declaracion-definitiva/index-create',
								];
								$caption = $caption . '. ' . Yii::t('frontend', 'Categories Registered') . ' ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo;
								$subCaption = $subCaption . '. ' . Yii::t('frontend', 'Categories Registers ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo);
								return $this->render('/aaee/declaracion/definitiva/declaracion-definitiva-form', [
			  																	'model' => $modelMultiplex,
			  																	'findModel' => $findModel,
			  																	'btnSearchCategory' => $btnSearchCategory,
			  																	'caption' => $caption,
			  																	'opciones' =>$opciones,
			  																	'subCaption' => $subCaption,



					  					]);
							}
						}
					} elseif( isset($postData['btn-confirm-create']) ) {
						if ( $postData['btn-confirm-create'] == 5 ) {

							if ( count($modelMultiplex) > 1 ) {
								foreach ( $modelMultiplex as $key => $model ) {
									if ( $key > 0 ) {
										$modelMultiplex[$key]->iva_enero = $modelMultiplex[0]->iva_enero;
										$modelMultiplex[$key]->iva_febrero = $modelMultiplex[0]->iva_febrero;
										$modelMultiplex[$key]->iva_marzo = $modelMultiplex[0]->iva_marzo;
										$modelMultiplex[$key]->iva_abril = $modelMultiplex[0]->iva_abril;
										$modelMultiplex[$key]->iva_mayo = $modelMultiplex[0]->iva_mayo;
										$modelMultiplex[$key]->iva_junio = $modelMultiplex[0]->iva_junio;
										$modelMultiplex[$key]->iva_julio = $modelMultiplex[0]->iva_julio;
										$modelMultiplex[$key]->iva_agosto = $modelMultiplex[0]->iva_agosto;
										$modelMultiplex[$key]->iva_septiembre = $modelMultiplex[0]->iva_septiembre;
										$modelMultiplex[$key]->iva_octubre = $modelMultiplex[0]->iva_octubre;
										$modelMultiplex[$key]->iva_noviembre = $modelMultiplex[0]->iva_noviembre;
										$modelMultiplex[$key]->iva_diciembre = $modelMultiplex[0]->iva_diciembre;

										$modelMultiplex[$key]->pp_industria = $modelMultiplex[0]->pp_industria;
										$modelMultiplex[$key]->pagos_retencion = $modelMultiplex[0]->pagos_retencion;
										$modelMultiplex[$key]->islr = $modelMultiplex[0]->islr;
									}
								}
							}

							$result = self::actionBeginSave($modelMultiplex, $postData);
							if ( $result ) {
								$this->_transaccion->commit();
								return self::actionView($modelMultiplex[0]->nro_solicitud);
							} else {
								$this->_transaccion->rollBack();
								$this->redirect(['error-operacion', 'cod'=> 920]);

      						}
						}
					}


			  		if ( isset($findModel) ) {
			  			$añoImpositivo = (int)$lapso['a'];
						$periodo = (int)$lapso['p'];
						$subCaption = $subCaption . '. ' . Yii::t('frontend', 'Rubro(s) Registrado(s) ' . $añoImpositivo . ' - ' . $periodo);

						$rubroRegistradoModels = $searchSustitutiva->findRubrosRegistrados($añoImpositivo, $periodo)->all();

						foreach ( $rubroRegistradoModels as $i => $rubroModel ) {
							$monto = isset($declaracionDefinitiva[$rubroModel->rubroDetalle->rubro]) ? $declaracionDefinitiva[$rubroModel->rubroDetalle->rubro] : 0;
							$modelMultiplex[$i] = New SustitutivaBaseForm();
							$modelMultiplex[$i]['id_contribuyente'] = $rubroModel->actividadEconomica->id_contribuyente;
							$modelMultiplex[$i]['ano_impositivo'] = $rubroModel->actividadEconomica->ano_impositivo;
							$modelMultiplex[$i]['id_rubro'] = $rubroModel->id_rubro;
							$modelMultiplex[$i]['id_impuesto'] = $rubroModel->id_impuesto;
							$modelMultiplex[$i]['exigibilidad_periodo'] = $rubroModel->exigibilidad_periodo;
							$modelMultiplex[$i]['fecha_inicio'] = $findModel->fecha_inicio;
							$modelMultiplex[$i]['periodo_fiscal_desde'] = $rubroModel->periodo_fiscal_desde;
							$modelMultiplex[$i]['periodo_fiscal_hasta'] = $rubroModel->periodo_fiscal_hasta;
							$modelMultiplex[$i]['tipo_declaracion'] = $lapso['tipo'];
							$modelMultiplex[$i]['rubro'] = $rubroModel->rubroDetalle->rubro;
							$modelMultiplex[$i]['descripcion'] = $rubroModel->rubroDetalle->descripcion;
							$modelMultiplex[$i]['estimado'] = $rubroModel->estimado;;
							$modelMultiplex[$i]['reales'] = $rubroModel->reales;
							$modelMultiplex[$i]['sustitutiva'] = $rubroModel->sustitutiva;
							$modelMultiplex[$i]['rectificatoria'] = $rubroModel->rectificatoria;
							$modelMultiplex[$i]['auditoria'] = $rubroModel->auditoria;
							$modelMultiplex[$i]['usuario'] = isset(Yii::$app->user->identity->login) ? Yii::$app->user->identity->login : null;
							$modelMultiplex[$i]['fecha_hora'] = date('Y-m-d H:i:s');
							$modelMultiplex[$i]['origen'] = 'WEB';
							$modelMultiplex[$i]['estatus'] = 0;


						}

						$opciones = [
							'back' => '/aaee/declaracion/sustitutiva/sustitutiva/index-create',
						];
						$caption = $caption . '. ' . Yii::t('frontend', 'Rubro(s) Registrado(s)') . ' ' . $añoImpositivo . ' - ' . $periodo;

						if ( $lapso['tipo'] == 1 ) {
							return $this->render('/aaee/declaracion/sustitutiva/declaracion-sustitutiva-estimada-form', [
		  																	'model' => $modelMultiplex,
		  																	'findModel' => $findModel,
		  																	'btnSearchCategory' => $btnSearchCategory,
		  																	'caption' => $caption,
		  																	'opciones' =>$opciones,
		  																	'subCaption' => $subCaption,

				  					]);
						} elseif ( $lapso['tipo'] == 2 ) {
							return $this->render('/aaee/declaracion/sustitutiva/declaracion-sustitutiva-definitiva-form', [
		  																	'model' => $modelMultiplex,
		  																	'findModel' => $findModel,
		  																	'btnSearchCategory' => $btnSearchCategory,
		  																	'caption' => $caption,
		  																	'opciones' =>$opciones,
		  																	'subCaption' => $subCaption,
		  							]);
						}

			  		} else {
			  			// No se encontraron los datos del contribuyente principal.
			  			$this->redirect(['error-operacion', 'cod' => 938]);
			  		}
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
				$searchRamo = New SustitutivaBaseSearch($idContribuyente);

				// Se espera recibir un arreglo con los atributos de la entidad respectiva.
				$exigibilidad = $searchRamo->getExigibilidadSegunAnoImpositivo($añoImpositivo);
				if ( count($exigibilidad) > 0 ) {
					return $searchRamo->getViewListaExigibilidad($exigibilidad);
				}
			}
			return "<option> - </option>";
		}




		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $models modelos de DeclaracionBaseForm.
		 * @param array $postEnviado post enviado desde el formulario.
		 * @return boolean retorna true si se realizan todas las operacions de
		 * insercion y actualizacion con exitos o false en caso contrario.
		 */
		private function actionBeginSave($models, $postEnviado)
		{
			$result = false;
			$nroSolicitud = 0;

			if ( isset($_SESSION['idContribuyente']) ) {
				if ( isset($_SESSION['conf']) ) {
					$conf = $_SESSION['conf'];

					$this->_conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->_conn = $this->_conexion->initConectar('db');
	      			$this->_conn->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();

					$nroSolicitud = self::actionCreateSolicitud($this->_conexion,
															    $this->_conn,
															    $models[0],
															    $conf);
					if ( $nroSolicitud > 0 ) {
						foreach ( $models as $key => $model ) {
							$model->nro_solicitud = $nroSolicitud;

							// Se pasa a guardar en la sl_declaraciones.
							$result = self::actionCreateDeclaracionDefinitiva($this->_conexion,
																   			  $this->_conn,
																   			  $model,
																   			  $conf);

							if ( $result ) {
								if ( $conf['nivel_aprobacion'] == 1 ) {
									$result = self::actionUpdateMontoDefinitiva($this->_conexion,
																			    $this->_conn,
																			    $model);

									if ( $result ) {
										$result = self::actionUpdateMontoIva($this->_conexion,
																			 $this->_conn,
																			 $model);
									}

								}
							}
							if ( !$result ) { break; }

						}		// Fin del ciclo de models.

						if ( $result ) {
							if ( $result ) {
								$result = self::actionGenerarEstimada($this->_conexion, $this->_conn, $models);
							}

							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($this->_conexion, $this->_conn, $models, $conf);
							}

							if ( $result ) {
								$result = self::actionEnviarEmail($models, $conf);
								$result = true;
							}
						}
					}

				} else {
					// No se obtuvieron los parametros de la configuracion.
					$this->redirect(['error-operacion', 'cod' => 955]);
				}
			} else {
				// No esta defino el contribuyente.
				$this->redirect(['error-operacion', 'cod' => 932]);
			}
			return $result;
		}




		/**
		 * Metodo que guarda el registro respectivo en la entidad "solicitudes-contribuyente".
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection.
		 * @param  model $model modelo de DeclaracionBaseForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna true si guardo correctamente o false sino guardo.
		 */
		private function actionCreateSolicitud($conexionLocal, $connLocal, $model, $conf)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$user = isset($model->usuario) ? $model->usuario : null;
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

				if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
					$nroSolicitud = $connLocal->getLastInsertID();
				}
			}

			return $nroSolicitud;
		}




		/**
		 * Metodo que guarda el registro detalle de la solicitid en la entidad
		 * "sl" respectiva.
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de DeclaracionBaseForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna un true si guardo el registro, false en caso contrario.
		 */
		private static function actionCreateDeclaracionDefinitiva($conexionLocal, $connLocal, $model, $conf)
		{
			$result = false;
			$estatus = 0;
			$user = isset($model->usuario) ? $model->usuario : null;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			if ( isset($conexionLocal) && isset($connLocal) && isset($model) ) {
				if ( count($conf) > 0 ) {
					if ( $conf['nivel_aprobacion'] == 1 ) {
						$estatus = 1;
						$userFuncionario = $user;
						$fechaHoraProceso = date('Y-m-d H:i:s');
					}

					$tabla = '';
	      			$tabla = $model->tableName();

	      			// $model->attributes es array {
	      			// 							[attribute] => valor
	      			// 						}
					$arregloDatos = $model->attributes;

					$arregloDatos['estatus'] = $estatus;
					$arregloDatos['user_funcionario'] = $userFuncionario;
					$arregloDatos['fecha_hora_proceso'] = $fechaHoraProceso;

					$model->estatus = $estatus;
					$model->user_funcionario = $userFuncionario;

					$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);
				}
			}
			return $result;
		}





	    /**
	     * Metodo que realiza la actualizacion del atributo "reales", colocandole
	     * el monto que el usuario ingreso como monto de la declaracion definitiva.
	     * Esta actualizacion se realizara por rubro. Si el monto de la estimada del
	     * mismo periodo es igual a cero para un rubro, se actualizara tambien con el
	     * mismo monto declarado como definitiva para dicho rubro. En el modelo, el
	     * monto de la estimada de dicho lapso, se guarda en model->monto_minimo.
	     * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de DeclaracionBaseForm.
	     * @return boolean retorna un true si guardo el registro, false en caso contrario.
	     */
	    private static function actionUpdateMontoDefinitiva($conexionLocal, $connLocal, $model)
	    {
	    	$result = false;
	    	if ( isset($_SESSION['idContribuyente']) && isset($connLocal) && isset($conexionLocal) ) {
	    		$idContribuyente = $_SESSION['idContribuyente'];
	    		if ( $idContribuyente == $model->id_contribuyente ) {

	    			$ingresoModel = New ActEconIngresoForm();
	    			$tabla = $ingresoModel->tableName();

	    			// Condiciones para modificar el registro.
	    			$arregloCondicion['id_impuesto'] = $model->id_impuesto;
	    			$arregloCondicion['id_rubro'] = $model->id_rubro;
	    			$arregloCondicion['exigibilidad_periodo'] = $model->exigibilidad_periodo;
	    			$arregloCondicion['bloqueado'] = 0;
	    			$arregloCondicion['inactivo'] = 0;

	    			$arregloDatos['reales'] = $model->monto_new;
	    			if ( $model->monto_minimo == 0 ) {
		    			// Atributo a modificar.
		    			$arregloDatos['estimado'] = $model->monto_new;
		    		}

	   				$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion);
	      		}
	    	}
	    	return $result;
	    }





	    /**
	     * Metodo que realiza la actualizacion de los montos por iva, dicho atributos
	     * se encuentran en la entidad "act_econ".
	     * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de DeclaracionBaseForm.
	     * @return boolean retorna un true si guardo el registro, false en caso contrario.
	     */
	    private static function actionUpdateMontoIva($conexionLocal, $connLocal, $model)
	    {
	    	$result = false;
	    	if ( isset($_SESSION['idContribuyente']) && isset($connLocal) && isset($conexionLocal) ) {
	    		$idContribuyente = $_SESSION['idContribuyente'];
	    		if ( $idContribuyente == $model->id_contribuyente ) {

	    			$actModel = New ActEconForm();
	    			$tabla = $actModel->tableName();

	    			// Condiciones para modificar el registro.
	    			$arregloCondicion['id_contribuyente'] = $model->id_contribuyente;
	    			$arregloCondicion['id_impuesto'] = $model->id_impuesto;
	    			$arregloCondicion['ente'] = Yii::$app->ente->getEnte();
	    			$arregloCondicion['estatus'] = 0;


	    			// Atributos a modificar.
	    			$arregloDatos['iva_enero'] = $model->iva_enero;
	    			$arregloDatos['iva_febrero'] = $model->iva_febrero;
	    			$arregloDatos['iva_marzo'] = $model->iva_marzo;
	    			$arregloDatos['iva_abril'] = $model->iva_abril;
	    			$arregloDatos['iva_mayo'] = $model->iva_mayo;
	    			$arregloDatos['iva_junio'] = $model->iva_junio;
	    			$arregloDatos['iva_julio'] = $model->iva_julio;
	    			$arregloDatos['iva_agosto'] = $model->iva_agosto;
	    			$arregloDatos['iva_septiembre'] = $model->iva_septiembre;
	    			$arregloDatos['iva_octubre'] = $model->iva_octubre;
	    			$arregloDatos['iva_noviembre'] = $model->iva_noviembre;
	    			$arregloDatos['iva_diciembre'] = $model->iva_diciembre;

	    			$arregloDatos['islr'] = $model->islr;
	    			$arregloDatos['pp_industria'] = $model->pp_industria;
	    			$arregloDatos['pagos_retencion'] = $model->pagos_retencion;

	   				$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion);
	      		}
	    	}
	    	return $result;
	    }



	    /***/
	    private function actionGenerarEstimada($conexionLocal, $connLocal, $models)
	    {
	    	$result = false;
	    	$idContribuyente = $_SESSION['idContribuyente'];
	    	$declaracionSearch = New DeclaracionBaseSearch($idContribuyente);

	    	$mensaje = $declaracionSearch->cargarEstimadaPorOficio($models, $conexionLocal, $connLocal);

	    	if ( count($mensaje) == 0 ) { $result = true; }

	    	return $result;
	    }





		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  ConexionController  $conexionLocal instancia de la clase ConexionController
		 * @param  connection  $connLocal instancia de connection.
		 * @param  model $models arreglo de modelo de DeclaracionBaseForm.
		 * @param  array $postEnviado post enviado por el formulario. Lo que
		 * se busca es determinar los items seleccionados como documentos y/o
		 * requisitos a consignar para guardarlos.
		 * @return boolean retorna true si guarda efectivamente o false en caso contrario.
		 */
		private static function actionCreateDocumentosConsignados($conexionLocal, $connLocal, $models, $postEnviado)
		{
			$result = false;
			if ( isset($conexionLocal) && isset($connLocal) && isset($models) && count($postEnviado) > 0 ) {
				$modelDocumento = New DocumentoConsignadoForm();
				$tabla = $modelDocumento->tableName();
				$arregloCampos = $modelDocumento->attributes();

				$datosInsert['id_doc_consignado'] = null;
				$datosInsert['id_documento'] = 0;
				$datosInsert['id_contribuyente'] = $model->id_sede_principal;
				$datosInsert['id_impuesto'] = 0;
				$datosInsert['impuesto'] = 1;
				$datosInsert['nro_solicitud'] = $model->nro_solicitud;
				$datosInsert['codigo_proceso'] = null;
				$datosInsert['fecha_hora'] = $model->fecha_hora;
				$datosInsert['usuario'] = $model->user_funcionario;
				$datosInsert['estatus'] = $model->estatus;

				// Se obtiene el arreglo de el o los items de documentos y/o reuisitos
				// seleccionados. Basicamente lo que se obtiene es el identificador (id_documento)
				// del registro.
				$arregloChkDocumeto = $postEnviado['chkDocumento'];
				if ( count($arregloChkDocumeto) > 0 ) {
					foreach ( $arregloChkDocumeto as $documento ) {
						$datosInsert['id_documento'] = $documento;
						$arregloDatos[] = $datosInsert;
					}

					$result = $conexionLocal->guardarLoteRegistros($connLocal, $tabla, $arregloCampos, $arregloDatos);
				} else {
					$result = true;
				}
			}
			return $result;
		}




		/**
		 * Metodo que se encargara de gestionar la ejecucion y resultados de los procesos relacionados
		 * a la solicitud. En este caso los proceso relacionados a la solicitud en el evento "CREAR".
		 * Se verifica si se ejecutaron los procesos y si los mismos fueron todos positivos. Con
		 * el metodo getAccion(), se determina si se ejecuto algun proceso, este metodo retorna un
		 * arreglo, si el mismo es null se asume que no habia procesos configurados para que se ejecutaran
		 * cuando la solicitud fuese creada. El metodo resultadoEjecutarProcesos(), permite determinar el
		 * resultado de cada proceso que se ejecuto.
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de conexion que permite ejecutar las acciones en base
		 * de datos.
		 * @param  model $models arreglo de modelo de la instancia DeclaracionBaseForm.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna true si todo se ejecuto correctamente false en caso contrario.
		 */
		private function actionEjecutaProcesoSolicitud($conexionLocal, $connLocal, $models, $conf)
		{
			$result = true;
			$resultadoProceso = [];
			$acciones = [];
			$evento = '';
			if ( count($conf) > 0 ) {
				if ( $conf['nivel_aprobacion'] == 1 ) {
					$evento = Yii::$app->solicitud->aprobar();
				} else {
					$evento = Yii::$app->solicitud->crear();
				}


				$procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

				// Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
				// que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
				// de resultados donde el key del arrary es el nombre del proceso ejecutado y el valor
				// del elemento corresponda a un reultado de la ejecucion. La variable $model debe contener
				// el identificador del contribuyente que realizo la solicitud y el numero de solicitud.
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($models[0], $evento, $conexionLocal, $connLocal);

				// Se obtiene un array de acciones o procesos ejecutados. Sino se obtienen acciones
				// ejecutadas se asumira que no se configuraro ningun proceso para que se ejecutara
				// cuando se creara la solicitud.
				$acciones = $procesoEvento->getAccion();

				if ( count($acciones) > 0 ) {

					// Se evalua cada accion o proceso ejecutado para determinar si se realizo satisfactoriamnente.
					$resultadoProceso = $procesoEvento->resultadoEjecutarProcesos();

					if ( count($resultadoProceso) > 0 ) {
						foreach ( $resultadoProceso as $key => $value ) {
							if ( $value == false ) {
								$result = false;
								break;
							}
						}
					}
				}
			} else {
				$result = false;
			}

			return $result;

		}



		/**
		 * Metodo que permite enviar un email al contribuyente indicandole
		 * la confirmacion de la realizacion de la solicitud.
		 * @param  model $models array de modelo DeclaracionBaseForm que contiene la informacion
		 * del identificador del contribuyente.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna un true si envio el correo o false en caso
		 * contrario.
		 */
		private function actionEnviarEmail($models, $conf)
		{
			$result = false;
			$listaDocumento = '';
			if ( count($conf) > 0 ) {
				$parametroSolicitud = New ParametroSolicitud($conf['id_config_solicitud']);
				$nroSolicitud = $models[0]->nro_solicitud;
				$descripcionSolicitud = $parametroSolicitud->getDescripcionTipoSolicitud();
				$listaDocumento = $parametroSolicitud->getDocumentoRequisitoSolicitud();

				$email = ContribuyenteBase::getEmail($models[0]->id_contribuyente);
				try {
					$enviar = New PlantillaEmail();
					$result = $enviar->plantillaEmailSolicitud($email, $descripcionSolicitud, $nroSolicitud, $listaDocumento);
				} catch ( Exception $e ) {
					echo $e->getName();
				}
			}
			return $result;
		}


		/**
		 * Metodo que renderiza una vista con la informacion de la solicitud creada.
		 * @param  loong $id identificador de la solicitud creada.
		 * @return view retorna una vista con la informacion detalle de la solicitud.
		 * Informacion cargada por el contribuyente.
		 */
		public function actionView($id)
    	{
    		if ( isset($_SESSION['idContribuyente']) ) {
	    		if ( $id > 0 ) {
	    			$searchDeclaracion = New DeclaracionBaseSearch($_SESSION['idContribuyente']);
	    			$findModel = $searchDeclaracion->findSolicitudDeclaracion($id);
	    			$dataProvider = $searchDeclaracion->getDataProviderSolicitud($id);
	    			if ( isset($findModel) ) {
	    				return self::actionShowSolicitud($findModel, $searchDeclaracion, $dataProvider);
	    			} else {
						throw new NotFoundHttpException('No se encontro el registro');
					}
	    		} else {
	    			throw new NotFoundHttpException('Error ' . $id);
	    		}
	    	} else {
	    		throw new NotFoundHttpException('El contribuyente no esta defino');
	    	}
    	}




    	/***/
    	private function actionShowSolicitud($findModel, $modelSearch, $dataProvider)
    	{
    		if ( isset($findModel) && isset($modelSearch) ) {
 				$model = $findModel->all();
 				self::actionAnularSession(['begin']);
				$opciones = [
					'quit' => '/aaee/declaracion//declaracion-definitiva/quit',
				];
				return $this->render('/aaee/declaracion/sustitutiva/_view', [
																'codigo' => 100,
																'model' => $model,
																'modelSearch' => $modelSearch,
																'opciones' => $opciones,
																'dataProvider' => $dataProvider,
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