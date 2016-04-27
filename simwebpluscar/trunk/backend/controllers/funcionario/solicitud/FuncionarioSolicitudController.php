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

 	session_start();		// Iniciando session
 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use backend\models\funcionario\solicitud\FuncionarioSolicitudForm;
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

		public $conn;
		public $conexion;
		public $transaccion;

		const SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD = 'search_departamento';
		const SCENARIO_SEARCH_GLOBAL = 'search_global';
		const SCENARIO_DEFAULT = 'default';






		public function actionIndex()
		{
			if ( isset(Yii::$app->user->identity->username) ) {
				$_SESSION['errListaFuncionario'] = '';
				$_SESSION['errListaSolicitud'] = '';
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
		private function actionBeginSave($postData)
		{
			$result = false;
			if ( count($postData) > 0 ) {
				$conexion = New ConexionController();

  				// Instancia de conexion hacia la base de datos.
  				$this->conn = $conexion->initConectar('db');
  				$this->conn->open();

  				// Instancia de transaccion.
  				$transaccion = $this->conn->beginTransaction();

  				$result = self::actionCreate($postData, $conexion, $this->conn);

  				$this->conn->close();

			}
		}




		/***/
		private function actionCreate($postData, $conexionLocal, $connLocal)
		{
			$result = false;
			// Se buscan los identificadores de los funcionarios. Lo siguiente es un array de identificadores.
			$chkFuncionario = $postData['chk-funcionario'];

			// Se buscan los identificadores de las solicitudes. Lo siguiente es un array de identificadores.
			$chkSolicitud = $postData['chk-solicitud'];

			// Modelo de la entidad principal a guardar.
			$model = New FuncionarioSolicitud();
			$tabla = $model->tableName();

			$arregloDatos = $model->attributes;

			$arregloDatos['usuario'] = Yii::$app->user->identity->username;
			$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
			$arregloDatos['inactivo'] = 0;

			foreach ( $chkFuncionario as $funcionario ) {
				$arregloDatos['id_funcionario'] = $funcionario;
			}

		}





		/***/
		public function actionVerificarEnvio()
		{
			$_SESSION['errListaFuncionario'] = '';
			$_SESSION['errListaSolicitud'] = '';
			$request = Yii::$app->request;
			$postData = $request->post();

			$model = New FuncionarioSearch();
			$formName = $model->formName();
			$varPost = $postData[$formName];
			$listado = $varPost['listado'];

			if ( isset($postData['btn-send-request']) ) {
				if ( $postData['btn-send-request'] == 1 ) {

					// El envio se realizo de forma correcta a traves del boton respectivo.
					// Se verifica que haya seleccionado algun funcionario y solicitud de las listas.
					$chkFuncionario = isset($postData['chk-funcionario']) ? $postData['chk-funcionario'] : null;
					$chkSolicitud = isset($postData['chk-solicitud']) ? $postData['chk-solicitud'] : null;

					if ( count($chkFuncionario) > 0 && count($chkSolicitud) > 0 ) {
						// Todo bien. Se puede guardar.
						$result = self::actionBeginSave($postData);

					} else {
						if ( count($chkFuncionario) == 0 ) {
							$_SESSION['errListaFuncionario'] = 'Debe seleccionar al menos un funcionario';
						}
						if ( count($chkSolicitud) == 0 ) {
							$_SESSION['errListaSolicitud'] = 'Debe seleccionar al menos una solicitud';
						}
						if ( $listado == 1 ) {				// Viene de la consulta por Departamento y Unidades
							return $this->redirect(['buscar-por-departamento-unidad',
												    'idD' => $_SESSION['idD'],
												    'idU' => $_SESSION['idU']]);

						} elseif ( $listado == 2 ) {		// Viene de la consulta general (all).
							return $this->redirect(['buscar-funcionario-vigente']);
						}
					}
				} else {
					// El envio no se realizo al presionar el boton.
					return MensajeController::actionMensaje(404);
				}
			} else {
				return MensajeController::actionMensaje(404);
			}

		}




		/***/
		public function actionBuscarPorDepartamentoUnidad($idD, $idU)
		{
			$listado = 1;
			$_SESSION['idD'] = $idD;
			$_SESSION['idU'] = $idU;
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
														'listado' => $listado,
				]);

		}





		/**
		 * Metodo Search-All
		 * @return [type] [description]
		 */
		public function actionBuscarFuncionarioVigente()
		{
			$listado = 2;
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
														'listado' => $listado,
				]);
		}




		/***/
		public function actionListaImpuestoSolicitud()
		{
			$caption = Yii::t('backend', 'List of Request');
			$request = Yii::$app->request;
			$getData = $request->get();
			$impuesto = $getData['id'];		// Indice del combo impuesto.
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