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
 *  @file ProcesarSolicitudDetalle.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11/06/2016
 *
 *  @class ProcesarSolicitudDetalle
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

    namespace common\models\solicitudescontribuyente;

    use Yii;
    use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;



    /***/
    class ProcesarSolicitudDetalle extends SolicitudesContribuyenteForm
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
        private $_accion;



        /**
         * Constructor de la clase.
         * @param Active Record $model modelo de la entidad "solicitudes-contribuyente".
         * @param String $accion especifica el proceso a ejecutar sobre la solicitud,
         * este proceso queda definido por los eventos:
         * - Aprobar
         * - Negar
         * @param [type] $conn     [description]
         * @param [type] $conexion [description]
         */
        public function __construct($model, $accion, $conn, $conexion)
        {
            $this->_model = $model;
            $this->_accion = $accion;
            $this->_conn = $conn;
            $this->_conexion = $conexion;
        }




        /**
         * Metodo que realiza una busqueda de la solicitud con el parametro numero de solicitud.
         * @return Active Record Retorna un modelo de la solicitud encontrada.
         */
        private function getDatosSolicitudCreada()
        {
            // find sobre SoliicitudesContribuyente.
            // Metodo clase padre.
            $datos = $this->findSolicitudContribuyente($this->_nro_solicitud);
            return isset($datos) ? $datos : null;
        }



        /***/
        public function procesarSolicitudPorTipo()
        {
            $result = false;
            if ( isset($this->_model) && $this->_model !== null ) {
                if ( $this->_model->tipo_solicitud == 1 ) {

                }
            }
            return $result;
        }



    }

 ?>