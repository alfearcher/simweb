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
 *	@file CorreccionDomicilioFiscalController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2015
 *
 *  @class CorreccionDomicilioFiscalController
 *	@brief Clase CorreccionDomicilioFiscalController
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

 	namespace backend\controllers\aaee\correcciondomicilio;

 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\documentoconsignado\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use backend\controllers\mensaje\MensajeController;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscalForm;
	use backend\models\aaee\correcciondomicilio\CorreccionDomicilioFiscal;

	session_start();

	/**
	 * Class principal
	 */
	class CorreccionDomicilioFiscalController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $connLocal;
		public $conexion;
		public $transaccion;



		public function actionIndex()
		{
			if ( isset($_SESSION['idContribuyente']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$tipoNaturaleza = isset($_SESSION['tipoNaturaleza']) ? $_SESSION['tipoNaturaleza'] : null;
				if ( !$tipoNaturaleza == null ) {
					if ( $tipoNaturaleza == 'NATURAL' || $tipoNaturaleza == 'JURIDICO' )  {

						$model = New CorreccionDomicilioFiscalForm();

						$postData = Yii::$app->request->post();

				  		if ( $model->load($postData) && Yii::$app->request->isAjax ) {
							Yii::$app->response->format = Response::FORMAT_JSON;
							return ActiveForm::validate($model);
				      	}

//die(var_dump($postData));
				      	if ( $model->load($postData) ) {

				      		if ( $model->validate() ) {

				      			if ( $postData['btn-update'] == 1 ) {
				      				$postData['btn-update'] = 2;
					      			$_SESSION['model'] = $model;
					      			$_SESSION['postData'] = $postData;
					      			$datosContribuyente = $_SESSION['datosContribuyente'];

					      			return $this->render('/aaee/correccion-domicilio-fiscal/pre-view', [
				      	 															'model' => $model,
				      	 															'datosContribuyente' => $datosContribuyente,
												      	 							'preView' => true,
												      	 							'postData' => $postData,
											]);
						      	}
				      		}
				      	}

				      	$datosContribuyente = ContribuyenteBase::getDatosContribuyenteSegunID($idContribuyente);
				      	if ( isset($datosContribuyente) ) {
				      		$_SESSION['datosContribuyente'] = $datosContribuyente;

				      		return $this->render('/aaee/correccion-domicilio-fiscal/create', [
		      																'model' => $model,
		      																'datosContribuyente' => $datosContribuyente,
				      				]);
				      	} else {
				      		return self::gestionarMensajesLocales('No se pudo obtener los datos del contribuyente.');
				      	}

					} else {
						return self::gestionarMensajesLocales('El contribuyente no aplica para esta opción.');
					}
				} else {
					return self::gestionarMensajesLocales('El Tipo de Naturaleza del contribuyente no esta definido.');
				}
			} else {
				return self::gestionarMensajesLocales('El Contribuyente no esta definido.');
			}
		}






		/**
		 * [actionCreate description]
		 * @param  boolean $guardar [description]
		 * @return [type]           [description]
		 */
		public function actionCreate($guardar = false)
		{
			if ( $guardar == true ) {
				if ( isset($_SESSION['postData']) ) {
					$postData = $_SESSION['postData'];
					if ( $postData['btn-update'] == 2 ) {
						// Indica que el envio del formulario es correcto.
						if ( $_SESSION['idContribuyente'] ) {
							$idContribuyente = $_SESSION['idContribuyente'];

							$conexion = New ConexionController();

		      				// Instancia de conexion hacia la base de datos.
		      				$this->connLocal = $conexion->initConectar('db');
		      				$this->connLocal->open();

		      				$todoBien = false;

		      				$model = isset($_SESSION['model']) ? $_SESSION['model'] : null;
		      				$postData = isset($_SESSION['postData']) ? $_SESSION['postData'] : null;

		      				// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
		      				// Inicio de la transaccion.
							$transaccion = $this->connLocal->beginTransaction();

							// El metodo debe retornar un booleano.
							if ( self::actionCreateCorreccionDomicilioFiscal($idContribuyente, $model, $postData, $conexion, $this->connLocal) ) {
								if ( self::actionActualizarDomicilioFiscal($idContribuyente, $model, $postData, $conexion, $this->connLocal) ) {
									if ( self::actionCreateDocumentosConsignados($conexion, $this->connLocal, $idContribuyente) ) {
										$todoBien = true;
									}
								}
							}


							if ( $todoBien ) {

								//unset($_SESSION['datosContribuyente']);
								$transaccion->commit();
								$tipoError = 0;	// No error.
								$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
								if ( isset($_SESSION['idCorreccion']) ) { $idCorreccion = $_SESSION['idCorreccion']; }
								$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correcciondomicilio/correccion-domicilio-fiscal/view','idCorreccion' => $idCorreccion])."'>";
								//$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correcciondomicilio/correccion-domicilio-fiscal/view-ok'])."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

							} else {

								$transaccion->rollBack();
								$tipoError = 1; // Error.
								$msg = "AH ERROR OCCURRED!....WAIT";
								$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/correcciondomicilio/correccion-domicilio-fiscal/index")."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
							}

						} else {
							return self::gestionarMensajesLocales('El Contribuyente no esta definido.');
						}
					} else {
						return self::gestionarMensajesLocales('La solicitud de modificación no es valida.');
					}
				} else {
					return self::gestionarMensajesLocales('La solicitud de modificación no es valida.');
				}
			} else {
				return self::gestionarMensajesLocales('La solicitud de modificación no es valida.');
			}
		}




		/**
		 * Metodo que inserta un registro nuevo en laentidad respectiva.
		 * @param  [type] $model     [description]
		 * @param  [type] $postData  [description]
		 * @param  [type] $conexion  [description]
		 * @param  [type] $connLocal [description]
		 * @return retorna un boolean, true si se inserta el registro, false sino logra insertar el registro.
		 */
		private function actionCreateCorreccionDomicilioFiscal($idContribuyente, $model, $postData, $conexion, $connLocal)
		{
			$result = false;
			$idCorreccion = 0;
			if ( isset($postData) ) {
				if ( isset($model) ) {
					if ( isset($conexion) ) {
						$tabla = $model->tableName();
						$nombreForm = $model->formName();
						$arregloDatos = $model->attributes;
						$request = $postData[$nombreForm];

						foreach ( $arregloDatos as $key => $value ) {
							if ( isset($request[$key]) ) {
								$arregloDatos[$key] = $request[$key];
							}
						}

						$arregloDatos['id_contribuyente'] = $idContribuyente;
						$arregloDatos['domicilio_fiscal_new'] = $model->domicilio_fiscal_new;
						$arregloDatos['nro_solicitud'] = 0;
						$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
						$arregloDatos['usuario'] = Yii::$app->user->identity->username;
						$arregloDatos['estatus'] = 1;
						$arregloDatos['origen'] = 'LAN';

						if ( $conexion->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
							$idCorreccion = $connLocal->getLastInsertID();
							if ( $idCorreccion > 0 ) {
								$_SESSION['idCorreccion'] = $idCorreccion;
								$result = true;
							} else {
								$result = false;
							}
						}
					}
				}
			}
			return $result;
		}




		/**
		 * Metodo que actualiza el domicilio fiscal del contribuyente en la entidad respectiva.
		 * @param  [type] $model     [description]
		 * @param  [type] $postData  [description]
		 * @param  [type] $conexion  [description]
		 * @param  [type] $connLocal [description]
		 * @return retorna un boolean, true si es satisfactorio, false sino actualiza.
		 */
		private function actionActualizarDomicilioFiscal($idContribuyente, $model, $postData, $conexion, $connLocal)
		{
			$result = false;
			if ( $conexion ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					if ( isset($_SESSION['datosContribuyente']) ) {
						$datosContribuyente = $_SESSION['datosContribuyente'];
						if ( $model->id_contribuyente == $_SESSION['idContribuyente'] &&
							$datosContribuyente[0]['id_contribuyente'] == $_SESSION['idContribuyente']) {

							$tabla = 'contribuyentes';
							$nombreForm = $model->formName();
							$request = $postData[$nombreForm];

							// Domicilio Fiscal nuevo.
							$arregloDatos['domicilio_fiscal'] = $model->domicilio_fiscal_new;

							if ( $idContribuyente > 0 ) {
								$arrayCondicion['id_contribuyente'] = $idContribuyente;
								if ( $conexion->modificarRegistro($connLocal, $tabla, $arregloDatos, $arrayCondicion) ) {
									$result = true;
								} else {
									$result = false;
									break;
								}
							}
						}
					}
				}
			}
			return $result;
		}





		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  [type]  $conexion                [description]
		 * @param  [type]  $connLocal               [description]
		 * @param  integer $idContribuyenteGenerado [description]
		 * @return [type]                           [description]
		 */
		private static function actionCreateDocumentosConsignados($conexion, $connLocal, $idContribuyente = 0)
		{
			$result = false;
			if ( $idContribuyente > 0 ) {
				if ( isset($conexion) ) {
					if ( isset($_SESSION['postData']) ) {

						$seleccion = [];
						$postData = $_SESSION['postData'];

						if ( isset($postData['selection']) ) {
							$modelDocumento = new DocumentoConsignadoForm();

							$tabla = '';
			      			$tabla = $modelDocumento->tableName();

							$arregloDatos = $modelDocumento->attributes;

							$arregloDatos['id_contribuyente'] = $idContribuyente;
							$arregloDatos['nro_solicitud'] = 0;
							$arregloDatos['id_impuesto'] = 0;
							$arregloDatos['impuesto'] = 1;
							$arregloDatos['codigo_proceso'] = 'CORRECCION-DOMICILIO-FISCAL';
							$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
							$arregloDatos['estatus'] = 1;
							$arregloDatos['usuario'] = Yii::$app->user->identity->username;

			  				$seleccion = $postData['selection'];
			  				//die(var_dump($seleccion));
			  				foreach ( $seleccion as $key => $value ) {
			  					$arregloDatos['id_documento'] = $seleccion[$key];

			  					if ( $conexion->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
									$result = true;
								} else {
									$result = false;
									break;
								}
			  				}
				  		} else {
				  			$result = true;
				  		}
					}
				}
			}
			return $result;
		}




		/**
		 * [actionView description]
		 * @return [type] [description]
		 */
		public function actionView($idCorreccion)
		{
			if ( isset($_SESSION['idCorreccion']) ) {
    			if ( $_SESSION['idCorreccion'] == $idCorreccion ) {
    				$model = $this->findModel($idCorreccion);
    				if ( $_SESSION['idCorreccion'] == $model->id_correccion ) {
    					$postData = $_SESSION['postData'];
    					$datosContribuyente = $_SESSION['datosContribuyente'];
    					unset($_SESSION['postData'], $_SESSION['datosContribuyente'], $_SESSION['model'] );
			        	return $this->render('/aaee/correccion-domicilio-fiscal/pre-view',[
															        				'model' => $model,
															        				'preView' => false,
															        				'postData' => $postData,
															        				'datosContribuyente' => $datosContribuyente,

			        			]);
			        } else {
			        	return self::gestionarMensajesLocales('Numero de Inscripcion no valido.');
			        	//echo 'Numero de Inscripcion no valido.';
			        }
	        	} else {
	        		return self::gestionarMensajesLocales('Numero de Inscription no valido.');
	        		// echo 'Numero de Inscription no valido.';
	        	}
        	}
		}





		/**
		*	Metodo que busca el ultimo registro creado.
		* 	@param $idInscripcion, long que identifica el autonumerico generado al crear el registro.
		*/
		protected function findModel($idCorreccion)
    	{
        	if (($model = CorreccionDomicilioFiscal::findOne($idCorreccion)) !== null) {
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
    		unset($_SESSION['idCorreccion']);
    		unset($_SESSION['datosContribuyente']);
    		unset($_SESSION['model']);
    		unset($_SESSION['postData']);
    		return $this->render('/aaee/correccion-domicilio-fiscal/quit');
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



	}

?>