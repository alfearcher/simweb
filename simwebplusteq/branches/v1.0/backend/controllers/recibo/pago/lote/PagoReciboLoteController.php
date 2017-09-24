<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *  > This library is free software; you can redistribute it and/or modify it under
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *  > any later version.
 *  >
 *  > This library is distributed in the hope that it will be usefull,
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *  > for more details.
 *  >
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *  @file PagoReciboLoteController.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-04-2017
 *
 *  @class PagoReciboLoteController
 *  @brief Clase
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *  @inherits
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
    use common\mensaje\MensajeController;
    use common\models\session\Session;
    use backend\models\recibo\pago\lote\BusquedaArchivoTxtForm;
    use backend\models\recibo\pago\lote\ListaArchivoTxt;
    use backend\models\recibo\pago\lote\MostrarArchivoTxt;
    use backend\models\recibo\pago\lote\PagoReciboLoteSearch;
    use backend\models\recibo\deposito\Deposito;
    use backend\models\usuario\AutorizacionUsuario;


    session_start();        // Iniciando session

    /**
     * Clase que gestiona el pago en lote de los recibos. Utilizando una archivo txt enviado
     * por una entidad financiera. Este archivo txt debe cunplir con las especificaciones para
     * que pueda ser detectado y analizado correctamente por el modulo que realiza dicho analisis
     */
    class PagoReciboLoteController extends Controller
    {
        public $layout = 'layout-main';             //  Layout principal del formulario

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
            $varSessions = self::actionGetListaSessions();
            self::actionAnularSession($varSessions);
            $autorizacion = New AutorizacionUsuario();
            if ( $autorizacion->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']) ) {
                $_SESSION['begin'] = 1;
                $this->redirect(['mostrar-form-consulta']);
            } else {
                // Su perfil no esta autorizado.
                // El usuario no esta autorizado.
                $this->redirect(['error-operacion', 'cod' => 700]);
            }
        }




        /**
         * Metodo que muestra el formulario que permite realizar la busqueda de los archivos.
         * Mostrando un combo-lista de los bancos que envian los archivos y dos campos input
         * que permiten colocar un rango de fechas que representan las fechas de pagos.
         * @return view
         */
        public function actionMostrarFormConsulta()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            $postData = $request->post();

            $caption = Yii::t('backend', 'Procesar Pagos. Busqueda de Archivo de Pagos.');
            $model = New BusquedaArchivoTxtForm();
            if ( isset($_SESSION['begin']) ) {

                $formName = $model->formName();

                if ( isset($postData['btn-quit']) ) {
                    if ( $postData['btn-quit'] == 1 ) {
                        $this->redirect(['quit']);
                    }
                } elseif ( isset($postData['btn-back']) ) {
                    if ( $postData['btn-back'] == 1 ) {
                        $this->redirect(['index']);
                    }
                }

                if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }

                if ( $model->load($postData) ) {
                    if ( $model->validate() ) {
                        $_SESSION['postEnviado'] = $postData;
                        $this->redirect(['mostrar-lista-archivo']);
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
         * Metodo que permite renderizar una vista con una lista de nombres de los
         * archivos que se encontraron segun la consulta realizada por el usuario.
         * Se renderiza una vista donde el listado corresponde a los nombres de
         * archivos contenidos en botones que permite su seleccion, ademas se incluye
         * un checkbox que permite indicar si se quiere ver el contenido del archivo
         * sin formato.
         * @return view
         */
        public function actionMostrarListaArchivo()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            $postGet = $request->get();
            $postData = ( count($request->post()) > 0 ) ? $request->post() : $_SESSION['postEnviado'];

            if ( isset($postData['btn-quit']) ) {
                if ( $postData['btn-quit'] == 1 ) {
                    $this->redirect(['quit']);
                }
            } elseif ( isset($postData['btn-back']) ) {
                if ( $postData['btn-back'] == 1 ) {
                    $this->redirect(['index']);
                }
            } elseif ( isset($postData['data-file-type']) && isset($postData['data-file']) && isset($postData['data-path']) ) {
                if ( $postData['data-file-type'] == 'file' ) {

                    // Mostrar archivo
                    self::actionAnularSession(['postEnviado']);
                    $_SESSION['postEnviado'] = $request->post();
                    $this->redirect(['mostrar-archivo-txt']);

                } elseif ( $postData['data-file-type'] == 'file-flat' ) {

                    // Mostrar archivo plano
                    self::actionAnularSession(['postEnviado']);
                    $_SESSION['postEnviado'] = $request->post();
                    $this->redirect(['mostrar-archivo-txt-plano']);

                }

            }

            $caption = Yii::t('backend', 'Procesar Pagos. Busqueda de Archivo de Pagos.');
            $subCaption = Yii::t('backend', 'Listado de Archivos de pagos.');

            $model = New BusquedaArchivoTxtForm();
            $model->load($postData);

            $banco = $model->getBancoById($model->id_banco);
            $labelBanco = Yii::t('backend', 'Banco ') . ': ' . $banco->nombre;
            $labelRango = Yii::t('backend', 'Rango Consulta ') . ': ' . $model->fecha_desde . ' - ' . $model->fecha_hasta;

            // Se muestra una lista de archivos o un archivo segun el banco y el rango de la
            // fecha de pago indicada, se armaria el nombre del archivo en caso de ser necesario.
            $listaArchivo = New ListaArchivoTxt($model->id_banco, $model->fecha_desde, $model->fecha_hasta);
            $listaArchivo->iniciarListaArchivo();
            $provider = $listaArchivo->getDataProvider();

            $subCaption = Yii::t('backend', 'Listado de Archivos de pagos.');
            return $this->render('/recibo/pago/lote/lista-archivo-txt',[
                                            'model' => $model,
                                            'dataProvider' => $provider,
                                            'caption' => $caption,
                                            'subCaption' => $subCaption,
                                            'labelBanco' => $labelBanco,
                                            'labelRango' => $labelRango,
            ]);
        }




        /**
         * Metodo que permite mostrar una vista con el contenido del archivo txt enviado por el
         * banco. Esta vista sera un representacion bruta del contenido del archivo, tal como
         * lo mando el banco.
         * @return view
         */
        public function actionMostrarArchivoTxtPlano()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            if ( $usuario !== null ) {

                $request = Yii::$app->request;
                $postData = ( count($request->post()) > 0 ) ? $request->post() : $_SESSION['postEnviado'];

                $errorMensaje = "";
                if ( isset($postData['btn-quit']) ) {
                    if ( $postData['btn-quit'] == 1 ) {
                        $this->redirect(['quit']);
                    }
                } elseif ( isset($postData['btn-back']) ) {
                    if ( $postData['btn-back'] == 1 ) {
                        unset($_SESSION['postEnviado']['data-path']);
                        unset($_SESSION['postEnviado']['data-file']);
                        unset($_SESSION['postEnviado']['data-key']);
                        unset($_SESSION['postEnviado']['data-file-type']);
                        $this->redirect(['mostrar-lista-archivo']);
                    }
                }

                if ( isset($postData['data-file']) && isset($postData['data-path']) ) {

                    $model = New BusquedaArchivoTxtForm();
                    $model->load($postData);

                    $banco = $model->getBancoById($model->id_banco);

                    $archivo = $postData['data-file'];
                    $ruta = $postData['data-path'];
                    // Fecha de pago
                    $fecha = $postData['data-date'];
                    $model->fecha_pago = $fecha;

                    $htmlIdentidadBanco = $this->renderPartial('/recibo/pago/lote/identificar-banco-fecha',[
                                                                        'banco' => $banco,
                                                                        'archivo' => $archivo,
                                                                        'model' => $model,
                                                                        'detailView' => true,
                                        ]);

                    $mostrar = New MostrarArchivoTxt($ruta, $archivo);
                    $mostrar->iniciarMostrarArchivo();
                    $contenidoPlano = $mostrar->verArchivoPlano();
                    $errorMensaje = $mostrar->getError();

                    if ( !$mostrar->existeArchivo() ) {
                        $this->layout = 'layout-main';
                        return $this->render('/recibo/pago/lote/warnings', [
                                                            'archivo' => $archivo,
                                                            'mensajes' => $errorMensaje,
                                ]);
                    } else {
                        $this->layout = 'layoutbase';
                        return $this->render('/recibo/pago/lote/mostrar-archivo-txt-plano',[
                                                            'archivo' => $archivo,
                                                            'contenidoPlano' => $contenidoPlano,
                                                            'htmlIdentidadBanco' => $htmlIdentidadBanco,
                            ]);
                    }
                }

            } else {
                $this->redirect(['usuario-no-autorizado']);
            }
        }







        /**
         * Metodo que permite mostrar una vista con los nombres de los archivos txt
         * encontrados, segun los parametros de consulta previamente ingresados.
         * El resultado debe ser un grid con los nombres de los archivos encontrados.
         * @return view
         */
        public function actionMostrarArchivoTxt()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;
            $postData = ( count($request->post()) > 0 ) ? $request->post() : $_SESSION['postEnviado'];

            $postGet = $request->get();
            $errorMensaje = "";
            if ( isset($postData['btn-quit']) ) {
                if ( $postData['btn-quit'] == 1 ) {
                    $this->redirect(['quit']);
                }
            } elseif ( isset($postData['btn-back']) ) {
                if ( $postData['btn-back'] == 1 ) {
                    unset($_SESSION['postEnviado']['data-path']);
                    unset($_SESSION['postEnviado']['data-file']);
                    unset($_SESSION['postEnviado']['data-key']);
                    unset($_SESSION['postEnviado']['data-file-type']);
                    $this->redirect(['mostrar-lista-archivo']);
                }
            } elseif ( isset($postData['btn-analize-file']) ) {
                if ( $postData['btn-analize-file'] == 1 ) {
                    $listaRecibo = isset($postData['chkRecibo']) ? $postData['chkRecibo'] : [];

                }
            } elseif ( isset($postData['btn-procesar-pago']) ) {
                if ( $postData['btn-procesar-pago'] == 3 ) {
                    if ( count($postData) > 0 ) {
                        self::actionProcesarPagoArchivoTxt($postData);
                    }
                }
            }


            if ( isset($postData['data-file']) && isset($postData['data-path']) ) {

                $model = New BusquedaArchivoTxtForm();
                $model->load($postData);

                $banco = $model->getBancoById($model->id_banco);

                $archivo = $postData['data-file'];
                $ruta = $postData['data-path'];
                // Fecha de pago
                $fecha = $postData['data-date'];
                $model->fecha_pago = $fecha;

                $htmlIdentidadBanco = $this->renderPartial('/recibo/pago/lote/identificar-banco-fecha',[
                                                                        'banco' => $banco,
                                                                        'archivo' => $archivo,
                                                                        'model' => $model,
                                                                        'detailView' => true,
                                        ]);

                $mostrar = New MostrarArchivoTxt($ruta, $archivo);
                $mostrar->iniciarMostrarArchivo();
                $dataProvider = $mostrar->getDataProvider();

                $models = $dataProvider->getModels();
                $montoTotal = $mostrar->calcularTotalByRenglon($models, 'monto_total');
                $totalRegistro = $dataProvider->getTotalCount();

                $this->layout = 'layoutbase';
                if ( $model->sin_formato == 1 ) {
                    return $this->render('/recibo/pago/lote/mostrar-archivo-txt',[
                                                    'dataProvider' => $dataProvider,
                                                    'montoTotal' => $montoTotal,
                                                    'totalRegistro' => $totalRegistro,
                                                    'ruta' => $ruta,
                                                    'archivo' => $archivo,
                                                    'fecha' => $fecha,
                                                    'model' => $model,
                                                    'htmlIdentidadBanco' => $htmlIdentidadBanco,

                        ]);
                } else {
                    $htmlError = null;
                    $pagoReciboLoteSearch = New PagoReciboLoteSearch($mostrar);
                    $dataProvider = $pagoReciboLoteSearch->getDataProviderArchivoFormateado();
                    $errorMensaje = $pagoReciboLoteSearch->getError();

                    if ( count($errorMensaje) > 0 ) {
                        $this->layout = 'layout-main';
                        return $this->render('/recibo/pago/lote/warnings', [
                                                            'archivo' => $archivo,
                                                            'mensajes' => $errorMensaje,
                                ]);
                    } else {
                        return $this->render('/recibo/pago/lote/mostrar-archivo-txt-formateado',[
                                                        'dataProvider' => $dataProvider,
                                                        'montoTotal' => $montoTotal,
                                                        'totalRegistro' => $totalRegistro,
                                                        'ruta' => $ruta,
                                                        'archivo' => $archivo,
                                                        'fecha' => $fecha,
                                                        'model' => $model,
                                                        'htmlIdentidadBanco' => $htmlIdentidadBanco,
                                ]);
                    }
                }

            } else {
                // No se determino el nombre del archivo.

            }
        }




        /***/
        public function actionAnalizarArchivoTxt($listaRecibo)
        {

        }




        /**
         * Metodo que inica el proceso de procesar y guardar el pago del recibo
         * @param array $postEnviado post enviado desde el formulario que muestra un listado
         * resultado del archivo de conciliacion.
         * @return none
         */
        public function actionProcesarPagoArchivoTxt($postEnviado)
        {
            $listaRecibo = isset($postEnviado['chkRecibo']) ? $postEnviado['chkRecibo'] : [];
            if ( count($listaRecibo) > 0 ) {
                $ruta = isset($postEnviado['data-path']) ? $postEnviado['data-path'] : '';
                $archivo = isset($postEnviado['data-file']) ? $postEnviado['data-file'] : '';
                if ( trim($ruta) !== '' && trim($archivo) !== '' ) {
                    $mostrar = New MostrarArchivoTxt($ruta, $archivo);
                    $mostrar->iniciarMostrarArchivo();
                    if ( count($mostrar->getError()) == 0 ) {

                         // Contenido del archivo txt de pago arnado en un arreglo.
                        //$listaPago = $mostrar->getListaPago();
                        $pagoReciboLoteSearch = New PagoReciboLoteSearch($mostrar);
                        $pagoReciboLoteSearch->iniciarPagoReciboLote();

                    } else {
                        // Enviar mensaje de error, renderizar vista.
                        return $this->render('/recibo/pago/lote/error', [
                                                    'mensaje' => 'Error',
                                                    'urlBack' => 'xxxx',
                            ]);
                    }
                } else {
                    // No es valido la ruta del archivo.

                }
            } else {
                // No hay recibos en el archivo.

            }
        }




        /**
         * Metodo que permite renderizar una vista con la informacion del recibo.
         * Informacion relacionada al recibo como a las planillas asociadas al mismo,
         * tambien se muestra los mensajes de errores encontrados al momento de mostrar
         * el cotenido del archivo de conciliacion y que estan relacionada al recibo.
         * @return View
         */
        public function actionViewReciboModal()
        {
            $request = Yii::$app->request;
            $postGet = $request->get();

            // Numero de recibo de pago
            $nro = $postGet['nro'];
            return $this->renderAjax('@backend/views/recibo/pago/consulta/recibo-consultado', [
                                                        'model' => $this->findModelRecibo($nro),

                   ]);
        }



        /***/
        public function findModelRecibo($recibo)
        {
            $deposito = Deposito::findOne($recibo);

            if ( $deposito !== null ) {
                return $deposito;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }




        /**
         * Metodo renderiza una vista indicando que el usuario no es valido.
         * @return view.
         */
        public function actionUsuarioNoAutorizado()
        {
            return $this->redirect(['error-operacion', 'cod' => 999]);
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
                            'recibo',
                            'begin',
                            'postEnviado',
                            'datosRecibo',
                            'datosBanco',
                    ];

        }



    }
?>