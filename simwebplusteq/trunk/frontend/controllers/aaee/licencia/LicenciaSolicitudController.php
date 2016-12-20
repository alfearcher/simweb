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
 *	@file LicenciaSolicitudController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-11-2016
 *
 *  @class LicenciaSolicitudController
 *	@brief Clase LicenciaSolicitudController del lado del contribuyente frontend.
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


 	namespace frontend\controllers\aaee\licencia;


 	use Yii;
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
	use backend\models\aaee\licencia\LicenciaSolicitudSearch;
	use backend\models\aaee\licencia\LicenciaSolicitudForm;
	use backend\models\aaee\rubro\Rubro;
	use common\models\numerocontrol\NumeroControlSearch;
	use backend\models\aaee\historico\licencia\HistoricoLicenciaSearch;




	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de licencias.
	 */
	class LicenciaSolicitudController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;


		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 113;


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.

			self::actionAnularSession(['begin', 'tipo']);
			$request = Yii::$app->request;
			$getData = $request->get();

			$postData = $request->post();
			if ( isset($postData['btn-quit']) ) {
				if ( $postData['btn-quit'] == 1 ) {
					$this->redirect(['quit']);
				}
			}

			$urlNueva = 'index-nueva';
			$urlRenovacion = 'index-renovacion';
			$urlSalida = 'quit';

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {
					if ( ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($_SESSION['idContribuyente']) == 'JURIDICO' ) {
						// Se muestra un sud-menu de opciones donde el usuario especifica el tipo
						// de licencia, "Nueva" o "Renovacion".
						return $this->render('/aaee/licencia/sub-menu-tipo',[
													'urlNueva' => $urlNueva,
													'urlRenovacion' => $urlRenovacion,
													'urlSalida' => $urlSalida,
													'caption' => Yii::t('frontend', 'Seleccionar tipo de Licencia'),
							]);
					} else {
						// El tipo de naturaleza no es valido
						return $this->redirect(['error-operacion', 'cod' => 934]);
					}

				} else {
					// No esta definido el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 404]);
				}
			} else {
				// Solicitud no valida
				throw new NotFoundHttpException(Yii::t('frontend', 'No se pudo obtener la informacion de la configuracion de la solicitud'));
			}
		}



		/***/
		public function actionIndexNueva()
		{
			$_SESSION['tipo'] = 'NUEVA';
			$this->redirect(['check']);
		}



		/***/
		public function actionIndexRenovacion()
		{
			$_SESSION['tipo'] = 'RENOVACION';
			$this->redirect(['check']);
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
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['tipo']) ) {
				$idContribuyente = $_SESSION['idContribuyente'];
				$searchLicencia = New LicenciaSolicitudSearch($idContribuyente);

				$tipo = $_SESSION['tipo'];
				//$mensajes = [];
				$mensajes = $searchLicencia->validarEvento(date('Y'), $tipo);

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

					if ( isset($config) ) {
						$_SESSION['conf'] = $config;
						$_SESSION['begin'] = 1;
						$this->redirect(['index-create']);
					} else {
						// No se obtuvieron los parametros de la configuracion.
						return $this->redirect(['error-operacion', 'cod' => 955]);
					}
				} else {
					// Mostrar mensajes que indica porque no puede continuar con la solicitud.
					return $this->render('/aaee/licencia/mensaje-error',[
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
			self::actionAnularSession(['lapso']);
			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])  && isset($_SESSION['tipo']) ) {

				$tipoLicencia = $_SESSION['tipo'];
				$idContribuyente = $_SESSION['idContribuyente'];
				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				$model = New LicenciaSolicitudForm();
				$model->load($postData);

				$formName = $model->formName();

				$caption = Yii::t('frontend', 'Solicitud de Licencia');
				$subCaption = Yii::t('frontend', '');

		      	// Datos generales del contribuyente.
		      	$searchLicencia = New LicenciaSolicitudSearch($idContribuyente);
		      	$findModel = $searchLicencia->findContribuyente();

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$postData = [];			// Inicializa el post.
						$model->load($postData);
						$this->redirect(['index', 'id' => self::CONFIG]);
					}

				} elseif ( isset($postData['btn-create']) ) {
					if ( $postData['btn-create'] == 5 ) {
						if ( $model->load($postData) ) {
			    			if ( $model->validate() ) {
			    				// Vista previa
			    				$caption = 'Confirmar ' . $caption . '. ' . $tipoLicencia;
			    				$dataProvider = $searchLicencia->getDataProviderRubrosRegistrados($model->ano_impositivo, 1);
			    				return $this->render('/aaee/licencia/pre-view-create',[
		  												'model' => $model,
		  												'dataProvider' => $dataProvider,
		  												// 'tipo' => $tipoLicencia,
		  												// 'ano_impositivo' => $añoImpositivo,
		  												'periodo' => 1,
		  												'caption' => $caption,

		  					]);
							}
						}
					}

				} elseif ( isset($postData['btn-confirm-create']) ) {
					if ( $postData['btn-confirm-create'] == 3 ) {
						// Guardar la confirmacion
						$result = self::actionBeginSave($model, $postData);
						self::actionAnularSession(['begin']);
  						if ( $result ) {
							$this->_transaccion->commit();
							$this->_conn->close();
							return self::actionView($model->nro_solicitud);
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

		  		if ( isset($findModel) ) {
		  			$añoImpositivo = (int)date('Y');
		  			$dataProvider = $searchLicencia->getDataProviderRubrosRegistrados($añoImpositivo, 1);
		  			$model->id_contribuyente = $findModel->id_contribuyente;
		  			$model->ano_impositivo = $añoImpositivo;
		  			$model->tipo = $tipoLicencia;
		  			$model->usuario = Yii::$app->identidad->getUsuario();
		  			$model->fecha_hora = date('Y-m-d H:i:s');
		  			$model->origen = 'WEB';

		  			// Nro de licencia actual.
		  			$model->licencia = $findModel->id_sim;
		  			$caption .= '. ' . $tipoLicencia;
		  			return $this->render('/aaee/licencia/_create',[
		  												'model' => $model,
		  												'dataProvider' => $dataProvider,
		  												'tipo' => $tipoLicencia,
		  												'ano_impositivo' => $añoImpositivo,
		  												'periodo' => 1,
		  												'caption' => $caption,

		  					]);


		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  			$this->redirect(['error-operacion', 'cod' => 938]);
		  		}
			}
		}





		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $model modelo del tipo de clase LicenciaSolicitudForm.
		 * @param array $postEnviado post enviado desde el formulario.
		 * @return boolean retorna true si se realizan todas las operacions de
		 * insercion y actualizacion con exitos o false en caso contrario.
		 */
		private function actionBeginSave($model, $postEnviado)
		{
			$result = false;
			$nroSolicitud = 0;

			if ( isset($_SESSION['idContribuyente']) ) {
				if ( isset($_SESSION['conf']) ) {
					$conf = $_SESSION['conf'];

					// Identificadores de rurbos.
					$chkRubros = $postEnviado['chkRubro'];

					if ( count($chkRubros) > 0 ) {
						$this->_conexion = New ConexionController();

		      			// Instancia de conexion hacia la base de datos.
		      			$this->_conn = $this->_conexion->initConectar('db');
		      			$this->_conn->open();

		      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
		      			// Inicio de la transaccion.
						$this->_transaccion = $this->_conn->beginTransaction();

						$nroSolicitud = self::actionCreateSolicitud($model, $conf);
						if ( $nroSolicitud > 0 ) {

							$model->nro_solicitud = $nroSolicitud;

							// Se pasa a guardar en la sl_licencias.
							$result = self::actionCreateSolicitudLicencia($model, $conf, $chkRubros, $this->_conexion, $this->_conn);

							if ( $result ) {
								$result = self::actionCreateHistoricoLicencia($model, $conf, $chkRubros, $this->_conexion, $this->_conn);
							}

							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($this->_conexion, $this->_conn, $model, $conf);
							}

							if ( $result ) {
								$result = self::actionEnviarEmail($model, $conf);
								$result = true;
							}
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
			$user = isset($model->usuario) ? $model->usuario : Yii::$app->identidad->getUsuario();
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
				$modelSolicitud->id_impuesto = 0;
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
	     * Metodo que crea el historico de declaraciones, esto aplica si la solicitud de declaracion
	     * es de aprobacion directa.
		 * @param  model $model modelo del tipo de clase LicenciaSolicitudForm.
	     * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @param array $chkRubros arreglo de identificadores de los rubros.(id-rubro).
	     * @return boolean retorna true si guarda satisfactoriamente.
	     */
	    private static function actionCreateSolicitudLicencia($model, $conf, $chkRubros, $conexion, $conn)
	    {
	    	$result = false;

	    	if ( isset($_SESSION['idContribuyente']) && count($chkRubros) > 0 ) {
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

	    		// Arreglo de solo campos
	    		// [indice] => campo.
	    		// donde incice comienza en cero (0).
	    		$arregloCampos = $model->attributes();

	    		foreach ( $chkRubros as $key => $value ) {

	    			// Se crea un arreglo de valores para realizar la insercion en lote.
	    			$model->id_rubro = $value;
	    			$arregloDatos = $model->attributes;
	    			$arregloValores[] = array_values($arregloDatos);

	    		}

	    		$result = $conexion->guardarLoteRegistros($conn, $tabla, $arregloCampos, $arregloValores);

			}

	    	return $result;
	    }





	    /**
	     * Metodo que crea el historico de declaraciones, esto aplica si la solicitud de declaracion
	     * es de aprobacion directa.
		 * @param  model $model modelo del tipo de clase LicenciaSolicitudForm.
	     * @param  array $conf arreglo que contiene los parametros basicos de configuracion de la
		 * solicitud.
		 * @param array $chkRubros arreglo de identificadores de los rubros.(id-rubro).
	     * @return boolean retorna true si guarda satisfactoriamente.
	     */
	    private static function actionCreateHistoricoLicencia($model, $conf, $chkRubros, $conexionLocal, $connLocal)
	    {
	    	$result = [];
	    	if ( $conf['nivel_aprobacion'] == 1 ) {
		    	if ( isset($_SESSION['idContribuyente']) && count($chkRubros) > 0 ) {
		    		$idContribuyente = $_SESSION['idContribuyente'];
		    		$search = New HistoricoLicenciaSearch($idContribuyente);

		    		// Se arma la informacion del contribuyente para la licencia.
		    		$contribuyente = ContribuyenteBase::findOne($idContribuyente);

		    		$fechaVcto = date('Y') . '-12-31';
		    		$arregloContribuyente = [
		    				'id_contribuyente' => $idContribuyente,
		    				'nro_solicitud' => $model['nro_solicitud'],
		    				'rif' => $contribuyente->naturaleza . '-' . $contribuyente->cedula . '-' . $contribuyente->tipo,
		    				'descripcion' => $contribuyente->razon_social,
		    				'domicilio' => $contribuyente->domicilio_fiscal,
		    				'capital' => $contribuyente->capital,
		    				'representante' => $contribuyente->representante,
		    				'cedulaRep' => $contribuyente->naturaleza_rep . '-' . $contribuyente->cedula_rep,
		    				'catastro' => 0,
		    				'fechaEmision' => date('Y-m-d'),
		    				'fechaVcto' => $fechaVcto,

		    		];

		    		$fuente_json = json_encode($arregloContribuyente);


		    		// Se arma la informacion de los rubros.
					foreach ( $chkRubros as $key => $value ) {
						$infoRubro = Rubro::findOne($value);

						$rjson[] = [
								'nro_solicitud' => $model['nro_solicitud'],
								'id_contribuyente' => $model['id_contribuyente'],
								'ano_impositivo' => $model['ano_impositivo'],
								'id_rubro' => $infoRubro->id_rubro,
								'rubro' => $infoRubro->rubro,
								'descripcion' => $infoRubro->descripcion,
								'alicuota' => $infoRubro->alicuota,
								'minimo' => $infoRubro->minimo_ut,
							];
					}

					$arregloDatos = $search->attributes;

					$arregloDatos['id_contribuyente'] = $model->id_contribuyente;
					$arregloDatos['ano_impositivo'] = $model->ano_impositivo;
					$arregloDatos['nro_solicitud'] = $model->nro_solicitud;
					$arregloDatos['tipo'] = $model->tipo;
					$arregloDatos['licencia'] = $model->licencia;
					$arregloDatos['nro_control'] = '';
					$arregloDatos['serial_control'] = '';
					$arregloDatos['fuente_json'] = $fuente_json;
					$arregloDatos['rubro_json'] = json_encode($rjson);
					$arregloDatos['observacion'] = 'SOLICITUD LICENCIA';
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
					$evento = Yii::$app->solicitud->aprobar();
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
		 * Metodo que renderiza una vista con la informacion de la solicitud creada.
		 * @param  loong $id identificador de la solicitud creada.
		 * @return view retorna una vista con la informacion detalle de la solicitud.
		 * Informacion cargada por el contribuyente.
		 */
		public function actionView($id)
    	{
    		if ( isset($_SESSION['idContribuyente']) ) {
	    		if ( $id > 0 ) {
	    			$searchLicencia = New LicenciaSolicitudSearch($_SESSION['idContribuyente']);
	    			$findModel = $searchLicencia->findSolicitudLicencia($id);
	    			$dataProvider = $searchLicencia->getDataProviderSolicitud($id);
	    			if ( isset($findModel) ) {
	    				return self::actionShowSolicitud($findModel, $searchLicencia, $dataProvider);
	    			} else {
						throw new NotFoundHttpException('No se encontro el registro');
					}
	    		} else {
	    			throw new NotFoundHttpException('Error ' . $id);
	    		}
	    	} else {
	    		throw new NotFoundHttpException('El contribuyente no esta defino');
	    	}
    	}






    	/***/
    	private function actionShowSolicitud($findModel, $modelSearch, $dataProvider)
    	{
    		if ( isset($findModel) && isset($modelSearch) ) {
 				$model = $findModel->all();
 				self::actionAnularSession(['id_historico']);

 				$search = New HistoricoLicenciaSearch($model[0]->id_contribuyente);
 				$historico = $search->findHistoricoLicenciaSegunSolicitud($model[0]->nro_solicitud);

 				$_SESSION['id_historico'] = isset($historico[0]['id_historico']) ? $historico[0]['id_historico'] : null;

				$opciones = [
					'quit' => '/aaee/licencia/licencia-solicitud/quit',
				];
				return $this->render('/aaee/licencia/_view', [
														'codigo' => 100,
														'model' => $model,
														'modelSearch' => $modelSearch,
														'opciones' => $opciones,
														'dataProvider' => $dataProvider,
														'historico' => $historico,
														'caption' => 'Solicitud Creada Nro. ' . $model[0]->nro_solicitud,
					]);
			} else {
				throw new NotFoundHttpException('No se encontro el registro');
			}
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