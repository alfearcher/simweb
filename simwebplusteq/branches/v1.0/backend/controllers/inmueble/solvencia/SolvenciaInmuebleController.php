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
 *	@file SolvenciaInmuebleController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2016
 *
 *  @class SolvenciaInmuebleController
 *	@brief ClaseSolvenciaInmuebleController del lado del contribuyente backend.
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


 	namespace backend\controllers\inmueble\solvencia;


 	use Yii;
 	use yii\base\Model;
 	use yii\helpers\ArrayHelper;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\documento\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\enviaremail\PlantillaEmail;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
	use backend\models\inmueble\solvencia\SolvenciaInmuebleForm;
	use backend\models\inmueble\solvencia\SolvenciaInmuebleSearch;
	use backend\models\solvencia\SolvenciaForm;
	use backend\models\solvencia\SolvenciaSearch;
	use backend\models\aaee\historico\solvencia\HistoricoSolvenciaSearch;
	use backend\models\impuesto\Impuesto;
	use common\models\configuracion\solicitudplanilla\SolicitudPlanillaSearch;



	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de solvencias de vehiculos.
	 */
	class SolvenciaInmuebleController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;


		/**
		 * Identificador de  configuracion de la solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 116;


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion solicitada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin']);
			$request = Yii::$app->request;
			$getData = $request->get();

			$postData = $request->post();
			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {

					// Se redirecciona al metodo para chequear que puede crear la solicitud
					return $this->redirect(['check']);

				} else {
					// No esta definido el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 404]);
				}
			} else {
				// Solicitud no valida
				throw new NotFoundHttpException(Yii::t('frontend', 'No se pudo obtener la informacion de la configuracion de la solicitud'));
			}
		}





		/**
		 * Metodo que verifica que no exista ninguna solicitud que
		 * @return [type] [description]
		 */
		public function actionCheck()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'conf']);
			$mensajes = '';
			if ( isset($_SESSION['idContribuyente']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];

				$mensajes = [];
				$searchSolvencia = New SolvenciaInmuebleSearch($idContribuyente);
				$mensajes = $searchSolvencia->validarEvento(date('Y'));

				if ( count($mensajes) == 0 ) {
					$modelParametro = New ParametroSolicitud(self::CONFIG);
					// Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
					// del parametro y el valor del elemento es el contenido del campo en base de datos.
					$config = $modelParametro->getParametroSolicitud([
															'id_config_solicitud',
															'tipo_solicitud',
															'impuesto',
															'nivel_aprobacion'
												]);

					if ( count($config) > 0 ) {
						$_SESSION['conf'] = $config;
						$_SESSION['begin'] = 1;
						$this->redirect(['index-create']);
					} else {
						// No se obtuvieron los parametros de la configuracion.
						return $this->redirect(['error-operacion', 'cod' => 955]);
					}
				} else {
					// Mostrar mensajes que indica porque no puede continuar con la solicitud.
					return $this->render('@frontend/views/inmueble/solvencia/mensaje-error',[
														'mensajes' => $mensajes
							]);
				}

			} else {
				// No esta defino el contribuyente.
				throw new NotFoundHttpException(Yii::t('frontend', 'No se pudo obtener la informacion de inicio de session'));
			}

		}




		/**
		 * Metodo que inicia la carga del formulario que permite realizar la solicitud
		 * de anexo de ramos.
		 * @return view
		 */
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf']) ) {

				$idContribuyente = $_SESSION['idContribuyente'];
				$conf = $_SESSION['conf'];

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$model = New SolvenciaInmuebleForm();
				$model->load($postData);

				$formName = $model->formName();

				$caption = Yii::t('frontend', 'Solicitud de Solvencia de Inmueble');
				$subCaption = Yii::t('frontend', 'Lista de Inmueble(s)');

				// Mensaje que se setea cuando no se realizo ninguna seleccion y quiere crear una solicitud.
				$controlSeleccion = '';

				$chkIdImpuesto = [];
				if ( isset($postData['btn-create']) ) {
					if ( $postData['btn-create'] == 5 ) {
						if ( !isset($postData['chkIdImpuesto']) ) {
							$controlSeleccion = Yii::t('frontend', 'No ha realizado ninguna seleccion');
						} else {
							$chkIdImpuesto = $postData['chkIdImpuesto'];
						}
					}
				}

		      	// Lista de inmuebles.
		      	$searchSolvencia = New SolvenciaInmuebleSearch($idContribuyente);
		      	$provider = $searchSolvencia->getDataProviderInmueble($chkIdImpuesto);

		      	// Datos generales del contribuyente.
		      	$findModel = $searchSolvencia->findContribuyente();

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$postData = [];			// Inicializa el post.
						$model->load($postData);
						$this->redirect(['index', 'id' => self::CONFIG]);
					}

				} elseif ( isset($postData['btn-create']) ) {
					if ( $postData['btn-create'] == 5 ) {
						if ( $model->load($postData) ) {
			    			if ( $model->validate() && trim($controlSeleccion) == '' ) {
			    				// Aqui se muestra la lista de vehiculos seleccionada.
			    				$caption = 'Confirmar. ' . $caption;
			    				$subCaption = 'Seleccion realizada. ' . $subCaption;
			    				return $this->render('@frontend/views/inmueble/solvencia/pre-view-create-solvencia',[
		  															'model' => $model,
		  															'caption' => $caption,
		  															'subCaption' => $subCaption,
		  															'dataProvider' => $provider,
		  															'findModel' => $findModel,
		  								]);
							}
						}
					}

				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 5 ) {
						// Guardar la confirmacion
						$chkIdImpuesto = $postData['chkIdImpuesto'];
						foreach ( $chkIdImpuesto as $key => $value ) {

							$models[$key] = New SolvenciaInmuebleForm();
							$models[$key]->load($postData);
							$models[$key]['id_impuesto'] = $value;
							$models[$key]['ultimo_pago'] = $provider->allModels[$value]['ultimoPago'];
							$models[$key]['direccion'] = $provider->allModels[$value]['descripcion'];
							$models[$key]['catastro'] = $provider->allModels[$value]['catastro'];
						}

						$result = self::actionBeginSave($models, $postData);
						self::actionAnularSession(['begin', 'conf']);
  						if ( $result ) {
							$this->_transaccion->commit();
							$this->_conn->close();
							return self::actionView($models);
						} else {
							$this->_transaccion->rollBack();
							$this->_conn->close();
							$this->redirect(['error-operacion', 'cod'=> 920]);

  						}

					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		  		if ( count($findModel) > 0 ) {
		  			$provider = $searchSolvencia->getDataProviderInmueble();

		  			if ( $provider !== null ) {
		  				$añoImpositivo = (int)date('Y');
		  				$model->ano_impositivo = $añoImpositivo;
		  				$model->id_contribuyente = $idContribuyente;
		  				$model->id_impuesto = 0;
		  				$model->impuesto = $conf['impuesto'];
		  				$model->usuario = Yii::$app->identidad->getUsuario();
		  				$model->fecha_hora = date('Y-m-d H:i:s');
		  				$model->origen = 'LAN';
		  				$model->direccion = '';
		  				$model->catastro = '';

		  				// Mostrar lista de inmueble(s).
			  			return $this->render('@frontend/views/inmueble/solvencia/_list',[
			  												'model' => $model,
			  												'caption' => $caption,
			  												'subCaption' => $subCaption,
			  												'dataProvider' => $provider,
			  												'findModel' => $findModel,
			  												'controlSeleccion' => $controlSeleccion,
			  					]);
		  			} else {
		  				// No tiene inmuebles activos que mostrar.
		  				$this->redirect(['error-operacion', 'cod' => 509]);
		  			}

		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}





		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $model modelo del tipo de clase SolvenciaInmuebleForm.
		 * @param array $postEnviado post enviado desde el formulario.
		 * @return boolean retorna true si se realizan todas las operacions de
		 * insercion y actualizacion con exitos o false en caso contrario.
		 */
		private function actionBeginSave($models, $postEnviado)
		{
			$result = false;
			$nroSolicitud = 0;

			if ( isset($_SESSION['idContribuyente']) ) {
				if ( isset($_SESSION['conf']) ) {
					$conf = $_SESSION['conf'];

					// Solicitudes generadas.
					$listaNroSolicitud = [];

					$this->_conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->_conn = $this->_conexion->initConectar('db');
	      			$this->_conn->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();

					foreach ( $models as $model ) {

						$nroSolicitud = self::actionCreateSolicitud($model, $conf);
						if ( $nroSolicitud > 0 ) {

							$model->nro_solicitud = $nroSolicitud;

							// Se pasa a guardar en la sl_solvencias.
							$result = self::actionCreateSolicitudSolvencia($model, $conf, $this->_conexion, $this->_conn);

							if ( $result ) {
								$result = self::actionCreateHistoricoSolvencia($model, $conf, $this->_conexion, $this->_conn);
							}

							if ( $result ) {
								$result = self::actionCreateSolvencia($model, $conf, $this->_conexion, $this->_conn);
							}

							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($this->_conexion, $this->_conn, $model, $conf);
							}

							if ( !$result ) { break; }
						}
					}

					if ( $result ) {
						foreach ( $models as $model ) {
							$result = self::actionEnviarEmail($model, $conf);
							$result = true;
						}
					}

				} else {
					// No se obtuvieron los parametros de la configuracion.
					$this->redirect(['error-operacion', 'cod' => 955]);
				}
			} else {
				// No esta defino el contribuyente.
				$this->redirect(['error-operacion', 'cod' => 932]);
			}
			return $result;
		}




		/**
		 * Metodo que guarda el registro respectivo en la entidad "solicitudes-contribuyente".
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de connection.
		 * @param  model $model modelo de DeclaracionBaseForm.
		 * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @return boolean retorna true si guardo correctamente o false sino guardo.
		 */
		private function actionCreateSolicitud($model, $conf)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$user = Yii::$app->identidad->getUsuario();
			$nroSolicitud = 0;
			$modelSolicitud = New SolicitudesContribuyenteForm();
			$tabla = $modelSolicitud->tableName();
			$idContribuyente = $_SESSION['idContribuyente'];

			$nroSolicitud = 0;

			if ( count($conf) > 0 ) {
				// Valores que se pasan al modelo:
				// id-config-solicitud.
				// impuesto.
				// tipo-solicitud.
				// nivel-aprobacion
				$modelSolicitud->attributes = $conf;

				if ( $conf['nivel_aprobacion'] == 1 ) {
					$estatus = 1;
					$userFuncionario = $user;
					$fechaHoraProceso = date('Y-m-d H:i:s');
				}

				$modelSolicitud->id_contribuyente = $idContribuyente;
				$modelSolicitud->id_impuesto = $model->id_impuesto;
				$modelSolicitud->usuario = $user;
				$modelSolicitud->fecha_hora_creacion = date('Y-m-d H:i:s');
				$modelSolicitud->inactivo = 0;
				$modelSolicitud->estatus = $estatus;
				$modelSolicitud->nro_control = 0;
				$modelSolicitud->user_funcionario = $userFuncionario;
				$modelSolicitud->fecha_hora_proceso = $fechaHoraProceso;
				$modelSolicitud->causa = 0;
				$modelSolicitud->observacion = '';

				// Arreglo de datos del modelo para guardar los datos.
				$arregloDatos = $modelSolicitud->attributes;

				if ( $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDatos) ) {
					$nroSolicitud = $this->_conn->getLastInsertID();
				}
			}

			return $nroSolicitud;
		}



	    /**
	     * Metodo que crea el historico de solvencias, esto aplica si la solicitud de solvencia
	     * es de aprobacion directa.
		 * @param  model $model modelo del tipo de clase SolvenciaInmuebleForm.
	     * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
	     * @return boolean retorna true si guarda satisfactoriamente.
	     */
	    private static function actionCreateSolicitudSolvencia($model, $conf, $conexion, $conn)
	    {
	    	$result = false;

	    	if ( isset($_SESSION['idContribuyente']) ) {
	    		$idContribuyente = $_SESSION['idContribuyente'];

	    		$estatus = 0;
				$userFuncionario = '';
				$fechaHoraProceso = '0000-00-00 00:00:00';

	    		if ( $conf['nivel_aprobacion'] == 1 ) {
					$estatus = 1;
					$userFuncionario = Yii::$app->identidad->getUsuario();
					$fechaHoraProceso = date('Y-m-d H:i:s');
				}

				$model->estatus = $estatus;
				$model->user_funcionario = $userFuncionario;
				$model->fecha_hora_proceso = $fechaHoraProceso;

	    		// Tabla
	    		$tabla = $model->tableName();

    			// Se crea un arreglo de valores para realizar la insercion en lote.
    			$arregloDatos = $model->attributes;

	    		$result = $conexion->guardarRegistro($conn, $tabla, $arregloDatos);

			}

	    	return $result;
	    }





	    /**
	     * Metodo que crea el historico de declaraciones, esto aplica si la solicitud de solvencia
	     * es de aprobacion directa.
		 * @param  model $model modelo del tipo de clase SolvenciaInmuebleForm.
	     * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
	     * @return boolean retorna true si guarda satisfactoriamente.
	     */
	    private static function actionCreateHistoricoSolvencia($model, $conf, $conexionLocal, $connLocal)
	    {
	    	$result = [];
	    	if ( $conf['nivel_aprobacion'] == 1 ) {
		    	if ( isset($_SESSION['idContribuyente']) ) {
		    		$idContribuyente = $_SESSION['idContribuyente'];

		    		$search = New HistoricoSolvenciaSearch($idContribuyente, 1);

		    		$searchSolvencia = New SolvenciaInmuebleSearch($idContribuyente, $model->id_impuesto);
	    			$fechaVcto = $searchSolvencia->determinarFechaVctoSolvencia();
	    			if ( $fechaVcto == '' ) {
	    				$fechaVcto = '0000-00-00';
	    			}

	    			$impuesto = Impuesto::findOne($model->impuesto);
	    			$tipoImpuesto = $impuesto->descripcion;

		    		// Se arma la informacion del contribuyente para la licencia.
		    		$contribuyente = ContribuyenteBase::findOne($idContribuyente);
		    		if ( $contribuyente->tipo_naturaleza == 0 ) {
						$cedualRif = $contribuyente->naturaleza . '-' . $contribuyente->cedula;
						$descripcion = $contribuyente->apellidos . ' ' . $contribuyente->nombres;
		    		} elseif ( $contribuyente->tipo_naturaleza == 1 ) {
		    			$cedualRif = $contribuyente->naturaleza . '-' . $contribuyente->cedula . '-' . $contribuyente->tipo;
						$descripcion = $contribuyente->razon_social;
		    		}

		    		$arregloContribuyente = [
		    				'id_contribuyente' => $idContribuyente,
		    				'nro_solicitud' => $model->nro_solicitud,
		    				'rif' => $cedualRif,
		    				'descripcion' => $descripcion,
		    				'domicilio' => $model->direccion,
		    				'licencia' => $contribuyente->id_sim,
		    				'placa' => 0,
		    				'catastro' => $model->catastro,
		    				'fechaEmision' => date('Y-m-d', strtotime($model->fecha_hora)),
		    				'fechaVcto' => $fechaVcto,
		    				'liquidacion' => 0,
		    				'tipoImpuesto' => $tipoImpuesto,
		    				// 'id_impuesto' => $model->id_impuesto;
		    		];

		    		$fuente_json = json_encode($arregloContribuyente);

					$arregloDatos = $search->attributes;

					$arregloDatos['id_contribuyente'] = $model->id_contribuyente;
					$arregloDatos['ano_impositivo'] = $model->ano_impositivo;
					$arregloDatos['nro_solicitud'] = $model->nro_solicitud;
					$arregloDatos['impuesto'] = $model->impuesto;
					$arregloDatos['fecha_emision'] = date('Y-m-d', strtotime($model->fecha_hora));
					$arregloDatos['fecha_vcto'] = $fechaVcto;
					$arregloDatos['id_impuesto'] = $model->id_impuesto;
					$arregloDatos['nro_control'] = '';
					$arregloDatos['serial_control'] = '';
					$arregloDatos['fuente_json'] = $fuente_json;
					$arregloDatos['observacion'] = 'SOLICITUD SOLVENCIA INMUEBLE ' . $model->observacion;
					$arregloDatos['inactivo'] = 0;
					$arregloDatos['usuario'] = $model->usuario;
					$arregloDatos['fecha_hora'] = $model->fecha_hora;

					$result = $search->guardar($arregloDatos, $conexionLocal, $connLocal);
					if ( $result['id'] > 0 ) {
						return true;
					} else {
						return false;
					}
				}
	    	}
	    	return true;
	    }




	    /**
	     * Metodo que realiza la insercion en la entidad "solvencias".
	     * @param  model $model modelo del tipo de clase SolvenciaInmuebleForm.
	     * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
	     * solicitud.
	     * @param  conexioncontroller $conexionLocal clase ConexionController
	     * @param  connection $connLocal instancia.
	     * @return boolean retorna true si guarda de forma satisfactoria, false en caso contrario.
	     */
	    private static function actionCreateSolvencia($model, $conf, $conexionLocal, $connLocal)
	    {
	    	$result = true;
	    	if ( $conf['nivel_aprobacion'] == 1 ) {
	    		if ( isset($_SESSION['idContribuyente']) ) {
	    			$idContribuyente = $_SESSION['idContribuyente'];

	    			$searchSolvencia = New SolvenciaInmuebleSearch($idContribuyente, $model->id_impuesto);
	    			$fechaVcto = $searchSolvencia->determinarFechaVctoSolvencia();
	    			if ( $fechaVcto == '' ) {
	    				$fechaVcto = '0000-00-00';
	    			}

	    			$solvencia = New SolvenciaForm();

	    			$tabla = $solvencia->tableName();
	    			$arregloDatos = $solvencia->attributes;

	    			foreach ( $arregloDatos as $key => $value ) {
	    				if ( isset($model->$key) ) {
	    					$arregloDatos[$key] = $model->$key;
	    				}
	    			}

	    			$arregloDatos['ente'] = Yii::$app->ente->getEnte();
	    			$arregloDatos['serial_solvencia'] = '';
	    			$arregloDatos['fecha_emision'] = date('Y-m-d', strtotime($model->fecha_hora));
	    			$arregloDatos['fecha_vcto'] = $fechaVcto;
	    			$arregloDatos['status_solvencias'] = 0;
	    			$arregloDatos['nro_solvencia'] = 0;

	    			$searchSolvencia = New SolvenciaSearch($idContribuyente);
	    			$result = $searchSolvencia->guardar($arregloDatos, $conexionLocal, $connLocal);

	    		}
	    	}

	    	return $result;

	    }






		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  ConexionController  $conexionLocal instancia de la clase ConexionController
		 * @param  connection  $connLocal instancia de connection.
		 * @param  model $models arreglo de modelo de DeclaracionBaseForm.
		 * @param  array $postEnviado post enviado por el formulario. Lo que
		 * se busca es determinar los items seleccionados como documentos y/o
		 * requisitos a consignar para guardarlos.
		 * @return boolean retorna true si guarda efectivamente o false en caso contrario.
		 */
		private static function actionCreateDocumentosConsignados($conexionLocal, $connLocal, $models, $postEnviado)
		{
			$result = false;
			if ( isset($conexionLocal) && isset($connLocal) && isset($models) && count($postEnviado) > 0 ) {
				$modelDocumento = New DocumentoConsignadoForm();
				$tabla = $modelDocumento->tableName();
				$arregloCampos = $modelDocumento->attributes();

				$datosInsert['id_doc_consignado'] = null;
				$datosInsert['id_documento'] = 0;
				$datosInsert['id_contribuyente'] = $model->id_sede_principal;
				$datosInsert['id_impuesto'] = 0;
				$datosInsert['impuesto'] = 1;
				$datosInsert['nro_solicitud'] = $model->nro_solicitud;
				$datosInsert['codigo_proceso'] = null;
				$datosInsert['fecha_hora'] = $model->fecha_hora;
				$datosInsert['usuario'] = $model->user_funcionario;
				$datosInsert['estatus'] = $model->estatus;

				// Se obtiene el arreglo de el o los items de documentos y/o reuisitos
				// seleccionados. Basicamente lo que se obtiene es el identificador (id_documento)
				// del registro.
				$arregloChkDocumeto = $postEnviado['chkDocumento'];
				if ( count($arregloChkDocumeto) > 0 ) {
					foreach ( $arregloChkDocumeto as $documento ) {
						$datosInsert['id_documento'] = $documento;
						$arregloDatos[] = $datosInsert;
					}

					$result = $conexionLocal->guardarLoteRegistros($connLocal, $tabla, $arregloCampos, $arregloDatos);
				} else {
					$result = true;
				}
			}
			return $result;
		}




		/**
		 * Metodo que se encargara de gestionar la ejecucion y resultados de los procesos relacionados
		 * a la solicitud. En este caso los proceso relacionados a la solicitud en el evento "CREAR".
		 * Se verifica si se ejecutaron los procesos y si los mismos fueron todos positivos. Con
		 * el metodo getAccion(), se determina si se ejecuto algun proceso, este metodo retorna un
		 * arreglo, si el mismo es null se asume que no habia procesos configurados para que se ejecutaran
		 * cuando la solicitud fuese creada. El metodo resultadoEjecutarProcesos(), permite determinar el
		 * resultado de cada proceso que se ejecuto.
		 * @param  ConexionController $conexionLocal instancia de la clase ConexionController.
		 * @param  connection $connLocal instancia de conexion que permite ejecutar las acciones en base
		 * de datos.
		 * @param  model $model modelo de la instancia LicenciaSolicitudForm.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna true si todo se ejecuto correctamente false en caso contrario.
		 */
		private function actionEjecutaProcesoSolicitud($conexionLocal, $connLocal, $model, $conf)
		{
			$result = true;
			$resultadoProceso = [];
			$acciones = [];
			$evento = '';
			if ( count($conf) > 0 ) {
				if ( $conf['nivel_aprobacion'] == 1 ) {
					$evento = Yii::$app->solicitud->crear();
				} else {
					$evento = Yii::$app->solicitud->crear();
				}


				$procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

				// Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
				// que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
				// de resultados donde el key del arrary es el nombre del proceso ejecutado y el valor
				// del elemento corresponda a un reultado de la ejecucion. La variable $model debe contener
				// el identificador del contribuyente que realizo la solicitud y el numero de solicitud.
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, $evento, $conexionLocal, $connLocal);

				// Se obtiene un array de acciones o procesos ejecutados. Sino se obtienen acciones
				// ejecutadas se asumira que no se configuraro ningun proceso para que se ejecutara
				// cuando se creara la solicitud.
				$acciones = $procesoEvento->getAccion();

				if ( count($acciones) > 0 ) {

					// Se evalua cada accion o proceso ejecutado para determinar si se realizo satisfactoriamnente.
					$resultadoProceso = $procesoEvento->resultadoEjecutarProcesos();

					if ( count($resultadoProceso) > 0 ) {
						foreach ( $resultadoProceso as $key => $value ) {
							if ( $value == false ) {
								$result = false;
								break;
							}
						}
					}
				}
			} else {
				$result = false;
			}

			return $result;

		}



		/**
		 * Metodo que permite enviar un email al contribuyente indicandole
		 * la confirmacion de la realizacion de la solicitud.
		 * @param  model $model modelo LicenciaSolicitudForm que contiene la informacion
		 * del identificador del contribuyente.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna un true si envio el correo o false en caso
		 * contrario.
		 */
		private function actionEnviarEmail($model, $conf)
		{
			$result = false;
			$listaDocumento = '';
			if ( count($conf) > 0 ) {
				$parametroSolicitud = New ParametroSolicitud($conf['id_config_solicitud']);
				$nroSolicitud = $model->nro_solicitud;
				$descripcionSolicitud = $parametroSolicitud->getDescripcionTipoSolicitud();
				$listaDocumento = $parametroSolicitud->getDocumentoRequisitoSolicitud();

				$email = ContribuyenteBase::getEmail($model->id_contribuyente);
				try {
					$enviar = New PlantillaEmail();
					$result = $enviar->plantillaEmailSolicitud($email, $descripcionSolicitud, $nroSolicitud, $listaDocumento);
				} catch ( Exception $e ) {
					echo $e->getName();
				}
			}
			return $result;
		}




		/**
		 * Metodo que permite incocar las functiones que permitira actualizar el json del historico
		 * @param  SolvenciaVehiculoForm $models modelo que permitio guardar las solicitudes.
		 * @return view
		 */
		public function actionView($models)
    	{
    		if ( isset($_SESSION['idContribuyente']) ) {
	    		if ( count($models) > 0 ) {
	    			foreach ( $models as $model ) {

	    				$search = New HistoricoSolvenciaSearch($model->id_contribuyente,  $model->impuesto);
 						$historico = $search->findHistoricoSolvenciaSegunSolicitud($model->nro_solicitud);

		 				if ( isset($historico[0]['id_historico']) ) {
		 					self::actionUpdateTasaHistorico($historico[0]['id_historico'], $search, $historico[0]['nro_solicitud']);
		 				}
	    			}

	    			return self::actionMostrarSolicitudCreada($models);

	    		} else {
	    			throw new NotFoundHttpException('Error ');
	    		}
	    	} else {
	    		throw new NotFoundHttpException('El contribuyente no esta defino');
	    	}
    	}




    	/***/
    	public function actionMostrarSolicitudCreada($models)
    	{
    		if ( count($models) > 0 ) {
    			foreach ( $models as $model ) {
    				$listaNroSolicitud[] = $model->nro_solicitud;
    				$idContribuyente = $model->id_contribuyente;
    			}

	    		$search = New SolvenciaInmuebleSearch($idContribuyente);
	    		$dataProvider = $search->getDataProviderSolicitud($listaNroSolicitud);

	    		$opciones = [
					'quit' => '/inmueble/solvencia/solvencia-inmueble/quit',
				];

				$caption = Yii::t('frontend', 'Solicitud Creada');
				$subCaption = Yii::t('frontend', 'Solicitud');
	    		return $this->render('@frontend/views/inmueble/solvencia/_view',[
												'model' => $models,
												'dataProvider' => $dataProvider,
												'codigo' => 100,
												'opciones' => $opciones,
												'caption' => $caption,
												'subCaption' => $subCaption,
	    					]);
    		}
    	}






    	/**
    	 * Metodo que invoca una funcion para actualizar el atributo "fuente-json"
    	 * de la entidad "historico-solvencias"
    	 * @param integer $idHistorico identificador del historico.
    	 * @param  HistoricoSolvenciaSearch $historicoSearch
    	 * @param  integer $nroSolicitud identificador de la solicitud creada.
    	 * @return
    	 */
    	private function actionUpdateTasaHistorico($idHistorico, $historicoSearch, $nroSolicitud)
    	{

			// Se identifica la tasa liquidada por el concepto de solicitud de solvencia
            $searchPlanilla = New SolicitudPlanillaSearch($nroSolicitud,
                                                          Yii::$app->solicitud->crear());

            $findModel = $searchPlanilla->findSolicitudPlanilla();
            $planillas = $findModel->one();

            $liquidacion = 0;
            if ( isset($planillas->planilla) ) {
                $liquidacion = $planillas->planilla;
                $historicoSearch->actualizarLiquidacionHistorico($idHistorico, $liquidacion);
            }

            return true;

    	}




    	/**
		 * Metodo salida del modulo.
		 * @return view
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/menu/menu-vertical');
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
		 * Metodo que renderiza una vista indicando que le proceso se ejecuto
		 * satisfactoriamente.
		 * @param  integer $cod codigo que permite obtener la descripcion del
		 * codigo de la operacion.
		 * @return view.
		 */
		public function actionProcesoExitoso($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}



		/**
		 * Metodo que renderiza una vista que indica que ocurrio un error en la
		 * ejecucion del proceso.
		 * @param  integer $cod codigo que permite obtener la descripcion del
		 * codigo de la operacion.
		 * @return view.
		 */
		public function actionErrorOperacion($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
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
							'postData',
							'conf',
							'begin',
							'lapso'
					];
		}

	}
?>