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
 *  @file SolicitudePlanillaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-05-2016
 *
 *  @class SolicitudePlanillaForm
 *  @brief Modelo
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
 *  @inherits
 *
 */

    namespace common\models\configuracion\solicitudplanilla;

    use Yii;
    use yii\base\Model;
    use yii\db\ActiveRecord;
    use common\models\configuracion\solicitudplanilla\SolicitudPlanilla;


    /***/
    class SolicitudPlanillaForm extends SolicitudPlanilla
    {
        public $id_solicitud_planilla;
        public $nro_solicitud;
        public $id_config_solicitud;
        public $id_config_solic_tasa_multa;
        public $planilla;
        public $inactivo;
        public $usuario;
        public $fecha_hora;
        public $origen;
        public $evento;




        /**
         *  Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
         */
        public function rules()
        {
            return [
                [['nro_solicitud', 'id_config_solicitud',
                  'id_config_solic_tasa_multa', 'planilla'],
                  'required',
                  'message' => Yii::t('backend', '{attribute} is required')],
                [['nro_solicitud', 'id_config_solic_tasa_multa',
                  'planilla', 'inactivo', 'id_config_solicitud'],
                  'integer'],
                ['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
                ['inactivo', 'default', 'value' => 0],
                ['usuario', 'default', 'value' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login, 'on' => 'backend'],
                ['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
                ['usuario', 'default', 'value' => Yii::$app->user->identity->email, 'on' => 'backend'],
                ['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
                ['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
                [['evento'], 'string'],
            ];
        }






        /**
        *   Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
        *   @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
        */
        public function attributeLabels()
        {
            return [
                'nro_solicitud' => Yii::t('backend', 'Number Request'),
                'id_config_solicitud' => Yii::t('backend', 'Setup'),
                'id_config_solic_tasa_multa' => Yii::t('backend', 'Id. Taxpayer'),
                'usuario' => Yii::t('backend', 'User'),
                'fecha_hora' => Yii::t('backend', 'Date/Hour Creacion'),
                'inactivo' => Yii::t('backend', 'Condition'),
                'usuario' => Yii::t('backend', 'User'),
                'planilla' => Yii::t('backend', 'Planilla'),
                'evento' => Yii::t('backend', 'Events'),

            ];
        }






    }

 ?>