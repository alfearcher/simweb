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
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common○\models\session\Session;
	use backend\models\documentoconsignado\DocumentoConsignadoForm;
	use backend\models\configuracion\solicitud\ConfigurarSolicitudForm;

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
		public function actionCreate()
		{
			if ( Session::existeUser() ) {

				$model = New ConfigurarSolicitudForm();
				$request = Yii::$app->request;
				$postData = $request->post();

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ( $model->load($postData) ) {
					if ( $model->validate() ) {

						if ( $postData['btn-create'] == 1 ) {
							$postData['btn-create'] = 2;

							$_SESSION['postData'] = $postData;
							//$urlPost = '/condominio/condominio/create';

							return $this->render('/condominio/_pre-view',[
																	'postData' => $postData,
																	'model' => $model,
																	'estoyCreate' => true,
																	'estoyUpdate' => false,
																	'urlPost' => $urlPost,
										]);
						} elseif ( $puedoCreate && isset($postData) ) {
							// Se guardan los datos
							self::actionBeginSave($postData, $model, 'create');
						}
					}
				}

				return $this->render('/configuracion/solicitud/create-config-solicitud-form', [
																		'model' => $model,
					]);
			} else {
				MensajeController::actionMensaje(999, false);
			}
		}





		/**
		*	Metodo muestra la vista con la informacion que fue guardada.
		*/
		public function actionView($idImpuesto)
    	{
    		if ( isset($_SESSION['idImpuesto']) ) {
    			if ( $_SESSION['idImpuesto'] == $idImpuesto ) {
    				$model = $this->findModel($idImpuesto);
    				if ( $_SESSION['idImpuesto'] == $model->id_impuesto ) {
			        	return $this->render('/aaee/autorizar-ramo/view',
			        			['model' => $model,

			        			]);
			        } else {
			        	echo 'Numero de Inscripcion no valido.';
			        }
	        	} else {
	        		echo 'Numero de Inscription no valido.';
	        	}
        	}
    	}




		/**
		*	Metodo que busca el ultimo registro creado.
		* 	@param $idInscripcion, long que identifica el autonumerico generado al crear el registro.
		*/
		protected function findModel($idImpuesto)
    	{
        	if (($model = ActEconIngresoForm::findOne($idImpuesto)) !== null) {
            	return $model;
        	} else {
            	throw new NotFoundHttpException('The requested page does not exist.');
        	}
    	}





    	/**
    	 * [actionQuit description]
    	 * @return [type] [description]
    	 */
    	public function actionQuit()
    	{
    		unset($_SESSION['idImpuesto']);
    		unset($_SESSION['idContribuyente']);
    		unset($_SESSION['datosContribuyente']);
    		unset($_SESSION['anoImpositivo']);
    		return $this->render('/aaee/autorizar-ramo/quit');
    	}





    	/**
    	 * Metodo que permite renderizar una vista con el catalogo de rubros que el usuario consulte.
    	 * @param  $anoImpositivo, integer año del catalogo de rubros, este es determinado por el año de inicio
    	 * y los años iniciales y finales del catalogo de rubros.
    	 * @param string que permite realizar busquedas personalizadas en el catalogo de rubros, permite localizar
    	 * rubros por descripcion especificas o por el codigo del o los rubros. Para realizar busquedas por varios
    	 * codigos de rubros a la vez, se tienen que separar cada codigo con una coma (,), ejemplo: 201, 302, 5044.
    	 * @return reurna una vista con los rubros localizados y un boton para adicionarlos a una lista de rubros
    	 * seleccionados.
    	 */
    	public function actionListaRubros($anoImpositivo, $params = '')
    	{
    		// Se busca primero el dataProvider.
    		$request = Yii::$app->request->queryParams;
    		$modelSearch = New BusquedaRubroForm();
    		$model = New AutorizarRamoForm();
    		$dataProvider = $model->searchRubro($anoImpositivo, $params);
//die(var_dump($dataProvider));
    		if ( Yii::$app->request->isGet ) {
    			if ( isset($dataProvider) && !isset($_SESSION['anoImpositivo']) ) {
    				$_SESSION['anoImpositivo'] = $anoImpositivo;
    			}
    			if ( isset($request['page']) ) {
    				$model->load($request);
    				return $this->renderAjax('/aaee/autorizar-ramo/create-lista-rubro', [
    			 																	'model' => $model,
    			 																	'dataProvider' => $dataProvider,
    			 																	]);
    			} else {
    				return $this->renderAjax('/aaee/autorizar-ramo/create-lista-rubro', [
    																				'model' => $model,
    																				'dataProvider' => $dataProvider
    																				]);
    			}
    		}
    	}






    	/**
    	 * Metodo que permite adicional un rubro en una lista de rubros para autorizar
    	 * y renderiza la vista.
    	 * @param $idRubro, long que identifica al rubro seleccionado, es una autonumerico
    	 * de una tabla.
    	 * @return retona una vista con el rubro agregado en un gridview.
    	 */
    	public function actionAddRubro($idRubro)
    	{
    		if ( $idRubro > 0 ) {
	    		if ( !isset($_SESSION['arrayIdRubros']) ) {
	    			$_SESSION['arrayIdRubros'][] = $idRubro;
	    		} else {
	    			$arrayIdRurbros = [];
	    			$arrayIdRurbros = $_SESSION['arrayIdRubros'];
	    			if ( !in_array($idRubro, $arrayIdRurbros) ) {
	    				$_SESSION['arrayIdRubros'][] = $idRubro;
	    			} else {
	    				// ya existe'
	    			}
	    		}
	    		$modelRamo = New AutorizarRamoForm();
	    		$dataProvider = $modelRamo->getAddRubro($_SESSION['arrayIdRubros']);
	    		if ( Yii::$app->request->isGet ) {
	    			return $this->renderAjax('/aaee/autorizar-ramo/create-add-rubro-lista', [
	    																					'modelRamo' => $modelRamo,
	    																					'dataProvider' => $dataProvider
	    																					]);
	    		}
	    	}
    	}






    	/**
    	 * [actionRemoveRubro description]
    	 * @param  [type] $idRubro [description]
    	 * @return [type]          [description]
    	 */
    	public function actionRemoveRubro($idRubro)
    	{
    		if ( Yii::$app->request->isGet ) {
	    		if ( $idRubro > 0 ) {
		    		if ( !isset($_SESSION['arrayIdRubros']) ) {
		    			// NO esta definido los rubros en la lista.
		    			// Abortar remocion.
		    		} else {

		    			// Lista de id-rubro despues de suprimir el $idRubro indicado.
		    			$arrayIdRubrosActualizado = [];

		    			$arrayIdRubros = [];
		    			$arrayIdRubros = $_SESSION['arrayIdRubros'];
		    			foreach ( $arrayIdRubros as $key => $value ) {
		    				if ( $arrayIdRubros[$key] != $idRubro ) {
		    					$arrayIdRubrosActualizado[$key] = $value;
		    				}
		    			}

		    			$modelRamo = New AutorizarRamoForm();
		    			if ( count($arrayIdRubrosActualizado) > 0 ) {
		    				unset($_SESSION['arrayIdRubros']);
		    				//session_destroy($_SESSION['arrayIdRubros']);

		    				foreach ($arrayIdRubrosActualizado as $key => $value) {
		    					$_SESSION['arrayIdRubros'][] = $arrayIdRubrosActualizado[$key];
		    				}

			    			$dataProvider = $modelRamo->getAddRubro($_SESSION['arrayIdRubros']);

		    			} elseif ( count($arrayIdRubrosActualizado) == 0 ) {
		    				unset($_SESSION['arrayIdRubros']);
		    				$dataProvider = $modelRamo->getAddRubro(['-1']);

		    			}

			    			return $this->renderAjax('/aaee/autorizar-ramo/create-add-rubro-lista', [
			    																				'modelRamo' => $modelRamo,
			    																				'dataProvider' => $dataProvider
			    																				]);
		    		}
		    	} else {
		    		// Rubro no definido
		    	}
		    }
    	}





    	/**
    	 * [gestionarMensajesLocales description]
    	 * @param  [type] $mensajeLocal [description]
    	 * @return [type]               [description]
    	 */
    	public function gestionarMensajesLocales($mensajeLocal)
    	{
    		if ( trim($mensajeLocal) != '' ) {
    			return MensajeController::actionMensaje($mensajeLocal);
    		}
    	}




    	/**
    	 * Metodo que guarda en
    	 * @param  [type] $conexion  [description]
    	 * @param  [type] $connLocal [description]
    	 * @return [type]            [description]
    	 */
    	private static function actionCreateRamosAutorizados($conexion, $connLocal, $postData)
    	{
    		if ( isset($_SESSION['idContribuyente']) && isset($connLocal) ) {
    			//$datosContribuyente = $_SESSION['datosContribuyente'];
    			if ( $_SESSION['idContribuyente'] == $postData['id-contribuyente'] ) {

	    			$modelAutorizarRamo = New AutorizarRamoForm();

	    			$arrayDatos = $modelAutorizarRamo->attributes;

	    			$arrayDatos['nro_solicitud'] = 0;
	    			$arrayDatos['id_contribuyente'] = $_SESSION['idContribuyente'];
	    			$arrayDatos['fecha_inicio'] = date('Y-m-d', strtotime($postData['fecha-inicio']));
	    			$arrayDatos['ano_impositivo'] = $postData['ano-catalogo'];
	    			$arrayDatos['id_rubro'] = 0;
	    			$arrayDatos['fecha_hora'] = date('Y-m-d H:i:s');
	    			$arrayDatos['usuario'] = Yii::$app->user->identity->username;
	    			$arrayDatos['estatus'] = 0;
	    			$arrayDatos['origen'] = 'LAN';

					// Se procede a guardar primero en la entidad donde se guardan los rubros autorizados.
	      			$tabla = '';
	      			$tabla = $modelAutorizarRamo->tableName();

	      			$todoBien = false;
	      			// Se pasan los id de los rubros incluidos a un arreglo.
	      			$arrayIdRubros = $_SESSION['arrayIdRubros'];
	      			if ( count($arrayIdRubros) > 0 ) {

		      			foreach ($arrayIdRubros as $key => $value) {
		      				$arrayDatos['id_rubro'] = $value;
		      				if ( !$conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
		      					$todoBien = false;
		      					break;
							} else {
								$todoBien = true;
							}
		      			}
		      		}
	      		}
    		}
    		return $todoBien;
    	}






    	/**
    	 * [actionCreateActEcon description]
    	 * @param  [type] $conexion  [description]
    	 * @param  [type] $connLocal [description]
    	 * @return [type]            [description]
    	 */
    	private static function actionCreateActEcon($conexion, $connLocal, $postData)
    	{
    		if ( isset($_SESSION['idContribuyente'])  && isset($connLocal)  ) {
    			if ( $_SESSION['idContribuyente'] == $postData['id-contribuyente'] ) {
    				//$datosContribuyente = $_SESSION['datosContribuyente'];
	    			$modelActEcon = New ActEconForm();

	    			$arrayDatos = $modelActEcon->attributes;
	    			foreach ($arrayDatos as $key => $value) {
	    				$arrayDatos[$key] = 0;
	    			}
		    		$arrayDatos['ente'] = Yii::$app->ente->getEnte();
		    		$arrayDatos['id_contribuyente'] = $postData['id-contribuyente'];
		    		$arrayDatos['ano_impositivo'] = $postData['ano-catalogo'];
		    		$arrayDatos['exigibilidad_declaracion'] = 1;

		    		// Se procede a guardar en la entidad maestra de las declaraciones.
	      			$tabla = '';
	      			$tabla = $modelActEcon->tableName();

					if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
						$idImpuesto = 0;
						return $idImpuesto = $connLocal->getLastInsertID();
					}
		    	}
		    }
	    	return false;
	    }





	    /**
	     * [actionCreateActEconIngresos description]
	     * @param  [type] $conexion   [description]
	     * @param  [type] $connLocal  [description]
	     * @param  [type] $idImpuesto [description]
	     * @return [type]             [description]
	     */
	    private static function actionCreateActEconIngresos($conexion, $connLocal, $idImpuesto)
	    {
	    	if ( isset($_SESSION['idContribuyente']) && isset($connLocal) ) {
	    		if ( $idImpuesto > 0 ) {

		    		$arrayIdRubros = $_SESSION['arrayIdRubros'];
		    		if ( count($arrayIdRubros) > 0 ) {

			    		$modelActEconIngreso = New ActEconIngresoForm();
			    		$arrayDatos = $modelActEconIngreso->attributes;

			    		foreach ($arrayDatos as $key => $value) {
			    			$arrayDatos[$key] = 0;
			    		}
			    		$arrayDatos['id_impuesto'] = $idImpuesto;
			    		$arrayDatos['exigibilidad_periodo'] = 1;
			    		$arrayDatos['periodo_fiscal_desde'] = null;
			    		$arrayDatos['periodo_fiscal_hasta'] = null;

			    		// Se procede a guardar en la entidad maestra de las declaraciones.
		      			$tabla = '';
		      			$tabla = $modelActEconIngreso->tableName();
		      			$todoBien = false;

		      			foreach ($arrayIdRubros as $key => $value) {
		      				$arrayDatos['id_rubro'] = $arrayIdRubros[$key];
		      				if ( !$conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
								$todoBien = false;
								break;
							} else {
								$todoBien = true;
							}
		      			}
		      		}
	      		}
	    	}
	    	return $todoBien;
	    }





	    /**
	     * [actionCreateDocumentosConsignados description]
	     * @param  [type]  $conexion                [description]
	     * @param  [type]  $connLocal               [description]
	     * @param  integer $idContribuyenteGenerado [description]
	     * @return [type]                           [description]
	     */
	    private static function actionCreateDocumentosConsignados($conexion, $connLocal, $postData, $idImpuesto)
		{
			if ( isset($_SESSION['idContribuyente']) && isset($connLocal) ) {
				if ( $_SESSION['idContribuyente'] == $postData['id-contribuyente'] ) {
					if ( isset($conexion) ) {
						if ( isset($postData) ) {

							$seleccion = [];
							//$postData = $_SESSION['postData'];

							if ( isset($postData['selection']) ) {
								$modelDocumento = new DocumentoConsignadoForm();

								$tabla = '';
				      			$tabla = $modelDocumento->tableName();

								$arregloDatos = $modelDocumento->attributes;

								$arregloDatos['id_contribuyente'] = $postData['id-contribuyente'];
								$arregloDatos['nro_solicitud'] = 0;
								$arregloDatos['id_impuesto'] = $idImpuesto;
								$arregloDatos['impuesto'] = 1;
								$arregloDatos['codigo_proceso'] = 'AUTORIZAR-RAMO';
								$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
								$arregloDatos['estatus'] = 0;
								$arregloDatos['usuario'] = Yii::$app->user->identity->username;

								if ( isset($postData['selection']) ) {
					  				$seleccion = $postData['selection'];
					  				//die(var_dump($seleccion));
					  				foreach ( $seleccion as $key => $value ) {
					  					$arregloDatos['id_documento'] = $seleccion[$key];

					  					if ( !$conexion->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
											return false;
										}
					  				}
					  				return true;
					  			}
					  		} else {
					  			return true;
					  		}
						}
					}
				}
			}
			return false;
		}
	}
?>