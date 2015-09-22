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
 *	@file BuscarGeneralController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 04-08-2015
 *
 *  @class BuscarGeneralController
 *	@brief Clase principal BuscarGeneralController, que permite leventar
 * 	@brief un formulario de busqueda por diferentes parametros al contribuyente
 *
 *
 *	@property
 *
 *
 *	@method
 *  actionCreate
 *
 *
 *	@inherits
 *
 */


 	namespace backend\controllers\buscargeneral;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\buscargeneral\BuscarGeneralForm;
	use backend\models\buscargeneral\BuscarGeneral;
	//use  yii\web\Session;

	//$session = Yii::$app()->session;		// Iniciando session

	session_start();

	/**
	 * 	Controlador principal del modulo de BuscarGeneral.
	 */
	class BuscarGeneralController extends Controller
	{

		public $layout = 'layout-main';				//	Layout principal del formulario

		/*public $connLocal;
		public $conexion;
		public $transaccion;

		public $naturaleza;
		public $cedula;
		public $tipo;
		public $razonSocial;
		public $nombres;
		public $apellidos;
		public $idContribuyente;*/





		/**
		*
		*/
		public function actionIndex()
		{

			$params = Yii::$app->request->queryParams;
			if ( isset($params['page']) ) {
				if ( $params['page'] > 0 ) {
					//die(var_dump($params) . 'Primero');
					//die(var_dump(self::getDataProviderGlobal()) . ' ***Primero****');
					//die(var_dump($dataProvider));
				}
			}

			$model = New BuscarGeneralForm();
	  		$arrayParametros = [];

	  		$request = Yii::$app->request;

	  		if ( $model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
	      	}

	      	if ( $model->load(Yii::$app->request->post()) ) {

	      	 	if ( $model->validate() ) {
	      	 		$request = Yii::$app->request;
	      	 		$arrayParametros = $request->bodyParams;

	      	 		// Creo mi proveedor de datos para realizar la busqueda y mostrar lo encontrado.
	      	 		$dataProvider = $model->BuscarContribuyente($arrayParametros);

	      	 		//$session['dataProvider'] = $dataProvider;
	      	 		$_SESSION['dataProvider'] = $dataProvider;


	      	 		// Se levanta una vista con el resultado de la consulta, la misma tiene formato de tabla.
	      	 		return $this->render('/buscar-general/contribuyente-encontrado-form', ['searchModel' => $model,'dataProvider' => $dataProvider,]);

	      	 	} else {
	      	 		//die('validate no');
	      	 		$model->getErrors();
	      	 	}
	      	} else {

	      		if ( isset($params['page']) ) {
	      			$params = Yii::$app->request->queryParams;
	      			//$dataProvider = $session['dataProvider'];
	      			$dataProvider = $_SESSION['dataProvider'];

	      			$model->load($params);
      				return $this->render('/buscar-general/contribuyente-encontrado-form', ['searchModel' => $model,'dataProvider' => $dataProvider,]);
	  			}
	  		}

      		$titulo = Yii::t('backend', 'Main Search');
      		$tipoNat = isset($model->tipo_naturaleza) ? $model->tipo_naturaleza : 3;
  			return $this->render('/buscar-general/prueba-form-buscar', ['model' => $model, 'titulo' => $titulo, 'tipoNat' => $tipoNat]);
		}





		/**
		*
		*/
		public function actionOk($idContribuyente)
		{
			$contribuyente = BuscarGeneralForm::getDescripcionContribuyenteSegunID($idContribuyente);
			$tipoNaturaleza = BuscarGeneralForm::getTipoNaturaleza(0, $idContribuyente);
			//$session['idContribuyente'] = $idContribuyente;
			//$session['contribuyente'] = $contribuyente;
			$_SESSION['idContribuyente'] = $idContribuyente;
			$_SESSION['contribuyente'] = $contribuyente;
			$_SESSION['tipoNaturaleza'] = $tipoNaturaleza;

			return $this->render('/buscar-general/view-ok',['mostrarMenuPrincipal' => 1]);
		}





		/**
		 * 	Metodo para anular la sesion actual.
		 * @return boolean, que indica si se anulo la session.
		 */
		public  function actionEliminarSession()
		{
			//unset(Yii::app()->session['var']);
			session_unset();
			return $this->render('/buscar-general/view-ok',['mostrarMenuPrincipal' => 0]);
		}



	  	/**
	  	 * 	Metodo que renderiza una vista con campos de busqueda que dependera del tipo
	  	 * 	de contribuyente, juridico o natural.
	  	 * 	@param $tipoNaturaleza, variable de tipo integer que determina el tipo
	  	 * 	de contribuyente, juridico o natural.
	  	 * 	@return returna una vista con los campos para realizar la busqueda del contribuyente,
	  	 * 	los campo que se visualizaran dependeran del tipo de contribuyente.
	  	 */
	  	public function actionVistaCamposTipoNaturaleza($tipoNaturaleza = null)
	  	{
	  		if ( $tipoNaturaleza == 0 ) {
	  			// Buscar la vista con los campos de busqueda para los contribuyentes tipo persona natural.
	  			return $this->renderPartial('/buscar-general/prueba-cedula-rif-form', ['tipoNaturaleza' => $tipoNaturaleza ]);
	  		} elseif ( $tipoNaturaleza == 1 ) {
	  			// Buscar la vista con los campos de busqueda para los contribuyentes tipo persona juridica.
	  			return $this->renderPartial('/buscar-general/prueba-cedula-rif-form', ['tipoNaturaleza' => $tipoNaturaleza ]);
	  		}
	  	}



	  	/**
	  	 * Metodo que muestra una vista de los datos mas representativos del contribuyente
	  	 * @param $idContribuyente, long que identifica al contribuyente.
	  	 * @return Vista con los datos mas representativos
	  	 */
	  	public function actionView($idContribuyente)
    	{
        	return $this->render('/buscar-general/view', [
            	'model' => $this->findModel($idContribuyente),
        	]);
    	}




    	/**
    	 * [findModel description]
    	 * @param  [type] $idContribuyente [description]
    	 * @return [type]                  [description]
    	 */
    	protected function findModel($idContribuyente)
    	{
        	if (($model = BuscarGeneral::findOne($idContribuyente)) !== null) {
            	return $model;
        	} else {
            	throw new NotFoundHttpException('The requested page does not exist.');
        	}
    	}

	}
 ?>