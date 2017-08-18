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
		* Metodo que muestra el formulario principal de busqueda.
		* Si la consulta es exitosa se renderiza una vista con el listado de contribuyentes.
		*/
		public function actionIndex()
		{

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

	      	 		$_SESSION['postEnviado'] = $request->bodyParams;

	      	 		// Creo mi proveedor de datos para realizar la busqueda y mostrar lo encontrado.
	      	 		$dataProvider = $model->BuscarContribuyente($arrayParametros);

	      	 		$_SESSION['dataProvider'] = $dataProvider;

	      	 		// Se levanta una vista con el resultado de la consulta, la misma tiene formato de tabla.
	      	 		return $this->render('/buscar-general/contribuyente-encontrado-form', ['searchModel' => $model,'dataProvider' => $dataProvider,]);

	      	 	} else {
	      	 		//die('validate no');
	      	 		$model->getErrors();
	      	 	}
	      	} else {
	      		$params = Yii::$app->request->get();
	      		if ( isset($params['page']) ) {
	      			$params = isset($_SESSION['postEnviado']) ? $_SESSION['postEnviado'] : Yii::$app->request->queryParams;
	      			$model->load($params);
	      			$dataProvider = $model->BuscarContribuyente($params);
      				return $this->render('/buscar-general/contribuyente-encontrado-form', ['searchModel' => $model,'dataProvider' => $dataProvider,]);
	  			}
	  		}

      		$titulo = Yii::t('backend', 'Main Search');
      		$tipoNat = isset($model->tipo_naturaleza) ? $model->tipo_naturaleza : 3;
  			return $this->render('/buscar-general/prueba-form-buscar', ['model' => $model, 'titulo' => $titulo, 'tipoNat' => $tipoNat]);
		}





		/**
		*	Este idContribuyente viene desde la lista donde el usuario selecciono "Ok".
		*
		*/
		public function actionOk($idContribuyente)
		{
			$contribuyente = BuscarGeneralForm::getDescripcionContribuyenteSegunID($idContribuyente);
			$tipoNaturaleza = BuscarGeneralForm::getTipoNaturaleza(0, $idContribuyente);
			if ( isset($_SESSiON['idContribuyente']) ) {
    			if ( $_SESSION['idContribuyente'] == $idContribuyente ) {
    				unset($_SESSION['idContribuyente']);
    				unset($_SESSION['contribuyente']);
    				unset($_SESSION['tipoNaturaleza']);
    			}
    		}

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
    		unset($_SESSION['idContribuyente']);
 			if ( isset($idContribuyente) ) {
	    		if ( isset($_SESSION['idContribuyente']) ) {
	    			// if ( $_SESSION['idContribuyente'] == $idContribuyente ) {
	    				return $this->render('/buscar-general/view', [
			            	'model' => $this->findModel($idContribuyente),
			        	]);
	    			// } else {
	    			// 	unset($_SESSION['idContribuyente']);
	    			// }
	    		} else {
	    			return $this->render('/buscar-general/view', [
			            	'model' => $this->findModel($idContribuyente),
			        	]);
	    		}
	    	} else {
	    		echo 'Contribuyente no definido';
	    	}
    	}




    	/**
    	 * [findModel description]
    	 * @param  [type] $idContribuyente [description]
    	 * @return [type]                  [description]
    	 */
    	protected function findModel($idContribuyente)
    	{
  			$model = BuscarGeneral::find()->alias('B')
  			                              ->joinWith('afiliacion A', true, 'INNER JOIN')
  			                              ->where('B.id_contribuyente =:id_contribuyente',
  			                          					[':id_contribuyente' => $idContribuyente])
  			                              ->one();

  			if ( $model !== null ) {
  				return $model;
  			} else {
  				throw new NotFoundHttpException('The requested page does not exist.');
  			}
    	}

	}
 ?>