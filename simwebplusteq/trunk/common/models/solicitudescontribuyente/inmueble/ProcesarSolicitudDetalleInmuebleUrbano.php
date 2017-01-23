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
    use common\models\solicitudescontribuyente\inmueble\ProcesarAvaluoInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarActualizacionDatosInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarCambioNumeroCatastralInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarCambioPropiedadHorizontalInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarDesincorporacionInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarDesintegracionInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarCambioPropietarioVendedorInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarCambioPropietarioCompradorInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarSolicitudSolvenciaInmueble;
    use common\models\solicitudescontribuyente\inmueble\ProcesarLinderosInmuebleUrbano;
    use common\models\solicitudescontribuyente\inmueble\ProcesarRegistrosInmuebleUrbano;



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
                    //Inscripcion
                    $procesar = New ProcesarInscripcionInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 21 ) {
                    //Avaluo
                    $procesar = New ProcesarAvaluoInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 22 ) {
                    //Certificado Catastral

                } elseif ( $this->_model->tipo_solicitud == 23 ) {
                    //Renovacion Certificado Catastral

                } elseif ( $this->_model->tipo_solicitud == 24 ) {
                    //Solvencia

                } elseif ( $this->_model->tipo_solicitud == 25 ) {
                    //Cambio Numero Catastral
                    $procesar = New ProcesarCambioNumeroCatastralInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 26 ) {
                    //Cambio Propietario Vendedor
                    $procesar = New ProcesarCambioPropietarioVendedorInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 27 ) {
                    //Integracion
                    $procesar = New ProcesarIntegracionInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 28 ) {
                    //Desintegracion
                    $procesar = New ProcesarDesintegracionInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 29 ) {
                    //Cambio Propiedad Horizontal
                    $procesar = New ProcesarCambioPropiedadHorizontalInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 30 ) {
                    //cambio otros datos
                    $procesar = New ProcesarActualizacionDatosInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 31 ) {
                    //Modificar Avaluo

                } elseif ( $this->_model->tipo_solicitud == 65 ) {
                    //Desincorporacion
                    $procesar = New ProcesarDesincorporacionInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 67 ) {
                    //Cambio Propietario Comprador
                    $procesar = New ProcesarCambioPropietarioCompradorInmuebleUrbano($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 82 ) {

                    $procesar = New ProcesarSolicitudSolvenciaInmueble($this->_model,
                                                                       $this->_evento,
                                                                       $this->_conn,
                                                                       $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 84 ) {
                    //linderos
                    $procesar = New ProcesarLinderosInmuebleUrbano($this->_model,
                                                                       $this->_evento,
                                                                       $this->_conn,
                                                                       $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 85 ) { 
                    //inmuebles registros
                    $procesar = New ProcesarRegistrosInmuebleUrbano($this->_model,
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