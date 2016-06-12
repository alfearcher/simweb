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

    namespace common\models\solicitudescontribuyente;

    use Yii;
    use common\models\solicitudescontribuyente\ProcesarSolicitudDetalle;



    /***/
    class ProcesarSolicitudDetalle
    {
        /**
         * $_model, modelo de la entidad "solicitudes-contribuyente", es el modelo de la
         * solicitud maestro.
         * @var Active Record.
         */
        private $_model;

        private $_conn;
        private $_conexion;

        /**
         * $_accion, especifica el tipo de proceso a ejecutar sobre la solicitud. Los procesos a ejecutar
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
         * @param Active Record model, modelo de la entidad solicitud maestra, es un find
         * que posee la informacion de la solicitud a procesar.
         * @param String $accionLocal especifica el proceso a ejecutar sobre la solicitud,
         * este proceso queda definido por los eventos:
         * - Aprobar
         * - Negar
         * @param [type] $connLocal     [description]
         * @param [type] $conexionLocal [description]
         */
        public function __construct($model, $accionLocal, $connLocal, $conexionLocal)
        {
            $this->_model = $model;
            $this->_accion = $accionLocal;
            $this->_conn = $connLocal;
            $this->_conexion = $conexionLocal;
        }




        



    }

 ?>