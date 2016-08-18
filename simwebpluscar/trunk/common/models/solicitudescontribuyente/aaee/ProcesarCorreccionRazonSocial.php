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
 *  @file ProcesarCorreccionRazonSocial.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09/08/2016
 *
 *  @class ProcesarCorreccionRazonSocial
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
    use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocialSearch;
    use backend\models\aaee\correccionrazonsocial\CorreccionRazonSocialForm;
    use common\models\contribuyente\ContribuyenteBase;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarCorreccionRazonSocial extends CorreccionRazonSocialSearch
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
         * CorreccionRazonSocial si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findCorreccionRazonSocial()
        {
            // Este find retorna el modelo de la entidad "sl-correcciones-rs".
            $findModel = $this->findSolicitudCorreccionRazonSocial($this->_model->nro_solicitud);

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
            // modelo de CorreccionCapital. Uno o varios registros.
            $modelCorreccion = self::findCorreccionRazonSocial();
            if ( $modelCorreccion !== null ) {
                // Entidad "sl-".
                $result = self::updateSolicitudCorreccionRazonSocial($modelCorreccion);
                if ( $result ) {
                    $result = self::updateRazonSocial($modelCorreccion);
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
            $modelCorreccion = self::findCorreccionRazonSocial();
            if ( $modelCorreccion !== null ) {
                $result = self::updateSolicitudCorreccionRazonSocial($modelCorreccion);
            }

            return $result;
        }



        /**
         * Metodo que realiza la actualizacin de los atributos segun el evento a ejecutar
         * sobre la solicitud.
         * @param  Active Record $modelCorreccion modelo de la entidad "sl-correciones-rs"
         * (CorreccionRazonSocial). Este modelo contiene los datos-detalles, referida a los
         * datos cargados al momento de elaborar la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudCorreccionRazonSocial($modelCorreccion)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New CorreccionRazonSocialForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($modelCorreccion[0]->nro_solicitud) ? $modelCorreccion[0]->nro_solicitud : null;

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
         * Metodo que actualiza la razon social de los contribuyentes que estan en la solicitud
         * segun el contenido nuevo que se coloco en la solicitud.
         * @param  model $modelCorreccion modelo de CorreccionRazonSocial. Contiene los datos
         * del find.
         * @return boolean retorna true si ejecuta la actualizacion de los montos de los
         * capitales, false en caso contrario.
         */
        private function updateRazonSocial($modelCorreccion)
        {
            $result = false;
            $tabla = ContribuyenteBase::tableName();

            foreach ( $modelCorreccion as $key => $value ) {
                $arregloCondicion = ['id_contribuyente' => $modelCorreccion[$key]->id_contribuyente];
                $arregloDatos = [
                    'razon_social' => $modelCorreccion[$key]->razon_social_new,
                ];

                $result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);
                if ( !$result ) { break; }
            }

            return $result;
        }





    }

 ?>