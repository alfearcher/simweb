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
 *  @file ProcesarInscripcionActividadEconomica.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19/06/2016
 *
 *  @class ProcesarInscripcionActividadEconomica
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
    use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaForm;
    use backend\models\aaee\inscripcionactecon\InscripcionActividadEconomicaSearch;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarInscripcionActividadEconomica extends InscripcionActividadEconomicaSearch
    {
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
         * @param Active Record $model, modelo de la entidad "solicitudes-contribuyente".
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
            parent::__construct($model['id_contribuyente']);
        }




        /***/
        public function procesarSolicitud()
        {
            $result = false;
            if ( $this->_evento == Yii::$app->solicitud->aprobar() ) {
                $result = self::aprobarDetalleSolicitud();

            } elseif ( $this->_evento == Yii::$app->solicitud->negar() ) {

            }

            return $result;
        }



        /***/
        public function findInscripcion()
        {
            // Este find retorna el modelo de la entidad "sl-inscripciones-act-econ"
            // con datos, ya que en el metodo padre se ejecuta el ->one() que realiza
            // la consulta.
            $modelFind = $this->findInscripcion($this->_model->nro_solicitud);
            return isset($modelFind) ? $modelFind : null;
        }



        /***/
        private function aprobarDetalleSolicitud()
        {
            $result = false;
            $modelInscripcion = self::findInscripcion();
            if ( $modelInscripcion !== null ) {
                if ( $modelInscripcion['id_contribuyente'] == $this->_model->id_contribuyente ) {
                    $result = self::updateSolicitudInscripcion();

                }
            }
            return $result;
        }


        /***/
        private function updateSolicitudInscripcion()
        {
            $result = false;
            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New InscripcionActividadEconomicaForm();
            $tableName = $model->tableName();
die(var_dump($tableName));
        }





    }

 ?>