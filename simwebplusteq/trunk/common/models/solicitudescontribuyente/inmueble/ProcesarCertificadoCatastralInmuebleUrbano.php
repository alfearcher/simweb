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
 *  @file ProcesarInscripcionInmuebleUrbano.php
 *
 *  @author Alvaro Jose Fernandez ARcher
 *
 *  @date 19/07/2016
 *
 *  @class ProcesarInscripcionInmuebleUrbano
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
    use backend\models\aaee\InmueblesUrbanosForm;
    use backend\models\inmueble\SlCertificadoCatastralSearch;
    use common\models\contribuyente\ContribuyenteBase;
    use common\models\inmueble\certificadocatastral\JsonCertificado;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarCertificadoCatastralInmuebleUrbano extends SlCertificadoCatastralSearch
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
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findCertificadoCatastralUrbano()
        {
            // Este find retorna el modelo de la entidad "sl-inscripciones-act-econ"
            // con datos, ya que en el metodo padre se ejecuta el ->one() que realiza
            // la consulta.
            $modelFind = $this->findCertificado($this->_model->nro_solicitud);
            return isset($modelFind) ? $modelFind : null;
        }

        /**
        * Metodo que retorna un arreglo de atributos que seran actualizados
        * al momento de procesar la solicitud (aprobar o negar). Estos atributos
        * afectaran a la entidad respectiva de la clase.
        * @param String $evento, define la accion a realizar sobre la solicitud.
        * - Aprobar.
        * - Negar.
        * @return Array Retorna un arreglo de atributos segun el evento.
        */
        public function atributosUpDateProcesarSolicitud($evento)
        {
            $atributos = [
                Yii::$app->solicitud->aprobar() => [
                                    'estatus' => 1,
                                    'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,
                                    'fecha_hora_proceso' => date('Y-m-d H:i:s')
                ],
                Yii::$app->solicitud->negar() => [
                                    'estatus' => 9,
                                    'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,
                                    'fecha_hora_proceso' => date('Y-m-d H:i:s')
                ],
            ];

           return $atributos[$evento];
        }



        /**
         * Metodo que inicia la aprobacion del detalle de la solicitud.
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function aprobarDetalleSolicitud()
        {
            $result = false;
            $modelInscripcion = self::findCertificadoCatastralUrbano();
            if ( $modelInscripcion !== null ) {
                if ( $modelInscripcion['id_contribuyente'] == $this->_model->id_contribuyente ) {
                    $result = self::updateSolicitudInscripcion($modelInscripcion);
                    // if ( $result ) {
                    //     $result = self::updateContribuyente($modelInscripcion);
                    // }
                } else {
                    self::setErrors(Yii::t('backend', 'Error in the ID of taxpayer'));
                }
            } else {
                self::setErrors(Yii::t('backend', 'Request not find'));
            }

            return $result;
        }



        /**
         * Metodo que incia el proceso de negacion de la solicitud.
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function negarDetalleSolicitud()
        {
            $result = false;
            $modelInscripcion = self::findCertificadoCatastralUrbano();
            if ( $modelInscripcion !== null ) {
                if ( $modelInscripcion['id_contribuyente'] == $this->_model->id_contribuyente ) {
                    $result = self::updateSolicitudInscripcion($modelInscripcion);
                } else {
                    self::setErrors(Yii::t('backend', 'Error in the ID of taxpayer'));
                }
            }

            return $result;
        }



        /**
         * Metodo que realiza la actualizacin de los atributos segun el evento a ejecutar
         * sobre la solicitud.
         * @param  Active Record $modelInscripcion modelo de la entidad "sl-inscripciones-act-econ".
         * Este modelo contiene los datos-detalles, referida a los datos cargados al momento de elaborar
         * la solicitud.
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudInscripcion($modelInscripcion)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New SlCertificadoCatastralSearch($modelInscripcion->id_contribuyente);
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloDatos = self::atributosUpDateProcesarSolicitud($this->_evento);

            $camposModel = $modelInscripcion->toArray();

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($camposModel['nro_solicitud']) ? $camposModel['nro_solicitud'] : null;

            if ( count($arregloDatos) == 0 || count($arregloCondicion) == 0 ) { $cancel = true; }

            // Si no existe en la solicitud un campo que viene del modelo que deba ser
            // actualizado, entonces el proceso debe ser cancelado.
            if ( !$cancel ) {
                if($arregloDatos['estatus'] == 9)
                {
                    $result = $this->_conexion->modificarRegistro($this->_conn, $tableName,
                                                              $arregloDatos, $arregloCondicion);
                } elseif($arregloDatos['estatus'] == 1) {

                    $tableNameMaster = 'historico_certificados_catastrales';

                    $jsonInmueble = new JsonCertificado(); 
                    $json = $jsonInmueble->DatosJson($modelInscripcion['id_impuesto']);

                    $arregloDatosMaster = [ 
                                           
                                            'id_impuesto' => $modelInscripcion['id_impuesto'],
                                            'nro_solicitud' => $modelInscripcion['nro_solicitud'],
                                            'fecha_hora' => $modelInscripcion['fecha_hora'],
                                            'ano_impositivo' => $modelInscripcion['ano_impositivo'],
                                            'id_contribuyente' => $modelInscripcion['id_contribuyente'],
                                            'tipo' => $modelInscripcion['tipo'],
                                            'certificado_catastral' => 'CC-'.$modelInscripcion['id_impuesto'].'-'.$modelInscripcion['id_contribuyente'] ,
                                            'nro_control' => 0,
                                            'serial_control' => 0,
                                            'inmueble_json' => $json['inmuebleJson'],
                                            'avaluo_json' => $json['avaluoJson'],
                                            'registro_json' => $json['registroJson'],
                                            'usuario' => $modelInscripcion['usuario'],
                                            'inactivo' => 0,
                                            'observacion' => 'creada',
                                            'firma_control' => $json['firmaControl'],
                                         ];

                    $resultInsert = $this->_conexion->guardarRegistro($this->_conn, $tableNameMaster, $arregloDatosMaster);
                    $resultId = $this->_conn->getLastInsertID();
                    $_SESSION['idObjeto']=$resultId;

                    $result = $this->_conexion->modificarRegistro($this->_conn, $tableName,
                                                              $arregloDatos, $arregloCondicion);

                } else {
                    if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update request')); }
                    return $result;
                }

            }

            if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update request')); }
            return $result;
        }


    }

 ?>