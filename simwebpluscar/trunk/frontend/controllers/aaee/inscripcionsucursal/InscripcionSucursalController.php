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
 *	@date 21-07-2016
 *
 *  @class InscripcionSucursalController
 *	@brief Clase InscripcionSucursalController del lado del contribuyente frontend.
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


 	namespace frontend\controllers\aaee\inscripcionsucursal;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursal;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursalForm;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursalSearch;
	use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaForm;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\documento\DocumentoConsignadoForm;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use backend\models\registromaestro\TipoNaturaleza;
	use backend\models\TelefonoCodigo;
	use yii\helpers\ArrayHelper;
	use common\models\session\Session;
	use common\models\configuracion\solicitud\ParametroSolicitud;
	use common\models\configuracion\solicitud\SolicitudProcesoEvento;
	use common\enviaremail\PlantillaEmail;
	use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;

	session_start();		// Iniciando session

	/**
	 * Clase principal que controla la creacion de solicitudes de Inscripcion de Sucursales.
	 * Solicitud que se realizara del lado del contribuyente (frontend). Se mostrara una vista
	 * previa de la solicitud realizada por el contribuyente y se le indicara al contribuyente
	 * que confirme la operacion o retorne a la vista inicial donde cargo la informacion para su
	 * ajuste. Cuando el contribuyente confirme su inetencion de crear la solicitud, es cuando
	 * se guardara en base de datos.
	 */
	class InscripcionSucursalController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';

		/**
		 * Identificador de  configuracion d ela solicitud. Se crea cuando se
		 * configura la solicitud que gestiona esta clase.
		 */
		const CONFIG = 85;


		/**
		 * Metodo que mostrara el formulario de cargar inicial de la solicitud, para
		 * que el contribuyente ingrese la informacion soliictada.
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			// Se verifica que el contribuyente sea de tipo naturaleza "Juridico".
			self::actionAnularSession(['begin', 'conf', 'exigirDocumento']);
			$request = Yii::$app->request;
			$getData = $request->get();

			// identificador de la configuracion de la solicitud.
			$id = $getData['id'];
			if ( $id == self::CONFIG ) {
				if ( isset($_SESSION['idContribuyente']) ) {

					// Se determina si el contribuyente es una sede principal.
					$idContribuyente = $_SESSION['idContribuyente'];
					$search = New InscripcionSucursalSearch($idContribuyente);
					if ( $search->getSedePrincipal() == true ) {

						$tipoSolicitud = 0;
						$modelParametro = New ParametroSolicitud($id);
						// Se obtiene el tipo de solicitud. Se retorna un array donde el key es el nombre
						// del parametro y el valor del elemento es el contenido del campo en base de datos.
						$config = $modelParametro->getParametroSolicitud([
																'id_config_solicitud',
																'tipo_solicitud',
																'impuesto',
																'nivel_aprobacion'
													]);

						if ( isset($config) ) {
							$documentoConsignar = $modelParametro->getDocumentoRequisitoSolicitud();
							if ( $documentoConsignar !== null ) {
								$_SESSION['exigirDocumento'] = true;
							} else {
								$_SESSION['exigirDocumento'] = false;
							}

							$_SESSION['conf'] = $config;
							$_SESSION['begin'] = 1;
							$this->redirect(['index-create']);
						} else {
							// No se obtuvieron los parametros de la configuracion.
							return $this->redirect(['error-operacion', 'cod' => 955]);
						}

					} else {
						// El contribuyente no es una sede principal.
						return $this->redirect(['error-operacion', 'cod' => 936]);
					}
				} else {
					// No esta defino el contribuyente.
					return $this->redirect(['error-operacion', 'cod' => 932]);
				}
			} else {
				// Parametro de configuracion no coinciden.
				return $this->redirect(['error-operacion', 'cod' => 955]);
			}
		}



		/***/
		public function actionIndexCreate()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			// Se verifica que el contribuyente sea de tipo naturaleza "Juridico".

			if ( isset($_SESSION['idContribuyente']) && isset($_SESSION['begin']) && isset($_SESSION['conf'])) {

				$request = Yii::$app->request;
				$postData = $request->post();
				$exigirDocumento = false;
				$mensajeErrorChk = '';

				// Se determina si el contribuyente es una sede principal.
				$idContribuyente = $_SESSION['idContribuyente'];
				$exigirDocumento = $_SESSION['exigirDocumento'];

				$model = New InscripcionSucursalForm();
				$formName = $model->formName();
				$model->scenario = self::SCENARIO_FRONTEND;

				if ( isset($postData['btn-back-form']) ) {
					if ( $postData['btn-back-form'] == 3 ) {
						$model->load($postData);
					}
				}

		  		if ( $model->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($model);
		      	}

		      	if ( $model->load($postData) ) {
		      		if ( $model->validate() ) {
		      			if ( !isset($postData['chkDocumento']) && $exigirDocumento ) {
	      	 				$mensajeErrorChk = Yii::t('frontend', 'Select the documents consigned');
			      		}
		      			if ( trim($mensajeErrorChk) == '' ) {
		      				// Validacion correcta.
		      				if ( isset($postData['btn-create']) ) {
		      					if ( $postData['btn-create'] == 1 ) {

		      						// Mostrar vista previa.
		      						$datosRecibido = $postData[$formName];
		      						// Se obtiene el arreglo de los items seleccionados en el form para indicar
		      						// los documentos consignados.
		      						$arregloDocumetoChk = isset($postData['chkDocumento']) ? $postData['chkDocumento'] : [];

		      						if ( count($arregloDocumetoChk) > 0 ) {
			      						// Se crea un DataProvider con los documentos seleccionados
			      						// por el contribuyente. Para mostrar un grid con la lista de item
			      						// documentos seleccionados.
			      						$search = New InscripcionSucursalSearch($idContribuyente);
			      						$dataProvider = $search->getDataProviderDocumentoSeleccionado($arregloDocumetoChk);
			      					}

			      					$url = Url::to(['begin-save']);
		      						return $this->render('/aaee/inscripcion-sucursal/pre-view-create', [
		      																	'model' => $model,
		      																	'datosRecibido' => $datosRecibido,
		      																	'dataProvider' => $dataProvider,
		      																	//'url' => $url,
		      							]);
		      					}
		      				} elseif ( isset($postData['btn-confirm-create']) ) {
		      					if ( $postData['btn-confirm-create'] == 2 ) {
		      						$result = self::actionBeginSave($model, $postData);
		      						if ( $result ) {
										$this->_transaccion->commit();
										$this->redirect(['proceso-exitoso', 'cod' => 100]);
									} else {
										$this->_transaccion->rollBack();
										$this->redirect(['error-operacion', 'cod'=> 920]);

		      						}
		      					}
		      				}
		      			}
			      	}
		      	 }

		      	// Se muestra el form de la solicitud.
		      	// Datos generales del contribuyente sede principal.
		      	$search = New InscripcionSucursalSearch($idContribuyente);
		      	$datos = $search->getDatosContribuyente($idContribuyente);
		  		if ( $datos ) {
		  			// Se crea la lista para los tipos de naturaleza, esta lista se utilizara
		  			// en el combo-lista.
		  			$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 1 and 4')->all();
					$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');

					// Se crea la lista de telefonos para los combo-lista.
					// Telefono local.
					$listaTelefonoCodigo = TelefonoCodigo::getListaTelefonoCodigo(false);

					// Se crea la lista de telefonos moviles.
					$listaTelefonoMovil = TelefonoCodigo::getListaTelefonoCodigo(true);

					$modelTelefono = new TelefonoCodigo();

					$url = Url::to(['index-create']);
		  			return $this->render('/aaee/inscripcion-sucursal/_create', [
		  											'model' => $model,
		  											//'modelActEcon' => $modelActEcon,
		  											'datos' => $datos,
		  											'listaNaturaleza' => $listaNaturaleza,
		  											'listaTelefonoCodigo' => $listaTelefonoCodigo,
		  											'listaTelefonoMovil' => $listaTelefonoMovil,
		  											'modelTelefono' => $modelTelefono,
		  											'mensajeErrorChk' => $mensajeErrorChk,
		  					]);
		  		} else {
		  			// No se encontraron los datos del contribuyente principal.
		  		}

			} else {
				// No esta defino el contribuyente.
				return $this->redirect(['error-operacion', 'cod' => 932]);
			}
		}




		/**
		 * Metodo que comienza el proceso para guardar la solicitud y los demas
		 * procesos relacionados.
		 * @param model $model modelo de InscripcionSucursalForm.
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

					$this->_conexion = New ConexionController();

	      			// Instancia de conexion hacia la base de datos.
	      			$this->_conn = $this->_conexion->initConectar('db');
	      			$this->_conn->open();

	      			// Instancia de tipo transaccion para asegurar la integridad del resguardo de los datos.
	      			// Inicio de la transaccion.
					$this->_transaccion = $this->_conn->beginTransaction();

					$nroSolicitud = self::actionCreateSolicitud($this->_conexion, $this->_conn, $model, $conf);
					if ( $nroSolicitud > 0 ) {
						$model->nro_solicitud = $nroSolicitud;

						$result = self::actionCreateSucursal($this->_conexion, $this->_conn, $model, $conf);
						if ( $result ) {
							$result = self::actionCreateDocumentosConsignados($this->_conexion, $this->_conn, $model, $postEnviado);
							if ( $result ) {
								$result = self::actionEjecutaProcesoSolicitud($this->_conexion, $this->_conn, $model, $conf);
								if ( $result ) {
									$result = self::actionEnviarEmail($model, $conf);
									$result = true;
								}
							}
						}
					}


					if ( $conf['nivel_aprobacion'] == 1 ) {

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
		 * @param  Class $conexionLocal instancia de tipo ConexionController
		 * @param  [type] $connLocal     [description]
		 * @param  [type] $model         [description]
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion
		 * de la ordenaza.
		 * @return boolean retorna true si guardo correctamente o false sino guardo.
		 */
		private function actionCreateSolicitud($conexionLocal, $connLocal, $model, $conf)
		{
			$estatus = 0;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			$user = isset(Yii::$app->user->identity->login) ? Yii::$app->user->identity->login : null;
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

				if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
					$nroSolicitud = $connLocal->getLastInsertID();
				}
			}

			return $nroSolicitud;
		}




		/**
		 * [actionCreateContribuyente description]
		 * @param  [type] $conexionLocal [description]
		 * @param  [type] $connLocal     [description]
		 * @param  [type] $model         [description]
		 * @param  [type] $conf          [description]
		 * @return [type]                [description]
		 */
		private static function actionCreateContribuyente($conexionLocal, $connLocal, $model, $conf)
		{
			$idContribuyenteGenerado = 0;
			$result = false;
			if ( isset($_SESSION['idContribuyente']) == $model->id_sede_principal ) {
				// id de la sede principal.
				$id = $model->id_sede_principal;

				$modelContribuyente = New ContribuyenteBase();

				$arregloDatos = $_SESSION['datosContribuyente'];
				$arregloDatos['fecha_inclusion'] = date('Y-m-d');

				// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
				$arregloDatos['fecha_inicio'] = date('Y-m-d', strtotime($arregloDatos['fecha_inicio']));

				// Se procede a guardar primero en la entidad contribuyentes, debido a que se requiere el
				// id generado para guardar en las otras entidades.
      			$tabla = '';
      			$tabla = $modelContribuyente->tableName();

				if ( $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos) ) {
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
		 * [actionCreateSucursal description]
		 * @param  [type] $conexionLocal [description]
		 * @param  [type] $connLocal     [description]
		 * @param  model $model modelo de InscripcionSucursalForm.
		 * @param  [type] $conf          [description]
		 * @return [type]                [description]
		 */
		private static function actionCreateSucursal($conexionLocal, $connLocal, $model, $conf)
		{
			$result = false;
			$estatus = 0;
			$user = isset(Yii::$app->user->identity->login) ? Yii::$app->user->identity->login : null;
			$userFuncionario = '';
			$fechaHoraProceso = '0000-00-00 00:00:00';
			if ( isset($conexionLocal) && isset($connLocal) && isset($model) ) {
				if ( count($conf) > 0 ) {
					if ( $conf['nivel_aprobacion'] == 1 ) {
						$estatus = 1;
						$userFuncionario = $user;
						$fechaHoraProceso = date('Y-m-d H:i:s');
					}

					$tabla = '';
	      			$tabla = $model->tableName();

	      			// $model->attributes es array {
	      			// 							[attribute] => valor
	      			// 						}
					$arregloDatos = $model->attributes;

					$arregloDatos['estatus'] = $estatus;
					$arregloDatos['user_funcionario'] = $userFuncionario;
					$arregloDatos['fecha_hora_proceso'] = $fechaHoraProceso;

					$model->estatus = $estatus;
					$model->user_funcionario = $userFuncionario;

					// Se ajusta el formato de fecha incio de dd-mm-aaaa a aaaa-mm-dd.
					$arregloDatos['fecha_inicio'] = date('Y-m-d', strtotime($arregloDatos['fecha_inicio']));

					$result = $conexionLocal->guardarRegistro($connLocal, $tabla, $arregloDatos);
				}
			}
			return $result;
		}




		/**
		 * Metodo para guardar los documentos consignados.
		 * @param  ConexionController  $conexionLocal instancia de la clase ConexionController
		 * @param  connection  $connLocal instancia de connection.
		 * @param  model $model modelo de InscripcionSucursalForm.
		 * @param  array $postEnviado post enviado por el formulario. Lo que
		 * se busca es determinar los items seleccionados como documentos y/o
		 * requisitos a consignar para guardarlos.
		 * @return boolean retorna true si guarda efectivamente o false en caso contrario.
		 */
		private static function actionCreateDocumentosConsignados($conexionLocal, $connLocal, $model, $postEnviado)
		{
			$result = false;
			if ( isset($conexionLocal) && isset($connLocal) && isset($model) && count($postEnviado) > 0 ) {
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
		 * @param  model $model modelo de la instancia InscripcionSucursalForm.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return boolean retorna true si todo se ejecuto correctamente false en caso contrario.
		 */
		private function actionEjecutaProcesoSolicitud($conexionLocal, $connLocal, $model, $conf)
		{
			$result = true;
			$resultadoProceso = [];
			$acciones = [];
			if ( count($conf) > 0 ) {
				$procesoEvento = New SolicitudProcesoEvento($conf['id_config_solicitud']);

				// Se buscan los procesos que genera la solicitud para ejecutarlos, segun el evento.
				// que en este caso el evento corresponde a "CREAR". Se espera que retorne un arreglo
				// de resultados donde el key del arrary es el nombre del proceso ejecutado y el valor
				// del elemento corresponda a un reultado de la ejecucion. La variable $model debe contener
				// el identificador del contribuyente que realizo la solicitud y el numero de solicitud.
				$procesoEvento->ejecutarProcesoSolicitudSegunEvento($model, Yii::$app->solicitud->crear(), $conexionLocal, $connLocal);

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
		 * @param  model $model modelo que contiene la informacion
		 * del identificador del contribuyente.
		 * @param  array $conf arreglo que contiene los parametros principales de la configuracion de la
		 * solicitud.
		 * @return Boolean Retorna un true si envio el correo o false en caso
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

				$email = ContribuyenteBase::getEmail($model->id_sede_principal);
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
		 * [actionQuit description]
		 * @return [type] [description]
		 */
		public function actionQuit()
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return $this->render('/menu/menu-vertical');
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
		public function actionProcesoExitoso($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}



		/**
		 * [actionErrorOperacion description]
		 * @param  [type] $codigo [description]
		 * @return [type]         [description]
		 */
		public function actionErrorOperacion($cod)
		{
			$varSession = self::actionGetListaSessions();
			self::actionAnularSession($varSession);
			return MensajeController::actionMensaje($cod);
		}



		/**
		 * [actionGetListaSessions description]
		 * @return [type] [description]
		 */
		public function actionGetListaSessions()
		{
			return $varSession = [
							'postData',
							'conf',
							'begin',
							'exigirDocumento',
					];
		}

	}
?>