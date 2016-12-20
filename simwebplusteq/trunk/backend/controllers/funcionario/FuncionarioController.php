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
	use backend\models\utilidad\tarifa\transaccioninmobiliaria\TarifaTransaccionInmobiliariaSearch;
	use common\models\calculo\transaccioninmobiliaria\TransaccionInmobiliaria;



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

		public $connLocal;
		public $conexion;
		public $transaccion;



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