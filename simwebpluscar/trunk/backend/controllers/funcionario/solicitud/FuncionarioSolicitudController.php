<?php
/**
 * @copyright 2016 © by ASIS CONSULTORES 2012 - 2016
 * All rights reserved - SIMWebPLUS
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




 	namespace backend\controllers\funcionario\solicitud;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	//use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use backend\models\funcionario\solicitud\FuncionarioSolicitudForm;
	//use backend\models\funcionario\FuncionarioForm;
	use backend\models\funcionario\solicitud\FuncionarioSearch;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\utilidad\departamento\DepartamentoForm;
	use backend\models\utilidad\unidaddepartamento\UnidadDepartamentoForm;
	use backend\controllers\MenuController;
	use backend\models\configuracion\tiposolicitud\TipoSolicitudSearch;
	use backend\models\impuesto\ImpuestoForm;

 /**
  *	@file FuncionarioSolicitudController.php
  *
  * @date 21-04-2016
  *
  * @author Jose Rafael Perez Teran
  *
  *
  *	@class FuncionarioSolicitudController
  *	@brief Clase principal de la entidad Funcionario Solicitud.
  *
  *
  *
  *
  *	@property
  *
  *
  *	@method
  *	actionCreate
  *
  *
  *	@inherits
  *
  */


 	/**
 	 * Clase principal que permite gestionar la asignacion de responsabilidaddes en lo
 	 * referente al tipo de solicitudes que cada funcionario debe atender y procesar.
 	 */

	class FuncionarioSolicitudController extends Controller
	{

		public $layout = 'layout-main';				//	Layout principal del formulario.

		public $connLocal;
		public $conexion;
		public $transaccion;

		const SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD = 'search_departamento';
		const SCENARIO_SEARCH_GLOBAL = 'search_global';
		const SCENARIO_DEFAULT = 'default';






		public function actionIndex()
		{
			if ( isset(Yii::$app->user->identity->username) ) {
				$puedoCreate = false;
				$model = New FuncionarioSearch();

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-search']) ) {
					$model->scenario = self::SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD;
				} elseif ( isset($postData['btn-search-all']) ) {
					$model->scenario = ''; //self::SCENARIO_DEFAULT'';
				}

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}


				if ( $model->load($postData) ) {

					if ( isset($postData['btn-search']) ) {
						if ( $model->validate() ) {

							$formName = $model->formName();
							$idDepartamento = $postData[$formName]['id_departamento'];
							$idUnidad = $postData[$formName]['id_unidad'];
							return $this->redirect(['buscar-por-departamento-unidad', 'idD' => $idDepartamento, 'idU' => $idUnidad]);
							// self::actionBuscarPorDepartamentoUnidad($idDepartamento, $idUnidad);
						}
					} elseif ( isset($postData['btn-search-all']) ) {
						// Search-All de los funcionarios con cuentas vigentes.
						//return self::actionBuscarFuncionarioVigente();
						return $this->redirect(['buscar-funcionario-vigente']);
					}
				}

				// Modelo adicionales para la busqueda de los funcionarios.
				$modelDepartamento = New DepartamentoForm();
				$modelUnidad = New UnidadDepartamentoForm();

				// Se define la lista de item para el combo de departamentos.
				$listaDepartamento = $modelDepartamento->getListaDepartamento();

				return $this->render('/funcionario/solicitud/funcionario-solicitud-form', [
																				'model' => $model,
																				'modelDepartamento' => $modelDepartamento,
																				'modelUnidad' => $modelUnidad,
																				'caption' => 'Assign Request to Official',
																				'listaDepartamento' => $listaDepartamento,

					]);




			} else {
				// No esta definido el usuario. Eliminar todas las variables de session y salir.
				return MensajeController::actionMensaje(999);
			}
		}





		/***/
		public function actionCreate()
		{

		}





		public function actionPrueba()
		{
			return "molalal";
		}




		/***/
		public function actionBuscarPorDepartamentoUnidad($idD, $idU)
		{
			$request = Yii::$app->request;
			$postData = $request->post();
			$captionDepartamento = 'Departamento: ' . DepartamentoForm::getDescripcionDepartamento($idD);
			$captionUnidad = 'Unidad: ' . UnidadDepartamentoForm::getDescripcionUnidadDepartamento($idU);
			$subCaption = $captionDepartamento . ' / ' . $captionUnidad;

			$model = New FuncionarioSearch();
			$model->scenario = self::SCENARIO_SEARCH_GLOBAL;

			$modelImpuesto = new ImpuestoForm();
			// Lista para el combo impuestos.
			$listaImpuesto = $modelImpuesto->getListaImpuesto();

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}

			if ( $model->load($postData) ) {
				if ( isset($postData['btn-search-global']) ) {
					if ( $model->validate() ) {
// die('kakakak');
					}
				}
			}

			// Se genera un dataprovider con los parametros enviados.
			$dataProvider = $model->getDataProviderFuncionarioPorDepartamento($idD, $idU);

			return $this->render('/funcionario/solicitud/_list', [
														'model' => $model,
														'dataProvider' => $dataProvider,
														'caption' => Yii::t('backend', 'Lists of Official'),
														'subCaption' => $subCaption,
														'modelImpuesto' => $modelImpuesto,
														'listaImpuesto' => $listaImpuesto,
				]);

		}





		/**
		 * Metodo Search-All
		 * @return [type] [description]
		 */
		public function actionBuscarFuncionarioVigente()
		{

			$request = Yii::$app->request;
			$postData = $request->post();

			$model = New FuncionarioSearch();
			$model->scenario = self::SCENARIO_SEARCH_GLOBAL;

			$modelImpuesto = new ImpuestoForm();
			// Lista para el combo impuestos.
			$listaImpuesto = $modelImpuesto->getListaImpuesto();

			$subCaption = 'All';
			// puedo obtener el all() de todos los registros de un modelo de la siguinete manera
			/**
			 *  $m = $model->findFuncionarioVigente();
			 *  $m->all();
			 */

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}

			if ( $model->load($postData) ) {
				if ( isset($postData['btn-search-global']) ) {
					if ( $model->validate() ) {
// die('kakakak');
					}
				}
			}

			// Se genera un dataprovider de todos los funcionarios con cuentas vigentes.
			$dataProvider = $model->getDataProviderFuncionarioVigente();

			return $this->render('/funcionario/solicitud/_list', [
														'model' => $model,
														'dataProvider' => $dataProvider,
														'caption' => Yii::t('backend', 'Lists of Official'),
														'subCaption' => $subCaption,
														'modelImpuesto' => $modelImpuesto,
														'listaImpuesto' => $listaImpuesto,
				]);
		}




		/***/
		public function actionListaImpuestoSolicitud()
		{
			$caption = Yii::t('backend', 'List of Request');
			$request = Yii::$app->request;
			$postData = $request->get();
			$impuesto = $postData['id'];		// Indice del combo impuesto.
			$modelSolicitud = New TipoSolicitudSearch();
			$dataProvider = $modelSolicitud->getDataProviderSolicitudImpuesto($impuesto);

			return $this->renderAjax('/funcionario/solicitud/lista-impuesto-solicitud', [
														'modelSolicitud' => $modelSolicitud,
														'dataProvider' => $dataProvider,
														'caption' => $caption,
				]);

		}




		/***/
		public function actionQuit()
		{
			$varSession = [];
			self::actionAnularSession($varSession);
			return $this->render('/funcionario/quit');
		}



		/***/
		private function actionAnularSession($varSessions)
		{
			Session::actionDeleteSession($varSessions);
		}




	}

 ?>