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
 *  @file ProcesarSolicitudDetalleInmuebleUrbano.php
 *
 *  @author Alvaro Jose Fernandez Archer
 *
 *  @date 18/07/2016
 *
 *  @class ProcesarSolicitudDetalleInmuebleUrbano
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

    namespace common\models\solicitudescontribuyente\inmueble;

    use Yii;
    use common\models\solicitudescontribuyente\inmueble\ProcesarInscripcionInmuebleUrbano;



    /***/
    class ProcesarSolicitudDetalleInmuebleUrbano
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
            $result = false;
            if ( isset($this->_model) && $this->_model !== null ) {
                if ( $this->_model->tipo_solicitud == 20 ) {
                    $procesar = New ProcesarInscripcionInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 21 ) {

                } elseif ( $this->_model->tipo_solicitud == 22 ) {

                } elseif ( $this->_model->tipo_solicitud == 23 ) {

                } elseif ( $this->_model->tipo_solicitud == 24 ) {

                } elseif ( $this->_model->tipo_solicitud == 25 ) {

                } elseif ( $this->_model->tipo_solicitud == 26 ) {

                } elseif ( $this->_model->tipo_solicitud == 27 ) {

                } elseif ( $this->_model->tipo_solicitud == 28 ) {

                } elseif ( $this->_model->tipo_solicitud == 29 ) {

                } elseif ( $this->_model->tipo_solicitud == 30 ) {

                } elseif ( $this->_model->tipo_solicitud == 31 ) {

                }

            }
            return $result;
        }








    }

 ?>