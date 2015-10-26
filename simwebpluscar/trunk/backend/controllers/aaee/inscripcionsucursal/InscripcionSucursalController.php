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
 *	@file InscripcionSucursalController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 04-10-2015
 *
 *  @class InscripcionSucursalController
 *	@brief Clase InscripcionSucursalController, inscripcion de sucursal
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


 	namespace backend\controllers\aaee\inscripcionsucursal;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursal;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursalForm;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaForm;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\documentoconsignado\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use yii\base\Model;
	use backend\controllers\mensaje\MensajeController;

	session_start();		// Iniciando session

	/**
	 *
	 */
	class InscripcionSucursalController extends Controller
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
					$model = New InscripcionSucursalForm();
					$modelActEcon = New InscripcionActividadEconomicaForm();

					$postData = Yii::$app->request->post();
			  		$request = Yii::$app->request;
			  		$models = [$model, $modelActEcon];

			  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						return ActiveForm::validateMultiple($models);
			      	}

			      	if ( $model->load($postData) ) {

			      	 	if ( $model->validate() ) {
			      	 		// if ( !self::actionValidateRegistroMercantil($postData) ) {
			      	 		// 	die('NO se puede continuar faltan valores en el registro merbantil.');
			      	 		// }
			      	 		$modelContribuyente = New ContribuyenteBase();

			      	 		$_SESSION['model'] = $model;
			      	 		$_SESSION['postData'] = $postData;

			      	 		foreach ( $modelContribuyente->attributes as $key => $value) {
			      	 			if ( isset($postData[$model->formName()][$key]) ) {
			      	 				$datosContribuyente[$key] = $postData[$model->formName()][$key];

			      	 			} elseif ( isset($postData[$modelActEcon->formName()][$key]) ) {
			      	 				$datosContribuyente[$key] = $postData[$modelActEcon->formName()][$key];

			      	 			} else {
			      	 				if ( $key == 'id_cp' OR $key == 'no_declara' OR $key == 'agente_retencion'
			      	 					OR $key == 'econ_informal' OR $key == 'inactivo' OR $key == 'no_sujeto'
			      	 					OR $key == 'foraneo' OR $key == 'manzana_limite' OR $key == 'cuenta'
			      	 					OR $key == 'licencia' OR $key == 'num_empleados' OR $key == 'grupo_contribuyente'
			      	 					OR $key == 'cedula_rep' OR $key == 'tipo_contribuyente' OR $key == 'nivel') {

			      	 					$datosContribuyente[$key] = 0;

			      	 				} else {
			      	 					$datosContribuyente[$key] = null;
			      	 				}
			      	 			}
			      	 		}
			      	 		$naturaleza = $datosContribuyente['naturaleza'];
			      	 		$cedula = $datosContribuyente['cedula'];
			      	 		$tipo = $datosContribuyente['tipo'];

			      	 		$datosContribuyente['id_rif'] = ContribuyenteBase::getUltimoIdRifSucursalSegunRIF($naturaleza, $cedula, $tipo)['id_rif'] + 1;
			      	 		$datosContribuyente['ente'] = Yii::$app->ente->getEnte();
			      	 		$datosContribuyente['tipo_naturaleza'] = 1;

			      	 		// Todo bien la validacion es correcta.
			      	 		// Se redirecciona a una preview para confirmar la creacion del registro.
			      	 		$_SESSION['datosContribuyente'] = $datosContribuyente;

			      	 		return $this->render('/aaee/inscripcion-sucursal/pre-view', [
			      	 																	'model' => $datosContribuyente,
											      	 									'preView' => true
											      	 									]);
			      	 		//$arrayParametros = $request->bodyParams;

			      	 	} else {
			      	 		//die('validate no');
			      	 		$model->getErrors();
			      	 	}
			      	} else {
			      		echo 'No paso Model::loadMultiple';
			  		}

			  		// Datos de la cede principal.
			  		$datos = ContribuyenteBase::getDatosContribuyenteSegunID($_SESSION['idContribuyente']);
			  		if ( $datos ) {
			  			if ( InscripcionSucursalForm::sedePrincipal($datos) ) {
				  			if ( InscripcionSucursalForm::datosRegistroMercantilValido($datos) ) {
					  			$_SESSION['datos'] = $datos;
					  			$model->naturaleza = $datos[0]['naturaleza'];
					  			$model->cedula = $datos[0]['cedula'];
					  			$model->tipo = $datos[0]['tipo'];
					  			$model->razon_social = $datos[0]['razon_social'];
					  			$model->nro_solicitud = 0;

					  			$modelActEcon->naturaleza_rep = $datos[0]['naturaleza_rep'];
					  			$modelActEcon->cedula_rep = $datos[0]['cedula_rep'];
					  			$modelActEcon->representante = $datos[0]['representante'];
					  			$modelActEcon->num_reg = $datos[0]['num_reg'];
					  			$modelActEcon->reg_mercantil = $datos[0]['reg_mercantil'];
					  			$modelActEcon->fecha = $datos[0]['fecha'];
					  			$modelActEcon->tomo = $datos[0]['tomo'];
					  			$modelActEcon->folio = $datos[0]['folio'];
					  			$modelActEcon->capital = $datos[0]['capital'];
					  		} else {
					  			return self::gestionarMensajesLocales('Los datos del Registro Mercantil de la sede principal no están completos.');
					  		}
				  		} else {
				  			return self::gestionarMensajesLocales('Contribuyente no aplica para esta opción. La razón social no es una sede principal.');
				  		}
			  		}

			  		if ( isset($_SESSION['model']) ) { $model = $_SESSION['model']; }

			  		if ( isset($_SESSION['modelActEcon']) ) { $modelActEcon = $_SESSION['modelActEcon']; }

		  			return $this->render('/aaee/inscripcion-sucursal/create', ['model' => $model, 'modelActEcon' => $modelActEcon, ]);
		  		} else {
		  			// No esta definido el contribuyente.
		  			return self::gestionarMensajesLocales('NO esta definido el contribuyente.');
		  			// die('NO esta definido el contribuyente');
		  		}
	  		} else {
	  			return self::gestionarMensajesLocales('Contribuyente no aplica para esta opción.');
	  			// echo 'Contribuyente no aplica para esta opción.';
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
		 * [actionCreateContribuyente description]
		 * @param  [type] $conexion [description]
		 * @return returna long, id del contribuyente creado. Si la operacion falla returna false.
		 */
		private static function actionCreateContribuyente($conexion, $connLocal)
		{
			if ( isset($_SESSION['datosContribuyente']) ) {

				$modelContribuyente = new ContribuyenteBase();

				$arrayDatos = $_SESSION['datosContribuyente'];
				$arrayDatos['fecha_inclusion'] = date('Y-m-d');

				// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
				$arrayDatos['fecha_inicio'] = date('Y-m-d', strtotime($arrayDatos['fecha_inicio']));

				// Se procede a guardar primero en la entidad contribuyentes, debido a que se requiere el
				// id generado para guardar en las otras entidades.
      			$tabla = '';
      			$tabla = $modelContribuyente->tableName();

				if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
					$idContribuyenteGenerado = 0;
					return $idContribuyenteGenerado = $connLocal->getLastInsertID();
				}
			}
			return false;
		}






		/**
		 * [actionCreateActividadEconomica description]
		 * @param  [type]  $conexion               [description]
		 * @param  [type]  $connLocal              [description]
		 * @param  integer $idContribuyenteGenerdo [description]
		 * @return [type]                          [description]
		 */
		private static function actionCreateActividadEconomica($conexion, $connLocal, $idContribuyenteGenerado = 0)
		{
			if ( $idContribuyenteGenerado > 0 ) {
				if ( isset($conexion) ) {
					if ( isset($_SESSION['postData']) ) {

						$postData = $_SESSION['postData'];
						$modelActEcon = new InscripcionActividadEconomicaForm();

						$arrayDatos = $modelActEcon->attributes;

						foreach ( $modelActEcon->attributes as $key => $value ) {
							if ( isset($postData[$modelActEcon->formName()][$key] ) ) {
								$arrayDatos[$key] = $postData[$modelActEcon->formName()][$key];
							}
						}
						// Campos faltantes.
						$arrayDatos['id_contribuyente'] = $idContribuyenteGenerado;
						$arrayDatos['nro_solicitud'] = 0;
						$arrayDatos['num_empleados'] = 0;
						$arrayDatos['cedula_rep'] = 0;

						// Se procede a guardar primero en la entidad correspondiente.
		      			$tabla = '';
		      			$tabla = $modelActEcon->tableName();

						if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {

							return true;
						}
					}
				}
			}
			return false;
		}






		/**
		 * Metodo para guardar la informacion de la sucursal.
		 * @param  [type]  $conexion               [description]
		 * @param  [type]  $connLocal              [description]
		 * @param  integer $idContribuyenteGenerdo [description]
		 * @return [type]                          [description]
		 */
		private static function actionCreateSucursal($conexion, $connLocal, $idContribuyenteGenerado = 0)
		{
			if ( $idContribuyenteGenerado > 0 ) {
				if ( isset($conexion) ) {
					if ( isset($_SESSION['postData']) ) {

						$postData = $_SESSION['postData'];
						$model = new InscripcionSucursalForm();

						$arrayDatos = $model->attributes;

						foreach ( $model->attributes as $key => $value ) {
							if ( isset($postData[$model->formName()][$key] ) ) {
								$arrayDatos[$key] = $postData[$model->formName()][$key];
							}
						}
						// Campos faltantes.
						$arrayDatos['id_contribuyente'] = $idContribuyenteGenerado;
						$arrayDatos['nro_solicitud'] = 0;

						// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
						$arrayDatos['fecha_inicio'] = date('Y-m-d', strtotime($arrayDatos['fecha_inicio']));

		      			$tabla = '';
		      			$tabla = $model->tableName();

						if ( $conexion->guardarRegistro($connLocal, $tabla, $arrayDatos) ) {
							$idInscripcionSucursal = 0;
							return $idInscripcionSucursal = $connLocal->getLastInsertID();
						}
					}
				}
			}
			return false;
		}




		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  [type]  $conexion                [description]
		 * @param  [type]  $connLocal               [description]
		 * @param  integer $idContribuyenteGenerado [description]
		 * @return [type]                           [description]
		 */
		private static function actionCreateDocumentosConsignados($conexion, $connLocal, $idContribuyenteGenerado = 0)
		{
			if ( $idContribuyenteGenerado > 0 ) {
				if ( isset($conexion) ) {
					if ( isset($_SESSION['postData']) ) {

						$seleccion = [];
						$postData = $_SESSION['postData'];

						if ( isset($postData['selection']) ) {
							$modelDocumento = new DocumentoConsignadoForm();

							$tabla = '';
			      			$tabla = $modelDocumento->tableName();

							$arregloDatos = $modelDocumento->attributes;

							$arregloDatos['id_contribuyente'] = $idContribuyenteGenerado;
							$arregloDatos['nro_solicitud'] = 0;
							$arregloDatos['id_impuesto'] = 0;
							$arregloDatos['impuesto'] = 1;
							$arregloDatos['codigo_proceso'] = null;
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
			return false;
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
    	 * Metodo que indica si los valores presentes en el formulario son validos.
    	 * Se evalua si los campos son validos a traves del modelo. Esto campos se encuentra
    	 * en el modelo de inscripcion de actividad economica.
    	 * @param $postData, post enviado desde el formulario.
    	 * @return returna boolean que indica si los campos en el formulario contienen valores validos.
    	 * true indica que todo esta bien, false todo lo contrario.
    	 */
    	private static function actionValidateRegistroMercantil($postData)
    	{
    		$modelActEcon = new InscripcionActividadEconomicaForm();
    		$nombreForm = $modelActEcon->formName();
    		foreach ( $postData[$nombreForm] as $key => $value ) {
    			if ( !isset($postData[$nombreForm]['fecha']) ) {
    				return false;
    			} elseif ( !isset($postData[$nombreForm]['num_reg']) ) {
    				return false;
    			} elseif ( !isset($postData[$nombreForm]['reg_mercantil']) ) {
    				return false;
    			}
    		}
    		return true;
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