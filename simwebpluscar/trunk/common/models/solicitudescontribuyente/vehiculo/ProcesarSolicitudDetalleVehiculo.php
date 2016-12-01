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
 *  @file ProcesarSolicitudDetalleIVehiculo.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 04/08/2016
 *
 *  @class ProcesarSolicitudDetalleVehiculo
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

    namespace common\models\solicitudescontribuyente\vehiculo;

    use Yii;
    use common\models\solicitudescontribuyente\vehiculo\ProcesarInscripcionVehiculo;




    /***/
    class ProcesarSolicitudDetalleVehiculo
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
         * Constructor de la clase.
         * @param Active Record $model modelo de la entidad "solicitudes-contribuyente".
         * @param String $evento especifica el proceso a ejecutar sobre la solicitud,
         * este proceso queda definido por los eventos:
         * - Aprobar
         * - Negar
         * @param [type] $conn     [description]
         * @param [type] $conexion [description]
         */
        public function __construct($model, $evento, $conn, $conexion)
        {
            $this->_model = $model;
            $this->_evento = $evento;

            $this->_conn = $conn;
            $this->_conexion = $conexion;

        }



        /**
         * Metodo que redirecciona a la clase que procesa la solicitud por su tipo.
         * @return Boolean Retorna un true o false.
         */
        public function procesarSolicitudPorTipo()
        {
            //die(var_dump($this->_model));

            $result = false;
            if ( isset($this->_model) && $this->_model !== null ) {

                if ( $this->_model->tipo_solicitud == 32 ) {

                    $procesar = New ProcesarInscripcionVehiculo($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 35 ) {

                    $procesar = New ProcesarCambioPropietarioVendedor($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 66 ) {

                    $procesar = New ProcesarCambioPropietarioComprador($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 36 ) {

                    $procesar = New ProcesarCambioPlaca($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 37 ) {

                    $procesar = New ProcesarDesincorporacionVehiculo($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 38 ) {

                    $procesar = New ProcesarCambioDatosVehiculo($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 68 ) {

                     $procesar = New ProcesarCambioCalcomaniaExtravio($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);

                    $result = $procesar->procesarSolicitud();



                } elseif ( $this->_model->tipo_solicitud == 27 ) {

                } elseif ( $this->_model->tipo_solicitud == 28 ) {

                } elseif ( $this->_model->tipo_solicitud == 29 ) {

                } elseif ( $this->_model->tipo_solicitud == 30 ) {

                } elseif ( $this->_model->tipo_solicitud == 31 ) {


                } elseif ( $this->_model->tipo_solicitud == 83 ) {

                    $procesar = New ProcesarSolicitudSolvenciaVehiculo($this->_model,
                                                                       $this->_evento,
                                                                       $this->_conn,
                                                                       $this->_conexion);

                    $result = $procesar->procesarSolicitud();
                }

            }
            return $result;
        }








    }

 ?>