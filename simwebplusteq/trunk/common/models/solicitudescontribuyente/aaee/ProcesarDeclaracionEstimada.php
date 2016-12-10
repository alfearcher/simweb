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
 *  @file ProcesarDeclaracionEstimada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01/10/2016
 *
 *  @class ProcesarDeclaracionEstimada
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
    use backend\models\aaee\declaracion\DeclaracionBaseSearch;
    use backend\models\aaee\declaracion\DeclaracionBaseForm;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\aaee\acteconingreso\ActEconIngresoForm;
    use backend\models\aaee\rubro\Rubro;
    use backend\models\aaee\historico\declaracion\HistoricoDeclaracionSearch;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarDeclaracionEstimada extends DeclaracionBaseSearch
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
         * @param connection $conn instancia de connection.
         * @param ConexionController $conexion instancia de la clase ConexionController.
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
         * @return boolean retorna una instancia modelo active record de
         * DeclaracionBase si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findDeclaracionEstimada()
        {
            // Este find retorna el modelo de la entidad "sl-declaraciones".
            $findModel = $this->findSolicitudDeclaracion($this->_model->nro_solicitud);

            // Lo siguiente puede generar uno o varios registros.
            $model = $findModel->all();
            return isset($model) ? $model : null;
        }



        /**
         * Metodo que inicia la aprobacion del detalle de la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function aprobarDetalleSolicitud()
        {
            $result = false;
            $idGenerado = 0;
            // modelo de DeclaracionBase. Uno o varios registros.
            $modelDeclaracion = self::findDeclaracionEstimada();
            if ( $modelDeclaracion !== null ) {
                // Entidad "sl-".
                $result = self::updateSolicitudDeclaracionEstimada($modelDeclaracion);
                if ( $result ) {
                    $result = self::updateActEconIngresos($modelDeclaracion);
                    if ( $result ) {
                        $result = self::createHistoricoDeclaracion($modelDeclaracion);
                    }
                }
            } else {
                self::setErrors(Yii::t('backend', 'Request not find'));
            }

            return $result;
        }



        /**
         * Metodo que incia el proceso de negacion de la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function negarDetalleSolicitud()
        {
            $result = false;
            $modelDeclaracion = self::findDeclaracionEstimada();
            if ( $modelDeclaracion !== null ) {
                $result = self::updateSolicitudDeclaracionEstimada($modelDeclaracion);
            }

            return $result;
        }



        /**
         * Metodo que realiza la actualizacin de los atributos segun el evento a ejecutar
         * sobre la solicitud.
         * @param  Active Record $modelDeclaracion modelo de la entidad "sl-declaraciones"
         * (DeclaracionBase). Este modelo contiene los datos-detalles, referida a los
         * datos cargados al momento de elaborar la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudDeclaracionEstimada($modelDeclaracion)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New DeclaracionBaseForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($modelDeclaracion[0]->nro_solicitud) ? $modelDeclaracion[0]->nro_solicitud : null;

            if ( count($arregloCampos) == 0 || count($arregloCondicion) == 0 ) { $cancel = true; }

            // Si no existe en la solicitud un campo que viene del modelo que deba ser
            // actualizado, entonces el proceso debe ser cancelado.
            if ( !$cancel ) {
                $result = $this->_conexion->modificarRegistro($this->_conn,
                                                              $tableName,
                                                              $arregloCampos,
                                                              $arregloCondicion);
            }

            if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update request')); }
            return $result;
        }




        /**
         * Metodo que actualiza el atributo "estimado", de la entidad "act-econ-ingresos"
         * Toma los montos del atributo monto_new, para actualizar "estimado".
         * @param active record $modelDeclaracion modelo de DeclaracionBase,
         * entidad "sl-declaraciones".
         * @return boolean retorna true si guarda, false en caso contrario.
         */
        private function updateActEconIngresos($modelDeclaracion)
        {
            $result = false;
            if ( $modelDeclaracion !== null ) {
                $ingresoModel = New ActEconIngresoForm();
                $tabla = $ingresoModel->tableName();

                foreach ( $modelDeclaracion as $model ) {
                    // Condiciones para modificar el registro.
                    $arregloCondicion['id_impuesto'] = $model->id_impuesto;
                    $arregloCondicion['id_rubro'] = $model->id_rubro;
                    $arregloCondicion['exigibilidad_periodo'] = $model->exigibilidad_periodo;
                    $arregloCondicion['bloqueado'] = 0;
                    $arregloCondicion['inactivo'] = 0;

                    // Atributo a modificar.
                    $arregloDatos['estimado'] = $model->monto_new;

                    $result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);
                    if ( !$result ) { break; }
                }
            }
            return $result;
        }




        /***/
        private function createHistoricoDeclaracion($modelDeclaracion)
        {
            $result = [];
            if ( $modelDeclaracion !== null ) {
                $historico = New HistoricoDeclaracionSearch($this->_model['id_contribuyente']);

                foreach ( $modelDeclaracion as $model ) {
                    $findModel = self::findRubro($model['id_rubro']);
                    $rjson[] = [
                            'nro_solicitud' => $model['nro_solicitud'],
                            'id_contribuyente' => $model['id_contribuyente'],
                            'id_impuesto' => $model['id_impuesto'],
                            'ano_impositivo' => $model['ano_impositivo'],
                            'exigibilidad_periodo' => $model['exigibilidad_periodo'],
                            'id_rubro' => $model['id_rubro'],
                            'rubro' => $findModel->rubro,
                            'descripcion' => $findModel->descripcion,
                            'tipo_declaracion' => $model['tipo_declaracion'],
                            'estimado_v' => $model['monto_v'],
                            'estimado' => $model['monto_new'],
                        ];
                }

                $arregloDatos = $historico->attributes;
                foreach ( $historico->attributes as $key => $value ) {

                    if ( isset($modelDeclaracion[0]->$key) ) {
                        $arregloDatos[$key] = $modelDeclaracion[0]->$key;
                    }

                }

                $arregloDatos['periodo'] = $modelDeclaracion[0]->exigibilidad_periodo;
                $arregloDatos['json_rubro'] = json_encode($rjson);
                $arregloDatos['observacion'] = 'APROBADA POR FUNCIONARIO, SOLICITUD DECLARACION ESTIMADA';
                $arregloDatos['por_sustitutiva'] = 0;

                $result = $historico->guardar($arregloDatos, $this->_conexion, $this->_conn);
                if ( $result['id'] > 0 ) {
                    return true;
                }

            return false;

            }
        }



        /***/
        private function findRubro($idRubro)
        {
            $findModel = Rubro::findOne($idRubro);
            return $findModel;
        }


    }

 ?>