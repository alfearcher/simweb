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
 *  @file ProcesarSolicitudContribuyente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07/06/2016
 *
 *  @class ProcesarSolicitudContribuyente
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
    //use yii\base\Model;
    //use yii\db\ActiveRecord;
    use yii\helpers\Url;
    use common\models\solicitudescontribuyente\SolicitudesContribuyente;
    use common\models\solicitudescontribuyente\SolicitudesContribuyenteForm;
    use common\models\solicitudescontribuyente\aaee\ProcesarSolicitudDetalleActividadEconomica;
    use common\models\solicitudescontribuyente\inmueble\ProcesarSolicitudDetalleInmuebleUrbano;
    use common\models\solicitudescontribuyente\vehiculo\ProcesarSolicitudDetalleVehiculo;
    use common\models\solicitudescontribuyente\propaganda\ProcesarSolicitudDetallePropaganda;
    use common\models\configuracion\solicitudplanilla\SolicitudPlanillaSearch;
    use common\models\planilla\PlanillaSearch;




    /**
     * Clase que procesa la solicitud, es decir, actualiza los valores de la solicitud
     * creada una vez que la misma es procesada. El procesamiento de la solicitud
     * consiste en una aprobacion o negacion de la misma. Se afectan las entidad principal
     * de la solicitud y las entidades donde se guardan los detalles de la misma.
     */
    class ProcesarSolicitudContribuyente extends SolicitudesContribuyenteForm
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
         * @param connection $connLocal instancia de connection.
         * @param ConexionController $conexionLocal instancia de la clase ConexionController.
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
            // find sobre SolicitudesContribuyente.
            // Metodo clase padre.
            $datos = $this->findSolicitudContribuyente($this->_nro_solicitud);
            return isset($datos) ? $datos : null;
        }





        /**
         * Metodo donde se busca la solicitud padre y se asegura que la misma este en un
         * estatus de pendiente para su procesamiento. Se llama al metodo que realiza la
         * actualizacion de los atributos respectivos y se recibe una respuesta de dicha
         * operacion. Luego se redirecciona para negar el detalle de la solicitud.
         * @param  integer $causa identificador del combo-lista que se selecciono al momento
         * de inidcar la causa de l negacion de la solicitud.
         * @param  string  $observacion nota colocada por el funcionario para complementar la
         * negacion o causa de la solicitud.
         * @return boolean retorna true si la actualizacion se realiza satisfactoriamente, sino
         * retorna false.
         */
        public function negarSolicitud($causa = 0, $observacion = '')
        {
            $result = false;
            if ( $this->_accion == Yii::$app->solicitud->negar() ) {
                // Model de la entidad SolicitudesContribuyente()
                $model = self::getDatosSolicitudCreada();
                if ( $model !== null && isset($model) ) {
                    // Se asegura que la solicitud este pendiente por aprobar.
                    if ( $model['estatus'] == 0 && $model['inactivo'] == 0 ) {
                        $result = self::negar($model, $causa, $observacion);
                        if ( $result ) {
                            // Lo siguiente anula las planillas asociadas a la solicitud, pero
                            // solo aquellas que esten pendiente.
                            $result = self::anularPlanillaSolicitud($model, $observacion);
                            if ( $result ) {
                                 // Lo siguiente inicia las acciones para procesar el detalle
                                // de la solicitud.
                                $result = self::procesarSolicitudPorImpuesto($model, Yii::$app->solicitud->negar());
                            }
                        }
                    }
                }
            }
            return $result;
        }



        /**
         * Metodo que ejecuta la negacion de la solicitud sobre la entidad principal
         * "solicitudes-contribuyente". Luego se realizara la negacion sobre las entidades
         * que representan el detalle de la solicitud.
         * @param  model $model modelo de SolicitudesContyribuyente.
         * @param  integer $causa identificador del combo-lista que se selecciono al momento
         * de inidcar la causa de l negacion de la solicitud.
         * @param  string $observacion nota colocada por el funcionario para complementar la
         * negacion o causa de la solicitud.
         * @return boolean retorna true si la actualizacion se realiza satisfactoriamente, sino
         * retorna false.
         */
        private function negar($model, $causa, $observacion)
        {
            $result = false;
            if ( $model['nro_solicitud'] == $this->_nro_solicitud ) {
                $arregloCondicion = ['nro_solicitud' => $this->_nro_solicitud];
                $tableName = $model->tableName();

                $arregloDatos = null;
                // Se obtienen los campos que se actualizaran.
                $arregloDatos = $this->atributosUpdateNegacion($causa, $observacion);

                $result = $this->_conexion->modificarRegistro($this->_conn, $tableName, $arregloDatos, $arregloCondicion);
            }
            return $result;

        }





        /**
         * Metodo que permite verificar la operacion de aprobacion de la solicitud y de verificar
         * que la solicitud enviada para su aprobacion se encuentre en un estatus de pendiente.
         * Se realiza una busqueda de la solicitud para su comprobacion.
         * @return Boolean] Retorna un true si la solicitud se aprobo de manera satisfactoria, o fals
         * sino se ejecuto la aprobacion de manera correcta.
         */
        public function aprobarSolicitud()
        {
            $result = false;
            if ( $this->_accion == Yii::$app->solicitud->aprobar() ) {
                // Model de la entidad SolicitudesContribuyente()
                $model = self::getDatosSolicitudCreada();
                if ( $model !== null && isset($model) ) {
                    // Se asegura que la solicitud este pendiente por aprobar.
                    if ( $model['estatus'] == 0 && $model['inactivo'] == 0 ) {
                        $result = self::aprobar($model);
                        if ( $result ) {
                            // Lo siguiente inicia las acciones para procesar el detalle
                            // de la solicitud.
                            $result = self::procesarSolicitudPorImpuesto($model, Yii::$app->solicitud->aprobar());
                        }
                    }
                }
            }
            return $result;
        }



        /**
         * Metodo que aprueba la solicitud, realizando los update respectivos sobre la entidad
         * maestra de las solicitudes (SolicitudesContribuyente).
         * @param  Active Record $model modelo de la entidad "solicitudes-contribuyente", este modelo
         * contiene la informacion de la solicitud que se busco con anterioridad.
         * @return Bollean Retorna un true si la actualizacion se realiza satisfactoriamente o false
         * en caso contrario.
         */
        private function aprobar($model)
        {
            $result = false;
            if ( $model['nro_solicitud'] == $this->_nro_solicitud ) {
                $arregloCondicion = ['nro_solicitud' => $this->_nro_solicitud];
                $tableName = $model->tableName();

                $arregloDatos = null;
                $arregloDatos = $this->atributosUpdateAprobacion();

                $result = $this->_conexion->modificarRegistro($this->_conn, $tableName, $arregloDatos, $arregloCondicion);
            }
            return $result;

        }




        /**
         * Metodo que inicia la anulacion de las planillas asociadas a la solicitud.
         * Las planillas deben de estar en estatus pendiente (0), para poderser anuladas,
         * se ejecutan una seriede validaciones para determinar si cualquiera de las planillas
         * no esta re;acionada a ningun procesos.
         * @param   model $model modelo de SolicitudesContyribuyente.
         * @param  string $observacion observacion colocada por el funcionario, la
         * misma es solicitada alfuncionario en el formulario de anulacion de solicitud.
         * @return boolean retorna true si se anulan todoas las planillas, false en caso
         * contrario.
         */
        private function anularPlanillaSolicitud($model, $observacion = '')
        {
            $result = false;
            // Se buscan las planillas asociadas a la solicitud.
            $searchSolicitudPlanilla = New SolicitudPlanillaSearch($model->nro_solicitud);

            // Se obtiene el modelo sin datos.
            $findModel = $searchSolicitudPlanilla->findSolicitudPlanilla();

            // Se ejecuta el find all, para obtener las planillas asociadas a la solicitud.
            $listaPlanillas = $findModel->asArray()->all();

            if ( count($listaPlanillas) > 0 ) {
                foreach ( $listaPlanillas as $planillas ) {
                    if ( isset($planillas['planilla']) ) {
                         $searchPlanilla = New PlanillaSearch($planillas['planilla']);
                         $result = $searchPlanilla->anularMiPlanilla($this->_conexion,
                                                                     $this->_conn,
                                                                     $observacion);
                         if ( !$result ) { break; }
                    }
                }
            } else {
                $result = true;
            }
            return $result;
        }




        /**
         * Metodo que rutea el procesamiento de la solicitud por impuesto de la misma.
         * Con la intencion de procesar el detalle correspondiente de la solicitud.
         * @param  model $model modelo de SolicitudesContyribuyente
         * @param  string $evento evento que se realiza sobre la solicitud:
         * - Aprobar
         * - Negar.
         * @return boolean retorna true si el procesamiento del detalle de la solicitud es
         * satisfactorio, false en caso contrario.
         */
        private function procesarSolicitudPorImpuesto($model, $evento)
        {
        //die('llegue a procesar');
            $result = false;
            if ( $model !== null ) {
                if ( $model['impuesto'] == 1 ) {
                    // Actividades Economicas
                    $procesarDetalle = New ProcesarSolicitudDetalleActividadEconomica($model, $evento, $this->_conn, $this->_conexion);
                    $result = $procesarDetalle->procesarSolicitudPorTipo();

                } elseif ( $model['impuesto'] == 2 ) {
                    // Inmuebles Urbanos.
                    $procesarDetalle = New ProcesarSolicitudDetalleInmuebleUrbano($model, $evento, $this->_conn, $this->_conexion);
                    $result = $procesarDetalle->procesarSolicitudPorTipo();

                } elseif ( $model['impuesto'] == 3 ) {

                    $procesarDetalle = New ProcesarSolicitudDetalleVehiculo($model, $evento, $this->_conn, $this->_conexion);
                    $result = $procesarDetalle->procesarSolicitudPorTipo();

                } elseif ( $model['impuesto'] == 4 ) {
                    //die('es 4');
                    $procesarDetalle = New ProcesarSolicitudDetallePropaganda($model, $evento, $this->_conn, $this->_conexion);
                    $result = $procesarDetalle->procesarSolicitudPorTipo();

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