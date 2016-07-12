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
 *	@file SolicitudCreadaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 11-07-2016
 *
 *  @class SolicitudCreadaController
 *	@brief Clase
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


 	namespace frontend\controllers\solicitud;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use yii\base\Exception;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\contribuyente\ContribuyenteBase;
	use frontend\models\solicitud\SolicitudSearchForm;
	use frontend\models\solicitud\SolicitudCreadaSearch;


	session_start();		// Iniciando session

	/**
	 *
	 */
	class SolicitudCreadaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		const SCENARIO_SEARCH = 'search';
        const SCENARIO_SEARCH_ALL = 'search_all';


        public function actionPrueba()
        {
        	$r = New SolicitudCreadaSearch(458);
        	$t = $r->getListaImpuestoSolicitudPendiente();

die(var_dump($t));
        }




		/***/
		public function actionIndexSearch()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			$model = New SolicitudSearchForm();

			if ( isset($postData['btn-search']) ) {
				$model->scenario = self::SCENARIO_SEARCH;
			} elseif ( isset($postData['btn-search-all']) ) {
				$model->scenario = self::SCENARIO_SEARCH_ALL;
			}

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}

			if ( $model->load($postData) ) {
				if ( isset($postData['btn-search']) ) {
					// Selecciono la busqueda por parametros.
					if ( $postData['btn-search'] == 1 ) {
						if ( $model->validate() ) {

						}
					}

				} elseif ( isset($postData['btn-search-all']) ) {
					// Selecciono la busqueda de totas las solicitudes.
					if ( $postData['btn-search-all'] == 1 ) {
						if ( $model->validate() ) {

						}
					}
				}
			}


			// // Modelo adicionales para la busqueda de los funcionarios.
			// $modelImpuesto = New ImpuestoForm();

			// // Se define la lista de item para el combo de impuestos.
			// $listaImpuesto = $modelImpuesto->getListaImpuesto();

			// $caption = Yii::t('backend', 'Search of requests');
			// 	return $this->render('/funcionario/solicitud/funcionario-desincorporar-solicitud-form', [
			// 																	'model' => $model,
			// 																	'modelImpuesto' => $modelImpuesto,
			// 																	'caption' => $caption,
			// 																	'listaImpuesto' => $listaImpuesto,

			// 		]);




		}




		/**
		 * Metodo que permite renderizar un combo de tipos de solicitudes
		 * segun el parametro impuestos.
		 * @param  integer $i identificador del impuesto.
		 * @return Renderiza una vista con un combo de impuesto.
		 */
		public function actionListSolicitud($i)
	    {
	     //   // die('hola, entro a list');
	     //   	$userLocal = Yii::$app->user->identity->username;
	    	// $model = New SolicitudAsignadaSearch();

	    	// // Todas las solicitudes asignadas.
	    	// $listaSolicitud = $model->getTipoSolicitudAsignada($userLocal);

	    	// // Lista de solicitudes filtradas por el impuesto, es decir, las solicitudes
	    	// // relacionada al impuesto $i.
	    	// $lista = $model->getFiltrarSolicitudAsignadaSegunImpuesto($i, $listaSolicitud);

	     //    $countSolicitud = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	     //    									   ->andWhere(['IN', 'id_tipo_solicitud', $lista])
	     //    									   ->andwhere('inactivo =:inactivo', [':inactivo' => 0])
	     //    									   ->count();

	     //    //$solicitudes = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])->all();

	     //    $solicitudes = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	     //    									->andWhere(['IN', 'id_tipo_solicitud', $lista])
	     //    									->andwhere('inactivo =:inactivo', [':inactivo' => 0])
	     //    									->all();

	     //    if ( $countSolicitud > 0 ) {
	     //    	echo "<option value='0'>" . "Select..." . "</option>";
	     //        foreach ( $solicitudes as $solicitud ) {
	     //            echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
	     //        }
	     //    } else {
	     //        echo "<option> - </option>";
	     //    }
	    }







    	/**
		 * [actionQuit description]
		 * @return [type] [description]
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/funcionario/solicitud-asignada/quit');
		}



		/**
		 * [actionAnularSession description]
		 * @param  [type] $varSessions [description]
		 * @return [type]              [description]
		 */
		public function actionAnularSession($varSessions)
		{
			Session::actionDeleteSession($varSessions);
		}


		/**
		 * [actionProcesoExitoso description]
		 * @return [type] [description]
		 */
		public function actionProcesoExitoso()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
		}



		/**
		 * [actionErrorOperacion description]
		 * @param  [type] $codigo [description]
		 * @return [type]         [description]
		 */
		public function actionErrorOperacion($codigo)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($codigo);
		}



		/**
		 * [actionGetListaSessions description]
		 * @return [type] [description]
		 */
		public function actionGetListaSessions()
		{
			return $varSession = [

					];
		}



	}
?>