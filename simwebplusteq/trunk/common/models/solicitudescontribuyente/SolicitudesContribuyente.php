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
 *  @file SolicitudesContribuyenteForm.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 30/03/2016
 *
 *  @class SolicitudesContribuyenteForm
 *  @brief Modelo que instancia la conexion a la base de datos para buscar datos de la tabla solicitudes_contribuyente.
 *
 *
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *  getDb
 *  tableName
 *
 *
 *
 *
 *  @inherits
 *
 */

    namespace common\models\solicitudescontribuyente;

    use Yii;
    use yii\base\Model;
    use common\models\Users;
    use yii\db\ActiveRecord;
    use backend\models\configuracion\tiposolicitud\TipoSolicitud;
    use backend\models\impuesto\Impuesto;
    use backend\models\funcionario\solicitud\FuncionarioSolicitud;
    use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;
    use backend\models\configuracion\nivelaprobacion\NivelAprobacion;
    use backend\models\utilidad\causanegacionsolicitud\CausaNegacionSolicitud;
    use backend\models\solicitud\estatus\EstatusSolicitud;



    class SolicitudesContribuyente extends ActiveRecord
    {

        public static function getDb()
        {
          return Yii::$app->db;
        }


        /**
         *  Metodo que retorna el nombre de la tabla que utiliza el modelo.
         *  @return Nombre de la tabla del modelo.
         */
        public static function tableName()
        {
          return 'solicitudes_contribuyente';
        }



        /**
         * Relacion con la entidad "config-tipos-solicitudes".
         * @return [type] [description]
         */
        public function getTipoSolicitud()
        {
            return $this->hasOne(TipoSolicitud::className(), ['id_tipo_solicitud' => 'tipo_solicitud']);
        }


        /**
         * Relacion con la entidad "impuestos"
         * @return Active Record.
         */
        public function getImpuestos()
        {
            return $this->hasOne(Impuesto::className(), ['impuesto' => 'impuesto']);
        }



        /**
         * Relacion con la entidad "funcionarios-solicitudes"
         * @return Active Record
         */
        public function getFuncionarioSolicitud()
        {
            return $this->hasMany(FuncionarioSolicitud::className(), ['tipo_solicitud' => 'tipo_solicitud']);
        }


        /**
         * Relacion con la entidad "solicitudes-planillas", estas son las planillas
         * que se generan al momento de: crear, aprobar o negar la solicitud.
         * @return Active Record.
         */
        public function getSolicitudPlanilla()
        {
            return $this->hasMany(SolicitudPlanilla::className(), ['nro_solicitud' => 'nro_solicitud']);
        }


        /**
         * Relacion con la entidad "niveles-aprobacion".
         * @return Active Record.
         */
        public function getNivelAprobacion()
        {
            return $this->hasOne(NivelAprobacion::className(), ['nivel_aprobacion' => 'nivel_aprobacion']);
        }


        /**
         * Relacion con la entidad "causas- negacion-solicitud".
         * @return Active Record.
         */
        public function getCausaNegacion()
        {
            return $this->hasOne(CausaNegacionSolicitud::className(), ['causa' => 'causa']);
        }



        /**
         * Relacion con la entidad "estatus-solicitudes". EstatusSolicitud.
         * @return Active Record.
         */
        public function getEstatusSolicitud()
        {
            return $this->hasOne(EstatusSolicitud::className(), ['estatus_solicitud' => 'estatus']);
        }



         /**
         * Metodo que permite obtener una descripcion del estatus del registro.
         * @param  integer $estatus indica el estatus de la solicitud.
         * @return string retorna la descripcion del estatus de la solicitud.
         */
        public function getDescripcionEstatus($estatus)
        {
            $descripcion = '';
            if ( $estatus == 0 ) {
                $descripcion = 'PENDIENTE';
            } elseif ( $estatus == 1 ) {
                $descripcion = 'APROBADA';
            } elseif ( $descripcion == 9 ) {
                $descripcion = 'ANULADA';
            } else {
                $descripcion = 'NO DEFINIDA';
            }
            return $descripcion;
        }




        /***/
        public function getDescripcionTipoSolicitud($nroSolicitud)
        {
            $findModel = SolicitudesContribuyente::find()->where('nro_solicitud =:nro_solicitud',
                                                                    [':nro_solicitud' => $nroSolicitud])
                                                         ->joinWith('tipoSolicitud')
                                                         ->one();
            $tipo = $findModel->tipoSolicitud['descripcion'];
            return $tipo;
        }

    }

 ?>