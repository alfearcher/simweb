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
 *	@date 09-05-2016
 *
 *  @class InscripcionActividadEconomicaController
 *	@brief Clase InscripcionActividadEconomicaController, controlador del lado del frontend
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


 	namespace frontend\controllers\aaee\inscripcionactecon;


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
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\configuracion\solicitud\ParametroSolicitud;

	session_start();		// Iniciando session

	/**
	 *
	 */
	class InscripcionActividadEconomicaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $conn;
		public $conexion;
		public $transaccion;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';



		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$poseeSolicitud = false;
			$request = Yii::$app->request;
			$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;

			$getData = $request->get();

			if ( isset($getData['id']) && $idContribuyente > 0 ) {

				// identificador de la configuracion de la solicitud.
				$id = $getData['id'];
				$tipoSolicitud = 0;
				$tipoNaturaleza = '';
				$modelParametro = New ParametroSolicitud($id);
				// // Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
				// // del parametro y el valor del elemento es el contenido del campo en base de datos.
				$config = $modelParametro->getParametroSolicitud([
															'id_config_solicitud',
															'tipo_solicitud',
															'impuesto'
															]);

//die(var_dump($config));
				$_SESSION['conf'] = $config;

				$modelSearch = New InscripcionActividadEconomicaSearch($idContribuyente);
				$tipoNaturaleza = $modelSearch->getTipoNaturalezaDescripcionSegunID();
				if ( $tipoNaturaleza == 'JURIDICO') {
					// Se determina si el contribuyente ya posee una solicitud de este tipo, si es asi
					// se aborta la operacion de solicitud.
					$poseeSolicitud = $modelSearch->yaPoseeSolicitudSimiliar();
					if ( $poseeSolicitud ) {
						// Ya posee una solicitud de este tipo y no puede continuar.
						return MensajeController::actionMensaje(945);
					} else {
						return $this->redirect(['index-create']);
					}
				} else {
					// Naturaleza del Contribuyente no definido o no corresponde con el tipo de solicitud.
  					return MensajeController::actionMensaje(930);
				}
			} else {
				// No esta definido el identificador de la configuracion de la solicitud.
				return MensajeController::actionMensaje(940);
			}

		}



		/***/
		public function actionIndexCreate()
		{
			if ( isset($_SESSION['idContribuyente']) ) {
				$model = New InscripcionActividadEconomicaForm();
				$model->scenario = self::SCENARIO_FRONTEND;

		  		if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load(Yii::$app->request->post()) ) {

		      	 	if ( $model->validate() ) {
		      	 		// Todo bien la validacion es correcta.
		      	 		$_SESSION['guardar'] = 1;
		      	 		self::actionBeginSave($model);
		      	 	}
		  		}

		  		$url = Url::to(['index-create']);
		  		$bloquear = false;
	  			return $this->render('/aaee/inscripcion-actividad-economica/_create', [
	  																'model' => $model,
	  																'bloquear' => $bloquear,
	  																'url' => $url,
	  				]);
	  		} else {
	  			// Contribuyente no definido.
	  			return MensajeController::actionMensaje(930);
	  		}

		}



		/***/
		public function actionBeginSave($model)
		{
			$result = false;
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['guardar'])  ) {
				if ( $_SESSION['idContribuyente'] > 0 && $_SESSION['guardar'] == 1 ) {

					$conexion = New ConexionController();

					// Instancia de conexion hacia la base de datos.
			      	$this->conn = $conexion->initConectar('db');
			      	$this->conn->open();

			      	// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
			      	// Inicio de la transaccion.
					$transaccion = $this->conn->beginTransaction();
					$result = self::actionCreateSolicitud($conexion, $this->conn);


					$this->conn->close();

				} else {
					// Operacion no ejecutada.
					return MensajeController::actionMensaje(920);
				}
			} else {
				return MensajeController::actionMensaje(920);
			}
			return $result;
		}




		/**
		 * 	Metodo que guarda el registro respectivo
		 * 	@return renderiza una vista final de la informacion a guardar.
		 */
		public function actionCreateSolicitud($conexionLocal, $connLocal)
		{
			$modelSolicitud = New SolicitudesContribuyenteForm();
			$tablaName = $modelSolicitud->tableName();

			// Arreglo de datos del modelo para guardar los datos.
			$arregloDatos = $modelSolicitud->attributes;

			$nroSolicitud = 0;
			$conf = isset($_SESSION['conf']) ? $_SESSION['conf'] : null;

			$modelSolicitud->attributes = $conf;

die(var_dump($modelSolicitud->attributes));
			return $nroSolicitud;
		}


		/***/
		private function actionCreateInscripcionActEcon($model, $conexionLocal, $connLocal)
		{

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