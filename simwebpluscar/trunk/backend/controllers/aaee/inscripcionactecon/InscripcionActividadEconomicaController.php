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
 *	@file InscripcionActividadEconomicaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 19-09-2015
 *
 *  @class InscripcionActividadEconomicaController
 *	@brief Clase InscripcionActividadEconomicaController, inscripcion de contribuyentes
 *	en el area del impuesto Actividad Economica
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


 	namespace backend\controllers\aaee\inscripcionactecon;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomica;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaForm;
	use common\conexion\ConexionController;
	use backend\controllers\MenuController;

	//$session = Yii::$app()->session;
	session_start();		// Iniciando session

	/**
	 *
	 */
	class InscripcionActividadEconomicaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $connLocal;
		public $conexion;
		public $transaccion;




		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$tipoNaturaleza = isset($_SESSION['tipoNaturaleza']) ? $_SESSION['tipoNaturaleza'] : null;
			if ( $tipoNaturaleza == 'JURIDICO') {
				if ( isset($_SESSION['idContribuyente']) ) {
					$model = New InscripcionActividadEconomicaForm();

			  		$request = Yii::$app->request;

			  		if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validate($model);
			      	}

			      	if ( $model->load(Yii::$app->request->post()) ) {

			      	 	if ( $model->validate() ) {
			      	 		// Todo bien la validacion es correcta.
			      	 		// Se redirecciona a una preview para confirmar la creacion del registro.
			      	 		$_SESSION['model'] = $model;
			      	 		return $this->render('/aaee/inscripcion-actividad-economica/pre-view', ['model' => $model, 'preView' => true]);
			      	 		//$arrayParametros = $request->bodyParams;

			      	 	} else {
			      	 		//die('validate no');
			      	 		$model->getErrors();
			      	 	}
			      	} else {

			  		}
			  		if ( isset($_SESSION['model']) ) { $model = $_SESSION['model']; }

		  			return $this->render('/aaee/inscripcion-actividad-economica/create', ['model' => $model]);
		  		} else {
		  			// No esta definido el contribuyente.
		  			die('NO esta definido el contribuyente');
		  		}
	  		} else {
	  			echo 'Contribuyente no aplica para esta opción.';
	  		}
		}





		/**
		 * 	Metodo que guarda el registro respectivo
		 * 	@return renderiza una vista final de la informacion a guardar.
		 */
		public function actionCreate($guardar = false)
		{
			if ( $_SESSION['idContribuyente'] ) {
				if ( $guardar == true ) {
					if ( isset($_SESSION['model']) ) {
						$model = $_SESSION['model'];
						$arrayDatos = $model->attributes;

						// nombre de la tabla
		      			$tabla = '';
		      			$tabla = $model->tableName();

		      			$conexion = New ConexionController();

		      			// Instancia de conexion hacia la base de datos.
		      			$this->connLocal = $conexion->initConectar('db');
		      			$this->connLocal->open();

		      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
		      			// Inicio de la transaccion.
						$transaccion = $this->connLocal->beginTransaction();

						if ( $conexion->guardarRegistro($this->connLocal, $tabla, $arrayDatos) ) {
							//$idInscripcion = $conexion->getUltimoIdCreado();
							$idInscripcion = $this->connLocal->getLastInsertID();

							// Se continua con la actualizacion de los valores en la tabla contribuyente.
							$model = $_SESSION['model'];

							unset($_SESSION['model']);

							// Se inicia la actualizacion a nivel de la entidad del contribuyente.
							if ( self::actualizarContribuyente($conexion, $this->connLocal, $model) == true ) {
								$transaccion->commit();
								$tipoError = 0;	// No error.
								$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
								$_SESSION['idInscripcion'] = $idInscripcion;
								$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/inscripcionactecon/inscripcion-actividad-economica/view','idInscripcion' => $idInscripcion])."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

							} else {
								$transaccion->rollBack();
								$tipoError = 1; // Error.
								$msg = "AH ERROR OCCURRED!....WAIT";
								$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/inscripcion-actividad-economica/view")."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

							}

						} else {
							$transaccion->rollBack();
							$tipoError = 1; // Error.
							$msg = "AH ERROR OCCURRED!....WAIT";
								$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/inscripcion-actividad-economica/view")."'>";
							return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
						}
						$this->connLocal->close();
					} else {
						// No esta definido el modelo.
						die('No esta definido el modelo');
					}
				}
			} else {
				echo 'No esta definido el contribuyente';
			}
		}





		/**
		*	Metodo muestra la vista con la informacion que fue guardada.
		*/
		public function actionView($idInscripcion)
    	{
    		if ( isset($_SESSION['idInscripcion']) ) {
    			if ( $_SESSION['idInscripcion'] == $idInscripcion ) {
    				$model = $this->findModel($idInscripcion);
    				if ( $_SESSION['idContribuyente'] == $model->id_contribuyente ) {
			        	return $this->render('/aaee/inscripcion-actividad-economica/pre-view',
			        			['model' => $model, 'preView' => false,

			        			]);
			        } else {
			        	echo 'Numero de Inscripcion no valido.';
			        }
	        	} else {
	        		echo 'Numero de Inscripción no valido.';
	        	}
        	}
    	}




		/**
		*	Metodo que busca el ultimo registro creado.
		* 	@param $idInscripcion, long que identifica el autonumerico generado al crear el registro.
		*/
		protected function findModel($idInscripcion)
    	{
        	if (($model = InscripcionActividadEconomica::findOne($idInscripcion)) !== null) {
            	return $model;
        	} else {
            	throw new NotFoundHttpException('The requested page does not exist.');
        	}
    	}





    	/**
    	*
    	*/
    	public function actionQuit()
    	{
    		unset($_SESSION['idInscripcion']);
    		return $this->render('/aaee/inscripcion-actividad-economica/quit');
    	}





    	/**
    	*	Metodo que realiza la actualizacion de los campos segun los valores cargados
    	* 	@param $conexion, instancia de tipo ConexionController.
    	* 	@param $connLocal, instancia de tipo Connection.
    	* 	@param $model, instancia de tipo InscripcionActividadEconomicaForm, que
    	* 	posee todos los valores a guardar.
    	*/
    	protected function actualizarContribuyente($conexion, $connLocal, $model)
    	{
    		$arrayDatosValores = [];
    		$tabla = 'contribuyentes';
    		$arrayCondicion = [];
    		$arrayCondicion['id_contribuyente'] = $model->id_contribuyente;

    		$arrayDatosValores = self::armarArregloDatosInscripcion($model);
    		if ( is_array($arrayDatosValores) ) {
    			return $conexion->modificarRegistro($connLocal, $tabla, $arrayDatosValores, $arrayCondicion);
    		} else {
    			return false;
    		}
    	}





    	/**
    	*	Metodo que armar un array de estructura campo => valor, los mismos son
    	* 	los campos que seran acrtualizados en la entidad contribuyentes.
    	* 	@return arreglo de datos con la estructura campo => valor.
    	*/
    	protected function armarArregloDatosInscripcion($model)
    	{
    		$arrayCampos = [];
    		$arrayCampos = $model->atributosUpDate();
    		$arrayCamposValores = [];

    		foreach ( $arrayCampos as $campo => $value ) {
    			$arrayCamposValores[$value] = $model[$value];
    		}

    		return $arrayCamposValores;
    	}





    	/**
    	 *	METODO PENDIENTE
    	 *	Metodo que coloca los registros anteriores pendientes (estatus = 0) del contribuyente por
    	 * 	Inscripcion de Actividad Economica, en una condicion de sustituidos (estatus = 3), lo que
    	 * 	indica que el funcionario vovlio a cargar un registro por concepto de Inscrcipcion de Actividad
    	 * 	Economica. Este proceso de cambio de estatus se realiza para determinar cual fue el ultimo
    	 * 	@param $idContribuyente, long que identifica al contribuyente.
    	 * 	@return bollean, retorna tru o false, si retorna true la actualizacion se realizo satisfactoriamente.
    	 */
    	protected function sustituirRecordInscripcion($idContribuyente)
    	{

    	}


	}
?>