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
 *	@file ReporteReciboController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 02-10-2017
 *
 *  @class ReporteReciboController
 *	@brief Clase ReporteReciboController
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


 	namespace backend\controllers\reporte\recaudacion\recibo;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
	use backend\models\recibo\recibo\ReciboSearch;
	use backend\models\impuesto\Impuesto;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\usuario\AutorizacionUsuario;
	use backend\models\reporte\recaudacion\recibo\ReporteReciboSearch;
	use backend\models\recibo\estatus\EstatusDepositoSearch;
	use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
    use backend\models\recibo\depositodetalle\DepositoDetalleSearch;
    use backend\models\recibo\txt\RegistroTxtReciboSearch;
    use backend\models\buscargeneral\BuscarGeneral;


	session_start();		// Iniciando session


	/**
	 * Clase que gestiona la generacion de un reporte de los recibos.
	 */
	class ReporteReciboController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario




		/**
		 * Metodo que inicia el modulo
		 * @return
		 */
		public function actionIndex()
		{
			// Se verifica que el contribuyente haya iniciado una session.
			$varSessions = self::actionGetListaSessions();
			self::actionAnularSession($varSessions);
			$autorizacion = New AutorizacionUsuario();
			if ( $autorizacion->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']) ) {
				$_SESSION['begin'] = 1;
				$this->redirect(['mostrar-form-busqueda']);
			} else {
				// Su perfil no esta autorizado.
				// El usuario no esta autorizado.
            	$this->redirect(['error-operacion', 'cod' => 700]);
			}
		}



		/***/
		public function actionMostrarFormBusqueda()
		{
			if ( isset($_SESSION['begin']) ) {
				$request = Yii::$app->request;
				$postData = $request->post();

				// Modelo del formulario de busqueda.
				$reporteReciboSearch = New ReporteReciboSearch();
				$formName = $reporteReciboSearch->formName();

				if ( isset($postData['btn-quit']) ) {
					if ( $postData['btn-quit'] == 1 ) {
						$this->redirect(['quit']);
					}
				}

				if ( $reporteReciboSearch->load($postData)  && Yii::$app->request->isAjax ) {
					Yii::$app->response->format = Response::FORMAT_JSON;
					return ActiveForm::validate($reporteReciboSearch);
		      	}

		      	$caption = Yii::t('backend', 'Reporte de Recibos');

		      	if ( $reporteReciboSearch->load($postData) ) {
					if ( isset($postData['btn-search-params']) ) {
						if ( $postData['btn-search-params'] == 2 ) {
							if ( $reporteReciboSearch->validate(['fecha_desde', 'fecha_hasta']) ) {
								self::actionAnularSession(['begin']);
								$_SESSION['postEnviado'] = $postData;
								$_SESSION['begin'] = 2;
								$this->redirect(['search-deposito']);
			      			}
						}
					} elseif ( isset($postData['btn-search-recibo']) ) {
						if ( $postData['btn-search-recibo'] == 3 ) {
							if ( $reporteReciboSearch->validate(['recibo']) ) {
								self::actionAnularSession(['begin']);
								$_SESSION['postEnviado'] = $postData;
								$_SESSION['begin'] = 2;
								$this->redirect(['search-deposito']);
			      			}
			      		}
					}
				}

				$searchEstatus = New EstatusDepositoSearch();
				$lista = $searchEstatus->getListaEstatus();
				$listaUsuario = $reporteReciboSearch->getListaUsuarioCreadorFuncionario();
				$listaUsuarioPago = $reporteReciboSearch->getListaUsuarioPago();

				return $this->render('/reporte/recaudacion/recibo/reporte-recibo-busqueda-form', [
															'model' => $reporteReciboSearch,
															'lista' => $lista,
															'listaUsuario' => $listaUsuario,
															'listaUsuarioPago' => $listaUsuarioPago,
															'caption' => $caption,
							]);


			} else {
				// Session no valida.
				$this->redirect(['']);
			}
		}




		/***/
		public function actionSearchDeposito()
		{
			if ( isset($_SESSION['begin']) ) {
				if ( $_SESSION['begin'] == 2 ) {
					$postEnviado = isset($_SESSION['postEnviado']) ? $_SESSION['postEnviado'] : [];

					$reporteReciboSearch = New ReporteReciboSearch();
					$formName = $reporteReciboSearch->formName();
					$reporteReciboSearch->load($postEnviado);
					$dataProvider = $reporteReciboSearch->searchDeposito($postEnviado);

					$request = Yii::$app->request;

					if ( $request->post('btn-quit') !== null ) {
						if ( $request->post('btn-quit') == 1 ) {
							$this->redirect(['quit']);
						}
					} elseif ( $request->post('btn-back') !== null ) {
						if ( $request->post('btn-back') == 2 ) {
							$this->redirect(['index']);
						}
					}

					if ( $request->isGet ) {
						$postEnviado = $request->get();
					} else {
						$postEnviado = $request->post() !== null ? $request->post() : $_SESSION['postEnviado'];
						$_SESSION['postEnviado'] = $postEnviado;
					}


					if ( isset($postEnviado['page']) ) {
						$postEnviado = $_SESSION['postEnviado'];
						$model->load($postEnviado);
					}

					$searchEstatus = New EstatusDepositoSearch();
					$listaEstatus = $searchEstatus->getListaEstatus();
//die(var_dump($dataProvider->getModels()));
					$caption = Yii::t('backend', 'Reporte de Recibos');
					return $this->render('/reporte/recaudacion/recibo/reporte-general-recibo', [
												'caption' => $caption,
												'dataProvider' => $dataProvider,
												'model' => $reporteReciboSearch,
												'listaEstatus' => $listaEstatus,
					]);
				} else {
					// Session no valida.
					$this->redirect(['']);
				}
			} else {
				// Session no valida.
				$this->redirect(['']);
			}
		}



		/**
         * Metodo que permite renderizar una vista con la informacion del recibo.
         * Informacion relacionada al recibo como a las planillas asociadas al mismo,
         * tambien se muestra los mensajes de errores encontrados al momento de mostrar
         * el cotenido del archivo de conciliacion y que estan relacionada al recibo.
         * @return View
         */
        public function actionViewReciboModal($nro)
        {
            $request = Yii::$app->request;
            $postGet = $request->get();

            // Numero de recibo de pago
            $nro = $postGet['nro'];

            $pagoIndividualSearch = New PagoReciboIndividualSearch($nro);

            // $dataProviders[0]->getModels(), es el modelo de la entidad "depositos".
            // $dataProviders[1]->getModels(), es el modelo de la entidad "depositos-planillas"
            $dataProviders = $pagoIndividualSearch->getDataProviders();

            $totales = $pagoIndividualSearch->getTotalesReciboPlanilla($dataProviders);
            $htmlDatoRecibo = $this->renderPartial('/recibo/pago/consulta/datos-recibo-planilla', [
                                                                'dataProviderRecibo' => $dataProviders[0],
                                                                'dataProviderReciboPlanilla' => $dataProviders[1],
                                                                'totales' => $totales,
            ]);


            // Datos de la formas de pago.
            $detalleSearch = New DepositoDetalleSearch();
            $dataProviderDetalle = $detalleSearch->getDataProviderDepositoDetalle($nro);
            $htmlDepositoDetalle = $this->renderPartial('@backend/views/recibo/deposito-detalle/deposito-detalle-forma-pago',[
                                                                'dataProviderDetalle' => $dataProviderDetalle,
            ]);


            // Informacion de la corrida del txt guardada referente al recibo
            $registroTxtRecibo = New RegistroTxtReciboSearch();
            $registroTxtRecibo->setNumeroRecibo($nro);
            $dataProviderRegistroTxtRecibo = $registroTxtRecibo->getDataProviderByRecibo();
            $htmlRegistroTxtRecibo = $this->renderPartial('/recibo/txt/recibo/registro-txt-recibo-resumen',[
                                                                'dataProviderRegistroTxtRecibo' => $dataProviderRegistroTxtRecibo,
            ]);

            // Resumen
            return $this->renderAjax('/recibo/pago/consulta/recibo-consultado', [
                                                        'htmlDatoRecibo'=> $htmlDatoRecibo,
                                                        'htmlDepositoDetalle' => $htmlDepositoDetalle,
                                                        'htmlRegistroTxtRecibo' => $htmlRegistroTxtRecibo,
                                                        'recibo' => $nro,
            ]);
        }




        /**
		 * Metodo que permite renderizar una vista con la informacion preliminar de
		 * la licencia segun el numero de solicitud de la misma.
		 * @return View
		 */
		public function actionViewContribuyenteModal()
		{
			$request = Yii::$app->request;
			$postGet = $request->get();

			// Identificador del contribuyente
			$id = (int)$postGet['id'];

			return $this->renderAjax('@backend/views/buscar-general/view', [
			            	'model' => $this->findModel($id),
			       ]);
		}



		/**
    	 * Metodo que realiza la consulta para obtener los datos del contribuyente con
    	 * la informacion de su afiliacion. Retorna la informacion de ambas entidades.
    	 * @param integer $idContribuyente identificador del contribuyente
    	 * @return BuscarGeneral
    	 */
    	protected function findModel($idContribuyente)
    	{
  			$model = BuscarGeneral::find()->alias('B')
  			                              ->where('B.id_contribuyente =:id_contribuyente',
  			                          					[':id_contribuyente' => $idContribuyente])
  			                              ->one();

  			if ( $model !== null ) {
  				return $model;
  			} else {
  				throw new NotFoundHttpException('The requested page does not exist.');
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
			return Yii::$app->getResponse()->redirect(array('/menu/vertical'));
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
						'begin',
					];
		}

	}
?>