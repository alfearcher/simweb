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
 *  @file ProcesarSolicitudLicencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22/11/2016
 *
 *  @class ProcesarSolicitudLicencia
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
    use backend\models\aaee\licencia\LicenciaSolicitudSearch;
    use backend\models\aaee\licencia\LicenciaSolicitudForm;
    use common\models\contribuyente\ContribuyenteBase;
    use backend\models\aaee\rubro\Rubro;
    use backend\models\aaee\historico\licencia\HistoricoLicenciaSearch;
    use yii\base\ErrorException;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarSolicitudLicencia extends LicenciaSolicitudSearch
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
         * LicenciaSolicitud si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findLicenciaSolicitud()
        {
            // Este find retorna el modelo de la entidad "sl-licencias".
            $findModel = $this->findSolicitudLicencia($this->_model->nro_solicitud);

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
            // modelo de LicenciaSolicitud. Uno o varios registros.
            $modelLicencia = self::findLicenciaSolicitud();
            if ( $modelLicencia !== null ) {
                // Entidad "sl-".
                $result = self::updateSolicitudLicencia($modelLicencia);
                if ( $result ) {
                    $result = self::createHistoricoLicencia($modelLicencia);

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
            $modelLicencia = self::findLicenciaSolicitud();
            if ( $modelLicencia !== null ) {
                $result = self::updateSolicitudLicencia($modelLicencia);
            }

            return $result;
        }



        /**
         * Metodo que realiza la actualizacin de los atributos segun el evento a ejecutar
         * sobre la solicitud.
         * @param  Active Record $modelLicencia modelo de la entidad "sl-licencias"
         * (LicenciaSolicitud). Este modelo contiene los datos-detalles, referida a los
         * datos cargados al momento de elaborar la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudLicencia($modelLicencia)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New LicenciaSolicitudForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($modelLicencia[0]->nro_solicitud) ? $modelLicencia[0]->nro_solicitud : null;

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
         * Metodo que crea el historico de licencia,
         * @param  model $modelLicencia modelo del tipo de clase LicenciaSolicitud.
         * @return boolean retorna true si guarda satisfactoriamente.
         */
        private function createHistoricoLicencia($modelLicencia)
        {
            $result = false;

            if ( isset($_SESSION['idContribuyente']) ) {
                $idContribuyente = $_SESSION['idContribuyente'];
                $search = New HistoricoLicenciaSearch($idContribuyente);

                // Se arma la informacion del contribuyente para la licencia.
                $contribuyente = ContribuyenteBase::findOne($idContribuyente);

                $fechaVcto = date('Y') . '-12-31';
                $arregloContribuyente = [
                        'id_contribuyente' => $idContribuyente,
                        'nro_solicitud' => $modelLicencia[0]['nro_solicitud'],
                        'rif' => $contribuyente->naturaleza . '-' . $contribuyente->cedula . '-' . $contribuyente->tipo,
                        'descripcion' => $contribuyente->razon_social,
                        'domicilio' => $contribuyente->domicilio_fiscal,
                        'capital' => $contribuyente->capital,
                        'representante' => $contribuyente->representante,
                        'cedulaRep' => $contribuyente->naturaleza_rep . '-' . $contribuyente->cedula_rep,
                        'catastro' => 0,
                        'fechaEmision' => date('Y-m-d'),
                        'fechaVcto' => $fechaVcto,
                ];

                $fuente_json = json_encode($arregloContribuyente);

                // Se arma la informacion de los rubros.
                foreach ( $modelLicencia as $model ) {
                    $infoRubro = Rubro::findOne($model['id_rubro']);

                    $rjson[] = [
                            'nro_solicitud' => $model['nro_solicitud'],
                            'id_contribuyente' => $model['id_contribuyente'],
                            'ano_impositivo' => $model['ano_impositivo'],
                            'id_rubro' => $infoRubro->id_rubro,
                            'rubro' => $infoRubro->rubro,
                            'descripcion' => $infoRubro->descripcion,
                            'alicuota' => $infoRubro->alicuota,
                            'minimo' => $infoRubro->minimo_ut,
                        ];
                }

                $arregloDatos = $search->attributes;

                $arregloDatos['id_contribuyente'] = $modelLicencia[0]->id_contribuyente;
                $arregloDatos['ano_impositivo'] = $modelLicencia[0]->ano_impositivo;
                $arregloDatos['nro_solicitud'] = $modelLicencia[0]->nro_solicitud;
                $arregloDatos['tipo'] = $modelLicencia[0]->tipo;
                $arregloDatos['licencia'] = $modelLicencia[0]->licencia;
                $arregloDatos['nro_control'] = '';
                $arregloDatos['serial_control'] = '';
                $arregloDatos['fuente_json'] = $fuente_json;
                $arregloDatos['rubro_json'] = json_encode($rjson);
                $arregloDatos['observacion'] = 'SOLICITUD LICENCIA NUEVA APROBADA POR FUNCIONARIO';
                $arregloDatos['inactivo'] = 0;

                $arregloDatos['usuario'] = Yii::$app->identidad->getUsuario();
                $arregloDatos['fecha_hora'] = date('Y-m-d H:i:s');

                $firmaControl = json_encode($arregloContribuyente) . json_encode($rjson);

                $arregloDatos['firma_control'] = md5($firmaControl);

                $result = $search->guardar($arregloDatos, $this->_conexion, $this->_conn);
                if ( $result['id'] > 0 ) {
                    return true;
                } else {
                    return false;
                }
            }

            return false;
        }








    }

 ?>