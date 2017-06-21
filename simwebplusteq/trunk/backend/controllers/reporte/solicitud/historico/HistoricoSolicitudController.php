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
 *	@file HistoricoSolicitudController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 16-06-2017
 *
 *  @class HistoricoSolicitudController
 *	@brief Clase que gestiona la consulta de las solicitudes emitidas. Creabdo un historico
 * de las mismas.
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


 	namespace backend\controllers\reporte\solicitud\historico;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\reporte\solicitud\historico\HistoricoSolicitudSearch;


	session_start();


	/**
	 * Clase que permite gestionar la consulta del historico de las solcitudes
	 * creadas. Se establece un avanico de opciones de consultas.
	 */
	class HistoricoSolicitudController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario


		const SCENARIO_IMPUESTO = 'impuesto';
		const SCENARIO_CONTRIBUYENTE = 'contribuyente';
		const SCENARIO_SOLICITUD = 'solicitud';
		const SCENARIO_DEFAULT = 'default';




		/**
		 * Metodo inicio de la clase.
		 * @return none
		 */
		public function actionIndex()
		{
			$varSessions = self::actionGetListaSessions();
			self::actionAnularSession($varSessions);
			$_SESSION['begin'] = 1;
			$this->redirect(['mostrar-form-consulta-solicitud']);
		}



		/**
		 * Metodo que permite mostrar un formulario de consulta.
		 * @return none
		 */
		public function actionMostrarFormConsultaSolicitud()
		{
			$usuario = Yii::$app->identidad->getUsuario();
			$model = New HistoricoSolicitudSearch();
			$formName = $model->formName();
			$esFuncionario = true;

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


				if ( count($postData) == 0 ) {
					$model->scenario = self::SCENARIO_DEFAULT;
				} else {

					if ( isset($postData['page']) ) {
						$postData = $_SESSION['postData'];
					}

					if ( isset($postData[$formName]) ) {
						if ( $postData[$formName]['fecha_desde'] !== '' || $postData[$formName]['fecha_hasta'] !== '' ||
							 $postData[$formName]['impuesto'] !== '' || $postData[$formName]['tipo_solicitud'] !== '' ) {
							$model->scenario = self::SCENARIO_IMPUESTO;
						} elseif ( $postData[$formName]['id_contribuyente'] !== '' ) {
							$model->scenario = self::SCENARIO_CONTRIBUYENTE;
						} elseif ( $postData[$formName]['nro_solicitud'] !== '' ) {
							$model->scenario = self::SCENARIO_SOLICITUD;
						}
					}
				}

				$model->load($postData);

				$caption = Yii::t('backend', 'Historico de Solicitudes Creadas');
				$subCaption = Yii::t('backend', 'Parametros de consulta');

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load($postData) ) {
		      		if ( $model->validate() ) {
						$subCaption = Yii::t('backend', 'Resultado de la Consulta');
		      			$dataProvider = $model->getDataProvider();
		      			return $this->render('/reporte/solicitud/historico/historico-solicitud-reporte', [
		      										'dataProvider' => $dataProvider,
		      										'caption' => $caption,
		      										'subCaption' => $subCaption,

		      		  		]);
		      		}
		      	}

		      	// Lista de impuestos. Aplica para todos.
		      	$listaImpuesto = $model->getListaImpuestoContribuyenteJuridico();

		      	// Lista de Tipo de Solicitudes
		      	$listaTipoSolicitud = [];

		      	// Lista de Estatus o Condicion del registro.
		      	$listaEstatus = $model->getListaEstatusSolicitud();

		      	// Se muestra el formulario de busqueda.
		      	return $this->render('/reporte/solicitud/historico/historico-solicitud-consulta-form', [
				      											'model' => $model,
				      											'caption' => $caption,
				      											'subCaption' => $subCaption,
				      											'listaImpuesto' => $listaImpuesto,
				      											'listaTipoSolicitud' => $listaTipoSolicitud,
				      											'listaEstatus' => $listaEstatus,
				      											'esFuncionario' => $esFuncionario,
		      		  ]);

			} else {
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/**
		 * Metodo que permite obtener la lista para la vista de los tipos de solicitudes
		 * segun el impuesto enviado.
		 * @return view
		 */
		public function actionListaSolicitud()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();
			$impuesto = $postGet['i'];

			$historicoSolicitudSearch = New HistoricoSolicitudSearch();
			return $lista = $historicoSolicitudSearch->armarComboTipoSolicitud($impuesto);
		}



		/**
		 * Metodo que permite renderizar una vista con la informacion preliminar de
		 * la licencia segun el numero de solicitud de la misma.
		 * @return View
		 */
		public function actionViewSolicitudModal()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();

			// Identificador del contribuyente
			$id = $postGet['id'];

			// Numero de solicitud
			$nroSolicitud = $postGet['nro'];

			// Se buscan los detalle de la solicitud.
			$historicoSolicitudSearch = New HistoricoSolicitudSearch();
			$viewDetalle = $historicoSolicitudSearch->getViewDetalleSolicitud($nroSolicitud);

			// Modelo de datos de la solicitud maestro.
			$viewMaestro = self::actionViewMaestroSolicitud($nroSolicitud);

			$caption = Yii::t('backend', 'Detalle de la Solicitud Nro. ') . $nroSolicitud;
			$subCaption = Yii::t('backend', 'Detalle');

			return $this->renderAjax('/reporte/solicitud/historico/solicitud-creada', [
												'viewMaestro' => $viewMaestro,
												'viewDetalle' => $viewDetalle,
												'caption' => $caption,
												'subCaption' => $subCaption,
					]);
		}





		/**
	     * Metodo que retorna la vista con la informacion maestro de la solicitud-
	     * @param SolicitudesContribuyente $model modelo que contiene la consulta realizada
	     * sobre la solicitud.
	     * @return view.
	     */
	    public function actionViewMaestroSolicitud($nroSolicitud)
	    {
	    	$historicoSolicitudSearch = New HistoricoSolicitudSearch();
	    	$model = $historicoSolicitudSearch->findDataSolicitudMaestro($nroSolicitud);
die(var_dump($model));
	    	if ( $model !== null ) {
	    		return $this->renderPartial('/solicitud/busqueda/view-maestro-solicitud', [
	    												'model' => $model,
	    			]);
	    	}
			return null;
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