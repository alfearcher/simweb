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
    use backend\models\inmueble\SlInmueblesUrbanosSearch;
    use common\models\contribuyente\ContribuyenteBase;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarInscripcionInmuebleUrbano extends SlInmueblesUrbanosSearch
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
        public function findInscripcionInmuebleUrbano()
        {
            // Este find retorna el modelo de la entidad "sl-inscripciones-act-econ"
            // con datos, ya que en el metodo padre se ejecuta el ->one() que realiza
            // la consulta.
            $modelFind = $this->findInscripcion($this->_model->nro_solicitud);
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
                                    'estatus_funcionario' => 1,
                                    'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,
                                    'fecha_funcionario' => date('Y-m-d H:i:s')
                ],
                Yii::$app->solicitud->negar() => [
                                    'estatus_funcionario' => 9,
                                    'user_funcionario' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login,
                                    'fecha_funcionario' => date('Y-m-d H:i:s')
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
            $modelInscripcion = self::findInscripcionInmuebleUrbano();
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
            $modelInscripcion = self::findInscripcionInmuebleUrbano();
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
            $model = New SlInmueblesUrbanosSearch($modelInscripcion->id_contribuyente);
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
                if($arregloDatos['estatus_funcionario'] == 9)
                {
                    $result = $this->_conexion->modificarRegistro($this->_conn, $tableName,
                                                              $arregloDatos, $arregloCondicion);
                } elseif($arregloDatos['estatus_funcionario'] == 1) {

                    $tableNameMaster = 'inmuebles';

                    $arregloDatosMaster = [

                                            'id_contribuyente' => $camposModel['id_contribuyente'],
                                            'ano_inicio' => $camposModel['ano_inicio'],
                                            'direccion' => $camposModel['direccion'],
                                            'casa_edf_qta_dom' => $camposModel['casa_edf_qta_dom'],
                                            'piso_nivel_no_dom' => $camposModel['piso_nivel_no_dom'],
                                            'apto_dom' => $camposModel['apto_dom'],
                                            'medidor' => $camposModel['medidor'],
                                            'observacion' => $camposModel['observacion'],
                                            'tipo_ejido' => $camposModel['id_contribuyente'],
                                            'manzana_limite' => $camposModel['manzana_limite'],
                                            
                                            'tlf_hab' => $camposModel['tlf_hab'],
                                       'medidor' => $camposModel['medidor'],
                                       'id_sim' => $camposModel['id_sim'],
                                       'observacion' => $camposModel['observacion'],
                                       'inactivo' => $camposModel['inactivo'],
                                       'catastro' => $camposModel['catastro'],
                                       'id_habitante' => $camposModel['id_habitante'],
                                       'tipo_ejido' => $camposModel['tipo_ejido'],
                                       'propiedad_horizontal' => $camposModel ['propiedad_horizontal'],
                                       //catastro inmueble
                                       'estado_catastro' => $camposModel['estado_catastro'],
                                       'municipio_catastro' => $camposModel['municipio_catastro'],
                                       'parroquia_catastro' => $camposModel['parroquia_catastro'],
                                       'ambito_catastro' => $camposModel['ambito_catastro'],
                                       'sector_catastro' => $camposModel['sector_catastro'],
                                       'manzana_catastro' => $camposModel['manzana_catastro'],
                                       //parcelas 
                                       'parcela_catastro' => $camposModel['parcela_catastro'],
                                       'subparcela_catastro' => $camposModel['subparcela_catastro'],
                                       'nivel_catastro' => $camposModel['nivel_catastro'],
                                       'unidad_catastro' => $camposModel['unidad_catastro'],

                                       'liquidado' => $camposModel['liquidado'], 
                                       'lote_1' => $camposModel['lote_1'],
                                       'lote_2' => $camposModel ['lote_2'],
                                       'lote_3' => $camposModel ['lote_3'], 
                                       'nivel' => $camposModel['nivel'],

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