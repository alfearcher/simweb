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
 *  @file InscripcionPropaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @class InscripcionPropaganda
 *  @brief Clase Modelo
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */

    namespace backend\models\propaganda\inscripcionpropaganda;

    use Yii;
    use yii\base\Model;
    use yii\db\ActiveRecord;
    use backend\models\propaganda\uso\UsoPropaganda;
    use backend\models\propaganda\clase\ClasePropaganda;
    use backend\models\propaganda\uso\TipoPropaganda;
    use backend\models\propaganda\mediotransporte\MedioTransporte;
    use backend\models\propaganda\mediodifusion\MedioDifusion;
    use backend\models\solicitud\estatus\EstatusSolicitud;
    use common\models\solicitudescontribuyente\SolicitudesContribuyente;



    /**
    *  Clase
    */
    class InscripcionPropaganda extends ActiveRecord
    {

        /**
         *  Metodo que retorna el nombre de la base de datos donde se tiene la conexion actual.
         *  Utiliza las propiedades y metodos de Yii2 para traer dicha informacion.
         *  @return Nombre de la base de datos
         */
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
            return 'sl_propagandas';
        }



        /**
         * Relacion con la entidad "usos-propagandas".
         * @return [type] [description]
         */
        public function getUsoPropaganda()
        {
            return $this->hasOne(UsoPropaganda::className(), ['uso_propaganda' => 'uso_propaganda']);
        }



        /**
         * Relacion con la entidad "clases-propagandas".
         * @return [type] [description]
         */
        public function getClasePropaganda()
        {
            return $this->hasOne(ClasePropaganda::className(), ['clase_propaganda' => 'clase_propaganda']);
        }



        /**
         * Relacion con la entidad "tipos-propagandas".
         * @return [type] [description]
         */
        public function getTipoPropaganda()
        {
            return $this->hasOne(TipoPropaganda::className(), ['tipo_propaganda' => 'tipo_propaganda']);
        }



        /**
         * Relacion con la entidad "medios-transportes".
         * @return [type] [description]
         */
        public function getMedioTransporte()
        {
            return $this->hasOne(MedioTransporte::className(), ['medio_transporte' => 'medio_transporte']);
        }



        /**
         * Relacion con la entidad "medios-difusion".
         * @return [type] [description]
         */
        public function getMedioDifusion()
        {
            return $this->hasOne(MedioDifusion::className(), ['medio_difusion' => 'medio_difusion']);
        }


        /**
         * Relacion con la entidad "estatus-solicitudes", EstatusSolicitud
         * @return Active Record.
         */
        public function getEstatusSolicitud()
        {
            return $this->hasOne(EstatusSolicitud::className(), ['estatus_solicitud' => 'estatus']);
        }


        /**
         * Metodo que permite obtener la descripcion del tipo de solicitud
         * @param  long $nroSolicitud identificacion de la solicitud. Autoincremental
         * que se genera al crear la solicitud.
         * @return string retorna la descripcion de la solicitud.
         */
         public function getDescripcionTipoSolicitud($nroSolicitud)
         {
            return $tipo = SolicitudesContribuyente::getDescripcionTipoSolicitud($nroSolicitud);
         }


    }

?>