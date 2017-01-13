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
 *	@file ListadoSolicitudDeclaracionController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2016
 *
 *  @class ListadoSolicitudDeclaracionController
 *	@brief Clase ListadoSolicitudDeclaracionController del lado del funcionario backend.
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
	use backend\models\aaee\listado\ListadoSolicitudDeclaracionSearch;
	use backend\models\impuesto\ImpuestoForm;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use common\models\solicitudescontribuyente\DetalleSolicitudCreada;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanillaSearch;
	use common\models\session\Session;
	use common\models\totalizar\TotalizarGrid;


	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de licencias.
	 */
	class ListadoSolicitudDeclaracionController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		const SCENARIO_NRO_SOLICITUD = 'numero_solicitud';
		const SCENARIO_TIPO = 'tipo';
		const SCENARIO_DEFAULT = 'default';




		public function actionIndex()
		{
			self::actionAnularSession(['postInicial']);
			$this->redirect(['mostrar-buscar-solicitud']);
		}



		/***/
		public function actionMostrarBuscarSolicitud()
		{

			// $request = Yii::$app->request->queryParams;
			$request = Yii::$app->request;
			if ( $request->isGet ) {
				$postData = $request->get();
			} else {
				$postData = $request->post();
				$_SESSION['postInicial'] = $postData;
			}

			$model = New ListadoSolicitudDeclaracion();

			if ( isset($postData['nro_solicitud']) )  {
				$model->scenario = self::SCENARIO_NRO_SOLICITUD;
			} else {
				$model->scenario = self::SCENARIO_TIPO;
			}

			if ( isset($postData['btn-back-form']) ) {
				if ( $postData['btn-back-form'] == 1 ) {
					$postData = [];
					$request = [];
					$model->load($postData);
				}
			}


			if ( isset($postData['page']) ) {

				if ( isset($_SESSION['postInicial']) ) {
					$postData = $_SESSION['postInicial'];
				}


				$model->load($postData);
				$dataProvider = $model->search($postData);

				$totalizar = New TotalizarGrid();
				$totalDeclarado = $totalizar->getTotalizar($dataProvider,'suma');

	        	return $this->render('/aaee/listado/listado-solicitud-declaracion',[
	        				'listadoModel' => $model,
	        				'dataProvider' => $dataProvider,
	        				'totalDeclarado' => $totalDeclarado,
	        		]);


	        } else {

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ( isset($_SESSION['postInicial']) ) {
					$postData = $_SESSION['postInicial'];
				}

				if ( isset($postData['nro_solicitud']) )  {

					if ( $model->load($postData) ) {
						if ( $model->validate() ) {

	        				$dataProvider = $model->search($postData);

				        	return $this->render('/aaee/listado/listado-solicitud-declaracion',[
				        				'listadoModel' => $model,
				        				'dataProvider' => $dataProvider,
				        		]);
						}
					}

				} else {

					if ( $model->load($postData) ) {
						if ( $model->validate() ) {
							if ( isset($_SESSION['postInicial']) ) {
								$postData = $_SESSION['postInicial'];
							}

	        				$dataProvider = $model->search($postData);
	        				$model->load($postData);

	        				$totalizar = New TotalizarGrid();
							$totalDeclarado = $totalizar->getTotalizar($dataProvider,'suma');

				        	return $this->render('/aaee/listado/listado-solicitud-declaracion',[
				        				'listadoModel' => $model,
				        				'dataProvider' => $dataProvider,
				        				'totalDeclarado' => $totalDeclarado,
				        		]);
						}
					}
				}

				$rutaLista = 'aaee/listado/listado-solicitud-declaracion/listar-solicitud';
				$listaImpuesto = [];
				// Modelo adicionales para la busqueda de los funcionarios.
				$modelImpuesto = New ImpuestoForm();

				// Se define la lista de item para el combo de impuestos.
				// El primer parametro se refiere a la condicion del registro 0 => activo, 1 => inactivo.
				$listaImpuesto = $modelImpuesto->getListaImpuesto(0, [1]);

				$listaEstatus = $model->getListaEstatus();

				$caption = Yii::t('backend', 'Search Request');
				return $this->render('/solicitud/busqueda/busqueda-solicitud', [
															'model' => $model,
															'modelImpuesto' => $modelImpuesto,
															'caption' => $caption,
															'listaImpuesto' => $listaImpuesto,
															'rutaLista' => $rutaLista,
															'listaEstatus' => $listaEstatus,
								]);
			}
		}



		/**
		 * Metodo que permite renderizar un combo de tipos de solicitudes
		 * segun el parametro impuestos.
		 * @param  Integer $i identificador del impuesto.
		 * @return Renderiza una vista con un combo de impuesto.
		 */
		public function actionListarSolicitud($i)
	    {

			$solicitudes = TipoSolicitud::find()->where('impuesto =:impuesto',
															[':impuesto' => $i])
												->andWhere(['IN', 'id_tipo_solicitud', [8,73]])
												->all();

	        if ( count($solicitudes) > 0 ) {
	        	echo "<option value='0'>" . "Select..." . "</option>";
	            foreach ( $solicitudes as $solicitud ) {
	                echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }
	    }



	    /**
		 * Metodo que permite renderizar una vista de los detalles de la planilla
		 * que se encuentran en la solicitud.
		 * @return View Retorna una vista que contiene un grid con los detalles de la
		 * planilla.
		 */
		public function actionViewDetalleSolicitud()
		{
			$request = Yii::$app->request;
			$getData = $request->get();

			$nroSolicitud = 0;
			$nroSolicitud = $getData['nro'];

			// Vista detalle de la solicitud, es la informacion que se cargo.
			$detalle = New DetalleSolicitudCreada($nroSolicitud);
			$viewDetalleSolicitud = $detalle->getDatosSolicitudCreada();


			// Se buscan las planillas relacionadas a la solicitud. Se refiere a las planillas
			// de impueso "tasa".
			$modelPlanilla = New SolicitudPlanillaSearch($nroSolicitud, Yii::$app->solicitud->crear());
			$dataProvider = $modelPlanilla->getArrayDataProvider();

			$caption = Yii::t('frontend', 'Planilla(s)');
			$viewSolicitudPlanilla = $this->renderAjax('@common/views/solicitud-planilla/solicitud-planilla', [
															'caption' => $caption,
															'dataProvider' => $dataProvider,
									]);



			$viewDocumentoConsignado = '';

			$caption = Yii::t('frontend', 'Request details. Pendiente');
			$opciones = [
				'back' => '#',
				'quit' => '#',
			];
			if ( $viewDetalleSolicitud !== false ) {
				return $this->renderAjax('/solicitud/busqueda/view-detalle-solicitud',[
												'viewDetalleSolicitud' => $viewDetalleSolicitud,
												'viewSolicitudPlanilla' => $viewSolicitudPlanilla,
												'viewDocumentoConsignado' => $viewDocumentoConsignado,
												'caption' => $caption,
												'opciones' => $opciones,
						]);
			} else {
				throw new NotFoundHttpException('Solicitud no encontrada');
			}

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
							'postInicial',
					];
		}

	}
?>