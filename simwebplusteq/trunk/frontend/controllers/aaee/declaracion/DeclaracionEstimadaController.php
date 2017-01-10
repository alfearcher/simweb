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
 *	@file DeclaracionEstimadaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 06-09-2016
 *
 *  @class DeclaracionEstimadaController
 *	@brief Clase DeclaracionEstimadaController del lado del contribuyente frontend.
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


 	namespace frontend\controllers\aaee\declaracion;


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
	use backend\models\aaee\anexoramo\AnexoRamoSearch;
	use backend\models\aaee\anexoramo\AnexoRamoForm;
	use backend\models\aaee\rubro\Rubro;
	use backend\models\aaee\actecon\ActEconForm;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use backend\models\aaee\declaracion\DeclaracionBaseSearch;
	use backend\models\aaee\declaracion\DeclaracionBaseForm;
	use yii\base\Model;
	use backend\models\aaee\historico\declaracion\HistoricoDeclaracionSearch;
	use common\controllers\pdf\boletin\BoletinController;
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
	class DeclaracionEstimadaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		const SCENARIO_ESTIMADA = 'estimada';
		const SCENARIO_SEARCH = 'search';

		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 108;	// Estimada


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
					//$idContribuyente = $_SESSION['idContribuyente'];
					//$searchDeclaracion = New DeclaracionBaseSearch($idContribuyente);

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
						$this->redirect(['index-create']);
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




		/**
		 * Metodo que inicia la carga del formulario que permite realizar la solicitud
		 * de anexo de ramos.
		 * @return view
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			self::actionAnularSession(['lapso']);
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$idContribuyente = $_SESSION['idContribuyente'];
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

				$model = New DeclaracionBaseForm();

				$formName = $model->formName();
				$model->scenario = self::SCENARIO_SEARCH;

				$caption = Yii::t('frontend', 'Presentation Estimated Tax');
				$subCaption = Yii::t('frontend', 'Select Fiscal Period');

		      	// Datos generales del contribuyente.
		      	$searchDeclaracion = New DeclaracionBaseSearch($idContribuyente);
		      	$findModel = $searchDeclaracion->findContribuyente();

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$model->scenario = self::SCENARIO_SEARCH;
						$postData = [];			// Inicializa el post.
						$model->load($postData);
					}

				} elseif ( isset($postData['btn-accept']) ) {
					if ( $postData['btn-accept'] == 1 ) {
						$model->scenario = self::SCENARIO_SEARCH;
						$model->load($postData);

						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {

			    				$msjControls = $searchDeclaracion->validarEvento($model->ano_impositivo, $model->exigibilidad_periodo, 1);

		    					if ( count($msjControls) == 0 ) {
				   					$_SESSION['lapso'] = [
				   								'a' => $model->ano_impositivo,
				   								'p' => $model->exigibilidad_periodo,
				   					];
				   					$this->redirect(['declarar-create']);
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
					// Solo se considerara el año actual para la declaracion estimada.
					$listaAño = $searchDeclaracion->getListaAnoRegistrado(1);
					if ( count($listaAño) == 0 ) {
						$errorListaAño = Yii::t('frontend', 'No se encontraron RUBROS AUTORIZADOS cargados ');
						$errorMensaje = ( trim($errorMensaje) !== '' ) ? $errorMensaje = $errorMensaje . '. ' . $errorListaAño : $errorListaAño;
					}


					$url = Url::to(['index-create']);
					$rutaLista = "/aaee/declaracion/declaracion-estimada/lista-periodo";
					return $this->render('/aaee/declaracion/estimada/_create',
																[
																	'model' => $model,
																	'caption' => $caption,
																	'subCaption' => $subCaption,
																	'findModel' => $findModel,
																	'listaAño' => $listaAño,
																	'url' =>$url,
																	'rutaLista' => $rutaLista,
																	'searchDeclaracion' => $searchDeclaracion,
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
		public function actionDeclararCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;

				$lapso = isset($_SESSION['lapso']) ? $_SESSION['lapso'] : null;
				$mensajeDeclaracion = '';
				$mensajeDeclaracionInicial = '';

				if ( count($lapso) > 0 ) {
					$btnSearchCategory = 1;
					$postData = $request->post();

					if ( isset($postData['btn-quit']) ) {
						if ( $postData['btn-quit'] == 1 ) {
							$this->redirect(['quit']);
						}
					}


					// Lo siguiente crea un array del modelo DeclaracionBaseForm(), para la validacion
					// individual de cada uno de los input (campos) del mismo tipo. En este caso el
					// campo donde se registrara la declracion sera tantas veces como ramos (rubros) tenga
					// registrado el contribuyente.
					$modelMultiplex = [New DeclaracionBaseForm()];

					$caption = Yii::t('frontend', 'Presentation Estimated Tax');
					$formName = $modelMultiplex[0]->formName();

					// Se obtienen solo los campos.
					$datos = isset($postData[$formName]) ? $postData[$formName] : [];

					$count = ( count($datos) > 0 ) ? count($datos) : 0;
					$result = false;
					if ( $count > 0 ) {
						foreach ( $datos as $key => $value ) {
							$modelMultiplex[$key] = New DeclaracionBaseForm();
							$modelMultiplex[$key]->scenario = self::SCENARIO_ESTIMADA;
						}
						Model::loadMultiple($modelMultiplex, $postData);
						$result = Model::validateMultiple($modelMultiplex);
					}

					if ( Model::loadMultiple($modelMultiplex, $postData)  && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validateMultiple($modelMultiplex);
			      	}

			      	$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : [];
					$rutaAyuda = Yii::$app->ayuda->getRutaAyuda($conf['tipo_solicitud'], 'frontend');

			      	// Datos generales del contribuyente.
			      	$searchDeclaracion = New DeclaracionBaseSearch($idContribuyente);
			      	$findModel = $searchDeclaracion->findContribuyente();


			      	$mensajeDeclaracionInicial = $searchDeclaracion->controlDeclaracionEstimada($lapso['a'], $lapso['p'], []);


					if ( isset($postData['btn-back-form']) ) {
						if ( $postData['btn-back-form'] == 1 ) {
							$postData = [];			// Inicializa el post.
							$this->redirect(['index-create']);
						} elseif ( $postData['btn-back-form'] == 9 ) {
							$opciones = [
									'back' => '/aaee/declaracion/declaracion-estimada/index-create',
							];
							$caption = $caption . '. ' . Yii::t('frontend', 'Rubros Registrados') . ' ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo;
							$subCaption = Yii::t('frontend', 'Rubros Registrados ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo);
							return $this->render('/aaee/declaracion/estimada/declaracion-estimada-form', [
		  																	'model' => $modelMultiplex,
		  																	'findModel' => $findModel,
		  																	'btnSearchCategory' => $btnSearchCategory,
		  																	'caption' => $caption,
		  																	'opciones' =>$opciones,
		  																	'subCaption' => $subCaption,
		  																	'rutaAyuda' => $rutaAyuda,
		  																	'mensajeDeclaracion' => $mensajeDeclaracion,
		  																	'mensajeDeclaracionInicial' => $mensajeDeclaracionInicial,


				  					]);
						}
					} elseif( isset($postData['btn-create']) ) {
						if ( $postData['btn-create'] == 3 ) {

							$suma = 0;
							// Aqui se controla que la suma de lo declarado sea mayor a cero (0).
							$suma = self::actionControlDeclaracion($postData[$formName]);
							if ( $suma <= 0 ) {
								$mensajeDeclaracion = Yii::t('frontend', 'LA SUMA DEL MONTO DECLARADO NO CUMPLE CON LO REQUERIDO. DEBE SER MAYOR A CERO (0).');
								$result = false;
							}

							if ( trim($mensajeDeclaracion) == '' ) {
							   	$mensajeDeclaracion = $searchDeclaracion->controlDeclaracionEstimada($lapso['a'], $lapso['p'], $postData[$formName]);
								if ( trim($mensajeDeclaracion) !== '' ) {
									$result = false;
								}
							}


							if ( $result ) {
								// Presentar preview.
								$opciones = [
									'back' => '/aaee/declaracion/declaracion-estimada/index-create',
								];
								$caption = Yii::t('frontend', 'Confirm') . ' ' . $caption . '. ' . Yii::t('frontend', 'Pre View');
								$subCaption = Yii::t('frontend', 'Rubros Registrados ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo);

								return $this->render('/aaee/declaracion/estimada/pre-view-create', [
																	'model' => $modelMultiplex,
																	'findModel' => $findModel,
																	'caption' => $caption,
	  																'opciones' =>$opciones,
	  																'subCaption' => $subCaption,
									]);

							} else {
								$opciones = [
									'back' => '/aaee/declaracion/declaracion-estimada/index-create',
								];
								$caption = $caption . '. ' . Yii::t('frontend', 'Rubros Registrados') . ' ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo;
								$subCaption = Yii::t('frontend', 'Rubros Registrados ' . $modelMultiplex[0]->ano_impositivo . ' - ' . $modelMultiplex[0]->exigibilidad_periodo);
								return $this->render('/aaee/declaracion/estimada/declaracion-estimada-form', [
			  																	'model' => $modelMultiplex,
			  																	'findModel' => $findModel,
			  																	'btnSearchCategory' => $btnSearchCategory,
			  																	'caption' => $caption,
			  																	'opciones' =>$opciones,
			  																	'subCaption' => $subCaption,
			  																	'rutaAyuda' => $rutaAyuda,
			  																	'mensajeDeclaracion' => $mensajeDeclaracion,
			  																	'mensajeDeclaracionInicial' => $mensajeDeclaracionInicial,


					  					]);
							}
						}
					} elseif( isset($postData['btn-confirm-create']) ) {
						if ( $postData['btn-confirm-create'] == 5 ) {
							self::actionAnularSession(['begin']);
							$result = self::actionBeginSave($modelMultiplex, $postData);
							if ( $result ) {
								$this->_transaccion->commit();
								$this->_conn->close();
								return self::actionView($modelMultiplex[0]->nro_solicitud);
							} else {
								$this->_transaccion->rollBack();
								$this->_conn->close();
								$this->redirect(['error-operacion', 'cod'=> 920]);
      						}

						}
					}


			  		if ( isset($findModel) ) {
			  			$añoImpositivo = (int)$lapso['a'];
						$periodo = (int)$lapso['p'];
						$subCaption = Yii::t('frontend', 'Rubros Registrados ' . $añoImpositivo . ' - ' . $periodo);

						$mismaOrdenanza = true;
						$mismaOrdenanza = $searchDeclaracion->esLaMismaOrdenaza($añoImpositivo);

						// Lo siguiente obtiene una declracion de la definitiva del año anterior.
						// [ramo] => monto estimada
						$declaracionDefinitiva = $searchDeclaracion->getDefinitivaAnterior($añoImpositivo-1, $periodo);

						$rubroRegistradoModels = $searchDeclaracion->findRubrosRegistrados($añoImpositivo, $periodo)->all();

						foreach ( $rubroRegistradoModels as $i => $rubroModel ) {
							$monto = isset($declaracionDefinitiva[$rubroModel->rubroDetalle->rubro]) ? $declaracionDefinitiva[$rubroModel->rubroDetalle->rubro] : 0;
							$modelMultiplex[$i] = New DeclaracionBaseForm();
							$modelMultiplex[$i]['id_contribuyente'] = $rubroModel->actividadEconomica->id_contribuyente;
							$modelMultiplex[$i]['ano_impositivo'] = $rubroModel->actividadEconomica->ano_impositivo;
							$modelMultiplex[$i]['id_rubro'] = $rubroModel->id_rubro;
							$modelMultiplex[$i]['id_impuesto'] = $rubroModel->id_impuesto;
							$modelMultiplex[$i]['exigibilidad_periodo'] = $rubroModel->exigibilidad_periodo;
							$modelMultiplex[$i]['fecha_inicio'] = $findModel->fecha_inicio;
							$modelMultiplex[$i]['periodo_fiscal_desde'] = $rubroModel->periodo_fiscal_desde;
							$modelMultiplex[$i]['periodo_fiscal_hasta'] = $rubroModel->periodo_fiscal_hasta;
							$modelMultiplex[$i]['tipo_declaracion'] = 1;
							$modelMultiplex[$i]['rubro'] = $rubroModel->rubroDetalle->rubro;
							$modelMultiplex[$i]['descripcion'] = $rubroModel->rubroDetalle->descripcion;
							$modelMultiplex[$i]['monto_new'] = 0;
							$modelMultiplex[$i]['monto_v'] = ( $mismaOrdenanza ) ? $rubroModel->estimado : 0;
							$modelMultiplex[$i]['monto_minimo'] = ( $mismaOrdenanza ) ? $monto : 0;
							$modelMultiplex[$i]['usuario'] = isset(Yii::$app->user->identity->login) ? Yii::$app->user->identity->login : null;
							$modelMultiplex[$i]['fecha_hora'] = date('Y-m-d H:i:s');
							$modelMultiplex[$i]['origen'] = 'WEB';
							$modelMultiplex[$i]['estatus'] = 0;

						}

						$opciones = [
							'back' => '/aaee/declaracion/declaracion-estimada/index-create',
						];

						$caption = $caption . '. ' . Yii::t('frontend', 'Rubros Registrados') . ' ' . $añoImpositivo . ' - ' . $periodo;
						return $this->render('/aaee/declaracion/estimada/declaracion-estimada-form', [
	  																	'model' => $modelMultiplex,
	  																	'findModel' => $findModel,
	  																	'btnSearchCategory' => $btnSearchCategory,
	  																	'caption' => $caption,
	  																	'opciones' =>$opciones,
	  																	'subCaption' => $subCaption,
	  																	'rutaAyuda' => $rutaAyuda,
	  																	'mensajeDeclaracion' => $mensajeDeclaracion,
	  																	'mensajeDeclaracionInicial' => $mensajeDeclaracionInicial,
			  					]);

			  		} else {
			  			// No se encontraron los datos del contribuyente principal.
			  			$this->redirect(['error-operacion', 'cod' => 938]);
			  		}
			  	}
			}
		}



		/**
		 * Metodo que controla el monto declarado, el mismo debe ser mayor a cero (0).
		 * @param  array $postEnviado post enviado desde el formulario de la declaracion.
		 * @return boolean retorna true si el monto declarado cumple la condicon,
		 * false en caso contrario.
		 */
		private function actionControlDeclaracion($postEnviado)
		{
			$result = false;
			$suma = 0;
			if ( count($postEnviado) > 0 ) {

				foreach ( $postEnviado as $post ) {
					$suma = $suma + (float)$post['monto_new'];
				}

				if ( $suma > 0 ) { $result = true; }
			}

			return $suma;
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
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $models modelo de DeclaracionBaseForm.
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
							$result = self::actionCreateDeclaracionEstimada($this->_conexion,
																   			$this->_conn,
																   			$model,
																   			$conf);

							if ( $result ) {
								if ( $conf['nivel_aprobacion'] == 1 ) {
									$result = self::actionUpdateActEconIngresos($this->_conexion,
																			    $this->_conn,
																			    $model);

								}
							}
							if ( !$result ) { break; }

						}		// Fin del ciclo de models.

						if ( $result ) {
							$result = self::actionCreateHistoricoDeclaracion($this->_conexion, $this->_conn, $models, $conf);
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
		private static function actionCreateDeclaracionEstimada($conexionLocal, $connLocal, $model, $conf)
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
	     * Metodo que realiza al insercion de los detalles de los ramos. Se realiza una
	     * insercion por cada ramo que se declare.
	     * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $model modelo de DeclaracionBaseForm.
	     * @return boolean retorna un true si guardo el registro, false en caso contrario.
	     */
	    private static function actionUpdateActEconIngresos($conexionLocal, $connLocal, $model)
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

	    			// Atributo a modificar.
	    			$arregloDatos['estimado'] = $model->monto_new;

	   				$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion);
	      		}
	    	}
	    	return $result;
	    }




	    /**
	     * Metodo que crea el historico de declaraciones, esto aplica si la solicitud de declaracion
	     * es de aprobacion directa.
	     * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection
		 * @param  model $models modelo de DeclaracionBaseForm.
	     * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
	     * @return boolean retorna true si guarda satisfactoriamente.
	     */
	    private static function actionCreateHistoricoDeclaracion($conexionLocal, $connLocal, $models, $conf)
	    {
	    	$result = [];
	    	if ( $conf['nivel_aprobacion'] == 1 ) {
		    	if ( isset($_SESSION['idContribuyente']) && count($models) > 0 ) {
		    		$idContribuyente = $_SESSION['idContribuyente'];
		    		$search = New HistoricoDeclaracionSearch($idContribuyente);

					foreach ( $models as $model ) {
						$rjson[] = [
								'nro_solicitud' => $model['nro_solicitud'],
								'id_contribuyente' => $model['id_contribuyente'],
								'id_impuesto' => $model['id_impuesto'],
								'ano_impositivo' => $model['ano_impositivo'],
								'exigibilidad_periodo' => $model['exigibilidad_periodo'],
								'id_rubro' => $model['id_rubro'],
								'rubro' => $model['rubro'],
								'descripcion' => $model['descripcion'],
								'tipo_declaracion' => $model['tipo_declaracion'],
								'estimado_v' => $model['monto_v'],
								'estimado' => $model['monto_new'],
							];
					}

					$arregloDatos = $search->attributes;
					foreach ( $search->attributes as $key => $value ) {

						if ( isset($models[0]->$key) ) {
							$arregloDatos[$key] = $models[0]->$key;
						}

					}
					$arregloDatos['periodo'] = $models[0]->exigibilidad_periodo;
					$arregloDatos['json_rubro'] = json_encode($rjson);
					$arregloDatos['observacion'] = 'SOLICITUD DECLARACION ESTIMADA';
					$arregloDatos['por_sustitutiva'] = 0;

					$result = $search->guardar($arregloDatos, $conexionLocal, $connLocal);
					if ( $result['id'] > 0 ) {
						return true;
					} else {
						return false;
					}
				}
	    	}
	    	return true;
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
		public function actionGenerarCertificadoEstimada()
		{

			$idContribuyente = $_SESSION['idContribuyente'];
			$lapso = $_SESSION['lapso'];
			$a = $lapso['a'];
			$p = $lapso['p'];

			$request = Yii::$app->request;

			$idEnviado = 0;
			if ( $request->isGet ) {
				if ( $request->get('id') !== null ) {
					$idEnviado = $request->get('id');
				}

				if ( isset($_SESSION['id_historico']) ) {

					$id = $_SESSION['id_historico'];
					if ( $idEnviado == $id ) {

						// Controlador para emitir el comprobante de declaracion.
						$declaracion = New DeclaracionController($idContribuyente, $a, $p);
						$declaracion->actionGenerarCertificadoDeclaracionSegunHistorico($id);

					} else {
						throw new NotFoundHttpException(Yii::t('frontend', 'Numero de control no valido'));
					}

				} else {
					throw new NotFoundHttpException(Yii::t('frontend', 'Numero de control no definido'));
				}
			} else {
				throw new NotFoundHttpException(Yii::t('frontend', 'Solicitud no valida'));
			}
		}



		/**
		 * Metodo para responser a la solicitud de generacion de la declaracion estimada.
		 * Lo que aparecera sera un pdf con el resumen de la declaracion.
		 * @return [type] [description]
		 */
		public function actionGenerarComprobanteEstimada()
		{

			$idContribuyente = $_SESSION['idContribuyente'];
			$lapso = $_SESSION['lapso'];
			$a = $lapso['a'];
			$p = $lapso['p'];
			$request = Yii::$app->request;

			$idEnviado = 0;
			if ( $request->isGet ) {
				if ( $request->get('id') !== null ) {
					$idEnviado = $request->get('id');
				}


				if ( isset($_SESSION['id_historico']) ) {

					$id = $_SESSION['id_historico'];
					if ( $idEnviado == $id ) {

						// Controlador para emitir el comprobante de declaracion.
						$declaracion = New DeclaracionController($idContribuyente, $a, $p);
						$declaracion->actionGenerarComprobanteSegunHistorico($id);

					} else {
						throw new NotFoundHttpException(Yii::t('frontend', 'Numero de control no valido'));
					}

				} else {
					throw new NotFoundHttpException(Yii::t('frontend', 'Numero de control no definido'));
				}
			} else {
				throw new NotFoundHttpException(Yii::t('frontend', 'Solicitud no valida'));
			}

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
 				self::actionAnularSession(['id_historico']);

 				$search = New HistoricoDeclaracionSearch($model[0]->id_contribuyente);
 				$historico = $search->findHistoricoDeclaracionSegunSolicitud($model[0]->nro_solicitud);

 				$_SESSION['id_historico'] = isset($historico[0]['id_historico']) ? $historico[0]['id_historico'] : null;

				$opciones = [
					'quit' => '/aaee/declaracion/declaracion-estimada/quit',
				];
				return $this->render('/aaee/declaracion/estimada/_view', [
																'codigo' => 100,
																'model' => $model,
																'modelSearch' => $modelSearch,
																'opciones' => $opciones,
																'dataProvider' => $dataProvider,
																'historico' => $historico,
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