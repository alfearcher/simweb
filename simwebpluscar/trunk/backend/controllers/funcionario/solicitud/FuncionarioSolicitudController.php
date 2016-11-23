<?php
/**
 * @copyright 2016 © by ASIS CONSULTORES 2012 - 2016
 * All rights reserved - SIMWebPLUS
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




 	namespace backend\controllers\funcionario\solicitud;

 	session_start();		// Iniciando session
 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use backend\models\funcionario\solicitud\FuncionarioSolicitud;
	use backend\models\funcionario\solicitud\FuncionarioSearch;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\utilidad\departamento\DepartamentoForm;
	use backend\models\utilidad\unidaddepartamento\UnidadDepartamentoForm;
	use backend\controllers\MenuController;
	use backend\models\configuracion\tiposolicitud\TipoSolicitudSearch;
	use backend\models\configuracion\tiposolicitud\TipoSolicitud;
	use backend\models\impuesto\ImpuestoForm;

 /**
  *	@file FuncionarioSolicitudController.php
  *
  * @date 21-04-2016
  *
  * @author Jose Rafael Perez Teran
  *
  *
  *	@class FuncionarioSolicitudController
  *	@brief Clase principal de la entidad Funcionario Solicitud.
  *
  *
  *
  *
  *	@property
  *
  *
  *	@method
  *	actionCreate
  *
  *
  *	@inherits
  *
  */


 	/**
 	 * Clase principal que permite gestionar la asignacion de responsabilidaddes en lo
 	 * referente al tipo de solicitudes que cada funcionario debe atender y procesar.
 	 */

	class FuncionarioSolicitudController extends Controller
	{

		public $layout = 'layout-main';				//	Layout principal del formulario.

		public $conn;
		public $conexion;
		public $transaccion;

		const SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD = 'search_departamento';
		const SCENARIO_SEARCH_GLOBAL = 'search_global';
		const SCENARIO_DEFAULT = 'default';
		const SCENARIO_SEARCH_IMPUESTO_SOLICITUD = 'search_impuesto_solicitud';





		/**
		 * Metodo que permite iniciar el proceso de desincorporacion de una solicitud
		 * relacionada a una funcionario. Aqui delete no es borrado fisico sino logico.
		 * @return [type] [description]
		 */
		public function actionIndexDelete()
		{
			if ( isset(Yii::$app->user->identity->username) ) {
				$model = New FuncionarioSearch();
				$formName = $model->formName();
				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-search-parameters']) ) {
					$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
				} elseif ( isset($postData['btn-search-impuesto']) ) {
					$model->scenario = self::SCENARIO_SEARCH_IMPUESTO_SOLICITUD;
				}

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				if ( $model->load($postData) ) {
					if ( isset($postData['btn-search-parameters']) ) {
						// Busqueda de funcionarios por parametros
						if ( $model->validate() ) {
							$params = $postData[$formName]['searchGlobal'];
							return self::actionBuscarFuncionarioParaDesincorporar(0, 0, $params);
						}

					} elseif ( isset($postData['btn-search-impuesto']) ) {
						// Busqueda de los funcionarios por impuesto y tipo de solicitud
						if ( $model->validate() ) {
							$tipoSolicitud = $postData[$formName]['tipo_solicitud'];
							$impuesto = $postData[$formName]['impuesto'];
							return self::actionBuscarFuncionarioParaDesincorporar($tipoSolicitud, $impuesto);
						}
					}
				}

				// Modelo adicionales para la busqueda de los funcionarios.
				$modelImpuesto = New ImpuestoForm();

				// Se define la lista de item para el combo de impuestos.
				$listaImpuesto = $modelImpuesto->getListaImpuesto();

				$caption = Yii::t('backend', 'Remove Request');
				return $this->render('/funcionario/solicitud/funcionario-desincorporar-solicitud-form', [
																				'model' => $model,
																				'modelImpuesto' => $modelImpuesto,
																				'caption' => $caption,
																				'listaImpuesto' => $listaImpuesto,

					]);

			} else {
				// No esta definido el usuario. Eliminar todas las variables de session y salir.
				return MensajeController::actionMensaje(999);
			}

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
	        $countSolicitud = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])->count();

	        $solicitudes = TipoSolicitud::find()->where(['impuesto' => $i, 'inactivo' => 0])->all();

	        if ( $countSolicitud > 0 ) {
	        	echo "<option value='0'>" . "Select..." . "</option>";
	            foreach ( $solicitudes as $solicitud ) {
	                echo "<option value='" . $solicitud->id_tipo_solicitud . "'>" . $solicitud->descripcion . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }
	    }



	    /**
	     * Metodo que permite inactivar una solicitud relacionada a un o varios funcionarios.
	     * El listado inicial esta identificado el tipo de solicitud y le sigue un listado
	     * de funcionario.
	     * @return [type] [description]
	     */
	    public function actionInactivarSeleccionSolicitud()
	    {
	    	$result = false;
	    	$request = Yii::$app->request;
	    	$postData = $request->post();

	    	$chkIdFuncionarioSolicitud = isset($postData['chk-id-funcionario-solicitud']) ? $postData['chk-id-funcionario-solicitud'] : null;

	    	// Se verifica que el post enviado desde el formulario este correcto
	    	if ( isset($postData['btn-remove-request']) && count($chkIdFuncionarioSolicitud) > 0 ) {
	    		$result = self::actionBeginSave($postData, 'delete');		// delete a nivel logico.
	    		if ( $result ) {
					$this->redirect(['proceso-exitoso']);
				} else {
					$this->redirect(['error-operacion', 'cod' => 920]);
				}
	    	} else {
	    		return MensajeController::actionMensaje(404);
	    	}
	    }




	    /**
	     * Metodo que permite renderizar una vista con las solicitudes relacionadas a un funcionario.
	     * De este listado el usuario podra seleccionar las solicitudes que desee desincorporar.
	     * Si el post enviado por el formulario donde se selecciona al funcionario no es valido
	     * se renderizara un mensaje de error.
	     * @return [type] [description]
	     */
	    public function actionTiposSolicitudesSegunFuncionario()
	    {
	    	$request = Yii::$app->request;
	    	if ( $request->isGet ) {
	    		$postData = $request->get();
	    		$id = $_SESSION['id'];
	    	} else {
	    		$postData = $request->post();
	    		// Se obtiene el identificador del funcionario.
	    		$id = isset($postData['id']) ? $postData['id'] : 0;
	    		$_SESSION['id'] = $id;
	    	}

	    	if ( $id > 0 ) {
	    		$_SESSION['id'] = $id;
	    		// Se muestra un listado con los tipos de solicitudes relacionados
	    		// a un funcionario. Este listado tendra la posibilidad de seleccion
	    		// multiple para la desincorporacion de la(s) solicitud(es).

	    		$model = New FuncionarioSearch();
	    		$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
	    		$model->load($postData);
	    		$dataProvider = $model->getDataProviderTipoSolicitudSegunFuncionario($id);

	    		$caption = Yii::t('backend', 'Remove Request');
	    		$funcionario = $model->getFuncionarioSegunId($id);
	    		$subCaption = $funcionario['apellidos'] . ' ' . $funcionario['nombres'];
	    		$listado = 3;
	    		$url = Url::to(['inactivar-seleccion-solicitud']);
	    		return $this->render('/funcionario/solicitud/seleccionar-solicitud-desincorporar', [
																		'model' => $model,
																		'dataProvider' => $dataProvider,
																		'caption' => $caption,
																		'subCaption' => $subCaption,
																		'listado' => $listado,
																		'url' => $url,
					]);
	    	} else {
    			self::actionAnularSession(['id']);
    			return MensajeController::actionMensaje(404);
	    	}
	    }



	    /***/
	    public function actionBuscarFuncionarioParaDesincorporar($tipoSolicitud, $impuesto, $params = '')
		{
			$caption = Yii::t('backend', 'Remove Request');
			if ( trim($params) != '' ) {
				//	Lista de funcionarios, si saber que solicitudes tienen asociada.
				$listado = 2;

				$url = Url::to(['tipos-solicitudes-segun-funcionario']);
				$model = New FuncionarioSearch();
				$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
				$model->searchGlobal = $params;

				$subCaption = $params;

				// Se genera un dataprovider de funcionarios.
				$dataProvider = $model->getDataProviderFuncionarioParaDesincorporar();

				return $this->render('/funcionario/solicitud/seleccionar-funcionario-desincorporar', [
																		'model' => $model,
																		'dataProvider' => $dataProvider,
																		'caption' => $caption,
																		'subCaption' => $subCaption,
																		'listado' => $listado,
																		'url' => $url,
					]);


			} elseif ( $tipoSolicitud > 0 && $impuesto > 0 ) {
				// Lista de funcionario y se sabe a que solicitud estan relacionados. La utilizada en la busqueda.
				// Aqui la desincorpracion es directa porque ya se conoce a los funcioanrios que estas relacionados
				// a una solcitud.
				$listado = 1;
				//$url = Url::to(['prueba']);
				$url = Url::to(['inactivar-seleccion-solicitud']);
				$model = New FuncionarioSearch();
				$model->scenario = self::SCENARIO_SEARCH_IMPUESTO_SOLICITUD;

				// Arreglo multi-dimensional que contiene el su primer elemento los datos del impuesto
				// y en su segundo elemento los datos del tipo de solicitud.
				$infoSolicitud = $model->getInfoSolicitudImpuesto($tipoSolicitud);

				$captionImpuesto = 'Impuesto: ' . $infoSolicitud['impuesto'][$impuesto];
				$captionSolicitud = 'Solicitud: ' . $infoSolicitud['solicitud'][$tipoSolicitud];
				$subCaption = $captionImpuesto . ' / ' . $captionSolicitud;

				// Se genera un dataprovider de funcionarios, busqueda realizada por el tipo de solicitud.
				$dataProvider = $model->getDataProviderFuncionarioParaDesincorporar($tipoSolicitud);

				return $this->render('/funcionario/solicitud/lista-funcionario-por-solicitud', [
																		'model' => $model,
																		'dataProvider' => $dataProvider,
																		'caption' => $caption,
																		'subCaption' => $subCaption,
																		'listado' => $listado,
																		'url' => $url,
					]);
			}

		}




		/**
		 * [actionIndex description]
		 * @return [type] [description]
		 */
		public function actionIndexCreate()
		{
			if ( isset(Yii::$app->user->identity->username) ) {
				$_SESSION['errListaFuncionario'] = '';
				$_SESSION['errListaSolicitud'] = '';
				$_SESSION['postIndex'] = null;

				$model = New FuncionarioSearch();
				$formName = $model->formName();
				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-search']) ) {
					$model->scenario = self::SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD;
				} elseif ( isset($postData['btn-search-parameters']) ) {
					$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
				} else {
					$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
				}

				if ( $model->load($postData) && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
				}

				$_SESSION['postIndex'] = $postData;
				if ( $model->load($postData) ) {
					if ( isset($postData['btn-search']) ) {
						// Busqueda de funcionarios por departamento y unidad.
						if ( $model->validate() ) {
							$idDepartamento = $postData[$formName]['id_departamento'];
							$idUnidad = $postData[$formName]['id_unidad'];
							return self::actionBuscarPorDepartamentoUnidad($idDepartamento, $idUnidad, $model);
						}

					} elseif ( isset($postData['btn-search-parameters']) ) {
						// Busqueda de los funcionarios por parametro, DNI, apellidos o nombres.
						if ( $model->validate() ) {
							$params = $postData[$formName]['searchGlobal'];
							return self::actionBuscarFuncionarioPorParametros($params, $model);
						}
					} elseif ( isset($postData['btn-search-all']) ) {
						// Busqueda de todos los funcionarios con cuentas vigentes.
						return self::actionBuscarFuncionarioAll($model);
					}
				}

				// Modelo adicionales para la busqueda de los funcionarios.
				$modelDepartamento = New DepartamentoForm();
				$modelUnidad = New UnidadDepartamentoForm();

				// Se define la lista de item para el combo de departamentos.
				$listaDepartamento = $modelDepartamento->getListaDepartamento();

				return $this->render('/funcionario/solicitud/funcionario-solicitud-form', [
																				'model' => $model,
																				'modelDepartamento' => $modelDepartamento,
																				'modelUnidad' => $modelUnidad,
																				'caption' => 'Assign Request to Official',
																				'listaDepartamento' => $listaDepartamento,

					]);

			} else {
				// No esta definido el usuario. Eliminar todas las variables de session y salir.
				return MensajeController::actionMensaje(999);
			}
		}



		/**
		 * [actionBeginSave description]
		 * @param  [type] $postData [description]
		 * @param  [type] $action   [description]
		 * @return [type]           [description]
		 */
		private function actionBeginSave($postData, $action)
		{
			$result = false;
			if ( strtolower($action) == 'create' ) {
				if ( count($postData) > 0 ) {
					$conexion = New ConexionController();

	  				// Instancia de conexion hacia la base de datos.
	  				$this->conn = $conexion->initConectar('db');
	  				$this->conn->open();

	  				// Instancia de transaccion.
	  				$transaccion = $this->conn->beginTransaction();

	  				$result = self::actionCreate($postData, $conexion, $this->conn);
	  				if ( $result ) {
	  					$transaccion->commit();
	  				} else {
	  					$transaccion->rollBack();
	  				}

	  				$this->conn->close();
				}
			} if ( strtolower($action) == 'delete' ) {
				if ( count($postData) > 0 ) {
					$conexion = New ConexionController();

	  				// Instancia de conexion hacia la base de datos.
	  				$this->conn = $conexion->initConectar('db');
	  				$this->conn->open();

	  				// Instancia de transaccion.
	  				$transaccion = $this->conn->beginTransaction();

	  				$result = self::actionDelete($postData, $conexion, $this->conn);
	  				if ( $result ) {
	  					$transaccion->commit();
	  				} else {
	  					$transaccion->rollBack();
	  				}

	  				$this->conn->close();
				}
			}

			return $result;
		}



		/***/
		private function actionDelete($postData, $conexionLocal, $connLocal)
		{
			$result = false;
			// Se buscan los identificadores del registro.
			$chkIdFuncionarioSolicitud['id_funcionario_solic'] = $postData['chk-id-funcionario-solicitud'];

			// Modelo de la entidad principal a guardar.
			$model = New FuncionarioSolicitud();
			$tabla = $model->tableName();

			// Se crea un arreglo de datos con los atributos de la clase.
			//$arregloDatos = $model->attributes;

			$arregloDatos['observacion'] = 'DESINCORPORADO POR: ' . Yii::$app->user->identity->username . ' / ' . date('Y-m-d H:i:s');
			$arregloDatos['inactivo'] = 1;

			//foreach ( $chkIdFuncionarioSolicitud as $id ) {
				//$arregloCondicion['id_funcionario_solic'] = $funcionario;
				$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $chkIdFuncionarioSolicitud);
				//if ( !$result ) { break; }
			//}

			return $result;

		}






		/**
		 * [actionCreate description]
		 * @param  [type] $postData      [description]
		 * @param  [type] $conexionLocal [description]
		 * @param  [type] $connLocal     [description]
		 * @return [type]                [description]
		 */
		private function actionCreate($postData, $conexionLocal, $connLocal)
		{
			$result = false;
			// Se buscan los identificadores de los funcionarios. Lo siguiente es un array de identificadores.
			$chkFuncionario = $postData['chk-funcionario'];

			// Se buscan los identificadores de las solicitudes. Lo siguiente es un array de identificadores.
			$chkSolicitud = $postData['chk-solicitud'];

			// Modelo de la entidad principal a guardar.
			$model = New FuncionarioSolicitud();
			$tabla = $model->tableName();

			// Se crea un arreglo de datos con los atributos de la clase.
			$arregloDatos = $model->attributes;

			$arregloDatos['id_funcionario_solic'] = null;
			$arregloDatos['usuario'] = Yii::$app->user->identity->username;
			$arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
			$arregloDatos['inactivo'] = 0;

			foreach ( $chkFuncionario as $funcionario ) {
				$arregloDatos['id_funcionario'] = $funcionario;
				foreach ( $chkSolicitud as $solicitud ) {
					$arregloDatos['tipo_solicitud'] = $solicitud;
					// Se inactiva cualquier solicitud que tenga el funcionario y que coincida con la que se guardara.
					$result = self::actionInactivarFuncionarioSolicitud($funcionario, $solicitud, $tabla, $conexionLocal, $connLocal);
					if ( $result ) {
						$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);
						if ( !$result ) { break; }
					} else {
						break;
					}
				}
				if ( !$result ) { break; }
			}

			return $result;

		}



		/**
		 * [actionVerificarEnvio description]
		 * @return [type] [description]
		 */
		public function actionVerificarEnvio()
		{
			$result = false;
			$_SESSION['errListaFuncionario'] = '';
			$_SESSION['errListaSolicitud'] = '';
			$request = Yii::$app->request;
			$postData = $request->post();

			$postIndex = $_SESSION['postIndex'];
			$model = New FuncionarioSearch();
			$formName = $model->formName();

			$varPost = $postData[$formName];
			$listado = $varPost['listado'];


			if ( isset($postData['btn-send-request']) ) {
				if ( $postData['btn-send-request'] == 1 ) {

					// El envio se realizo de forma correcta a traves del boton respectivo.
					// Se verifica que haya seleccionado algun funcionario y solicitud de las listas.
					$chkFuncionario = isset($postData['chk-funcionario']) ? $postData['chk-funcionario'] : null;
					$chkSolicitud = isset($postData['chk-solicitud']) ? $postData['chk-solicitud'] : null;

// die(var_dump($postData));

					if ( count($chkFuncionario) > 0 && count($chkSolicitud) > 0 ) {
						// Todo bien. Se puede guardar.
						$result = self::actionBeginSave($postData, "create");
						if ( $result ) {
							$this->redirect(['proceso-exitoso']);
						} else {
							$this->redirect(['error-operacion', 'cod' => 920]);
						}

					} else {
						if ( count($chkFuncionario) == 0 ) {
							$_SESSION['errListaFuncionario'] = 'Debe seleccionar al menos un funcionario';
						}
						if ( count($chkSolicitud) == 0 ) {
							$_SESSION['errListaSolicitud'] = 'Debe seleccionar al menos una solicitud';
						}
						if ( $listado == 1 ) {				// Viene de la consulta por Departamento y Unidades
							$model->scenario = self::SCENARIO_SEARCH_DEPARTAMENTO_UNIDAD;
							$model->load($postIndex);

							return self::actionBuscarPorDepartamentoUnidad($postIndex[$formName]['id_departamento'],
							 											   $postIndex[$formName]['id_unidad'],
							 											   $model);

						} elseif ( $listado == 2 ) {		// Viene de la consulta por parametros.
							$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
							$model->load($postIndex);
							return self::actionBuscarFuncionarioPorParametros($postIndex[$formName]['searchGlobal'],
							 												  $model);

						} elseif ( $listado == 3 ) {		// Viene de la consulta de todos los funcionarios.
							$model->scenario = self::SCENARIO_SEARCH_GLOBAL;
							$model->load($postIndex);
							return self::actionBuscarFuncionarioAll($model);
						}
					}
				} else {
					// El envio no se realizo al presionar el boton.
					return MensajeController::actionMensaje(404);
				}
			} else {
				return MensajeController::actionMensaje(404);
			}

		}




		/**
		 * [actionBuscarPorDepartamentoUnidad description]
		 * @param  [type] $idD   [description]
		 * @param  [type] $idU   [description]
		 * @param  [type] $model [description]
		 * @return [type]        [description]
		 */
		public function actionBuscarPorDepartamentoUnidad($idD, $idU, $model)
		{
			$listado = 1;

			$captionDepartamento = 'Departamento: ' . DepartamentoForm::getDescripcionDepartamento($idD);
			$captionUnidad = 'Unidad: ' . UnidadDepartamentoForm::getDescripcionUnidadDepartamento($idU);
			$subCaption = $captionDepartamento . ' / ' . $captionUnidad;

			$modelImpuesto = new ImpuestoForm();
			// Lista para el combo impuestos.
			$listaImpuesto = $modelImpuesto->getListaImpuesto();

			// Se genera un dataprovider con los parametros enviados.
			$dataProvider = $model->getDataProviderFuncionarioPorDepartamento($idD, $idU);

			return $this->render('/funcionario/solicitud/_list', [
														'model' => $model,
														'dataProvider' => $dataProvider,
														'caption' => Yii::t('backend', 'Assign Request to Official'),
														'subCaption' => $subCaption,
														'modelImpuesto' => $modelImpuesto,
														'listaImpuesto' => $listaImpuesto,
														'listado' => $listado,
				]);

		}





		/**
		 * [actionBuscarFuncionarioPorParametros description]
		 * @param  [type] $params [description]
		 * @param  [type] $model  [description]
		 * @return [type]         [description]
		 */
		public function actionBuscarFuncionarioPorParametros($params, $model)
		{
			$listado = 2;

			$modelImpuesto = new ImpuestoForm();
			// Lista para el combo impuestos.
			$listaImpuesto = $modelImpuesto->getListaImpuesto();

			$subCaption = $params;
			// puedo obtener el all() de todos los registros de un modelo de la siguinete manera
			/**
			 *  $m = $model->findFuncionarioVigente();
			 *  $m->all();
			 */

			// Se genera un dataprovider de todos los funcionarios con cuentas vigentes.
			$dataProvider = $model->getDataProviderFuncionarioVigente();

			return $this->render('/funcionario/solicitud/_list', [
														'model' => $model,
														'dataProvider' => $dataProvider,
														'caption' => Yii::t('backend', 'Assign Request to Official'),
														'subCaption' => $subCaption,
														'modelImpuesto' => $modelImpuesto,
														'listaImpuesto' => $listaImpuesto,
														'listado' => $listado,
				]);
		}





		/**
		 * [actionBuscarFuncionarioAll description]
		 * @param  [type] $model [description]
		 * @return [type]        [description]
		 */
		public function actionBuscarFuncionarioAll($model)
		{
			$listado = 3;

			$modelImpuesto = new ImpuestoForm();
			// Lista para el combo impuestos.
			$listaImpuesto = $modelImpuesto->getListaImpuesto();

			$subCaption = 'All';
			// puedo obtener el all() de todos los registros de un modelo de la siguinete manera
			/**
			 *  $m = $model->findFuncionarioVigente();
			 *  $m->all();
			 */

			// Se genera un dataprovider de todos los funcionarios con cuentas vigentes.
			$dataProvider = $model->getDataProviderFuncionarioVigente();

			return $this->render('/funcionario/solicitud/_list', [
														'model' => $model,
														'dataProvider' => $dataProvider,
														'caption' => Yii::t('backend', 'Assign Request to Official'),
														'subCaption' => $subCaption,
														'modelImpuesto' => $modelImpuesto,
														'listaImpuesto' => $listaImpuesto,
														'listado' => $listado,
				]);

		}



		/**
		 * [actionListaImpuestoSolicitud description]
		 * @return [type] [description]
		 */
		public function actionListaImpuestoSolicitud()
		{
			$caption = Yii::t('backend', 'List of Request');
			$request = Yii::$app->request;
			$getData = $request->get();
			$impuesto = $getData['id'];		// Indice del combo impuesto.
			$modelSolicitud = New TipoSolicitudSearch();
			$modelSolicitud->load($getData);
			$dataProvider = $modelSolicitud->getDataProviderSolicitudImpuesto($impuesto);

			return $this->renderAjax('/funcionario/solicitud/lista-impuesto-solicitud', [
														'modelSolicitud' => $modelSolicitud,
														'dataProvider' => $dataProvider,
														'caption' => $caption,
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
			return $this->render('/funcionario/quit');
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
							'errListaFuncionario',
							'errListaSolicitud',
							'idD',
							'idU',
							'postIndex',
							'id',
					];
		}



		/**
		 * [actionInactivarFuncionarioSolicitud description]
		 * @param  [type] $idFuncionario [description]
		 * @param  [type] $tipoSolicitud [description]
		 * @param  [type] $tabla         [description]
		 * @param  [type] $conexionLocal [description]
		 * @param  [type] $connLocal     [description]
		 * @return [type]                [description]
		 */
		public function actionInactivarFuncionarioSolicitud($idFuncionario, $tipoSolicitud, $tabla, $conexionLocal, $connLocal)
		{
			$result = false;
			$arregloCondicion = [
					'id_funcionario' => $idFuncionario,
					'tipo_solicitud' => $tipoSolicitud
			];
			$arregloDatos = ['inactivo' => 1];
			$result = $conexionLocal->modificarRegistro($connLocal, $tabla, $arregloDatos, $arregloCondicion);

			return $result;
		}


	}

 ?>