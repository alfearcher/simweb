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
 *  @file ProcesarSolicitudSolvenciaVehiculo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22/11/2016
 *
 *  @class ProcesarSolicitudSolvenciaVehiculo
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


    namespace common\models\solicitudescontribuyente\vehiculo;

    use Yii;
    use backend\models\vehiculo\solvencia\SolvenciaVehiculoSearch;
    use backend\models\vehiculo\solvencia\SolvenciaVehiculoForm;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\aaee\historico\solvencia\HistoricoSolvenciaSearch;
    use backend\models\solvencia\SolvenciaSearch;
    use backend\models\solvencia\SolvenciaForm;
    use yii\base\ErrorException;
    use common\models\configuracion\solicitudplanilla\SolicitudPlanillaSearch;
    use backend\models\impuesto\Impuesto;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarSolicitudSolvenciaVehiculo extends SolvenciaVehiculoSearch
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
         * SolvenciaActividadEconomica si todo se ejecuto satisfactoriamente,
         * false en caso contrario.
         */
        public function findSolicitudSolvenciaVehiculo()
        {
            // Este find retorna el modelo de la entidad "sl-solvencias".
            $findModel = $this->findSolicitudSolvencia($this->_model->nro_solicitud);

            // Lo siguiente puede generar uno o varios registros.
            $model = $findModel->all();
            return isset($model) ? $model : null;
        }



        /**
         * Metodo que inicia la aprobacion del detalle de la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente,
         * false en caso contrario.
         */
        private function aprobarDetalleSolicitud()
        {
            $result = false;
            // modelo de SolvenciaVehiculo. Uno o varios registros.
            $modelSolvencia = self::findSolicitudSolvenciaVehiculo();
            if ( $modelSolvencia !== null ) {
                // Entidad "sl-".
                $result = self::updateSolicitudSolvencia($modelSolvencia);
                if ( $result ) {
                    $result = self::createHistoricoSolvencia($modelSolvencia);
                }

                if ( $result ) {
                    $result = self::createSolvencia($modelSolvencia);
                }

            } else {
                self::setErrors(Yii::t('backend', 'Request not find'));
            }

            return $result;
        }



        /**
         * Metodo que incia el proceso de negacion de la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente,
         * false en caso contrario.
         */
        private function negarDetalleSolicitud()
        {
            $result = false;
            $modelSolvencia = self::findSolicitudSolvenciaVehiculo();

            if ( $modelSolvencia !== null ) {
                $result = self::updateSolicitudSolvencia($modelSolvencia);
            }

            return $result;
        }



        /**
         * Metodo que realiza la actualizacin de los atributos segun el evento a ejecutar
         * sobre la solicitud.
         * @param  Active Record $modelSolvencia modelo de la entidad "sl-solvencias"
         * (SolvenciaVehiculo). Este modelo contiene los datos-detalles,
         * referida a los datos cargados al momento de elaborar la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudSolvencia($modelSolvencia)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New SolvenciaVehiculoForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($modelSolvencia[0]->nro_solicitud) ? $modelSolvencia[0]->nro_solicitud : null;

            if ( count($arregloCampos) == 0 || count($arregloCondicion) == 0 ) { $cancel = true; }

            if ( $arregloCondicion['nro_solicitud'] > 0 ) {
                // Si no existe en la solicitud un campo que viene del modelo que deba ser
                // actualizado, entonces el proceso debe ser cancelado.
                if ( !$cancel ) {
                    $result = $this->_conexion->modificarRegistro($this->_conn,
                                                                  $tableName,
                                                                  $arregloCampos,
                                                                  $arregloCondicion);
                }

                if (!$result ) { self::setErrors(Yii::t('backend', 'Failed update request')); }
            }
            return $result;
        }





        /**
         * Metodo que crea el historico de Solvencia,
         * @param  model $modelSolvencia modelo del tipo de clase
         * SolvenciaVehiculo.
         * @return boolean retorna true si guarda satisfactoriamente.
         */
        private function createHistoricoSolvencia($modelSolvencia)
        {
            $result = false;

            if ( isset($_SESSION['idContribuyente']) ) {
                $idContribuyente = $_SESSION['idContribuyente'];
                $search = New HistoricoSolvenciaSearch($idContribuyente, $modelSolvencia[0]->impuesto);

                $searchSolvencia = New SolvenciaVehiculoSearch($idContribuyente, $modelSolvencia[0]->id_impuesto);

                $fechaVcto = $searchSolvencia->determinarFechaVctoSolvencia();
                if ( $fechaVcto == '' ) {
                    $fechaVcto = '0000-00-00';
                }

                // Se obtiene la placa del vehiculo
                $modelSearch = $searchSolvencia->findSolicitudSolvencia($modelSolvencia[0]->nro_solicitud);
                $modelSearch = $modelSearch->joinWith('vehiculo V1', true, 'INNER JOIN')->one();
                $placa = $modelSearch->vehiculo->placa;


                // Se identifica con una descripcion el tipo de impuesto
                $impuesto = Impuesto::findOne($modelSolvencia[0]->impuesto);
                $tipoImpuesto = $impuesto->descripcion;

                // Se identifica la tasa liquidada por el concepto de solicitud de solvencia
                $searchPlanilla = New SolicitudPlanillaSearch($modelSolvencia[0]->nro_solicitud,
                                                              Yii::$app->solicitud->crear());

                $findModel = $searchPlanilla->findSolicitudPlanilla();
                $planillas = $findModel->one();

                $liquidacion = 0;
                if ( isset($planillas->planilla) ) {
                    $liquidacion = $planillas->planilla;
                }

                // Se arma la informacion del contribuyente para la licencia.
                $contribuyente = ContribuyenteBase::findOne($idContribuyente);
                if ( $contribuyente->tipo_naturaleza == 0 ) {
                    $cedualRif = $contribuyente->naturaleza . '-' . $contribuyente->cedula;
                    $descripcion = $contribuyente->apellidos . ' ' . $contribuyente->nombres;
                } elseif ( $contribuyente->tipo_naturaleza == 1 ) {
                    $cedualRif = $contribuyente->naturaleza . '-' . $contribuyente->cedula . '-' . $contribuyente->tipo;
                    $descripcion = $contribuyente->razon_social;
                }

                $arregloContribuyente = [
                        'id_contribuyente' => $idContribuyente,
                        'nro_solicitud' => $modelSolvencia[0]->nro_solicitud,
                        'rif' => $cedualRif,
                        'descripcion' => $descripcion,
                        'domicilio' => $contribuyente->domicilio_fiscal,
                        'licencia' => $contribuyente->id_sim,
                        'placa' => $placa,
                        'catastro' => 0,
                        'fechaEmision' => date('Y-m-d', strtotime($modelSolvencia[0]->fecha_hora)),
                        'fechaVcto' => $fechaVcto,
                        'liquidacion' => $liquidacion,
                        'tipoImpuesto' => $tipoImpuesto,
                        // 'id_impuesto' => $model->id_impuesto;
                ];

                $fuente_json = json_encode($arregloContribuyente);

                $arregloDatos = $search->attributes;

                $arregloDatos['id_contribuyente'] = $modelSolvencia[0]->id_contribuyente;
                $arregloDatos['ano_impositivo'] = $modelSolvencia[0]->ano_impositivo;
                $arregloDatos['nro_solicitud'] = $modelSolvencia[0]->nro_solicitud;
                $arregloDatos['impuesto'] = $modelSolvencia[0]->impuesto;
                $arregloDatos['fecha_emision'] = date('Y-m-d', strtotime($modelSolvencia[0]->fecha_hora));
                $arregloDatos['fecha_vcto'] = $fechaVcto;
                $arregloDatos['id_impuesto'] = $modelSolvencia[0]->id_impuesto;
                $arregloDatos['nro_control'] = '';
                $arregloDatos['serial_control'] = '';
                $arregloDatos['fuente_json'] = $fuente_json;
                $arregloDatos['observacion'] = 'SOLICITUD SOLVENCIA VEHICULO ' . $modelSolvencia[0]->observacion;
                $arregloDatos['inactivo'] = 0;
                $arregloDatos['usuario'] = $modelSolvencia[0]->usuario;
                $arregloDatos['fecha_hora'] = $modelSolvencia[0]->fecha_hora;

                $result = $search->guardar($arregloDatos, $this->_conexion, $this->_conn);
                if ( $result['id'] > 0 ) {
                    return true;
                } else {
                    return false;
                }
            }

            return false;
        }





        /**
         * Metodo que inserta en la entidad "solvencias"
         * @param  SolvenciaVehiculo $modelSolvencia modelo con la consulta.
         * @return boolean retorna true si guarda satisfactoriamente.
         */
        private function createSolvencia($modelSolvencia)
        {
            $result = false;
            if ( isset($_SESSION['idContribuyente']) ) {
                $idContribuyente = $_SESSION['idContribuyente'];

                $searchSolvenciaActividad = New SolvenciaVehiculoSearch($idContribuyente, $modelSolvencia[0]->id_impuesto);
                $fechaVcto = $searchSolvenciaActividad->determinarFechaVctoSolvencia();
                if ( $fechaVcto == '' ) {
                    $fechaVcto = '0000-00-00';
                }

                $solvencia = New SolvenciaForm();

                $tabla = $solvencia->tableName();
                $arregloDatos = $solvencia->attributes;

                $arregloDatos['id_contribuyente'] = $modelSolvencia[0]->id_contribuyente;
                $arregloDatos['id_impuesto'] = $modelSolvencia[0]->id_impuesto;
                $arregloDatos['ano_impositivo'] = $modelSolvencia[0]->ano_impositivo;
                $arregloDatos['impuesto'] = $modelSolvencia[0]->impuesto;

                $arregloDatos['ente'] = Yii::$app->ente->getEnte();
                $arregloDatos['serial_solvencia'] = '';
                $arregloDatos['fecha_emision'] = date('Y-m-d', strtotime($modelSolvencia[0]->fecha_hora));
                $arregloDatos['fecha_vcto'] = $fechaVcto;
                $arregloDatos['status_solvencias'] = 0;
                $arregloDatos['nro_solvencia'] = 0;
                $arregloDatos['observacion'] = $modelSolvencia[0]->observacion;

                $searchSolvencia = New SolvenciaSearch($idContribuyente);
                $result = $searchSolvencia->guardar($arregloDatos, $this->_conexion, $this->_conn);

            }
            return $result;
        }





    }

 ?>