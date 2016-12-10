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
 *  @file ProcesarCorreccionCedulaRif.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04/08/2016
 *
 *  @class ProcesarCorreccionCedulaRif
 *  @brief
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *
 *
 *  @inherits
 *
 */

    namespace common\models\solicitudescontribuyente\aaee;

    use Yii;
    use backend\models\aaee\autorizarramo\AutorizarRamoSearch;
    use backend\models\aaee\autorizarramo\AutorizarRamoForm;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\aaee\actecon\ActEconForm;
    use backend\models\aaee\acteconingreso\ActEconIngresoForm;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarAutorizarRamo extends AutorizarRamoSearch
    {
        /**
         * [$_model modelo de la entidad "solicitudes-contribuyente"
         * @var Active Record.
         */
        private $_model;

        private $_conn;
        private $_conexion;

        /**
         * Especifica el tipo de proceso a ejecutar sobre la solicitud. Los procesos a ejecutar
         * son aquellos definidos por las variables:
         * - Aprobar    => Yii::$app->solicitud->aprobar()
         * - Negar      => Yii::$app->solicitud->negar()
         * Para verificar los procesos o eventos: common\classes\EventoSolicitud
         *
         * @var String
         */
        private $_evento;

        /**
         * $errors, array de mensajes que indica que ha sucedido un evento inexperado
         * que no permitio completar la operacion. Si count($arregloErrors) == 0, indica que
         * no hubo inconvenientes para realizar la operacion.
         * @var array
         */
        public $errors = [];



        /**
         * Constructor de la clase.
         * @param Active Record $model, modelo de la entidad "solicitudes-contribuyente".
         * @param String $evento especifica el proceso a ejecutar sobre la solicitud,
         * este proceso queda definido por los eventos:
         * - Aprobar
         * - Negar
         * @param connection $conn instancia de connection.
         * @param ConexionController $conexion instancia de la clase ConexionController.
         */
        public function __construct($model, $evento, $conn, $conexion)
        {
            $this->_model = $model;
            $this->_evento = $evento;
            $this->_conn = $conn;
            $this->_conexion = $conexion;
            parent::__construct($model['id_contribuyente']);
        }


        /**
         * Metodo que asigna un mensaje de error al arreglo. Esto permitira
         * saber si la ejecucion de los procesos de aprobacion o negacion
         * se realizaron satisfactiamente. Si existe un mensaje en este
         * arreglo, significa que sucedio algo que impidio la ejecucion del
         * proceso.
         * @param string $mensajeError mensaje de error.
         */
        private function setErrors($mensajeError = '')
        {
            $this->errors[] = $mensajeError;
        }


        /**
         * Metodo que permite obtener el arreglo de mensajes de errores.
         * @return Array Retorna arreglo con mensaje de errores.
         */
        public function getErrors()
        {
            return $this->errors;
        }



        /**
         * Metodo que inicia el procedimiento de procesar la solcitud.
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function procesarSolicitud()
        {
            $result = false;
            if ( $this->_evento == Yii::$app->solicitud->aprobar() ) {
                $result = self::aprobarDetalleSolicitud();

            } elseif ( $this->_evento == Yii::$app->solicitud->negar() ) {
                $result = self::negarDetalleSolicitud();
            }
            return $result;
        }



        /**
         * Metodo que permite obtener un modelo de los datos de la solicitud,
         * sobre la entidad "sl-", referente al detalle de la solicitud. Es la
         * entidad donde se guardan los detalle de esta solicitud.
         * @return boolean retorna una instancia modelo active record de
         * utorizarRamo si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findAutorizarRamo()
        {
            // Este find retorna el modelo de la entidad "sl-ramos-autorizados".
            $findModel = $this->findSolicitudAutorizarRamo($this->_model->nro_solicitud);

            // Lo siguiente puede generar uno o varios registros.
            $model = $findModel->all();
            return isset($model) ? $model : null;
        }



        /**
         * Metodo que inicia la aprobacion del detalle de la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function aprobarDetalleSolicitud()
        {
            $result = false;
            $idGenerado = 0;
            // modelo de AutorizarRamo. Uno o varios registros.
            $modelRamo = self::findAutorizarRamo();
            if ( $modelRamo !== null ) {
                // Entidad "sl-".
                $result = self::updateSolicitudAutorizarRamo($modelRamo);
                if ( $result ) {
                    $result = self::iniciarCicloAnoImpositivo($modelRamo);
                }
            } else {
                self::setErrors(Yii::t('backend', 'Request not find'));
            }

            return $result;
        }



        /**
         * Metodo que incia el proceso de negacion de la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function negarDetalleSolicitud()
        {
            $result = false;
            $modelRamo = self::findAutorizarRamo();
            if ( $modelRamo !== null ) {
                $result = self::updateSolicitudAutorizarRamo($modelRamo);
            }

            return $result;
        }



        /**
         * Metodo que realiza la actualizacin de los atributos segun el evento a ejecutar
         * sobre la solicitud.
         * @param  Active Record $modelRamo modelo de la entidad "sl-ramos-autorizos"
         * (AuitorizarRamo). Este modelo contiene los datos-detalles, referida a los
         * datos cargados al momento de elaborar la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudAutorizarRamo($modelRamo)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New AutorizarRamoForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($modelRamo[0]->nro_solicitud) ? $modelRamo[0]->nro_solicitud : null;

            if ( count($arregloCampos) == 0 || count($arregloCondicion) == 0 ) { $cancel = true; }

            // Si no existe en la solicitud un campo que viene del modelo que deba ser
            // actualizado, entonces el proceso debe ser cancelado.
            if ( !$cancel ) {
                $result = $this->_conexion->modificarRegistro($this->_conn,
                                                              $tableName,
                                                              $arregloCampos,
                                                              $arregloCondicion);
            }

            if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update request')); }
            return $result;
        }



        /**
         * Metodo que inicia el proceso para guardar y crear los registros en las entidades
         * respectivas segun los ramos autorizados. Se guarda en la entidad principal por cada
         * año impositivo, y por cada año se guardan los ramos correspondientes.
         * @param  model $modelRamo modelo de AutorizarRamo.
         * @return boolean retorna true si se guarda de forma correcta, false en caso contrario.
         */
        private function iniciarCicloAnoImpositivo($modelRamo)
        {
            $result = false;
            $idImpuesto = 0;
            $a = 0;             // Control del año que se va procesando.
            $listaIdRubro = [];
            $rubros = [];
            $searchModel = New AutorizarRamoSearch($modelRamo[0]->id_contribuyente);

            try {
                foreach ( $modelRamo as $ramo ) {
                    if ( $a !== $ramo['ano_impositivo'] ) {
                        $a = $ramo['ano_impositivo'];
                        $idImpuesto = self::createActEcon($ramo['id_contribuyente'], $a);
                        if ( $idImpuesto == 0 ) {
                            $result = false;
                            break;
                        }
                    }
                    $result = self::createActEconIngresos($ramo, $idImpuesto);
                    if ( !$result ) { break; }
                }
            } catch ( Exception $e ) {

            }

            return $result;

        }






        /**
         * Metodo realiza la insercion en la entidad principal de la declaracion.
         * La insercion se realiza por año impositivo.
         * @param long $idContribuyente identificador del contribuyente.
         * @param  integer $añoImpositivo año impositivo que corresponde.
         * @return boolean retorna true si guarda, false en caso contrario.
         */
        private function createActEcon($idContribuyente, $añoImpositivo)
        {
            $idImpuesto = 0;
            $exigDeclaracion = 0;

            $searchModel = New AutorizarRamoSearch($idContribuyente);
            $exigDeclaracion = $searchModel->getExigibilidadDeclaracionSegunAnoImpositivo($añoImpositivo);

            if ( $exigDeclaracion > 0 ) {
                $modelActEcon = New ActEconForm();

                $arregloDatos = $modelActEcon->attributes;
                foreach ( $arregloDatos as $key => $value ) {
                    $arregloDatos[$key] = 0;
                }
                $arregloDatos['ente'] = Yii::$app->ente->getEnte();
                $arregloDatos['id_contribuyente'] = $idContribuyente;
                $arregloDatos['ano_impositivo'] = $añoImpositivo;
                $arregloDatos['exigibilidad_declaracion'] = $exigDeclaracion;

                // Se procede a guardar en la entidad maestra de las declaraciones.
                $tabla = '';
                $tabla = $modelActEcon->tableName();

                if ( $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDatos) ) {
                    $idImpuesto = $this->_conn->getLastInsertID();
                }
            }
            return $idImpuesto;
        }



        /**
         * Metodo que guarda el detalle de los ramos autorizados, por cada ramo
         * se realiza una insercion.
         * @param  array $ramo arreglo de atributos correspondiente a una fila
         * de la solicitud. Este arreglo contiene los valores que se insertaran
         * en las entidades detalles donde se guardan los ramos.
         * @param long $idImpuesto identificador de la entidad maestra (act-econ).
         * @return boolean retorna true si guarda, false en caso contrario.
         */
        private function createActEconIngresos($ramo, $idImpuesto)
        {
            $result = false;
            if ( $idImpuesto > 0 ) {
                if ( count($ramo) > 0 ) {

                    $modelActEconIngreso = New ActEconIngresoForm();
                    $arregloDatos = $modelActEconIngreso->attributes;

                    foreach ( $arregloDatos as $key => $value ) {
                        $arregloDatos[$key] = 0;
                    }
                    $arregloDatos['id_impuesto'] = $idImpuesto;
                    $arregloDatos['exigibilidad_periodo'] = $ramo['periodo'];
                    $arregloDatos['periodo_fiscal_desde'] = isset($ramo['fecha_desde']) ? $ramo['fecha_desde'] : '0000-00-00';
                    $arregloDatos['periodo_fiscal_hasta'] = isset($ramo['fecha_hasta']) ? $ramo['fecha_hasta'] : '0000-00-00';
                    $arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');
                    $arregloDatos['usuario'] = $ramo['usuario'];
                    $arregloDatos['id_rubro'] = $ramo['id_rubro'];
                    $arregloDatos['condicion'] = 2;

                    // Se procede a guardar en la entidad maestra de las declaraciones.
                    $tabla = '';
                    $tabla = $modelActEconIngreso->tableName();

                    if ( $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDatos) ) {
                        $result = true;
                    }
                }
            }
            return $result;
        }



    }

 ?>