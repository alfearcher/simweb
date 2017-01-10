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
 *	@file LicenciaSolicitudController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2016
 *
 *  @class LicenciaSolicitudController
 *	@brief Clase LicenciaSolicitudController del lado del contribuyente backend.
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


 	namespace backend\controllers\aaee\listado;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\aaee\listado\ListadoSolicitudDeclaracion;
	use backend\models\impuesto\ImpuestoForm;



	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de licencias.
	 */
	class ListadoSolicitudDeclaracionController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario






		/***/
		public function actionIndex1()
		{

			$request = Yii::$app->request->queryParams;
			$postData = Yii::$app->request->post();


			$model = New ListadoSolicitudDeclaracion();

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}

			if ( $model->load($postData) ) {
				if ( $model->validate() ) {

				}
			}


			$rutaLista = Url::to(['lista-solicitud']);
			$listaImpuesto = [];
			// Modelo adicionales para la busqueda de los funcionarios.
			$modelImpuesto = New ImpuestoForm();

			// Se define la lista de item para el combo de impuestos.
			// El primer parametro se refiere a la condicion del registro 0 => activo, 1 => inactivo.
			$listaImpuesto = $modelImpuesto->getListaImpuesto(0, $listaImpuesto);

			$caption = Yii::t('backend', 'Search Request');
			return $this->render('/solicitud/busqueda/busqueda-solicitud', [
														'model' => $model,
														'modelImpuesto' => $modelImpuesto,
														'caption' => $caption,
														'listaImpuesto' => $listaImpuesto,
														'rutaLista' => $rutaLista,
							]);
		}





		/***/
		public function actionIndex()
		{

			$listadoModel = New ListadoSolicitudDeclaracion();
        	$dataProvider = $listadoModel->search(Yii::$app->request->queryParams);


        	return $this->render('/aaee/listado/listado-solicitud-declaracion',[
        				'listadoModel' => $listadoModel,
        				'dataProvider' => $dataProvider,
        		]);

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
							'lapso'
					];
		}

	}
?>