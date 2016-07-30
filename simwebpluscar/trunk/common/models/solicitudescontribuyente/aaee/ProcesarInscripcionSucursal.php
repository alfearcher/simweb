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
 *  @file ProcesarInscripcionSucursal.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28/07/2016
 *
 *  @class ProcesarInscripcionSucursal
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
    use backend\models\aaee\inscripcionsucursal\InscripcionSucursalSearch;
    use backend\models\aaee\inscripcionsucursal\InscripcionSucursalForm;
    use common\models\contribuyente\ContribuyenteBase;



    /**
     * Clase que se encarga de realizar los respectivos inserto update sobre las entidades
     * que esten relacionada con la aprobacion o negacion de la solicitud. la clase debe
     * entregar como respuesta un true o false.
     */
    class ProcesarInscripcionSucursal extends InscripcionSucursalSearch
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
         * InscripcioonSucursal si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        public function findInscripcionSucursal()
        {
            // Este find retorna el modelo de la entidad "sl-inscripciones-act-econ"
            // con datos, ya que en el metodo padre se ejecuta el ->one() que realiza
            // la consulta.
            $modelFind = $this->findInscripcion($this->_model->nro_solicitud);
            return isset($modelFind) ? $modelFind : null;
        }



        /**
         * Metodo que inicia la aprobacion del detalle de la solicitud.
         * @return Boolean Retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function aprobarDetalleSolicitud()
        {
            $result = false;
            $idGenerado = 0;
            // modelo de InscripcionSucursal.
            $modelInscripcion = self::findInscripcionSucursal();
            if ( $modelInscripcion !== null ) {
                if ( $modelInscripcion['id_contribuyente'] == $this->_model->id_contribuyente ) {
                    $result = self::updateSolicitudInscripcion($modelInscripcion);
                    if ( $result ) {
                        $idGenerado = self::crearContribuyente($modelInscripcion);
                        if ( $idGenerado > 0 ) {
                            $result = true;
                        } else {
                            $result = false;
                        }
                    }
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
            $modelInscripcion = self::findInscripcionSucursal();
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
         * @param  Active Record $modelInscripcion modelo de la entidad "sl-inscripciones-sucursales"
         * (InscripcionSucursal). Este modelo contiene los datos-detalles, referida a los datos cargados
         * al momento de elaborar la solicitud.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function updateSolicitudInscripcion($modelInscripcion)
        {
            $result = false;
            $cancel = false;            // Controla si el proceso se debe cancelar.

            // Se crea la instancia del modelo que contiene los campos que seran actualizados.
            $model = New InscripcionSucursalForm();
            $tableName = $model->tableName();

            // Se obtienen los campos que seran actualizados en la entidad "sl-".
            // Estos atributos ya vienen con sus datos cargados.
            $arregloCampos = $model->atributosUpDateProcesarSolicitud($this->_evento);

            $camposModel = $modelInscripcion->toArray();

            // Se define el arreglo para el where conditon del update.
            $arregloCondicion['nro_solicitud'] = isset($camposModel['nro_solicitud']) ? $camposModel['nro_solicitud'] : null;

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
         * Metodo que realiza la insercion del registro sobre la entidad "contribuyentes".
         * La creacion se hara con los datos de la sede principal de la sucursal execto
         * aquellos atributos que fueron cargados en la solicitud de inscripcion de sucursal
         * y que son propios de cada sucursal.
         * @param  Active Record $modelInscripcion modelo de la entidad "sl-inscripciones-sucursales",
         * con los datos cargados.
         * @return boolean retorna un true si todo se ejecuto satisfactoriamente, false
         * en caso contrario.
         */
        private function crearContribuyente($modelInscripcion)
        {
            $result = false;
            $cancel = false;
            $idGenerado = 0;
            $idRif = 0;
            $tabla = ContribuyenteBase::tableName();

            // Se determina si el solicitante es la sede principal de la sucursal.
            if ( $this->getSedePrincipal() ) {
                $modelContribuyente = self::findDatosSedePrincipal($this->_model['id_contribuyente']);
                $findArregloContribuyente = $modelContribuyente->asArray()->one();

                if ( count($findArregloContribuyente) > 0 ) {
                    // Verificar que el RIF o DNI de la sede principal coincidan con el de
                    // la sucursal creada en la solicitud.
                    if ( $modelInscripcion['naturaleza'] == $findArregloContribuyente['naturaleza'] &&
                         $modelInscripcion['cedula'] == $findArregloContribuyente['cedula'] &&
                         $modelInscripcion['tipo'] == $findArregloContribuyente['tipo'] ) {

                        // Retorna atributos de la entidad "contribuyentes".
                        $camposContribuyente = $findArregloContribuyente;

                        $modelSucursal = New InscripcionSucursalForm();
                        // Se obtienen los atributos particulares de las sucursales, que seran guardadas
                        // al momento de crear el registro.
                        $camposSucursal = $modelSucursal->getAtributoSucursal();

                        foreach ( $camposSucursal as $campo ) {
                            if ( array_key_exists($campo, $modelInscripcion->toArray()) ) {
                                $camposContribuyente[$campo] = $modelInscripcion[$campo];
                            } else {
                                $cancel = true;
                                self::setErrors(Yii::t('backend', 'Failed fields not match'));
                                break;
                            }
                        }

                        if ( !$cancel ) {
                            // Se actualiza la fecha de inclusion de la suucrsal, se sustituye la colocada
                            // de la sede principal por la actual.
                            $camposContribuyente['fecha_inclusion'] = date('Y-m-d');

                            // Se coloca el valor del identificador de la entidad en null, ya que este identificador
                            // no es de este registro, sino de la sede principal.
                            $camposContribuyente['id_contribuyente'] = null;

                            // Se pasa a obtener el identificador de la sucursal.
                            $idRif = $this->getIdentificadorSucursalNuevo($modelInscripcion['naturaleza'],
                                                                          $modelInscripcion['cedula'],
                                                                          $modelInscripcion['tipo']
                                                                        );

                            if ( $idRif > 0 ) {
                                $camposContribuyente['id_rif'] = $idRif;
                                $result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $camposContribuyente);
                                if ( $result ) {
                                    $idGenerado = $this->_conn->getLastInsertID();
                                }
                            }
                        }
                    } else {
                        // El RIF de la sede principal no coinciden con el de la solicitud.
                        self::setErrors(Yii::t('backend', 'DNI do not match'));
                    }
                }
            }
            return $idGenerado;

        }



        /**
         * Metodo que realiza una busqueda de los datos de la sede principal, la sede
         * principal estara definida por su identificador de registro (idContribuyente)
         * @param  long $idContribuyente identificador del contribuyente en la entidad.
         * @return active record retorna un modelo de ContribuyenteBase, sino encuentra el
         * registro devolvera un null.
         */
        public function findDatosSedePrincipal($idContribuyente)
        {
            $findModel = ContribuyenteBase::find()->where('id_contribuyente =:id_contribuyente', [':id_contribuyente' => $idContribuyente]);
            return isset($findModel) ? $findModel : null;
        }






    }

 ?>