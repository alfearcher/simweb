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
 *	@file PagoReciboLoteController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 20-04-2017
 *
 *  @class PagoReciboLoteController
 *	@brief Clase
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


 	namespace backend\controllers\recibo\pago\lote;


 	use Yii;
 	use yii\helpers\ArrayHelper;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\conexion\ConexionController;
	// use backend\controllers\mensaje\MensajeController;
	use common\mensaje\MensajeController;
	use common\models\session\Session;
    use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
    use backend\models\recibo\deposito\DepositoForm;
    use backend\models\recibo\depositodetalle\DepositoDetalleForm;
    use backend\models\recibo\depositodetalle\DepositoDetalleUsuarioForm;
    use backend\models\recibo\formapago\FormaPago;
    use backend\models\utilidad\banco\BancoSearch;
    use backend\models\utilidad\tipotarjeta\TipoTarjetaSearch;
    use backend\models\recibo\tipodeposito\TipoDepositoSearch;
    use backend\models\recibo\depositodetalle\VaucheDetalleUsuarioForm;
    use backend\models\recibo\prereferencia\PreReferenciaPlanillaForm;
    use backend\models\recibo\pago\individual\SerialReferenciaForm;
    use backend\models\recibo\pago\individual\SerialReferenciaUsuarioSearch;
    use backend\models\recibo\prereferencia\ReferenciaPlanillaUsuarioForm;
    use backend\models\recibo\txt\RegistroTxtSearch;
    use common\models\referencia\GenerarReferenciaBancaria;
    use common\models\distribucion\presupuesto\GenerarPlanillaPresupuesto;
    use backend\models\recibo\pago\individual\PagoReciboIndividual;

    use backend\models\recibo\pago\lote\BusquedaArchivoTxtForm;
    use backend\models\recibo\pago\lote\ListaArchivoTxt;
    use backend\models\recibo\pago\lote\MostrarArchivoTxt;

	session_start();		// Iniciando session

	/**
	 *
	 */
	class PagoReciboLoteController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		private $_conn;
		private $_conexion;
		private $_transaccion;





		/**
		 * Metodo que configura las variables que permitiran la interaccion
		 * con la base de datos.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}



        /**
         * Metodo que inicia el modulo. Muestra una vista para consultar un
         * recibo.
         * @return retorna una vista donde se debe colocar el numero de recibo
         * para consultarlo.
         */
		public function actionIndex()
		{
            $this->redirect(['mostrar-form-consulta']);
		}




        /***/
        public function actionMostrarFormConsulta()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            $postData = $request->post();

            $caption = Yii::t('backend', 'Procesar Pagos. Busqueda de Archivo de Pagos.');
            $model = New BusquedaArchivoTxtForm();
            if ( $model->usuarioAutorizado($usuario) ) {

                $formName = $model->formName();

                if ( isset($postData['btn-quit']) ) {
                    if ( $postData['btn-quit'] == 1 ) {
                        $this->redirect(['quit']);
                    }
                }


                if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }

                if ( $model->load($postData) ) {
                    if ( $model->validate() ) {
                        //$_SESSION['postEnviado'] = $postData;
                        //$this->redirect(['mostrar-archivo-txt']);

                        $a = New ListaArchivoTxt($model->id_banco, $model->fecha_pago);
                        $a->crearListaArchivo();
                        $provider = $a->getDataProvider();

                        $subCaption = Yii::t('backend', 'Listado de Archivos de pagos.');
                        return $this->render('/recibo/pago/lote/lista-archivo-txt',[
                                                    'dataProvider' => $provider,
                                                    'caption' => $caption,
                                                    'subCaption' => $subCaption,
                        ]);
                    }
                }

                $listaBanco = $model->getListaBancoRecaudador();

                $subCaption = Yii::t('backend', 'Busqueda de archivos txt de pago');

                // Formulario para la busqueda del archivo txt.
                return $this->render('/recibo/pago/lote/_find',[
                                                    'caption' => $caption,
                                                    'subCaption' => $subCaption,
                                                    'model' => $model,
                                                    'listaBanco' => $listaBanco,
                    ]);

            } else {
                // El usuario no esta autorizado.
                $this->redirect(['usuario-no-autorizado']);
            }

        }





        /**
         * [actionBuscarArchivoTxt description]
         * @return [type] [description]
         */
        public function actionBuscarArchivoTxt()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            $postData = $request->post();
            $postGet = $request->get();

        }





        /**
         * Metodo que permite mostrar una vista con los nombres de los archivos txt
         * encontrados, segun los parametros de consulta previamente ingresados.
         * El resultado debe ser un grid con los nombres de los archivos encontrados.
         * @return View
         */
        public function actionMostrarArchivoTxt()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            $postData = $request->post();
            $postGet = $request->get();

            if ( isset($postData['data-file']) ) {
                if ( isset($postData['data-path']) ) {
                    $archivo = $postData['data-file'];
                    $ruta = $postData['data-path'];

                    $mostrar = New MostrarArchivoTxt($ruta, $archivo);
                    $mostrar->iniciarMostrarArchivo();
                    $dataProvider = $mostrar->getDataProvider();

                    $models = $dataProvider->getModels();
                    $montoTotal = $mostrar->calcularTotalByRenglon($models, 'monto_total');
                    $totalRegistro = $dataProvider->getTotalCount();

                    $this->layout = 'layoutbase';
                    return $this->render('/recibo/pago/lote/mostrar-archivo-txt',[
                                                    'dataProvider' => $dataProvider,
                                                    'montoTotal' => $montoTotal,
                                                    'totalRegistro' => $totalRegistro,
                        ]);

                } else {
                    // No se determino el directorio del archivo

                }

            } else {
                // No se determino el nombre del archivo.

            }


        }






        /**
		 * Metodo salida del modulo.
		 * @return view
		 */
		public function actionQuit()
		{
			self::actionInicializarTemporal();
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
							'recibo',
							'begin',
							'postEnviado',
							'datosRecibo',
							'datosBanco',
					];

		}



	}
?>