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
 *	@file LicenciaNoEmitidaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 18-09-2017
 *
 *  @class LicenciaNoEmitidaController
 *	@brief Clase que gestiona la consulta de las licencias no emitidas para generar un reporte
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
	use backend\models\reporte\aaee\licencia\LicenciaNoEmitidaSearch;
	use backend\models\reporte\aaee\licencia\LicenciaNoEmitidaBusquedaForm;
	use backend\models\aaee\licencia\tipolicencia\TipoLicenciaSearch;
	use backend\models\usuario\AutorizacionUsuario;

	session_start();

	/**
	 * Clase que gestiona la consulta de los contribuyentes de actividad economica
	 * que aun no han solicitado la emision de la licencia de actividades, se muestra
	 * un formulario que permite localizar a los contribuyente que aun no han solicitado
	 * su licencia y se permite clasificar el tipo de licencia y definir las causas que
	 * posiblemente de la no emisin de la licencia.
	 */
	class LicenciaNoEmitidaController extends Controller
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
				$this->redirect(['mostrar-form-consulta-licencia-no-emitida']);

			} else {
				// Su perfil no esta autorizado.
				// El usuario no esta autorizado.
            	$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/**
		 * Metodo que muestra el formulario de consulta.
		 * @return view
		 */
		public function actionMostrarFormConsultaLicenciaNoEmitida()
		{
			$usuario = Yii::$app->identidad->getUsuario();
			$model = New LicenciaNoEmitidaSearch();
			$formName = $model->formName();

			if ( isset($_SESSION['begin']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();
				$mensajeCausa = '';

				if ( $request->post('btn-quit') !== null ) {
					if ( $request->post('btn-quit') == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( $request->post('btn-back') !== null ) {
					if ( $request->post('btn-back') == 1 ) {
						$this->redirect(['index']);
					}
				}

				// if ( $request->isGet ) {
				// 	$postData = $request->get();
				// } else {
				// 	$postData = $request->post() !== null ? $request->post() : $_SESSION['postData'];
				// 	$_SESSION['postData'] = $postData;
				// }

//die(var_dump($request->post()));
				//$model->load($postData);

				$caption = Yii::t('backend', 'Busqueda de Licencias No Emitidas');
				$subCaption = Yii::t('backend', 'Parametros de consulta');

				if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load($postData) ) {
		      		if ( !isset($postData['chkCausa']) ) {
		      			$mensajeCausa = Yii::t('backend', 'No ha seleccionado ninguna causa');
		      		}
		      		if ( $model->validate() && trim($mensajeCausa) == '' ) {
		      			$model->chkCausa = $postData['chkCausa'];
die(var_dump($model->init()));
		      		}
		      	}

		      	// Lista de tipo de licencia
		      	$tipoLicenciaSearch = New TipoLicenciaSearch();
		      	$listaTipoLicencia = $tipoLicenciaSearch->getListaTipo();

		      	$dataProvider = $model->dataProviderCausaNoEmision();

		      	// Se muestra el formulario de busqueda.
		      	return $this->render('/reporte/aaee/licencia-no-emitida/licencia-no-emitida-consulta-form', [
		      											'model' => $model,
		      											'caption' => $caption,
		      											'subCaption' => $subCaption,
		      											'listaTipoLicencia' => $listaTipoLicencia,
		      											'dataProvider' => $dataProvider,
		      											'mensajeCausa' => $mensajeCausa,
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
		public function actionViewLicenciaEmitidaModal()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();

			// Identificador del contribuyente
			$id = $postGet['id'];

			// Numero de solicitud
			$nroSolicitud = $postGet['nro'];

			// Identificador del historico
			$historico = $postGet['historico'];

			$licenciaEmitidaSearch = New LicenciaEmitidaSearch();
			$model = $licenciaEmitidaSearch->findHistoricoLicenciaById($historico);
			$caption = Yii::t('backend', 'Licencia Emitida, Nro. ') . $model->licencia . ' - ' . Yii::t('backend', 'Nro Control ') . $model->nro_control;

			return $this->renderAjax('/reporte/aaee/licencia/licencia-emitida', [
												'model' => $model,
												'caption' => $caption,
					]);
		}




		/**
		 * Metodo que permite renderizar una vista con la informacion preliminar de
		 * la licencia segun el numero de solicitud de la misma.
		 * @return View
		 */
		public function actionViewContribuyenteModal()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();

			// Identificador del contribuyente
			$id = (int)$postGet['id'];

			return $this->renderAjax('@backend/views/buscar-general/view', [
			            	'model' => $this->findModel($id),
			       ]);
		}



		/**
    	 * [findModel description]
    	 * @param  [type] $idContribuyente [description]
    	 * @return [type]                  [description]
    	 */
    	protected function findModel($idContribuyente)
    	{
  			$model = BuscarGeneral::find()->alias('B')
  			                              ->joinWith('afiliacion A', true, 'LEFT JOIN')
  			                              ->where('B.id_contribuyente =:id_contribuyente',
  			                          					[':id_contribuyente' => $idContribuyente])
  			                              ->one();

  			if ( $model !== null ) {
  				return $model;
  			} else {
  				throw new NotFoundHttpException('The requested page does not exist.');
  			}
    	}


    	/**
		 * Metodo que exporta el contenido de la consulta a formato excel
		 * @return view
		 */
		public function actionExportarExcel()
		{
			$postInicial = isset($_SESSION['postInicial']) ? $_SESSION['postInicial'] : [];
			if ( count($postInicial) > 0 ) {
				$licenciaSearch = New LicenciaEmitidaSearch();
				$postData = $postInicial;
				$licenciaSearch->scenario = $_SESSION['scenario'];
				$licenciaSearch->load($postData);
				$dataProvider = $licenciaSearch->getDataProvider(true);
				$model = $dataProvider->getModels();

				$licenciaSearch->exportarExcel($model);
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
							'postInicial',
							'scenario',
					];
		}





	}
?>