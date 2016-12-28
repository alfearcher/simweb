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




 	namespace backend\controllers\funcionario;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use backend\models\funcionario\FuncionarioForm;
	use backend\models\funcionario\Funcionario;
	use common\conexion\ConexionController;
	use backend\models\utilidad\departamento\DepartamentoForm;
	use backend\models\utilidad\unidaddepartamento\UnidadDepartamentoForm;
	use backend\models\utilidad\tiponaturaleza\TipoNaturaleza;
	use yii\helpers\ArrayHelper;
	use backend\models\utilidad\nivelfuncionario\NivelFuncionarioForm;
	use common\models\session\Session;


	session_start();

 /**
  *	@file FuncionarioController.php
  *
  * @date 07-07-2015
  *
  * @author Jose Rafael Perez Teran
  *
  *
  *	@class FuncionarioController
  *	@brief Clase principal de la entidad Funcionario.
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

	class FuncionarioController extends Controller
	{

		public $layout = 'layout-main';				//	Layout principal del formulario.

		private $_conn;
		private $_conexion;
		private $_transaccion;



		/***/
		public function actionIndexCreate()
		{
			if ( !isset($_SESSION['begin']) ) {
				$_SESSION['begin'] = 1;
			}

			$request = Yii::$app->request;
			$postData = $request->post();

			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}

			$model = New FuncionarioForm();
			$formName = $model->formName();

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
	      	}

// die(var_dump($postData));


	      	if ( $model->load($postData, $formName) ) {
	      		if ( $model->validate() ) {
	      			// Enviar a vista previa de lo que se quiere guardar.

	      			if ( isset($postData['btn-create']) ) {
	      				if ( $postData['btn-create'] == 3 ) {

							// Lista de Niveles de Funcionario
					      	$listaNivel = NivelFuncionarioForm::getListaNivel();

					      	// Liosta de los departamentos.
					      	$listaDepartamento = DepartamentoForm::getListaDepartamento();

					      	// Lista de Uniaades
					      	$listaUnidad = UnidadDepartamentoForm::getListaUnidadSegunDepartamento($model->id_departamento);

					      	// Lista de la naturaleza.
					      	// Se obtiene el combo-lista para la naturaleza del DNI
						  	$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
						  	$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');

					      	$caption = Yii::t('backend', 'Confirmar Crear Funcionario');
					      	return $this->render('/funcionario/pre-view-create-funcionario',[
					      											'model' => $model,
					      											'caption' => $caption,
					      											'listaDepartamento' => $listaDepartamento,
					      											'listaNaturaleza' => $listaNaturaleza,
					      											'listaNivel' => $listaNivel,
					      											'listaUnidad' => $listaUnidad,

					      			]);

	      				}

	      			} elseif ( isset($postData['btn-confirm-create']) ) {
	      				if ( $postData['btn-confirm-create'] == 5 ) {

	      					// Confirmo y se debe guardar
	      					$model->fecha_inicio = date('Y-m-d', strtotime($model->fecha_inicio));
	      					$model->vigencia = date('Y-m-d', strtotime($model->vigencia));

	      					$result = self::actionBeginSave($model, $postData);
      						self::actionAnularSession(['begin']);
      						if ( $result ) {
								$this->_transaccion->commit();
die(var_dump($model));
								//return self::actionView($model->id_funcionario);
							} else {
								$this->_transaccion->rollBack();
								$this->redirect(['error-operacion', 'cod'=> 920]);

      						}

	      				}
	      			}

	      		}
	      	}

	      	// Lista de Niveles de Funcionario
	      	$listaNivel = NivelFuncionarioForm::getListaNivel();

	      	// Liosta de los departamentos.
	      	$listaDepartamento = DepartamentoForm::getListaDepartamento();

	      	// Lista de la naturaleza.
	      	// Se obtiene el combo-lista para la naturaleza del DNI
		  	$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
		  	$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');

	      	$caption = Yii::t('backend', 'Crear Funcionario');
	      	return $this->render('/funcionario/create-funcionario-form',[
	      											'model' => $model,
	      											'caption' => $caption,
	      											'listaDepartamento' => $listaDepartamento,
	      											'listaNaturaleza' => $listaNaturaleza,
	      											'listaNivel' => $listaNivel,

	      			]);


		}




		/***/
		public function actionListaUnidad($i)
	    {

	    	$request = Yii::$app->request;
	    	$postData = $request->post();

	    	$idDepartamento = $i;

	    	$listaUnidad = UnidadDepartamentoForm::getListaUnidadSegunDepartamento($idDepartamento);

    	    if ( count($listaUnidad) > 0 ) {
        		echo "<option value='0'>" . "Select..." . "</option>";
            	foreach ( $listaUnidad as $u => $unidad ) {
                	echo "<option value='" . $u . "'>" . $unidad . "</option>";
            	}
	        } else {
	            echo "<option> - </option>";
	        }

	    }




	    /***/
	    private function actionBeginSave($model, $postEnviado)
		{
			$result = false;
			$idFuncionario = 0;

			$this->_conexion = New ConexionController();

  			// Instancia de conexion hacia la base de datos.
  			$this->_conn = $this->_conexion->initConectar('db');
  			$this->_conn->open();

  			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
  			// Inicio de la transaccion.
			$this->_transaccion = $this->_conn->beginTransaction();

			$idFuncionario = self::actionCreateFuncionario($model, $this->_conexion, $this->_conn);
			if ( $idFuncionario > 0 ) {
				$model->id_funcionario = $idFuncionario;
				$result = true;
			}
			return $result;
		}




		/***/
		private function actionCreateFuncionario($model, $conexion, $conn)
		{
			$result = false;
			$idFuncionario = 0;
			$tabla = $model->tableName();

			$result = $conexion->guardarRegistro($conn, $tabla, $model->attributes);
			if ( $result ) {
				$idFuncionario = $conn->getLastInsertID();
			}

			return $idFuncionario;
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
							'begin',
					];
		}




		/**
		 * 	Metodo que permite crear un registro de funcionario.
		 *  @return Vista del formulario de cargar.
		 */
		public function actionCreate()
	  	{

	  		$msg='';
	      	$model = new FuncionarioForm();

	      	if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {

				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
	      	}

	      	if ( $model->load(Yii::$app->request->post()) ) {

	    		// Se validan los valores de los campos enviados desde el formualario.
	    		// Cada uno debe cumplir con las reglas (rules), establecidas en el modelo (model) FuncionarioForm.
	      		if ( $model->validate() ) {

	      			// Se obtiene un arreglo de los campos con los datos para enviarlos a guardar.
	      			$arrayDatos = $model->attributes;

	      			// nombre de la tabla
	      			$tabla = '';
	      			$tabla = $model->tableName();	// Funcionarios.

	      			$conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->connLocal = $conexion->initConectar('db');
	      			$this->connLocal->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$transaccion = $this->connLocal->beginTransaction();

					if ( $conexion->guardarRegistro($this->connLocal, $tabla, $arrayDatos) ) {
						$transaccion->commit();
						$tipoError = 0;	// No error.
						$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
 						$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/funcionario/funcionario/create")."'>";
						return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

					} else {
						$transaccion->rollBack();
						$tipoError = 1; // Error.
						$msg = "AH ERROR OCCURRED!....WAIT";
 						$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/funcionario/funcionario/create")."'>";
						return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
					}
					$this->connLocal->close();

	      		} else {
	      			// Array de mensaje de Errores, segun las validaciones del modelo ($model).
	      			//die(var_dump($model->getErrors()));.
	      			$model->getErrors();

	      		}
	      	}

      		// Create es una vista que redirecciona a el formulario principal de carga.
      		return $this->render('/funcionario/create', ['model' => $model, 'msg' => $msg]);
	  	}



	  	public function actionPrueba()
	  	{

	  		$s = New TransaccionInmobiliaria();
	  		//$s = New SolicitudPlanillaSearch(22621);
die(var_dump($s->iniciarCalculoTransaccion(625000, 2016, 1)));


	  	}
	}

 ?>