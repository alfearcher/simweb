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
 *	@file AutorizarRamoController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 15-10-2015
 *
 *  @class AutorizarRamoController
 *	@brief Clase AutorizarRamoController, aprobacion de rubros
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


 	namespace backend\controllers\aaee\autorizarramo;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\autorizarramo\AutorizarRamo;
	use backend\models\aaee\autorizarramo\AutorizarRamoForm;
	use backend\models\aaee\autorizarramo\BusquedaRubroForm;
	use backend\models\aaee\actecon\ActEconForm;
	use backend\models\aaee\acteconingreso\ActEconIngresoForm;
	use backend\controllers\mensaje\MensajeController;


	session_start();		// Iniciando session

	/**
	 *
	 */
	class AutorizarRamoController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $connLocal;
		public $conexion;
		public $transaccion;




		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$tipoNaturaleza = isset($_SESSION['tipoNaturaleza']) ? $_SESSION['tipoNaturaleza'] : null;
			if ( $tipoNaturaleza == 'JURIDICO') {
				if ( isset($_SESSION['idContribuyente']) ) {
					$model = New AutorizarRamoForm();
					$modelSearch = New BusquedaRubroForm();
					//$dataProvider = $model->getProviderRubro();

					// Se controla que el contribuyente no tenga declaraciones cargadas.
					if ( !AutorizarRamoForm::tieneRecordActEcon($_SESSION['idContribuyente']) ) {

						$postData = Yii::$app->request->post();
						$request = Yii::$app->request;

				  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
							Yii::$app->response->format = Response::FORMAT_JSON;
							return ActiveForm::validate($model);
				      	}

				      	if ( $model->load($postData) ) {

				      	 	if ( $model->validate() ) {

				      	 		$_SESSION['model'] = $model;
				      	 		$_SESSION['postData'] = $postData;

				      	 		// Se redirecciona a una preview para confirmar la creacion del registro.

				      	 		return $this->render('/aaee/autorizar-ramo/pre-view', [
			      	 																	'model' => $datosContribuyente,
											      	 									'preView' => true
											      	 									]);
				      	 	} else {
				      	 		$model->getErrors();
				      	 	}
				  		}


				  		$datos = ContribuyenteBase::getDatosContribuyenteSegunID($_SESSION['idContribuyente']);
				  		if ( $datos ) {
				  			$_SESSION['datos'] = $datos;
				  			if ( isset($datos[0]['fecha_inicio']) ) {
				  				$anoInicio = date('Y', strtotime($datos[0]['fecha_inicio']));
				  				$anoCatalogo = $model->determinarAnoCatalogoSegunAnoInicio($anoInicio);

								if ( isset($_SESSION['model']) ) { $model = $_SESSION['model']; }

					  			return $this->render('/aaee/autorizar-ramo/create', ['model' => $model,
					  																 'anoCatalogo' => $anoCatalogo,
					  																 'datos' => $datos,
					  																 'modelSearch' => $modelSearch]);
				  			} else {
				  				return self::gestionarMensajesLocales('No posee la fecha de inicio de actividades.');
				  			}
						} else {
							return self::gestionarMensajesLocales('No se pudo recuperar la información del contribuyente.');
						}
					} else {
						return self::gestionarMensajesLocales('El contribuyente no aplica para esta opcion. Ya posee rubros asignados.');
					}
		  		} else {
		  			return self::gestionarMensajesLocales('No esta definido el contribuyente');
		  		}
	  		} else {
	  			return self::gestionarMensajesLocales('Contribuyente no aplica para esta opción.');
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
		 * 	Metodo que guarda el registro respectivo
		 * 	@return renderiza una vista final de la informacion a guardar.
		 */
		public function actionCreate($guardar = false)
		{
			if ( $_SESSION['idContribuyente'] ) {
				if ( $guardar == true ) {
					if ( isset($_SESSION['datosContribuyente']) ) {

		      			$conexion = New ConexionController();

		      			// Instancia de conexion hacia la base de datos.
		      			$this->connLocal = $conexion->initConectar('db');
		      			$this->connLocal->open();

		      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
		      			// Inicio de la transaccion.
						$transaccion = $this->connLocal->beginTransaction();

						// El metodo debe retornar un id contribuyente.
						$idContribuyenteGenerado = self::actionCreateContribuyente($conexion, $this->connLocal);
						if ( $idContribuyenteGenerado > 0 ) {

							if ( self::actionCreateActividadEconomica($conexion, $this->connLocal, $idContribuyenteGenerado) ) {

								$idInscripcion = self::actionCreateSucursal($conexion, $this->connLocal, $idContribuyenteGenerado);
								if ( $idInscripcion > 0 ) {
									if ( self::actionCreateDocumentosConsignados($conexion, $this->connLocal, $idContribuyenteGenerado) ) {
										$transaccion->commit();
										$tipoError = 0;	// No error.
										$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
										$_SESSION['idInscripcion'] = $idInscripcion;
										$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/inscripcionsucursal/inscripcion-sucursal/view','idInscripcion' => $idInscripcion])."'>";
										return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

									} else {
										$transaccion->rollBack();
										$tipoError = 1; // Error.
										$msg = "AH ERROR OCCURRED!....WAIT";
										$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/inscripcionsucursal/view")."'>";
										return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
									}
								} else {
									$transaccion->rollBack();
									$tipoError = 1; // Error.
									$msg = "AH ERROR OCCURRED!....WAIT";
									$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/inscripcionsucursal/view")."'>";
									return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

								}
							} else {
								$transaccion->rollBack();
								$tipoError = 1; // Error.
								$msg = "AH ERROR OCCURRED!....WAIT";
								$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/inscripcionsucursal/view")."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

							}

						} else {
							$transaccion->rollBack();
							$tipoError = 1; // Error.
							$msg = "AH ERROR OCCURRED!....WAIT";
								$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/inscripcionsucursal/view")."'>";
							return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
						}
						$this->connLocal->close();
					} else {
						// No esta definido el modelo.
						die('No esta definido el modelo');
					}
				}
			} else {
				echo 'No esta definido el contribuyente';
			}
		}





		/**
		*	Metodo muestra la vista con la informacion que fue guardada.
		*/
		public function actionView($idInscripcion)
    	{
    		if ( isset($_SESSION['idInscripcion']) ) {
    			if ( $_SESSION['idInscripcion'] == $idInscripcion ) {
    				$model = $this->findModel($idInscripcion);
    				if ( $_SESSION['idInscripcion'] == $model->id_inscripcion_sucursal ) {
			        	return $this->render('/aaee/inscripcion-sucursal/pre-view',
			        			['model' => $model, 'preView' => false,

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
		protected function findModel($idInscripcion)
    	{
        	if (($model = InscripcionSucursal::findOne($idInscripcion)) !== null) {
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
    		unset($_SESSION['idInscripcion']);
    		return $this->render('/aaee/inscripcion-sucursal/quit');
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
    		$dataProvider = $model->searchRubro($anoImpositivo, $params, $request);

    		if ( Yii::$app->request->isGet ) {
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
    		if ( Yii::$app->request->isGet ) {
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
		    			$modelRamo = New AutorizarRamoForm();
		    			$dataProvider = $modelRamo->getAddRubro($_SESSION['arrayIdRubros']);

		    			return $this->renderAjax('/aaee/autorizar-ramo/create-add-rubro-lista', [
		    																					'modelRamo' => $modelRamo,
		    																					'dataProvider' => $dataProvider
		    																					]);
		    		}
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
    	 * Metodo que guarda en
    	 * @param  [type] $conexion  [description]
    	 * @param  [type] $connLocal [description]
    	 * @return [type]            [description]
    	 */
    	private static function actionCreateRamosAutorizados($conexion, $connLocal)
    	{
    		if ( isset($_SESSION['datosRamos']) ) {
    			$modelRamo = New AutorizarRamoForm();

    			$arrayDatos = $_SESSION['datosRamos'];

    			$arrayDatos['fecha_inclusion'] = date('Y-m-d');

				// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
				$arrayDatos['fecha_inicio'] = date('Y-m-d', strtotime($arrayDatos['fecha_inicio']));

				// Se procede a guardar primero en la entidad donde se guardan los rubros autorizados.
      			$tabla = '';
      			$tabla = $modelRamo->tableName();

				if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
					return true;
				}
    		}
    		return false;
    	}







    	private static function actionCreateActEcon($conexion, $connLocal)
    	{
    		if ( isset($_SESSION['datosRamos']) ) {

	    		$modelActEcon = New ActEconForm();

	    		$arrayDatos = $modelActEcon->attribute;
	    		foreach ($arrayDatos as $key => $value) {
	    			$arrayDatos[$value] = 0;
	    		}
	    		$arrayDatos['ente'] = Yii::$app->ente->getEnte();
	    		$arrayDatos['id_contribuyente'] = $datosRamos['ano_impositivo'];
	    		$arrayDatos['ano_impositivo'] = $datosRamos['ano_impositivo'];
	    		$arrayDatos['exigibilidad_declaracion'] = 0;

	    		// Se procede a guardar en la entidad maestra de las declaraciones.
      			$tabla = '';
      			$tabla = $modelActEcon->tableName();

				if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
					$idImpuesto = 0;
					return $idImpuesto = $connLocal->getLastInsertID();
				}
	    	}
	    	return false;
	    }






	    private static function actionCreateActEconIngresos($conexion, $connLocal, $idImpuesto)
	    {
	    	if ( isset($_SESSION['datosRamos']) ) {
	    		$modelActEconIngreso = New ActEconIngresoForm();

	    		$arrayDatos = $modelActEconIngreso->attribute;
	    		foreach ($arrayDatos as $key => $value) {
	    			$arrayDatos[$value] = 0;
	    		}
	    		$arrayDatos['id_impuesto'] = $idImpuesto;
	    		$arrayDatos['exigibilidad_periodo'] = 1;
	    		$arrayDatos['periodo_fiscal_desde'] = null;
	    		$arrayDatos['periodo_fiscal_hasta'] = null;

	    		// Se procede a guardar en la entidad maestra de las declaraciones.
      			$tabla = '';
      			$tabla = $modelActEcon->tableName();

				if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
					return true;
				}
	    	}
	    	return false;
	    }
	}
?>