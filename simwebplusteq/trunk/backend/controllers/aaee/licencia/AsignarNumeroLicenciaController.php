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
 *	@file ProcesarLicenciaController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 28-01-2017
 *
 *  @class ProcesarLicenciaController
 *	@brief Clase ProcesarLicenciaController del lado del funcioonario, mpermite el procesamineto
 *  de las solicitudes de licencias por lote.
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


 	namespace backend\controllers\aaee\licencia;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\conexion\ConexionController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\aaee\licencia\asignarnumero\AsignarNumeroLicenciaSearch;



	session_start();		// Iniciando session




	/***/
	class AsignarNumeroLicenciaController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;


		/**
		 * Metodo
		 * @return [type] [description]
		 */
		public function actionIndex()
		{
			$this->redirect(['listado-contribuyente']);
		}




		/**
		 * Metodo que permite levantar una vista con los contribuyentes juridicos activos
		 * que tengan los rubros cargados del año actual.
		 * @return View
		 */
		public function actionListadoContribuyente()
		{
			$model = New AsignarNumeroLicenciaSearch();
			$autorizado = false;

			// Se determina si el usuario esta autorixado a utilizar el modulo.
			$autorizado = $model->estaAutorizado(Yii::$app->identidad->getUsuario());

			if ( $autorizado ) {

				$mensajes = '';
				// Ruta donde se atendera la solicitud de busqueda.
				$url = Url::to(['mostrar-listado']);

				$dataProvider = $model->getDataProvider();
				$_SESSION['begin'] = 1;
				$caption = Yii::t('backend', 'Asignar Numero de Licencia');
				$subCaption = Yii::t('backend', 'Listado de Contribuyentes de Actividades Economicas sin numero de  Licencias');

				// Se levanta el formulario listado.
				return $this->render('/aaee/licencia/asignar-numero/_view-listado-sin-licencia',[
																'mensajes' => $mensajes,
																'model' => $model,
																'url' => $url,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'dataProvider' => $dataProvider,
					]);

			} else {
				// El usuario no esta autorizado.
				$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/**
		 * [actionMostrarListado description]
		 * @return [type] [description]
		 */
		public function actionMostrarListado()
		{
			if ( isset($_SESSION['begin']) ) {

				$_SESSION['begin'] = 2;
				$request = Yii::$app->request;
				$postData = $request->post();

				$model = New AsignarNumeroLicenciaSearch();

				$chkIdContribuyente = isset($postData['chkIdContribuyente']) ? $postData['chkIdContribuyente'] : [];

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-back']) ) {
					if ( $postData['btn-back'] == 1 ) {

						// Regresar al listado inicial
						self::actionAnularSession(['begin']);
						$this->redirect(['index']);

					}
				} elseif ( isset($postData['btn-confirmar-asignar-numero-licencia']) ) {
					if ( $postData['btn-confirmar-asignar-numero-licencia'] == 5 ) {

						$chkIdContribuyenteActualizado = [];
						// Guardar lo seleccionado
						if ( count($chkIdContribuyente) > 0 ) {

							self::actionAnularSession(['begin']);
							foreach ( $chkIdContribuyente as $key => $value ) {

								$result = false;
								$result = $model->iniciarAsignacionNumeroLicencia($value);
								if ( $result && $model->getNroLicencia() > 0 ) {

									$chkIdContribuyenteActualizado[] = $value;
								}
							}

						}

						if ( count($chkIdContribuyenteActualizado) > 0 ) {

							// Mostrar a los contribuyentes actualizados. A los que se les asigno el nuemro
							// de licencia nuevo.
							$_SESSION['actualizado'] = $chkIdContribuyenteActualizado;
							$this->redirect(['mostrar-listado-actualizado']);
						}
					}
				}


		      	if ( count($chkIdContribuyente) > 0 ) {

		      		// Mostrar lista de los seleccionado.
					$dataProvider = $model->getDataProvider($chkIdContribuyente);
					$_SESSION['begin'] = 3;
					$caption = Yii::t('backend', 'Asignar Numero de Licencia');
					$subCaption = Yii::t('backend', 'Listado de Contribuyentes seleccionados');

					// Se levanta el formulario listado.
					return $this->render('/aaee/licencia/asignar-numero/listado-contribuyente-sin-licencia-seleccionado',[
																				'model' => $model,
																				'caption' => $caption,
																				'subCaption' => $subCaption,
																				'dataProvider' => $dataProvider,
						]);

		      	} else {
		      		$mensajes = Yii::t('backend', 'No ha seleccionado ningún registro');
		      	}


		      	// Ruta donde se atendera la solicitud de busqueda.
				$url = Url::to(['mostrar-listado']);

				$dataProvider = $model->getDataProvider();
				$caption = Yii::t('backend', 'Asignar Numero de Licencia');
				$subCaption = Yii::t('backend', 'Listado de Contribuyentes de Actividades Economicas sin numero de  Licencias');

				// Se levanta el formulario listado.
				return $this->render('/aaee/licencia/asignar-numero/_view-listado-sin-licencia',[
																'mensajes' => $mensajes,
																'model' => $model,
																'url' => $url,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'dataProvider' => $dataProvider,
					]);


			} else {
				// No ha iniciado correctamente el modulo.
				$this->redirect(['error-operacion', 'cod' => 702]);
			}
		}




		/***/
		public function actionMostrarListadoActualizado()
		{
			if ( isset($_SESSION['actualizado']) ) {

				$request = Yii::$app->request;
				$postData = $request->post();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						self::actionAnularSession(['actualizado']);
						$this->redirect(['quit']);
					}
				} elseif ( isset($postData['btn-asignar-otro']) ) {
					if ( $postData['btn-asignar-otro'] == 3 ) {
						self::actionAnularSession(['actualizado']);
						return $this->redirect(['index']);
					}
				}

				$idActualizado = $_SESSION['actualizado'];

				$model = New AsignarNumeroLicenciaSearch();

				$dataProvider = $model->getDataProviderContribuyenteActuqlizado($idActualizado);

				$caption = Yii::t('backend', 'Asignar Numero de Licencia');
				$subCaption = Yii::t('backend', 'Listado de Contribuyentes con numero de licencia asignados');

				// Se levanta el formulario listado con los contribuyentes actualizados.
				return $this->render('/aaee/licencia/asignar-numero/_view-listado-asignado',[
																'model' => $model,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'dataProvider' => $dataProvider,
																'codigo' => 100,
					]);
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
			return $this->render('/menu/menuvertical2');
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
							'actualizado',
					];
		}


	}
?>