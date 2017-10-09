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
 *  @file AjustePreReferenciaBancariaController.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-10-2017
 *
 *  @class AjustePreReferenciaBancariaController
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


    namespace backend\controllers\ajuste\pago\prereferencia;


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
    use backend\models\usuario\AutorizacionUsuario;
    use backend\models\pago\consulta\ConsultaGeneralPagoForm;
    use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
    use backend\models\recibo\depositodetalle\DepositoDetalleSearch;
    use backend\models\recibo\txt\RegistroTxtReciboSearch;
    use backend\models\buscargeneral\BuscarGeneral;
    use common\models\planilla\PlanillaSearch;
    use backend\models\ajuste\pago\prereferencia\AjustePreReferenciaBancariaForm;
    use backend\models\ajuste\pago\prereferencia\BusquedaPreReferenciaBancariaForm;
    use backend\models\ajuste\pago\prereferencia\AjustePreReferenciaBancaria;
    use backend\models\utilidad\tipo\ajusteprereferencia\TipoAjustePreReferenciaBancaria;


    session_start();        // Iniciando session

    /**
     *
     */
    class AjustePreReferenciaBancariaController extends Controller
    {
        public $layout = 'layout-main';             //  Layout principal del formulario

        private $_conn;
        private $_conexion;
        private $_transaccion;

        const SCENARIO_RECIBO = 'recibo';
        const SCENARIO_FECHA = 'fecha';



        /**
         * Metodo que configurationa las variables que permitiran la interaccion
         * con la base de datos.
         */
        private function setConexion()
        {
            $this->_conexion = New ConexionController();
            $this->_conn = $this->_conexion->initConectar('db');
        }



        /***/
        public function actionIndex()
        {
            $autorizacion = New AutorizacionUsuario();
            if ( $autorizacion->estaAutorizado(Yii::$app->identidad->getUsuario(), $_GET['r']) ) {
                $varSessions = self::actionGetListaSessions();
                self::actionAnularSession($varSessions);
                $_SESSION['begin'] = 1;
                $this->redirect(['mostrar-form-consulta']);
            } else {
                // Su perfil no esta autorizado.
                // El usuario no esta autorizado.
                $this->redirect(['error-operacion', 'cod' => 700]);
            }
        }



        /**
         * Metodo que renderiza el formulario de consulta de los pagos
         * @return none
         */
        public function actionMostrarFormConsulta()
        {
            $usuario = Yii::$app->identidad->getUsuario();
            $request = Yii::$app->request;

            if ( isset($_SESSION['begin']) ) {

                $model = New BusquedaPreReferenciaBancariaForm();
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
                        $model->scenario = self::SCENARIO_FECHA;
                    }
                }

                if ( $model->load($postData) && Yii::$app->request->isAjax ) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }

                $caption = Yii::t('backend', 'Ajuste de Pre-Referencia Bancarias');

                if ( $model->load($postData) ) {
                    if ( $model->validate() ) {
                        self::actionAnularSession(['begin']);
                        $_SESSION['begin'] = 2;
                        $_SESSION['postEnviado'] = $postData;
                        $this->redirect(['mostrar-listado-pre-referencia']);

                    }
                }

                // Listado de bancos relacionados a cuentas recaudadoras.
                $listaBanco = $model->listarBanco();

                $subCaption = Yii::t('backend', 'Parámetros de consulta. Consulta de pago');

                // Formulario general de consulta de pagos.
                return $this->render('/pago/consulta/consulta-general-pago-form', [
                                                'model' => $model,
                                                'listaBanco' => $listaBanco,
                                                'caption' => $caption,
                                                'subCaption' => $subCaption,

                ]);
            } else {
                $this->redirect(['error-operacion', 'cod' => 700]);
            }
        }



        /**
         * Metodo que lista las cuentas recaudadores segun el identificador del
         * banco, esto servira para colocarlo en la vista que renderiza un combo-lista.
         * @return array
         */
        public function actionListarCuentaRecaudadora()
        {
            $request = Yii::$app->request;
            $postGet = $request->get();
            $postData = $request->post();

            $model = New ConsultaGeneralPagoForm();
            return $model->listarCuentaRecaudadora($postGet);

        }


        /**
         * Metodo que indica la nota explicativa del proceso de ajuste, segun el tipo
         * seleccionado en el formulario de ajustes. Desde el formulario se envia una
         * peticion del tipo "get" que contiene la variable "tipo" que indica el tipo
         * de ajuste selccionado en el formulario, luego se envia el coontenido de la
         * nota-descriptiva del tipo de ajuste.
         * @return string
         */
        public function actionFindNotaTipoAjuste()
        {
            $request = Yii::$app->request;
            $postGet = $request->get();
            //$postData = $request->post();

            if ( isset($postGet['tipo']) ) {
                if ( (int)$postGet['tipo'] > 0 ) {
                    // Tipo de ajuste
                    $tipo = (int)$postGet['tipo'];
                    $result = TipoAjustePreReferenciaBancaria::find()->where(['inactivo' => 0])
                                                                     ->andWhere('tipo_ajuste =:tipo_ajuste',
                                                                                                [':tipo_ajuste' => $tipo])
                                                                     ->one();
                    return $result->nota_explicativa;
                }
            }
            return "";
        }





        /**
         * [actionMostrarListadoPreReferencia description]
         * @return [type] [description]
         */
        public function actionMostrarListadoPreReferencia()
        {
            if ( isset($_SESSION['begin']) ) {
                if ( $_SESSION['begin'] == 2 ) {

                    $mensajeSeleccion = '';
                    $request = Yii::$app->request;
                    $postEnviado = isset($_SESSION['postEnviado']) ? $_SESSION['postEnviado'] : [];
                    $model = New BusquedaPreReferenciaBancariaForm();
                    $formName = $model->formName();

                    $modelAjusteForm = New AjustePreReferenciaBancariaForm();

                    if ( isset($postEnviado['btn-search-request']) ) {
                        if ( trim($postEnviado[$formName]['recibo']) !== '' && $postEnviado[$formName]['recibo'] > 0 ) {
                            $model->scenario = self::SCENARIO_RECIBO;
                        } else {
                            $model->scenario = self::SCENARIO_FECHA;
                        }
                    }

                    $postData = $request->post();

                    if ( isset($postData['btn-ajustar-pre-referencia']) ) {
                        if ( (int)$postData['btn-ajustar-pre-referencia'] == 3 ) {
                            //  if ( $modelAjusteForm->load($postData) && Yii::$app->request->isAjax ) {
                            //     Yii::$app->response->format = Response::FORMAT_JSON;
                            //     return ActiveForm::validate($modelAjusteForm);
                            // }

                            $modelAjusteForm->load($postData);
                            if ( $modelAjusteForm->validate('tipo_ajuste', 'nota_explicativa') ) {

                                // Viene de presionar el boton para ajustar las pre-referencias,
                                // iniciar el proceso de ajuste de las pre-referencia.
                                $tipo = $postData[$modelAjusteForm->formName()]['tipo_ajuste'];

                                // Registros seleccionados
                                $idReferencias = isset($postData['chkIdReferencia']) ? $postData['chkIdReferencia'] : [];

                                if ( count($idReferencias) > 0 ) {
                                    $nota = Yii::t('backend', 'modificado por:') . ' ' . Yii::$app->identidad->getUsuario() . ' / ' . date('Y-m-d h:i:s');
                                    $ajuste = New AjustePreReferenciaBancaria($tipo, $nota);
                                    $ajuste->iniciarAjuste($idReferencias);
                                    if ( count($ajuste->getErrores()) == 0 ) {
                                        // No hubo errores. Se muestra los registros procesados.

                                    } else {
                                        // Mostrar errores ocurridos.
                                        return $this->render('/ajuste/pago/prereferencia/errores', [
                                                                            'mensajes' => $ajuste->getErrores(),
                                            ]);
                                    }

                                } else {
                                    $mensajeSeleccion = Yii::t('backend', 'No ha selccionado ningun registro');
                                }
                            }
                        }
                    }

                    $model->load($postEnviado);
                    $dataProvider = $model->getDataProvider();
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
                        if ( !isset($postData['btn-ajustar-pre-referencia']) ) {
                            $postEnviado = $request->post() !== null ? $request->post() : $_SESSION['postEnviado'];
                            $_SESSION['postEnviado'] = $postEnviado;
                        }
                    }

                    if ( isset($postEnviado['page']) ) {
                        $postEnviado = $_SESSION['postEnviado'];
                        $model->load($postEnviado);
                    }

                    $caption = Yii::t('backend', 'Ajuste de Pre-Referencias');
                    $subCaption = Yii::t('backend', 'Listado de Pre-Referencias y Tipos de Ajustes');

                    $listaTipoAjuste = $modelAjusteForm->listaTipoAjustePreReferencia();
                    // Listado de consulta.
                    return $this->render('/ajuste/pago/prereferencia/listado-pre-referencia', [
                                                    'caption' => $caption,
                                                    'subCaption' => $subCaption,
                                                    'dataProvider' => $dataProvider,
                                                    'model' => $model,
                                                    'listaTipoAjuste' => $listaTipoAjuste,
                                                    'modelAjusteForm' => $modelAjusteForm,
                                                    'mensajeSeleccion' => $mensajeSeleccion,
                            ]);
                } else {
                    // Session no valida.
                    $this->redirect(['session-no-valida']);
                }
            } else {
                // Session no valida.
                $this->redirect(['session-no-valida']);
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
         * Metodo que permite renderizar una vista de los detalles de la planilla
         * que se encuentran en la solicitud.
         * @return View Retorna una vista que contiene un grid con los detalles de la
         * planilla.
         */
        public function actionViewPlanillaModal()
        {
            $request = Yii::$app->request;
            $getData = $request->get();

            $planilla = $getData['p'];
            $planillaSearch = New PlanillaSearch($planilla);
            $dataProvider = $planillaSearch->getArrayDataProviderPlanilla();

            // Se determina si la peticion viene de un listado que contiene mas de una
            // pagina de registros. Esto sucede cuando los detalles de un listado contienen
            // mas de los manejados para una pagina en la vista.
            if ( isset($request->queryParams['page']) ) {
                $planillaSearch->load($request->queryParams);
            }
            $url = Url::to(['generar-pdf']);
            return $this->renderAjax('@backend/views/planilla/planilla-detalle', [
                                            'dataProvider' => $dataProvider,
                                            'caption' => 'Planilla: ' . $planilla,
                                            'p' => $planilla,
            ]);
        }


        /***/
        public function actionViewReferenciaModal()
        {
            $request = Yii::$app->request;
            $postGet = $request->get();

            // Identificador del contribuyente
            $id = (int)$postGet['id-referencia'];
die(var_dump($postGet));
            // return $this->renderAjax('@backend/views/buscar-general/view', [
            //                 'model' => $this->findModel($id),
            //        ]);
        }



        /**
         * Metodo que indica que la session ha terminado.
         * @return view, con mensaje.
         */
        public function actionSessionNoValida()
        {
            return MensajeController::actionMensaje(901);
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
                        'postEnviado',
                        'scenario',
                        'seleccion',
                    ];

        }

    }
?>