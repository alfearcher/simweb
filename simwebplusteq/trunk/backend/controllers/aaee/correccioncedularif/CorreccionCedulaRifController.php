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
 *	@file CorreccionCedulaRifController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 17-11-2015
 *
 *  @class CorreccionCedulaRifController
 *	@brief Clase CorreccionCedulaRifController
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


 	namespace backend\controllers\aaee\correccioncedularif;

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
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRifForm;
	use backend\models\aaee\correccioncedularif\CorreccionCedulaRif;


	session_start();


	class CorreccionCedulaRifController extends Controller
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
				if ( $tipoNaturaleza != null ) {
					if ( $tipoNaturaleza == 'JURIDICO' || $tipoNaturaleza == 'NATURAL' ) {

						$model = New CorreccionCedulaRifForm();
						$msjErrorLista = '';

						$postData = Yii::$app->request->post();
						$g=Yii::$app->request->getBodyParam('selection');

//die(var_dump($postData));
				  		if ( $model->load($postData) && Yii::$app->request->isAjax ) {
							Yii::$app->response->format = Response::FORMAT_JSON;
							return ActiveForm::validate($model);
				      	}

				      	if ( $model->load($postData) ) {

				      		if ( $model->validate() ) {

				      			if ( isset($postData['selection']) ) {
//die(var_dump($postData));
					      			if ($postData['btn-update'] == 1 ) {
					      				// Lista de sucursales (de existir) para mostrar en el pre-view,
					      				// solo se mostraran las sucursales tildadas en el formulario.
					      				$dataProvider = $model->getDataProviderSucursalesSegunId($postData['selection']);
					      				$postData['btn-update'] = 2;
						      			$_SESSION['model'] = $model;
						      			$_SESSION['postData'] = $postData;
						      			$datosContribuyente = $_SESSION['datosContribuyente'];
						      			$_SESSION['dataProvider'] = $dataProvider;

						      			return $this->render('/aaee/correccion-cedula-rif/pre-view', [
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
				      			self::actionEliminarVariablesSession(['dataProvider']);
				      		}

				      		// Se buscan a los contribuyentes que tengan el mismo rif.
				      		$dataProvider = $model->getDataProviderSucursalesSegunRif($naturaleza, $cedula, $tipo, 1);
							if ( $dataProvider ) {

					      		return $this->render('/aaee/correccion-cedula-rif/create', [
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

								// El metodo debe retornar un booleano.
								if ( self::actionCreateCorreccionCedulaRif($idContribuyente, $model, $postData, $conexion, $this->connLocal) ) {
									if ( self::actionActualizarCedulaRif($idContribuyente, $model, $postData, $conexion, $this->connLocal) ) {
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
								if ( self::actionCreateDocumentosConsignados($conexion, $this->connLocal, $idContribuyente) ) {
									$todoBien = true;
								} else {
									$todoBien = false;
								}
							}

							if ( $todoBien ) {
								// dataProvider de lo seleccinado en el formulario.
								$dataProvider = $_SESSION['dataProvider'];

								$transaccion->commit();
								$tipoError = 0;	// No error.
								$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
								$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correccioncedularif/correccion-cedula-rif/view'])."'>";
								//$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correccionrazonsocial/correccion-razon-social/view-ok'])."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);

							} else {

								$transaccion->rollBack();
								$tipoError = 1; // Error.
								$msg = "AH ERROR OCCURRED!....WAIT";
								$url = "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/correccioncedularif/correccion-cedula-rif/index")."'>";
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
		private function actionCreateCorreccionCedulaRif($idContribuyente, $model, $postData, $conexion, $connLocal)
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

						// arregloDatos, estructrura
							// [0] => campo0
							// [1] => campo1
							// .
							// .
							// .
							// [n] => campon
						// post enviado con los valores que seran guardados.
						foreach ( $arregloDatos as $key => $value ) {
							if ( isset($request[$key]) ) {
								$arregloDatos[$key] = $request[$key];
							}
						}

						// Se busca la cedula o rif del contribuyente que se va a actualizar.
						$cedulaRif = [];
						$cedulaRif = ContribuyenteBase::getCedulaRifTipoNaturalezaSegunID($idContribuyente);

						if ( count($cedulaRif) == 1 ) {
							// Arreglo de datos a guardar.
							$arregloDatos['id_contribuyente'] = $idContribuyente;
							$arregloDatos['tipo_naturaleza_v'] = $cedulaRif['tipo_naturaleza'];
							$arregloDatos['naturaleza_v'] = $cedulaRif['naturaleza'];
							$arregloDatos['cedula_v'] = $cedulaRif['cedula'];
							$arregloDatos['tipo_v'] = $cedulaRif['tipo'];
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
		private function actionActualizarCedulaRif($idContribuyente, $model, $postData, $conexion, $connLocal)
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

							// Cedula o rif nueva.
							if ( $request['tipo_naturaleza_new'] == 0 ) {

								$arrayDatos['naturaleza'] = $request['naturaleza_new'];
								$arrayDatos['cedula'] = $request['cedula_new'];

							} elseif ( $request['tipo_naturaleza_new'] == 1 ) {

								$arrayDatos['naturaleza'] = $request['naturaleza_new'];
								$arrayDatos['cedula'] = $request['cedula_new'];
								$arrayDatos['tipo'] = $request['tipo_new'];
							}

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
							$arregloDatos['codigo_proceso'] = 'CORRECCION-CEDULA-RIF';
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
					$dataProvider = $model->getDataProviderCorreccionesCedulaRif($idCorreccion);
					$postData = $_SESSION['postData'];

					$arrayVariables = ['postData', 'datosContribuyente', 'model', 'dataProvider', 'idCorreccion'];
					self::actionEliminarVariablesSession($arrayVariables);
		        	return $this->render('/aaee/correccion-cedula-rif/pre-view',
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
        	if ( ($model = CorreccionCedulaRif::find()->where(['in', 'id_correccion', $arrayIdCorreccion])->all()) !== null ) {
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
    		return $this->render('/aaee/correccion-cedula-rif/quit');
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