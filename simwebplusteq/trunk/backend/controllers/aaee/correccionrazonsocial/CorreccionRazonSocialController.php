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
 *	@file CorreccionRazonSocialController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 17-11-2015
 *
 *  @class CorreccionRazonSocialController
 *	@brief Clase CorreccionRazonSocialController
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


 	namespace backend\controllers\aaee\correccionrazonsocial;

 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	//use backend\models\documentoconsignado\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use yii\base\Model;
	use backend\controllers\mensaje\MensajeController;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocialForm;
	use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocial;


	session_start();


	class CorreccionRazonSocialController extends Controller
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
					if ( $tipoNaturaleza == 'JURIDICO') {

						$model = New CorreccionRazonSocialForm();
						$msjErrorLista = '';

						$postData = Yii::$app->request->post();

				  		if ( $model->load($postData) && Yii::$app->request->isAjax ) {
							Yii::$app->response->format = Response::FORMAT_JSON;
							return ActiveForm::validate($model);
				      	}

				      	if ( $model->load($postData) ) {

				      		if ( $model->validate() ) {

				      			if ( isset($postData['selection']) ) {

					      			if ($postData['btn-update'] == 1 ) {
					      				// Lista de sucursales (de existir) para mostrar en el pre-view,
					      				// solo se mostraran las sucursales tildadas en el formulario.
					      				$dataProvider = $model->getDataProviderSucursalesSegunId($postData['selection']);
					      				$postData['btn-update'] = 2;
						      			$_SESSION['model'] = $model;
						      			$_SESSION['postData'] = $postData;
						      			$datosContribuyente = $_SESSION['datosContribuyente'];
						      			$_SESSION['dataProvider'] = $dataProvider;

						      			return $this->render('/aaee/correccion-razon-social/pre-view', [
					      	 															'model' => $model,
					      	 															'datosContribuyente' => $datosContribuyente,
													      	 							'preView' => true,
													      	 							'postData' => $postData,
													      	 							'dataProvider' => $dataProvider,
												]);
						      		}
						      	} else {
						      		$msjErrorLista = Yii::t('backend', 'No items selected.');
						      	}
				      		}
				      	}

				      	$datosContribuyente = ContribuyenteBase::getDatosContribuyenteSegunID($idContribuyente);
				      	if ( isset($datosContribuyente) ) {
				      		$_SESSION['datosContribuyente'] = $datosContribuyente;

				      		$naturaleza = $datosContribuyente[0]['naturaleza'];
				      		$cedula = $datosContribuyente[0]['cedula'];
				      		$tipo = $datosContribuyente[0]['tipo'];

				      		if ( isset($_SESSION['dataProvider']) ) {
				      			$_SESSION['dataProvider'] = false;
				      		}

				      		// Se buscan a los contribuyentes que tengan el mismo rif.
				      		$dataProvider = $model->getDataProviderSucursalesSegunRif($naturaleza, $cedula, $tipo, 1);
							if ( $dataProvider ) {

					      		return $this->render('/aaee/correccion-razon-social/create', [
			      																	'model' => $model,
			      																	'datosContribuyente' => $datosContribuyente,
			      																	'dataProvider' => $dataProvider,
			      																	'msjErrorLista' => $msjErrorLista,
					      			]);
					      	} else {
					      		return self::gestionarMensajesLocales('No se pudo obtener el proveedor de datos.');
					      	}
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

							$conexion = New ConexionController();

		      				// Instancia de conexion hacia la base de datos.
		      				$this->connLocal = $conexion->initConectar('db');
		      				$this->connLocal->open();

		      				$todoBien = false;

		      				// La siguiente variable controla la necesidad de actualizar la informacion
		      				// que aparece en la barra del menu, si el contribuyente que aparece en la
		      				// barra el menu, esta dentro de los contribuyente que se van a actualizar
		      				// la descripcion del mismo en el menu debe ser modificada.
		      				$actualizarBarraMenu =  false;

		      				$model = isset($_SESSION['model']) ? $_SESSION['model'] : null;
		      				$postData = isset($_SESSION['postData']) ? $_SESSION['postData'] : null;

		      				// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
		      				// Inicio de la transaccion.
							$transaccion = $this->connLocal->beginTransaction();

							// Ahora se crea el arreglo de id contribuyentes que seran afectados
							// Se crea un ciclo para actualizar un contribuyente a la vez.
							// Si el o todos los contribuyentes son actualizados satisfactoriamente
							// se ejecuta el commit de todo el proceso.
							// $postData['selection'], representa a cada uno de los contribuyentes
							// seleccionados en el formulario.
							foreach ($postData['selection'] as $key => $value) {
								$idCorreccion = 0;
								$idContribuyente = $value;

								if ( $idContribuyente == $_SESSION['idContribuyente'] ) { $actualizarBarraMenu = true; }

								// El metodo debe retornar un booleano.
								if ( self::actionCreateCorreccionRazonSocial($idContribuyente, $model, $postData, $conexion, $this->connLocal) ) {
									if ( self::actionActualizarRazonSocial($idContribuyente, $model, $postData, $conexion, $this->connLocal) ) {
										$todoBien = true;
									} else {
										$todoBien = false;
										break;
									}
								} else {
									$todoBien = false;
									break;
								}
							}


							if ( $todoBien ) {
								// dataProvider de lo seleccinado en el formulario.
								$dataProvider = $_SESSION['dataProvider'];

								if ( $actualizarBarraMenu ) {
									// Se debe actualizar la descripcion del contribuyente que aparece en la barra superior.
									unset($_SESSION['contribuyente']);
									$nuevoContribuyente = ContribuyenteBase::getContribuyenteDescripcionSegunID($_SESSION['idContribuyente']);
									$_SESSION['contribuyente'] = $nuevoContribuyente;
								}
								$transaccion->commit();
								$tipoError = 0;	// No error.
								$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
								$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correccionrazonsocial/correccion-razon-social/view'])."'>";
								//$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correccionrazonsocial/correccion-razon-social/view-ok'])."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

							} else {

								$transaccion->rollBack();
								$tipoError = 1; // Error.
								$msg = "AH ERROR OCCURRED!....WAIT";
								$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/correccionrazonsocial/correccion-razon-social/index")."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
							}
							$this->connLocal->close();

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
		 * [actionCreateCorreccionRazonSocial description]
		 * @param  [type] $model     [description]
		 * @param  [type] $postData  [description]
		 * @param  [type] $conexion  [description]
		 * @param  [type] $connLocal [description]
		 * @return [type]            [description]
		 */
		private function actionCreateCorreccionRazonSocial($idContribuyente, $model, $postData, $conexion, $connLocal)
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

						$arrayIdCorreccion = isset($_SESSION['idCorreccion']) ? $_SESSION['idCorreccion'] : [];

						// post enviado con los valores que seran guardados.
						foreach ( $arregloDatos as $key => $value ) {
							if ( isset($request[$key]) ) {
								$arregloDatos[$key] = $request[$key];
							}
						}

						// Arreglo de datos a guardar.
						$arregloDatos['id_contribuyente'] = $idContribuyente;
						$arregloDatos['razon_social_v'] = ContribuyenteBase::getContribuyenteDescripcionSegunID($idContribuyente);
						$arregloDatos['razon_social_new'] = $model->razon_social_new;
						$arregloDatos['nro_solicitud'] = 0;
						$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
						$arregloDatos['usuario'] = Yii::$app->user->identity->username;
						$arregloDatos['estatus'] = 1;
						$arregloDatos['origen'] = 'LAN';

						if ( $conexion->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
							$idCorreccion = $connLocal->getLastInsertID();
							if ( $idCorreccion > 0 ) {
								$result = true;
								$arrayIdCorreccion[$idContribuyente] = $idCorreccion;
								self::actionEliminarVariablesSession(['idCorreccion']);
								$_SESSION['idCorreccion'] = $arrayIdCorreccion;
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
		 * [actionActualizarRazonSocial description]
		 * @param  [type] $model     [description]
		 * @param  [type] $postData  [description]
		 * @param  [type] $conexion  [description]
		 * @param  [type] $connLocal [description]
		 * @return [type]            [description]
		 */
		private function actionActualizarRazonSocial($idContribuyente, $model, $postData, $conexion, $connLocal)
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

							// Descripcion de la Razon Social nueva.
							$arregloDatos['razon_social'] = $model->razon_social_new;

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
		 * [actionView description]
		 * @return [type] [description]
		 */
		public function actionView()
		{
			if ( isset($_SESSION['idCorreccion']) ) {

				// $_SESSION['idCorreccion'], es un array donde el indice del arreglo es id del contribuyente y
				// el valor del arreglo es el id correccion del registro guardado en la tabla respectiva por
				// contribuyentes.
				$arrayIdCorreccion = $_SESSION['idCorreccion'];
				$idCorreccion = array_values($arrayIdCorreccion);
				$model = $_SESSION['model'];
				if ( $model ) {
					$dataProvider = $model->getDataProviderCorreccionesRazonSocial($idCorreccion);
					$postData = $_SESSION['postData'];

					$arrayVariables = ['postData', 'datosContribuyente', 'model', 'dataProvider', 'idCorreccion'];
					self::actionEliminarVariablesSession($arrayVariables);
		        	return $this->render('/aaee/correccion-razon-social/pre-view',
														        			[
														        				'model' => $model,
														        				'preView' => false,
														        				'dataProvider' => $dataProvider,
														        				'postData' => $postData,

														        			]);
		        } else {
		        	return self::gestionarMensajesLocales('No se encontrarón los registros guardados.');
		        }
        	} else {
        		return self::gestionarMensajesLocales('No se encontrarón los registros corregidos.');
        	}
		}





		/**
		 * Metodo que determina que todo el proceso fue exitoso.
		 * @return returna una vista con el mensaje indicado.
		 */
		public function actionViewOk()
		{
			//unset($_SESSION['postData'], $_SESSION['datosContribuyente'], $_SESSION['model'], $_SESSION['dataProvider']);
			// $arrayVariables = ['postData', 'datosContribuyente', 'model', 'dataProvider'];
			// self::actionEliminarVariablesSession($arrayVariables);
			// return self::gestionarMensajesLocales(Yii::t('backend', 'PROCESS SUCCESS.'));
		}



		/**
		*	Metodo que busca el ultimo registro creado.
		* 	@param $idInscripcion, long que identifica el autonumerico generado al crear el registro.
		*/
		protected function findModel($arrayIdCorreccion)
    	{
    		//if ( ($model = CorreccionRazonSocial::findOne($arrayIdCorreccion)) !== null ) {
        	if ( ($model = CorreccionRazonSocial::find()->where(['in', 'id_correccion', $arrayIdCorreccion])->all()) !== null ) {
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
    		$arrayVariables = ['idCorreccion', 'datosContribuyente', 'model', 'dataProvider', 'postData'];
    		self::actionEliminarVariablesSession($arrayVariables);
    		return $this->render('/aaee/correccion-razon-social/quit');
    	}



    	/**
    	 * [actionEliminarVariablesSession description]
    	 * @return [type] [description]
    	 */
    	public function actionEliminarVariablesSession($arrayVariables = [])
    	{
    		if ( count($arrayVariables) > 0 ) {
    			foreach ( $arrayVariables as $variable ) {
    				unset($_SESSION[$variable]);
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
	}
?>