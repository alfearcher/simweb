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
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use backend\models\funcionario\solicitud\FuncionarioSolicitudForm;
	use backend\models\funcionario\FuncionarioForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\utilidad\departamento\DepartamentoForm;
	use backend\models\utilidad\unidaddepartamento\UnidadDepartamentoForm;
	use backend\controllers\MenuController;

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






		/***/
		public function actionCreate()
		{
			if ( isset(Yii::$app->user->identity->username) ) {
				$puedoCreate = false;
				$model = New FuncionarioSolicitudForm();

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}


				if ( $model->load($postData) ) {

					if ( $model->validate() ) {

						if ( $postData['btn-create'] == 1 ) {
							$postData['btn-create'] = 2;

							//$_SESSION['postData'] = $postData;
							//$urlPost = '/administradora/administradora/create';

							// return $this->render('/administradora/_pre-view',[
							// 											'postData' => $postData,
							// 											'model' => $model,
							// 											'estoyCreate' => true,
							// 											'estoyUpdate' => false,
							// 											'urlPost' => $urlPost,
							//				]);
						} elseif ( $puedoCreate && isset($postData) ) {
							// Se guardan los datos
							//self::actionBeginSave($postData, $model, 'create');
						}
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




		public function actionBuscarFuncionario()
		{
			$request = Yii::$app->request->post();
die(var_dump($request));

			if ( $idDepartamento > 0 && $idUnidad > 0 ) {
				$model = New FuncionarioForm();
				$d = $model->findFuncionarioPorDepartamentoUnidad($idDepartamento, $idUnidad);
die(var_dump($d));
			}
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