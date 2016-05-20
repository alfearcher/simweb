<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *  > This library is free software; you can redistribute it and/or modify it under
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *  > any later version.
 *  >
 *  > This library is distributed in the hope that it will be usefull,
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *  > for more details.
 *  >
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *  @file SolicitudAsignada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-05-2016
 *
 *  @class SolicitudAsignadaController
 *  @brief Clase
 *
 *
 *  @property
 *
 *
 *  @method
 *  rules
 *  attributeLabels
 * 	scenarios
 *
 *
 *  @inherits
 *
 */


	namespace backend\controllers\funcionario\solicitud;

 	session_start();		// Iniciando session
 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use backend\models\funcionario\solicitud\SolicitudAsignadaSearch;
	use backend\models\funcionario\solicitud\SolicitudAsignadaForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\impuesto\ImpuestoForm;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use common\models\solicitudescontribuyente\DetalleSolicitudCreada;

	/**
	 *	Clase principal del formulario.
	 */
	class SolicitudAsignadaController extends Controller
	{

	   	public $layout = 'layout-main';				//	Layout principal del formulario.

		public $conn;
		public $conexion;
		public $transaccion;





		/***/
		public function actionIndex()
		{
			$request = Yii::$app->request;
			$postData = $request->post();

			// Modelo del formulario de busqueda de las solicitudes.
			$model = New SolicitudAsignadaForm();

			if ( $model->load($postData) && Yii::$app->request->isAjax ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}

			if ( $model->load($postData) ) {
				if ( $model->validate() ) {
					if ( isset($postData['btn-search-request']) ) {
						//return self::actionBuscarSolicitudesContribuyente($model);
						$_SESSION['postData'] = $postData;
						return $this->redirect(['buscar-solicitudes-contribuyente']);
					}
				}
			}
			// Lo siguiente permite determinar que impuestos estan relacionados a las
			// solicitudes permisadas para el funcionario.
			$listaImpuesto = null;
			$modelSearch = New SolicitudAsignadaSearch();
			$listaImpuesto = $modelSearch->getImpuestoSegunFuncionario();

			// Modelo adicionales para la busqueda de los funcionarios.
			$modelImpuesto = New ImpuestoForm();

			// Se define la lista de item para el combo de impuestos.
			// El primer parametro se refiere a la condicion del registro 0 => activo, 1 => inactivo.
			$listaImpuesto = $modelImpuesto->getListaImpuesto(0, $listaImpuesto);

			$caption = Yii::t('backend', 'Search Request');
			return $this->render('/funcionario/solicitud-asignada/busqueda-solicitud-form', [
																			'model' => $model,
																			'modelImpuesto' => $modelImpuesto,
																			'caption' => $caption,
																			'listaImpuesto' => $listaImpuesto,

				]);
		}



		public function actionProcesarSolicitud()
		{
			$request = Yii::$app->request;
			$postData = $request->post();
die(var_dump($postData));
		}




		/**
		 * Metodo que muestra la solicitud seleccionada por el funcionario.
		 * @return [type] [description]
		 */
		public function actionBuscarSolicitudSeleccionada()
		{
			$request = Yii::$app->request;
			$postData = $request->post();
			$contribuyente =null;
			$caption = Yii::t('backend', 'Infomation of the request');
			$subCaption = Yii::t('backend', 'Request');
			$url = Url::to(['procesar-solicitud']);

			// Identificador de la solicitud seleccionada por el usuario.
			// nro de solicitud.
			$id = isset($postData['id']) ? $postData['id'] : null;

			if ( $id != null ) {
				$modelSearch = New SolicitudAsignadaSearch();
				$infoSolicitud = $modelSearch->findSolicitudSeleccionada($id);

				// Se buscan los datos basicos del contribuyente.
				if ( isset($infoSolicitud->id_contribuyente) ) {
					$contribuyente = $modelSearch->getDatosBasicoContribuyenteSegunId($infoSolicitud->id_contribuyente);
					if ( count($contribuyente) > 0 ) {
						$_SESSION['idContribuyente'] = $infoSolicitud->id_contribuyente;
						$detalle = New DetalleSolicitudCreada($id);
						$viewDetalle = $detalle->getDatosSolicitudCreada();
//die(var_dump($viewDetalle));
						return $this->render('/funcionario/solicitud-asignada/view-solicitud-seleccionada', [
																				'model' => $infoSolicitud,
																				'caption' => $caption,
																				'subCaption' => $subCaption,
																				'listado' => 6,
																				'url' => $url,
																				'contribuyente' => $contribuyente,
																				'viewDetalle' => $viewDetalle,
							]);
					} else {
						// Contribuyente no definido.
						return MensajeController::actionMensaje(404);
					}

				} else {
					// Contribuyente no definido.
					return MensajeController::actionMensaje(404);
				}

			} else {
				// Solicitud no definida.
				return MensajeController::actionMensaje(404);
			}

		}




		/***/
		public function actionBuscarSolicitudesContribuyente()
		{
			$postInicial = isset($_SESSION['postData']) ? $_SESSION['postData'] : null;
			$model = New SolicitudAsignadaForm();     // Modelo del formulario de busqueda.
			$model->load($postInicial);

			$request = Yii::$app->request;
			$postData = isset($request->queryParams['page']) ? $request->queryParams : $postInicial;

			$url = Url::to(['buscar-solicitud-seleccionada']);
			$modelSearch = New SolicitudAsignadaSearch();
			$modelSearch->attributes = $model->attributes;

			$modelSearch->load($postData);

			$userLocal = Yii::$app->user->identity->username;

			// Lista de los identificadores de los tipos de solicitud asociado al funcionario.
			$lista = $modelSearch->getTipoSolicitudAsignada($userLocal);

			$caption = Yii::t('backend', 'Lists of Request Authorized');
			$subCaption = Yii::t('backend', 'Lists of Request Authorized');

			$dataProvider = $modelSearch->getDataProviderSolicitudContribuyente($lista);

			return $this->render('/funcionario/solicitud-asignada/lista-solicitudes-elaboradas', [
																'model' => $modelSearch,
																'dataProvider' => $dataProvider,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'url' => $url,
																'listado' => 5,
				]);

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
			return MensajeController::actionMensaje(100);
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
							'postData',
							'idContribuyente',
					];
		}



		/**
		 * Metodo que permite renderizar un combo de tipos de solicitudes
		 * segun el parametro impuestos.
		 * @param  Integer $i identificador del impuesto.
		 * @return Renderiza una vista con un combo de impuesto.
		 */
		public function actionListSolicitud($i)
	    {
	       // die('hola, entro a list');
	       	$userLocal = Yii::$app->user->identity->username;
	    	$model = New SolicitudAsignadaSearch();

	    	// Todas las solicitudes asignadas.
	    	$listaSolicitud = $model->getTipoSolicitudAsignada($userLocal);

	    	// Lista de solicitudes filtradas por el impuesto, es decir, las solicitudes
	    	// relacionada al impuesto $i.
	    	$lista = $model->getFiltrarSolicitudAsignadaSegunImpuesto($i, $listaSolicitud);

	        $countSolicitud = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	        									   ->andWhere(['IN', 'id_tipo_solicitud', $lista])
	        									   ->andwhere('inactivo =:inactivo', [':inactivo' => 0])
	        									   ->count();

	        //$solicitudes = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])->all();

	        $solicitudes = TipoSolicitud::find()->where('impuesto =:impuesto', [':impuesto' => $i])
	        									->andWhere(['IN', 'id_tipo_solicitud', $lista])
	        									->andwhere('inactivo =:inactivo', [':inactivo' => 0])
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
?>