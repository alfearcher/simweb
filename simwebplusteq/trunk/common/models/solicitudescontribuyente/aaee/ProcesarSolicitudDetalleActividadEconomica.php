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
 *  @file ProcesarSolicitudDetalleActividadEconomica.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11/06/2016
 *
 *  @class ProcesarSolicitudDetalleActividadEconomica
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
    use common\models\solicitudescontribuyente\aaee\ProcesarInscripcionActividadEconomica;
    use common\models\solicitudescontribuyente\aaee\ProcesarInscripcionSucursal;
    use common\models\solicitudescontribuyente\aaee\ProcesarCorreccionDomicilioFiscal;
    use common\models\solicitudescontribuyente\aaee\ProcesarCorreccionCedulaRif;
    use common\models\solicitudescontribuyente\aaee\ProcesarCorreccionCapital;
    use common\models\solicitudescontribuyente\aaee\ProcesarCorreccionRepresentanteLegal;
    use common\models\solicitudescontribuyente\aaee\ProcesarCorreccionRazonSocial;
    use common\models\solicitudescontribuyente\aaee\ProcesarAutorizarRamo;
    use common\models\solicitudescontribuyente\aaee\ProcesarCorreccionFechaInicio;
    use common\models\solicitudescontribuyente\aaee\ProcesarAnexarRamo;
    use common\models\solicitudescontribuyente\aaee\ProcesarDesincorporarRamo;
    use common\models\solicitudescontribuyente\aaee\ProcesarDeclaracionEstimada;
    use common\models\solicitudescontribuyente\aaee\ProcesarDeclaracionDefinitiva;
    use common\models\solicitudescontribuyente\aaee\ProcesarDeclaracionSustitutiva;
    use common\models\solicitudescontribuyente\aaee\ProcesarSolicitudLicencia;
    use common\models\solicitudescontribuyente\aaee\ProcesarSolicitudSolvenciaActividadEconomica;


    /**
     * Clase que permite rediccional entre las solicitudes pertenecientes al impuesto de
     * Actividades Economicas. Se determina el tipo de la solicitud y se instancia la  clase
     * respectiva de dicha solicitud.
     */
    class ProcesarSolicitudDetalleActividadEconomica
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
         * @param connsction $conn instancia de connection
         * @param ConexionController $conexion instancia de la clase ConexionController.
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
                if ( $this->_model->tipo_solicitud == 1 ) {
                    $procesar = New ProcesarInscripcionActividadEconomica($this->_model,
                                                                          $this->_evento,
                                                                          $this->_conn,
                                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 2 ) {
                    $procesar = New ProcesarInscripcionSucursal($this->_model,
                                                                $this->_evento,
                                                                $this->_conn,
                                                                $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 3 ) {
                    $procesar = New ProcesarSolicitudLicencia($this->_model,
                                                              $this->_evento,
                                                              $this->_conn,
                                                              $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 4 ) {
                    $procesar = New ProcesarSolicitudLicenciaRenovacion($this->_model,
                                                                        $this->_evento,
                                                                        $this->_conn,
                                                                        $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 7 ) {

                    $procesar = New ProcesarSolicitudSolvenciaActividadEconomica($this->_model,
                                                                                 $this->_evento,
                                                                                 $this->_conn,
                                                                                 $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 8 ) {
                    $procesar = New ProcesarDeclaracionDefinitiva($this->_model,
                                                                   $this->_evento,
                                                                   $this->_conn,
                                                                   $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 9 ) {
                    $procesar = New ProcesarAnexarRamo($this->_model,
                                                       $this->_evento,
                                                       $this->_conn,
                                                       $this->_conexion);

                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 10 ) {
                    $procesar = New ProcesarDesincorporarRamo($this->_model,
                                                              $this->_evento,
                                                              $this->_conn,
                                                              $this->_conexion);

                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 12 ) {
                    $procesar = New ProcesarCorreccionRazonSocial($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 13 ) {
                    $procesar = New ProcesarCorreccionDomicilioFiscal($this->_model,
                                                                      $this->_evento,
                                                                      $this->_conn,
                                                                      $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 14 ) {
                    $procesar = New ProcesarCorreccionRepresentanteLegal($this->_model,
                                                                         $this->_evento,
                                                                         $this->_conn,
                                                                         $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 15 ) {
                    $procesar = New ProcesarCorreccionCapital($this->_model,
                                                              $this->_evento,
                                                              $this->_conn,
                                                              $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 17 ) {
                    $procesar = New ProcesarDeclaracionSustitutiva($this->_model,
                                                                   $this->_evento,
                                                                   $this->_conn,
                                                                   $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 69 ) {
                    $procesar = New ProcesarCorreccionCedulaRif($this->_model,
                                                                $this->_evento,
                                                                $this->_conn,
                                                                $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 70 ) {
                    $procesar = New ProcesarAutorizarRamo($this->_model,
                                                          $this->_evento,
                                                          $this->_conn,
                                                          $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 71 ) {
                    $procesar = New ProcesarCorreccionFechaInicio($this->_model,
                                                                  $this->_evento,
                                                                  $this->_conn,
                                                                  $this->_conexion);
                    $result = $procesar->procesarSolicitud();

                } elseif ( $this->_model->tipo_solicitud == 73 ) {
                    $procesar = New ProcesarDeclaracionEstimada($this->_model,
                                                                $this->_evento,
                                                                $this->_conn,
                                                                $this->_conexion);
                    $result = $procesar->procesarSolicitud();


                } elseif ( $this->_model->tipo_solicitud == 'c' ) {
                }
            }
            return $result;
        }








    }

 ?>