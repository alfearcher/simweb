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
    use common\models\contribuyente\ContribuyenteBase;



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
        private function setErrors($mensajeError = '')
        {
            $this->errors[] = $mensajeError;
        }


        /***/
        public function getErrors()
        {
            return $this->errors
        }



        /***/
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



        /***/
        public function findInscripcionActividadEconomica()
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
            $modelInscripcion = self::findInscripcionActividadEconomica();
            if ( $modelInscripcion !== null ) {
                if ( $modelInscripcion['id_contribuyente'] == $this->_model->id_contribuyente ) {
                    $result = self::updateSolicitudInscripcion($modelInscripcion);
                    if ( $result ) {
                        $result = self::updateContribuyente($modelInscripcion);
                    }
                } else {
                    self::setErrors(Yii::t('backend', 'Error in the ID of taxpayer'));
                }
            } else {
                self::setErrors(Yii::t('backend', 'Request not find'));
            }

            return $result;
        }



        /***/
        private function negarDetalleSolicitud()
        {
            $result = false;
            $modelInscripcion = self::findInscripcionActividadEconomica();
            if ( $modelInscripcion !== null ) {
                if ( $modelInscripcion['id_contribuyente'] == $this->_model->id_contribuyente ) {
                    $result = self::updateSolicitudInscripcion($modelInscripcion);
                } else {
                    self::setErrors(Yii::t('backend', 'Error in the ID of taxpayer'));
                }
            }

            return $result;
        }



        /***/
        private function updateSolicitudInscripcion($modelInscripcion)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New InscripcionActividadEconomicaForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            $camposModel = $modelInscripcion->toArray();

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($camposModel['nro_solicitud']) ? $camposModel['nro_solicitud'] : null;

            foreach ( $arregloCampos as $campo ) {
                if ( isset($camposModel[$campo]) ) {
                    $arregloDatos[$campo] = $camposModel[$campo];
                } else {
                    $cancel = true;
                    break;
                }
            }

            if ( count($arregloCampos) == 0 || count($arregloCondicion) == 0 ) { $cancel = true; }

            // Si no existe en la solicitud un campo que viene del modelo que deba ser
            // actualizado, entonces el proceso debe ser cancelado.
            if ( !$cancel ) {
                $result = $this->_conexion->modificarRegistro($this->_conn, $tableName,
                                                              $arregloDatos, $arregloCondicion);
            }
            if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update request')); }
            return $result;
        }



        /***/
        private function updateContribuyente($modelInscripcion)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            $tablaPrincipal = ContribuyenteBase::tableName();

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New InscripcionActividadEconomicaForm();

            // Se obtienen los campos que seran actualizados en la entidad "contribuyentes".
            $arregloCampos = $model->atributosUpDate();

            // Se obtienen los campos y valores creados en la solicitud. lo siguiente genera un array de
            // campos => valores.
            $camposModel = $modelInscripcion->toArray();

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['id_contribuyente'] = isset($camposModel['id_contribuyente']) ? $camposModel['id_contribuyente'] : null;

            foreach ( $arregloCampos as $campo ) {
                if ( isset($camposModel[$campo]) ) {
                    $arregloDatos[$campo] = $camposModel[$campo];
                } else {
                    $cancel = true;
                    break;
                }
            }

            if ( count($arregloCampos) == 0 || count($arregloCondicion) == 0 ) { $cancel = true; }

            // Si no existe en la solicitud un campo que viene del modelo que deba ser
            // actualizado, entonces el proceso debe ser cancelado.
            if ( !$cancel ) {
                $result = $this->_conexion->modificarRegistro($this->_conn, $tablaPrincipal,
                                                              $arregloDatos, $arregloCondicion);
            }
            if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update taxpayer')); }

            return $result;
        }




    }

 ?>