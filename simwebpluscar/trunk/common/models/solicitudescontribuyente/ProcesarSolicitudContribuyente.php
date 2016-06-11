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



    /**
     * Clase que procesa la solicitud, es decir, actualiza los valores de la solicitud
     * creada una vez que la misma es procesada. El procesamiento de la solicitud
     * consiste en una aprobacion o negacion de la misma.
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
         * @param [type] $connLocal     [description]
         * @param [type] $conexionLocal [description]
         */
        public function __construct($nroSolicitud, $accionLocal, $connLocal, $conexionLocal)
        {
            $this->_nro_solicitud = $nroSolicitud;
            $this->_accion = $accionLocal;
            $this->_conn = $connLocal;
            $this->_conexion = $conexionLocal;
        }



        /***/
        private function getDatosSolicitudCreada()
        {
            // find sobre SoliicitudesContribuyente.
            // Metodo clase padre.
            $datos = $this->findSolicitudContribuyente($this->_nro_solicitud);
            return isset($datos) ? $datos : null;
        }




        /***/
        public function aprobarSolicitud()
        {
            $result = false;
            if ( $this->_accion == Yii::$app->solicitud->aprobar() ) {
                // Model de la entidad SolicitudesContribuyente()
                $model = self::getDatosSolicitudCreada();
                if ( $model !== null && isset($model) ) {
                    // Se asegura que la solicitud este pendiente por aprobar.
                    if ( $model['estatus'] == 0 ) {
                        $result = self::beginSave($model);
                    }
                }
            }
            return $result;
        }



        /***/
        private function beginSave($model)
        {
            $result = false;
            if ( $model['nro_solicitud'] == $this->_nro_solicitud ) {
                $arregloCondicion = null;

                $modelForm = New SolicitudesContribuyenteForm();

                $tableName = $model->tableName();
                $usuario = isset(Yii::$app->user->identity->email) ? Yii::$app->user->identity->email : Yii::$app->user->identity->login;

                $arregloUpdate = $modelForm->atributosUpdateAprobacion();
                $arregloDatos = null;

                $model->fecha_hora_proceso = date('Y-m-d H:i:s');
                $model->user_funcionario = $usuario;
                $model->estatus = 1;

                // Se pasan los valores que se actualizaran del modelo al arreglo de datos.
                foreach ( $arregloUpdate as $arreglo ) {
                    if ( isset($model[$arreglo]) ) {
                        $arregloDatos[$arreglo] = $model[$arreglo];
                    }
                }

                $result = $this->_conexion->modificarRegistro($this->_conn, $tableName, $arregloDatos, $arregloCondicion);
            }

        }


    }

 ?>