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
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-05-2016
 *
 *  @class SolicitudesContribuyenteForm
 *  @brief Modelo que instancia la conexion a la base de datos para buscar datos
 *  de la tabla solicitudes_contribuyente.
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
    use yii\db\ActiveRecord;
    use common\models\solicitudescontribuyente\SolicitudesContribuyente;


    /***/
    class SolicitudesContribuyenteForm extends SolicitudesContribuyente
    {
        public $nro_solicitud;
        public $id_config_solicitud;
        public $impuesto;
        public $id_impuesto;
        public $tipo_solicitud;
        public $usuario;
        public $id_contribuyente;
        public $fecha_hora_creacion;
        public $nivel_aprobacion;
        public $nro_control;
        public $firmma_digital;
        public $estatus;
        public $inactivo;
        public $user_funcionario;
        public $fecha_hora_proceso;
        public $causa;                      // Causa de desincorporacion o inactivacion.
        public $observacion;                // Observacion colocada por el funcionario para la desincorporacion o inactivacion.




        /**
         *  Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
         */
        public function rules()
        {
            return [
                [['id_contribuyente', 'id_config_solicitud',
                  'impuesto', 'id_impuesto',
                  'tipo_solicitud', 'usuario', 'nivel_aprobacion'],
                  'required',
                  'message' => Yii::t('backend', '{attribute} is required')],
                [['id_contribuyente', 'tipo_solicitud',
                  'impuesto', 'id_impuesto', 'estatus',
                  'inactivo', 'id_config_solicitud',
                  'nro_solicitud', 'causa', 'nivel_aprobacion'],
                  'integer'],
                [['usuario', 'user_funcionario',
                  'firmma_digital', 'observacion'], 'string'],
                ['fecha_hora_creacion', 'default', 'value' => date('Y-m-d H:i:s')],
                ['nro_solicitud', 'unique'],
                ['nivel_aprobacion', 'default', 'value' => 1],
                ['inactivo', 'default', 'value' => 0],
                ['estatus', 'default', 'value' => 0],
                ['fecha_hora_proceso', 'default', 'value' => date('Y-m-d H:i:s', strtotime('0000-00-00 00:00:00'))],
                ['user_funcionario', 'default', 'value' => null],
                ['firmma_digital', 'default', 'value' => null],
                ['observacion', 'default', 'value' => null],
                ['usuario', 'default', 'value' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->identity->login, 'on' => 'backend'],
                ['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
                ['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
                ['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
                ['id_contribuyente', 'default', 'value' => isset($_SESSION['idContribuyente']) ? $_SESSION['idContribuyente'] : null],
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
                'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
                'usuario' => Yii::t('backend', 'User'),
                'nivel_aprobacion'  => Yii::t('backend', 'Level Approve'),
                'impuesto' => Yii::t('backend', 'Tax'),
                'id_impuesto' => Yii::t('backend', 'Id. Object'),
                'fecha_hora_creacion' => Yii::t('backend', 'Date/Hour Creacion'),
                'fecha_hora_proceso' => Yii::t('backend', 'Date/Hour Proceso'),
                'tipo_solicitud' => Yii::t('backend', 'Type Request'),
                'nro_control' => Yii::t('backend', 'Nro Control'),
                'firmma_digital' => Yii::t('backend', 'Signal'),
                'estatus' => Yii::t('backend', 'Status'),
                'inactivo' => Yii::t('backend', 'Condition'),
                'user_funcionario' => Yii::t('backend', 'Public Office'),
                'causa'  => Yii::t('backend', 'Causa'),
                'observacion'  => Yii::t('backend', 'Note'),

            ];
        }


    }

 ?>