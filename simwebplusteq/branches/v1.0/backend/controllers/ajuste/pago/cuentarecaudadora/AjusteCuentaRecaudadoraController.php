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
 *  @file AjusteCuentaRecaudadoraController.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-05-2017
 *
 *  @class AjusteCuentaRecaudadoraController
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


    namespace backend\controllers\ajuste\pago\cuentarecaudadora;


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
    use backend\models\utilidad\banco\BancoSearch;
    use backend\models\recibo\prereferencia\ReferenciaPlanillaUsuarioForm;
    use backend\models\ajuste\pago\cuentarecaudadora\BusquedaCuentaRecaudadoraForm;
    use backend\models\recibo\prereferencia\PreReferenciaPlanilla;
    use backend\models\ajuste\pago\cuentarecaudadora\AjusteCuentaRecaudadoraSearch;



    session_start();        // Iniciando session

    /**
     *
     */
    class AjusteCuentaRecaudadoraController extends Controller
    {
        public $layout = 'layout-main';             //  Layout principal del formulario

        private $_conn;
        private $_conexion;
        private $_transaccion;

        const SCENARIO_RECIBO = 'recibo';
        const SCENARIO_LOTE = 'lote';
        const SCENARIO_UDPATE = 'update';
        const SCENARIO_DEFAULT = 'default';


        /**
         * Metodo que configurationa las variables que permitiran la interaccion
         * con la base de datos.
         */
        private function setConexion()
        {
            $this->_conexion = New ConexionController();
            $this->_conn = $this->_conexion->initConectar('db');
        }



        /**
         * Metodo que inicia el modulo. Muestra una vista para consultar los pagos donde
         * se permite la busqueda por numero de recibo o por rango de fecha.
         * @return retorna una vista donde se debe colocar el numero de recibo
         * para consultarlo.
         */
        public function actionIndex()
        {
            $varSessions = self::actionGetListaSessions();
            self::actionAnularSession($varSessions);

            $ajuste = New AjusteCuentaRecaudadoraSearch();
            $autorizado = false;

            // Se determina si el usuario esta autorixado a utilizar el modulo.
            $autorizado = $ajuste->estaAutorizado(Yii::$app->identidad->getUsuario());
            if ( $autorizado ) {
                $_SESSION['begin'] = 1;
                $this->redirect(['mostrar-form-consulta']);
            } else {
                // El usuario no esta autorizado.
                $this->redirect(['error-operacion', 'cod' => 700]);
            }
        }



        /**
         * Metodo que permite mostrar el formulario de busqueda de los registros.
         * La busqueda esta definida por nro de recibo y por rango de fecha con
         * algunos parametros adicionales.
         * @return none
         */
        public function actionMostrarFormConsulta()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            $model = New BusquedaCuentaRecaudadoraForm();
            $formName = $model->formName();
            $postData = $request->bodyParams;

            if ( isset($postData['btn-back']) ) {
                if ( $postData['btn-back'] == 1 ) {
                    $this->redirect(['index']);
                }
            } elseif ( isset($postData['btn-quit']) ) {
                if ( $postData['btn-quit'] == 1 ) {
                    $this->redirect(['quit']);
                }
            }

            if ( isset($postData['btn-search-request']) ) {
                if ( trim($postData[$formName]['recibo']) !== '' && $postData[$formName]['recibo'] > 0 ) {
                    $model->scenario = self::SCENARIO_RECIBO;
                } else {
                    $model->scenario = self::SCENARIO_LOTE;
                }
            }

            if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            // Listado de bancos relacionados a cuentas recaudadoras.
            $searchBanco = New BancoSearch();
            $listaBanco = $searchBanco->getListaBancoRelacionadaCuentaReceptora();

            if ( $model->load($postData) ) {

                if ( $model->validate() ) {
                    $_SESSION['postEnviado'] = $postData;
                    $_SESSION['scenario'] = $model->scenario;
                    $this->redirect(['mostrar-listado']);
                }

            }

            $caption = Yii::t('backend', 'Busqueda de pagos');
            return $this->render('/ajuste/pago/cuenta-recaudadora/busqueda-pago-form', [
                                            'model' => $model,
                                            'listaBanco' => $listaBanco,
                                            'caption' => $caption,
            ]);

        }




        /**
         * Metodo que permite mostrar la seleccion confirmada de los registros a actualizar
         * y en la misma vista muestra dos combos que permitiran la seleccion del banco
         * y de la nueva cuenta recaudadora.
         * @return none
         */
        public function actionMostrarSeleccion()
        {
            $request = Yii::$app->request;
            $postData = count($request->bodyParams) > 0 ? $request->bodyParams : [];
            $usuario = Yii::$app->identidad->getUsuario();

            if ( isset($postData['btn-back']) ) {
                if ( $postData['btn-back'] == 1 ) {
                    return $this->redirect(['mostrar-listado']);
                }
            } elseif ( isset($postData['btn-quit']) ) {
                if ( $postData['btn-quit'] == 1 ) {
                    $this->redirect(['quit']);
                }
            }

            $model = New BusquedaCuentaRecaudadoraForm();
            $formName = $model->formName();

            if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            $model->scenario = self::SCENARIO_UDPATE;
            // Recibos seleccionados.
            $chkPago = isset($_SESSION['seleccion']) ? $_SESSION['seleccion'] : ['0'];

            if ( $model->load($postData) ) {
                if ( $model->validate() ) {
                    if ( isset($postData['btn-update-seleccion']) ) {
                        if ( $postData['btn-update-seleccion'] == 3 ) {
                            // Si llego aqui es porque se procesara la solicitud de actualizacion.
                            // Recibos
                            $chkPagoConfirmado = isset($postData['chkPagoConfirm']) ? $postData['chkPagoConfirm'] : [];
                            if ( self::actionBeginUpdate($model, $chkPagoConfirmado) ) {
                                return self::actionMostrarResultado(200, $chkPagoConfirmado, $model);
                            } else {
                                // No se ejecuto la actualizacion.
                                return self::actionMostrarResultado(920, $chkPagoConfirmado, $model);
                            }

                        }
                    }
                }
            }

            if ( isset($_SESSION['begin']) ) {
                $dataProvider =  $model->getDataProviderSeleccion($chkPago);

                // Listado de bancos relacionados a cuentas recaudadoras.
                $searchBanco = New BancoSearch();
                $listaBanco = $searchBanco->getListaBancoRelacionadaCuentaReceptora();

                $caption = Yii::t('backend', 'Modificacion de la Cuenta Recaudadora');
                return $this->render('/ajuste/pago/cuenta-recaudadora/pago-seleccionado-form', [
                                                    'model' => $model,
                                                    'dataProvider' => $dataProvider,
                                                    'caption' => $caption,
                                                    'listaBanco' => $listaBanco,
                                     ]);
            } else {
                $this->redirect(['index']);
            }
        }




        /**
         * Metodo que permite mostrar el listado resultado de la consulta,
         * @return none
         */
        public function actionMostrarListado()
        {
            $request = Yii::$app->request;
            $postData = count($request->bodyParams) > 0 ? $request->bodyParams : $_SESSION['postEnviado'];
            $usuario = Yii::$app->identidad->getUsuario();

            $mensajeAdvertencia = '';
            $model = New BusquedaCuentaRecaudadoraForm();
            $formName = $model->formName();

            if ( isset($postData['btn-back']) ) {
                if ( $postData['btn-back'] == 1 ) {
                    $this->redirect(['index']);
                }
            } elseif ( isset($postData['btn-quit']) ) {
                if ( $postData['btn-quit'] == 1 ) {
                    $this->redirect(['quit']);
                }
            } elseif ( isset($postData['btn-seleccion']) ) {
                if ( $postData['btn-seleccion'] == 2 ) {

                    // Recibos seleccionados
                    $chkPago = isset($postData['chkPago']) ? $postData['chkPago'] : [];
                    if ( count($chkPago) == 0 ) {
                         $postData = $_SESSION['postEnviado'];
                         $mensajeAdvertencia = Yii::t('backend', 'No ha seleccionado ningun registros');
                    } else {
                        $_SESSION['seleccion'] = $chkPago;
                        $this->redirect(['mostrar-seleccion']);
                    }
                }
            }

            $model->scenario = $_SESSION['scenario'];
            $model->load($postData);
            $dataProvider = $model->getDataProviderPago();
            $caption = Yii::t('backend', 'Modificacion de la Cuenta Recaudadora');
            return $this->render('/ajuste/pago/cuenta-recaudadora/listado-pago-encontrado', [
                                            'model' => $model,
                                            'dataProvider' => $dataProvider,
                                            'caption' => $caption,
                                            'mensajeAdvertencia' => $mensajeAdvertencia,
                                 ]);
        }





        /**
         * Metodo que in icia el proceso de actualizacion
         * @param BusquedaCuentaRecaudadoraForm $model instancia de la clase con los datos
         * del banco y cuenta recaudadora. Con datos.  Moelo donde se elecciona la nueva
         * cuenta recaudadora y ya existen los recibos seleccionados para su actualizacion.
         * @param array $chkPagoSeleccions arreglo de identificadores (recibos) de la
         * entidad "depositos". Estos corresponden a los registros seleccionados para la
         * actualizacuion.
         * @return boolean
         */
        private function actionBeginUpdate($model, $chkPagoSeleccions)
        {
            $result = false;
            self::setConexion();
            $this->_transaccion = $this->_conn->beginTransaction();
            $this->_conn->open();
            foreach ( $chkPagoSeleccions as $chkItem ) {
                $result = self::actionUpdateDepositoDetalle($model, $chkItem);
                if ( !$result ) {
                    break;
                } else {
                    $result = self::actionAjustarPreReferencia($chkItem, $model->cuenta_deposito);
                }
            }

            if ( $result ) {
                $this->_transaccion->commit();
            } else {
                $this->_transaccion->rollBack();
            }
            $this->_conn->close();

            return $result;
        }




        /**
         * Metodo que ejecuta la actualizacion.
         * @param BusquedaCuentaRecaudadoraForm $model instancia de la clase con los datos
         * del banco y cuenta recaudadora.
         * @param integer $recibo identificador de la entidad "depositos"
         * @return boolean.
         */
        private function actionUpdateDepositoDetalle($model, $recibo)
        {
            $tabla = $model->getTableName();
            $arregloCondicion = [
                'recibo' => $recibo,
            ];
            $arregloDatos = [
                'codigo_banco' => $model->codigo_banco,
                'cuenta_deposito' => $model->cuenta_deposito,
            ];

            return $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);
        }



        /**
         * Metodo que inicia el proceso de insercion de registros y la actualizacion
         * de estatus.
         * @param integer $recibo identificador de la entidad "depositos". Numero de
         * recibo de pago.
         * @param string $nroCuentaRecaudadora numero de la cuenta recaudadora nueva.
         * @return boolean
         */
        private function actionAjustarPreReferencia($recibo, $nroCuentaRecaudadora)
        {
            return self::actionInsertarReferencia($recibo, $nroCuentaRecaudadora);
        }



        /**
         * Metodo que realiza la insercion en la entidad "pre-referencias-planillas".
         * Se inserta un registros con los datos existentes de la referencia pero con
         * la actualizacion de la observacion de la entidad "pre-referencias-planillas".
         * @param integer $recibo identificador de la entidad "depositos". Numero de
         * recibo de pago.
         * @param string $nroCuentaRecaudadora numero de la cuenta recaudadora nueva.
         * @return boolean
         */
        private function actionInsertarReferencia($recibo, $nroCuentaRecaudadora)
        {
            $result = false;
            $referencia = New PreReferenciaPlanilla();
            $tabla = $referencia->tableName();
            $registers = $referencia->find()->where(['IN', 'estatus', [0,1]])
                                                            ->andWhere('recibo =:recibo',
                                                                            ['recibo' => $recibo])
                                                            ->all();

            $nuevaCuentaRecaudadora = self::actionSetObservacionSerialManual($nroCuentaRecaudadora);
            foreach ( $registers as $register ) {

                $idReferencia = $register->id_referencia;
                $register->id_referencia = null;
                $register->observacion = $nuevaCuentaRecaudadora;
                $register->usuario = Yii::$app->identidad->getUsuario();
                $register->fecha_hora = date('Y-m-d H:i:s');

                $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $register->attributes);
                if ( !$result ) {
                    break;
                } else {

                    // Se inactiva la referencia existente para evitar la duplicidad de registros.
                    $result = self::actionInactivarReferencia($idReferencia, $referencia);
                    if ( !$result ) { break; }

                }
            }

            return $result;
        }



        /**
         * Metodo que realiza la inactivacion de la referencia bancaria.
         * @param integer $idReferencia identificador de la entidad "pre-referencias-planillas".
         * @return boolean.
         */
        private function actionInactivarReferencia($idReferencia, $referencia)
        {
            //$register = PreReferenciaPlanilla::findOne($idReferencia);
            $register = $referencia->find()
                                   ->where('id_referencia =:id_referencia',
                                                [':id_referencia' => $idReferencia])
                                   ->one();

            $tabla = $referencia->tableName();

            $arregloCondicion = [
                'id_referencia' => $idReferencia,
            ];
            $arregloDatos = [
                'estatus' => 9,
            ];
            return $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);
        }




        /**
         * Metodo que permite setear el valor de la observacion de la pre-referencia.
         * @param string $nroCuentaRecaudadora numero de la cuenta recaudadora.
         * @return string
         */
        public function actionSetObservacionSerialManual($nroCuentaRecaudadora)
        {
            return 'Cuenta Recaudadora: ' . $nroCuentaRecaudadora . ' Actualizacion efectuada por: ' . Yii::$app->identidad->getUsuario() . ' - ' . date('Y-m-d H:i:s');
        }



        /**
         * Metodo que renderiza un listado dde cuentas recaudadoras, segun el identificador
         * del banco seleccionado. La lista aparecera en la vista.
         * @return view
         */
        public function actionListarCuentaRecaudadora()
        {
            $request = Yii::$app->request;
            $postGet = $request->get();
            $postData = $request->post();

            $searchBanco = New BancoSearch();
            $id = isset($postGet['id']) ? (int)$postGet['id'] : 0;
            return $searchBanco->generarViewListaCuentaRecaudadora($id);

        }





        /***/
        public function actionMostrarResultado($codigo, $chkSeleccions, $model)
        {
            $varSessions = self::actionGetListaSessions();
            self::actionAnularSession($varSessions);
            $busqueda = New BusquedaCuentaRecaudadoraForm();
            $dataProvider = $busqueda->getDataProviderSeleccion($chkSeleccions);
            $caption = Yii::t('backend', 'Modificacion de la Cuenta Recaudadora');
            return $this->render('/ajuste/pago/cuenta-recaudadora/_resultado-operacion', [
                                                    'codigo' => $codigo,
                                                    'model' => $model,
                                                    'dataProvider' => $dataProvider,
                                                    'caption' => $caption,
                    ]);

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
                        'begin',
                        'postEnviado',
                        'scenario',
                        'seleccion',
                    ];

        }



    }
?>