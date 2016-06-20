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
 *	@file ConfigurarSolicitudController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 22-02-2016
 *
 *  @class ConfigurarSolicitudController
 *	@brief Clase ConfigurarSolicitudController, aprobacion de rubros
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


 	namespace backend\controllers\configuracion\solicitud;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use yii\db\Exception;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\documentoconsignado\DocumentoConsignadoForm;
	use backend\models\configuracion\solicitud\ConfigurarSolicitudForm;
	use backend\models\impuesto\ImpuestoForm;
	use backend\models\configuracion\tiposolicitud\TipoSolicitudForm;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use backend\models\utilidad\documento\DocumentoRequisitoForm;
	use backend\models\configuracion\detallesolicitud\SolicitudDetalleForm;
	use backend\models\configuracion\documentosolicitud\SolicitudDocumentoForm;
	use backend\models\configuracion\nivelaprobacion\NivelAprobacionForm;

	session_start();		// Iniciando session

	/**
	 *
	 */
	class ConfigurarSolicitudController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $connLocal;
		public $conexion;
		public $transaccion;




		/**
		 * Renderiza un Menu para la creacion, actualizacion y listado de las solicitudes
		 * @return returna un menu con las siguientes opciones:
		 * - create
		 * - update
		 * - list
		 */
		public function actionIndex()
		{
			return $this->render('/operacion-basica/_operacion', [
									'urlCreate' => Url::to('create'),
									'urlUpdate' => Url::to('update'),
									'urlList' => Url::to('list'),
									'caption' => Yii::t('backend', 'SETUP REQUEST.'),
				]);
		}


		/***/
		public function actionListaTipoSolicitud()
		{
			$countTipoSolicitud = 0;
			$request = Yii::$app->request;
			if ( Yii::$app->request->isPost ) {
				// Se toma el impuesto enviado.
				$impuesto = $request->get('id');
				$countTipoSolicitud = TipoSolicitudForm::totalTipoSolicitud($impuesto);
				if ( $countTipoSolicitud > 0 ) {
					$modelTipoSolicitud = TipoSolicitudForm::findTipoSolicitud($impuesto);

					foreach ($modelTipoSolicitud as $tipo) {
						 echo "<option value='" . $tipo->id_tipo_solicitud . "'>" . $tipo->descripcion . "</option>";
					}
				} else {
            		echo "<option> - </option>";
				}
			}
		}


		/***/
		public function actionListaDocumentoRequisito()
		{
			$impuesto = 0;
			$request = Yii::$app->request;
			$impuesto = $request->get('id');
			if ( $impuesto > 0 ) {
				$dataProvider = DocumentoRequisitoController::actionGetDataProviderSegunImpuesto($impuesto);

				return $this->renderAjax('/utilidad/documento-requisito/documento-requisito-gridview', [
																'dataProvider' => $dataProvider,
						]);
			}
			return false;
		}



		/***/
		public function actionCreate()
		{
			if ( Session::actionExisteUser() ) {

				$errorEjecutarEn = '';
				$model = New ConfigurarSolicitudForm();
				$request = Yii::$app->request;
				$postData = $request->post();
				$itemsEjecutar = isset($postData['combo']) ? $postData['combo'] : null;

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ( $model->load($postData) ) {
					if ( $model->validate() ) {
						if ( $model->validarRangoFecha($model) ) {
							if ( $model->validarProcesoSeleccion($itemsEjecutar, $model) ) {
								if ( $postData['btn-create'] == 1 && isset($postData) ) {
									$postData['btn-create'] = 2;
									// Se guardan los datos
									self::actionBeginSave($postData, $model, 'create');
								}
							} else {
								$errorEjecutarEn = $model->getFirstErrors('ejecutar_en')['ejecutar_en'];
							}
						}
					}
				}

				// Modelo para cargar el combo con la lista de los impuestos.
				$modelImpuesto = ImpuestoForm::findImpuesto();
				$modelNivelAprobacion = New NivelAprobacionForm();
				$listaNivelAprobacion = $modelNivelAprobacion->getListaNivelAprobacion();

				return $this->render('/configuracion/solicitud/create-config-solicitud-form', [
																		'model' => $model,
																		'modelImpuesto' => $modelImpuesto,
																		'listaNivelAprobacion' => $listaNivelAprobacion,
																		'postData' => $postData,
																		'errorEjecutarEn' => $errorEjecutarEn,
					]);
			} else {
				MensajeController::actionMensaje(999, false);
			}
		}



		/***/
		protected function actionBeginSave($postData, $model, $operacion)
		{
			$result = false;
			$idConfigSolicitud = 0;
			if ( isset($postData) && isset($model) ) {

				$conexion = New ConexionController();

				// Instancia de conexion hacia la base de datos.
				$conn = $conexion->initConectar('db');
				$conn->open();

				// Instancia de transaccion. Esto permite realizar el commit o rollBack de la operacion.
				$transaccion = $conn->beginTransaction();

				if ( strtolower(trim($operacion)) == 'create' ) {
					$idConfigSolicitud = self::actionCreateConfigurarSolicitud($postData, $model, $conn, $conexion);
					if ( $idConfigSolicitud > 0 ) {
						// Guardar los procesos que generara la solicitud, de haberlos.
						if ( self::actionCreateProcesoGenerado($postData, $model, $conn, $conexion, $idConfigSolicitud) ) {
							// Guardar los documentos a consignar, de haberlos.
							if ( self::actionCreateDocumento($postData, $model, $conn, $conexion, $idConfigSolicitud) ) {

								$result = true;
							}
						}
					}

					if ( $result == true ) {
						$transaccion->commit();
						return $this->redirect(['proceso-exitoso']);
					} else {
						$transaccion->rollBack();
						return $this->redirect(['error-operacion']);
					}


				} elseif ( strtolower(trim($operacion)) == 'update' ) {

					/*if ( self::actionUpdateCondominio($postData, $model, $conn, $conexion)) {
						$transaccion->commit();
						Session::actionDeleteSession(['postData', 'idCondominio']);
						return $this->redirect(['registro-creado', 'codigoMensaje' => 200]);

					} else {
						$transaccion->rollBack();
						return $this->redirect(['error-operacion']);
					}*/

				}
				$conn->close();
			} else {
				//return self::gestionarMensajesLocales(Yii::t('backend', 'Data for save no detect'));
				MensajeController::actionMensaje(910);
			}
		}




		/***/
		protected function actionCreateConfigurarSolicitud($postData, $model, $connLocal, $conexionLocal)
		{
			$result = 0;
			$tabla = $model->tableName();
			$nombreForm = $model->formName();

			// Lo siguiente obtiene un array de campos que seran tomados para guardarlos del modelo de dato.
			$arregloDatos = $model->attributes;

			// Se filtran los campos y valores que seran guardados en db.
			$request = $postData[$nombreForm];

			// Se pasan los valores enviados desde el form al model para que sean guardados.
			// Se crea un ciclo con los campos del model y si estan en el arregloDatos (post)
			// entonces se pasan al model, aquellos que no aparezacan se les asignara los valores
			// que tengan por defectos o de forma manual.
			foreach ( $arregloDatos as $key => $value ) {
				if ( isset($request[$key]) ) {
					$arregloDatos[$key] = $request[$key];
				}
			}

			if ( date($arregloDatos['fecha_desde']) ) {
				// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
				$arregloDatos['fecha_desde'] = date('Y-m-d', strtotime($arregloDatos['fecha_desde']));
			} else {
				$arregloDatos['fecha_desde'] = '0000-00-00';
			}
			if ( date($arregloDatos['fecha_hasta']) ) {
				// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
				$arregloDatos['fecha_hasta'] = date('Y-m-d', strtotime($arregloDatos['fecha_hasta']));
			} else {
				$arregloDatos['fecha_hasta'] = '0000-00-00';
			}

			$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');

			try {
				if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
					$result = $connLocal->getLastInsertID();
				}
			} catch ( Exception $e ) {
				 echo $e->errorInfo[2];
			}

			return $result;
		}




		/***/
		protected function actionCreateProcesoGenerado($postData, $model, $connLocal, $conexionLocal, $idConfigSolicitud = 0)
		{
			$result = false;
			$modelSolicitudDetalle = New SolicitudDetalleForm();
			$tabla = $modelSolicitudDetalle->tableName();
			$arregloCampo = $modelSolicitudDetalle->attributes;
			$arregloCampo = array_keys($arregloCampo);

//die(var_dump($arregloCampo));
			// Se obtienen los valores seleccionados en el grid de los procesos a generar
			//$arregloProceso = isset($postData['chk-proceso-generado']) ? $postData['chk-proceso-generado'] : null;
			$arregloProceso = isset($postData['combo']) ? $postData['combo'] : null;

			if ( count($arregloProceso) == 0 ) {
				return true;
			}

			foreach ( $arregloProceso as $key => $value ) {
				$arregloDatos[] = [null, $idConfigSolicitud, $key, $value, 0];
			}

//die(var_dump($arregloDatos));
			try {
					if ( $conexionLocal->guardarLoteRegistros($connLocal, $tabla, $arregloCampo, $arregloDatos) ) {
						$result = true;
					}
				} catch ( Exception $e ) {
				 //echo $e->errorInfo[2];
				 die(var_dump($e->errorInfo));
			}

			return $result;
		}



		/***/
		protected function actionCreateDocumento($postData, $model, $connLocal, $conexionLocal, $idConfigSolicitud = 0)
		{
			$result = false;
			$modelSolicitudDocumento = New SolicitudDocumentoForm();
			$tabla = $modelSolicitudDocumento->tableName();
			$arregloCampo = $modelSolicitudDocumento->attributes;
			$arregloCampo = array_keys($arregloCampo);

			// Se obtienen los valores seleccionados en el grid de los documentos a consignar.
			$arregloDocumento = isset($postData['chk-documento-requisito']) ? $postData['chk-documento-requisito'] : null ;

			if ( count($arregloDocumento) == 0 ) {
				return true;
			}

			foreach ( $arregloDocumento as $key => $value ) {
				$arregloDatos[] = [null, $idConfigSolicitud, $value, 0, 0, 0, 0, 0];
			}

			try {
					if ( $conexionLocal->guardarLoteRegistros($connLocal, $tabla, $arregloCampo, $arregloDatos) ) {
						$result = true;
					}
				} catch ( Exception $e ) {
				 //echo $e->errorInfo[2];
			}

			return $result;
		}







    	/**
    	 * [actionQuit description]
    	 * @return [type] [description]
    	 */
    	public function actionQuit()
    	{
    		Session::actionDeleteSession(['postData', 'model']);
    		return $this->render('/configuracion/solicitud/quit');
    	}




    	/***/
    	public function actionProcesoExitoso()
    	{
    		return MensajeController::actionMensaje(100);
    	}


    	/***/
    	public function actionErrorOperacion()
    	{
    		return MensajeController::actionMensaje(920);
    	}




    	/**
    	 * [gestionarMensajesLocales description]
    	 * @param  [type] $mensajeLocal [description]
    	 * @return [type]               [description]
    	 */
    	public function actionGestionarMensajesLocales($codigo, $render = true)
    	{
    		return MensajeController::actionMensaje($codigo, $render);
    	}



	}
?>