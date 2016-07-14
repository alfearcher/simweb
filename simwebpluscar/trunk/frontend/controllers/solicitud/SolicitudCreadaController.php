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
	use backend\models\impuesto\ImpuestoForm;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;


	session_start();		// Iniciando session

	/**
	 *
	 */
	class SolicitudCreadaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		const SCENARIO_SEARCH = 'search';
        const SCENARIO_SEARCH_ALL = 'search_all';



		/**
		 * Metodo que inicia el modulo de busqueda de las solicitudes. Mostrando
		 * una vista que permite al usuario la busqueda de sus solicitudes por
		 * - impuesto.
		 * - impuesto/solicitud.
		 * - impuesto/rango de fecha.
		 * - impuesto/solicitud/rango de fecha.
		 * - todos.
		 * Solo se muetran en los listados de busqueda los impuestos y/o solicitudes
		 * que el usuario tenga registradas como pendiente.
		 * @return not.
		 */
		public function actionIndexSearch()
		{
			self::actionAnularSession(['postSearch']);
			if ( isset($_SESSION['idContribuyente']) ) {
				$request = Yii::$app->request;
				$postData = $request->post();

				$model = New SolicitudSearchForm($_SESSION['idContribuyente']);

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
								$_SESSION['postSearch'] = $postData;
								return $this->redirect(['buscar-solicitud-pendiente']);
							}
						}

					} elseif ( isset($postData['btn-search-all']) ) {
						// Selecciono la busqueda de totas las solicitudes.
						if ( $postData['btn-search-all'] == 3 ) {
							if ( $model->validate() ) {
								$_SESSION['postSearch'] = $postData;
								return $this->redirect(['buscar-solicitud-pendiente']);
							}
						}
					}
				}


				// Se obtiene un arreglo de valores del atributo "impuesto" de las solicitudes
				// pendientes relacionadas al contribuyente.
				$id = $_SESSION['idContribuyente'];
				//$solicitudSearch = New SolicitudCreadaSearch($id);
        		//$arregloImpuesto = $solicitudSearch->getListaImpuestoSolicitudPendiente();
        		$arregloImpuesto = $model->getListaImpuestoSolicitudPendiente();

				// Se define la lista de item para el combo de impuestos.
				$modelImpuesto = New ImpuestoForm();
				$listaImpuesto = $modelImpuesto->getListaImpuesto(0, $arregloImpuesto);

				// Opciones del menu secundario del formulario.
				$opciones = [
					'undo' => '/solicitud/solicitud-creada/index-search',
				];

				$caption = Yii::t('backend', 'Search of requests');
				return $this->render('/solicitud/busqueda-solicitud/solicitud-search-form', [
			 																	'model' => $model,
			 																	'caption' => $caption,
			 																	'listaImpuesto' => $listaImpuesto,
			 																	'opciones' => $opciones,
			 																	'idContribuyente' => $id,

			 		]);

			}

		}


		/**
		 * Metodo que permite realizar la busqueda de las solicitudes pendientes
		 * segun el parametro de consulta seleccionado por el usuario. Se renderiza
		 * un vista tipo listado, si el listado sobrepasa un limite de 20 registros
		 * aparecera una botonera numerada en la perte inferior que permitira el
		 * desplazamiento entre listada.
		 * @return not.
		 */
		public function actionBuscarSolicitudPendiente()
		{
			if ( isset($_SESSION['idContribuyente']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];

				if ( isset($_SESSION['postSearch']) ) {
					$request = Yii::$app->request;
					if ( $request->isGet ) {

					}
					$postData = $_SESSION['postSearch'];
					$model = New SolicitudSearchForm($idContribuyente);
					$model->load($postData);
					$dataProvider = $model->getDataProviderSolicitudPendiente();
					$caption = Yii::t('frontend', 'List of Request');
					$opciones = [
						'back' => 'solicitud/solicitud-creada/index-search',
						'quit' => 'solicitud/solicitud-creada/quit',
					];
					return $this->render('/solicitud/busqueda-solicitud/solicitud-creada-list', [
																'model' => $model,
																'caption' => $caption,
																'opciones' => $opciones,
																'dataProvider' => $dataProvider,
						]);
				} else {
					throw new NotFoundHttpException(MensajeController::actionMensaje(404, false));
				}
			} else {
				throw new NotFoundHttpException('Error ');
			}
		}




		/**
		 * Metodo que permite renderizar un combo de tipos de solicitudes
		 * segun el parametro impuestos.
		 * @param  integer $i identificador del impuesto.
		 * @return Renderiza una vista con un combo de impuesto.
		 */
		public function actionListSolicitud($i)
	    {
	    	if ( isset($_SESSION['idContribuyente']) ) {
	    		$id = $_SESSION['idContribuyente'];
	    		$solicitudSearch = New SolicitudCreadaSearch($id);

	    		// Lista de identificadorees de tipo de solicitud, asociadas al contribuyente
	    		// segun el o los impuestos.
	    		$listaSolicitud = $solicitudSearch->getListaTipoSolicitudPendiente([$i]);

	    		$countSolicitud = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	         									       ->andWhere(['in', 'id_tipo_solicitud', $listaSolicitud])
	         									       ->andwhere('inactivo =:inactivo', [':inactivo' => 0])
	         									       ->count();

	         	$solicitudes = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])
	         										->andWhere(['in', 'id_tipo_solicitud', $listaSolicitud])
	         										->all();

	         	if ( $countSolicitud > 0 ) {
	        		echo "<option value='0'>" . "Select..." . "</option>";
		             foreach ( $solicitudes as $solicitud ) {
		                 echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
		             }
		         } else {
		             echo "<option> - </option>";
		         }
	    	}

	    }







    	/**
		 * [actionQuit description]
		 * @return [type] [description]
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/solicitud/busqueda-solicitud/quit');
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
						'postSearch',
						'postData',
					];
		}



	}
?>