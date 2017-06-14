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
 *	@file LicenciaEmitidaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 09-06-2017
 *
 *  @class LicenciaEmitidaController
 *	@brief Clase que gestiona la consulta de las licencias emitidas para generar un reporte
 *  por pantalla o impreso.
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


 	namespace backend\controllers\reporte\aaee\licencia;


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
	use backend\models\reporte\aaee\licencia\LicenciaEmitidaSearch;
	use backend\models\reporte\aaee\licencia\LicenciaEmitidaBusquedaForm;
	use backend\models\aaee\licencia\tipolicencia\TipoLicenciaSearch;
	use backend\models\aaee\licencia\LicenciaSolicitudSearch;


	session_start();


	/***/
	class LicenciaEmitidaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario


		const SCENARIO_FECHA = 'fecha';
		const SCENARIO_CONTRIBUYENTE = 'contribuyente';
		const SCENARIO_LICENCIA = 'licencia';
		const SCENARIO_DEFAULT = 'default';


		public function actionIndex()
		{
			$_SESSION['begin'] = 1;
			$this->redirect(['mostrar-form-consulta-licencia-emitida']);
		}



		/**
		 * Metodo que inicia el modulo de lqiquidacion.
		 * @return [type] [description]
		 */
		public function actionMostrarFormConsultaLicenciaEmitida()
		{
			$usuario = Yii::$app->identidad->getUsuario();
			$model = New LicenciaEmitidaSearch();
			$formName = $model->formName();

			if ( $model->estaAutorizado($usuario) ) {

				$request = Yii::$app->request;

				if ( $request->post('btn-quit') !== null ) {
					if ( $request->post('btn-quit') == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( $request->post('btn-back') !== null ) {
					if ( $request->post('btn-back') == 1 ) {
						$this->redirect(['index']);
					}
				}

				if ( $request->isGet ) {
					$postData = $request->get();
				} else {
					$postData = $request->post() !== null ? $request->post() : $_SESSION['postData'];
					$_SESSION['postData'] = $postData;
				}

				if ( isset($postData['page']) ) {
					$postData = $_SESSION['postData'];
					$model->load($postData);
				}

				if ( count($postData) == 0 ) {
					$model->scenario = self::SCENARIO_DEFAULT;
				} else {

					if ( isset($postData[$formName]) ) {
						if ( $postData[$formName]['fecha_desde'] !== '' || $postData[$formName]['fecha_hasta'] !== '' ) {
							$model->scenario = self::SCENARIO_FECHA;
						} elseif ( $postData[$formName]['id_contribuyente'] !== '' ) {
							$model->scenario = self::SCENARIO_CONTRIBUYENTE;
						} elseif ( $postData[$formName]['licencia'] !== '' ) {
							$model->scenario = self::SCENARIO_LICENCIA;
						}
					}
				}

				$caption = Yii::t('backend', 'Busqueda de Licencias Emitidas');
				$subCaption = Yii::t('backend', 'Parametros de consulta');

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load($postData) ) {
		      		if ( $model->validate() ) {
						$subCaption = Yii::t('backend', 'Resultado de la Consulta');
		      			$dataProvider = $model->getDataProvider();
		      			return $this->render('/reporte/aaee/licencia/licencia-emitida-reporte', [
		      										'dataProvider' => $dataProvider,
		      										'caption' => $caption,
		      										'subCaption' => $subCaption,

		      		  		]);
		      		}
		      	}

		      	// Lista de tipo de licencia
		      	$tipoLicenciaSearch = New TipoLicenciaSearch();
		      	$listaTipoLicencia = $tipoLicenciaSearch->getListaTipo();

		      	// Lista de Estatus o Condicion del registro
		      	$listaEstatus = [
		      		0 => 'PENDIENTE',
		      	];

		      	// Lista usuario
		      	$listaUsuario = $model->getListaUsuarioLicencia();

		      	// Se muestra el formulario de busqueda.
		      	return $this->render('/reporte/aaee/licencia/licencia-emitida-consulta-form', [
		      										'model' => $model,
		      										'caption' => $caption,
		      										'subCaption' => $subCaption,
		      										'listaTipoLicencia' => $listaTipoLicencia,
		      										'listaEstatus' => $listaEstatus,
		      										'listaUsuario' => $listaUsuario,
		      		  ]);

			} else {
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}





		/**
		 * Metodo que permite renderizar una vista con la informacion preliminar de
		 * la licencia segun el numero de solicitud de la misma.
		 * @return View
		 */
		public function actionViewPreLicenciaModal()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();

			// Identificador del contribuyente
			$id = $postGet['id'];

			// Numero de solicitud
			$nroSolicitud = $postGet['nro'];

			// Identificador del historico
			$historico = $postGet['historico'];

			$licenciaSearch = New LicenciaSolicitudSearch($id);
			$dataProvider = $licenciaSearch->getDataProviderRubroSegunSolicitud($nroSolicitud);

			$models = $dataProvider->getModels();
			$model = $models[0]['datosContribuyente'];

			return $this->renderAjax('/aaee/licencia/pre-view-datos-licencia',[
							'model' => $model,
							'models' => $models,
							'dataProvider' => $dataProvider,
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