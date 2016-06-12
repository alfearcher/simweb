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

        private $_nro_solicitud;
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
         * @param Long $nroSolicitud identificador de la solicitud creada por el
         * funcionario o contribuyente.
         * @param String $accionLocal especifica el proceso a ejecutar sobre la solicitud,
         * este proceso queda definido por los eventos:
         * - Aprobar
         * - Negar
         * @param [type] $connLocal     [description]
         * @param [type] $conexionLocal [description]
         */
        public function __construct($nroSolicitud, $accionLocal, $connLocal, $conexionLocal)
        {
            $this->_nro_solicitud = $nroSolicitud;
            $this->_accion = $accionLocal;
            $this->_conn = $connLocal;
            $this->_conexion = $conexionLocal;
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
        public function procesarSolicitudPorImpuesto()
        {
            $result = false;
            $model = self::getDatosSolicitudCreada();
            if ( $model !== null ) {
                if ( $model['impuesto'] == 1 ) {
                    // Actividades Economicas

                } elseif ( $model['impuesto'] == 2 ) {
                    // Inmuebles Urbanos.

                } elseif ( $model['impuesto'] == 3 ) {
                    // Vehiculos.

                } elseif ( $model['impuesto'] == 4 ) {
                    // Propaganda Comercial.

                } elseif ( $model['impuesto'] == 6 ) {
                    // Espectaculo Publico.

                } elseif ( $model['impuesto'] == 7 ) {
                    // Apuesta Licita.

                }
            }
            return $result;
        }



    }

 ?>