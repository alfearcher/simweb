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
 *	@date 30-10-2015
 *
 *  @class CorreccionCedulaRifController
 *	@brief Clase CorreccionCedulaRifController, inscripcion de sucursal
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

	session_start();		// Iniciando session

	/**
	 *
	 */
	class CorreccionCedulaRifController extends Controller
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
			if ( $tipoNaturaleza == 'JURIDICO' || $tipoNaturaleza == 'NATURAL' ) {
				if ( isset($_SESSION['idContribuyente']) ) {

					$model = New CorreccionCedulaRifForm();

					$postData = Yii::$app->request->post();
			  		$request = Yii::$app->request;

			  		if ( $model->load($postData) && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validate($model);
			      	}

			      	if ( $model->load($postData) ) {

			      	 	if ( $model->validateForm($model, $postData) ) {
			      	 		$_SESSION['model'] = $model;
			      	 		$datosContribuyente = $_SESSION['datosContribuyente'];
			      	 		$_SESSION['postData'] = $postData;
//die(var_dump(Yii::$app->request->post($model->formName())));
//$model->attributes = \Yii::$app->request->post(’ContactForm’);
//die(var_dump($postData));

			      	 		// Todo bien la validacion es correcta.
			      	 		// Se redirecciona a una preview para confirmar la creacion del registro.

			      	 		return $this->render('/aaee/correccion-cedula-rif/pre-view', [
			      	 																	'model' => $model,
			      	 																	'datosContribuyente' => $datosContribuyente,
											      	 									'preView' => true,
											      	 									'postData' => $postData,
											      	 									]);
			      	 		//$arrayParametros = $request->bodyParams;

			      	 	} else {
			      	 		$model->getErrors();
			      	 	}
			  		}

			  		$idContribuyente = isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : 0;
			  		$datosContribuyente = ContribuyenteBase::getDatosContribuyenteSegunID($idContribuyente);
			  		if ( isset($datosContribuyente) ) {
			  			$_SESSION['datosContribuyente'] = $datosContribuyente;
			  			if ( $datosContribuyente[0]['tipo_naturaleza'] == '1' && $tipoNaturaleza == 'JURIDICO' ) {

			  				$dataProvider = $model->getDataProviderSucursalesSegunRif($datosContribuyente[0]['naturaleza'],
			  																		  $datosContribuyente[0]['cedula'],
			  																		  $datosContribuyente[0]['tipo'],
			  																		  $datosContribuyente[0]['tipo_naturaleza']
			  																		  );


			  			} elseif ( $datosContribuyente[0]['tipo_naturaleza'] == '0' && $tipoNaturaleza == 'NATURAL' ) {

			  				$dataProvider = $model->getDataProviderSucursalesSegunRif($datosContribuyente[0]['naturaleza'],
			  																		  $datosContribuyente[0]['cedula'],
			  																		  $datosContribuyente[0]['tipo'],
			  																		  $datosContribuyente[0]['tipo_naturaleza']
			  																		  );

			  			}

			  			return $this->render('/aaee/correccion-cedula-rif/create', [
			  																		'model' => $model,
			  																		'datosContribuyente' => $datosContribuyente,
			  																		'dataProvider' => $dataProvider,
			  																		]);
			  		}

		  		} else {
		  			return self::gestionarMensajesLocales('No esta definido el contribuyente.');
		  		}
	  		} else {
	  			return self::gestionarMensajesLocales('Contribuyente no aplica para esta opción.');
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

		      			$todoBien = false;
		      			$model = isset($_SESSION['model']) ? $_SESSION['model'] : null;
		      			$postData = isset($_SESSION['postData']) ? $_SESSION['postData'] : null;

		      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
		      			// Inicio de la transaccion.
						$transaccion = $this->connLocal->beginTransaction();

						// El metodo debe retornar un id correcion.
						$idCorreccion = self::actionCreateCorreccionCedulaRif($model, $postData, $conexion, $this->connLocal);
						if ( $idCorreccion > 0 ) {

							if ( self::actionActualizarCedulaRif($model, $postData, $conexion, $connLocal) ) {
								$todoBien = true;
								$transaccion->commit();
								$tipoError = 0;	// No error.
								$msg = Yii::t('backend', 'SUCCESS!....WAIT.');
								$_SESSION['idCorreccion'] = $idCorreccion;
								$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute(['/aaee/correccioncedularif/correccion-cedula-rif/view','idCorreccion' => $idCorreccion])."'>";
								return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
							}
						}
						if ( !$todoBien ) {
							$transaccion->rollBack();
							$tipoError = 1; // Error.
							$msg = "AH ERROR OCCURRED!....WAIT";
							$url =  "<meta http-equiv='refresh' content='3; ".Url::toRoute("/aaee/correccioncedularif/correccion-cedula-rif/index")."'>";
							return $this->render('/mensaje/mensaje',['msg' => $msg, 'url' => $url, 'tipoError' => $tipoError]);
						}

						$this->connLocal->close();
					} else {
						// No esta definido el modelo.
						return self::gestionarMensajesLocales('No esta definido el modelo');
						// die('No esta definido el modelo');
					}
				}
			} else {
				return self::gestionarMensajesLocales('No esta definido el contribuyente');
				// echo 'No esta definido el contribuyente';
			}
		}





		/**
		 * [createCorreccionCedulaRif description]
		 * @param  [type] $model     [description]
		 * @param  [type] $postData  [description]
		 * @param  [type] $conexion  [description]
		 * @param  [type] $connLocal [description]
		 * @return [type]            [description]
		 */
		private function actionCreateCorreccionCedulaRif($model, $postData, $conexion, $connLocal)
		{
			$idCorreccion = 0;
			if ( isset($conexion) ) {
				if ( isset($_SESSION['datosContribuyente']) ) {
					$datosContribuyente = $_SESSION['datosContribuyente'];
					if ( isset($_SESSION['idContribuyente']) ) {
						$idContribuyente = $_SESSION['idContribuyente'];
						die(var_dump($postData));
						if ( $idContribuyente == $model->id_contribuyente && $model->id_contribuyente == $datosContribuyente[0]['id_contribuyente'] ) {
							$tabla = $model->tableName();
							$nombreForm = $model->formName();
							$arregloDatos = $model->attributes;
							$request = $postData[$nombreForm];
							// arregloDatos, estructrura
							// [0] => campo0
							// [1] => campo1
							// .
							// .
							// .
							// [n] => campon
							foreach ( $arregloDatos as $key => $value ) {
								if ( isset($request[$value]) ) {
									$arregloDatos[$key] = $request[$value];
								}
							}
							$arregloDatos['nro_solicitud'] = 0;
							$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
							$arregloDatos['usuario'] = Yii::$app->user->identity->username;
							$arregloDatos['estatus'] = 0;
							$arregloDatos['origen'] = 'LAN';

							if ( $conexion->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
								$idCorreccion = $connLocal->getLastInsertID();
							}
						}
					}
				}
			}
			return $idCorreccion;
		}





		/**
		 * [actionActualizarCedulaRif description]
		 * @param  [type] $model     [description]
		 * @param  [type] $postData  [description]
		 * @param  [type] $conexion  [description]
		 * @param  [type] $connLocal [description]
		 * @return [type]            [description]
		 */
		private function actionActualizarCedulaRif($model, $postData, $conexion, $connLocal)
		{
			$result = false;
			if ( $conexion ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					if ( isset($_SESSION['datosContribuyente']) ) {
						$datosContribuyente = $_SESSION['datosContribuyente'];
						if ( $model->id_contribuyente == $_SESSION['idContribuyente'] && $datosContribuyente[0]['id_contribuyente'] == $_SESSION['idContribuyente']) {
							$tabla = 'contribuyentes';
							$nombreForm = $model->formName();
							$request = $postData[$nombreForm];

							if ( $request['tipo_naturaleza_new'] == 0 ) {
								$arrayCondicion[0] = $request['id_contribuyente'];

								$arrayDatos['naturaleza'] = $request['naturaleza_new'];
								$arrayDatos['cedula'] = $request['cedula_new'];

							} elseif ( $request['tipo_naturaleza_new'] == 1 ) {
								// Se buscan los Id's de las sucursales asociadas, en condicion activo.
								$naturaleza = $request['naturaleza_v'];
								$cedula =  $request['cedula_v'];
								$tipo =  $request['tipo_v'];
								$sucursales = ContribuyenteBase::getListaSucursalesSegunRIF($naturaleza, $cedula, $tipo, 0);
								foreach ($sucursales as $key => $value) {
									$arrayCondicion[$key] = $sucursales[$key]['id_contribuyente'];
								}

								$arrayDatos['naturaleza'] = $request['naturaleza_new'];
								$arrayDatos['cedula'] = $request['cedula_new'];
								$arrayDatos['tipo'] = $request['tipo_new'];
							}

							if ( $conexion->modificarRegistro($conexion, $tabla, $arrayDatos, $arrayCondicion) ) {
								$result = true;
							}
						}
					}
				}
			}
			return $result;
		}






		/**
		*	Metodo muestra la vista con la informacion que fue guardada.
		*/
		public function actionView($idCorreccion)
    	{
    		if ( isset($_SESSION['idCorreccion']) ) {
    			if ( $_SESSION['idCorreccion'] == $idCorreccion ) {
    				$model = $this->findModel($idCorreccion);
    				if ( $_SESSION['idCorreccion'] == $model->id_correccion ) {
			        	return $this->render('/aaee/correccion-cedula-rif/pre-view',
			        			['model' => $model, 'preView' => false,

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
        	if (($model = CorreccionCedulaRif::findOne($idCorreccion)) !== null) {
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
    		return $this->render('/aaee/correccion-cedula-rif/quit');
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