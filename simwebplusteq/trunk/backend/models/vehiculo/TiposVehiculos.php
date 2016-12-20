<?php

namespace backend\models\vehiculo;

use Yii;

/**
 * This is the model class for table "tipos_vehiculos".
 *
 * @property string $tipo_vehiculo
 * @property string $descripcion
 */
class TiposVehiculos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_vehiculos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipo_vehiculo' => Yii::t('backend', 'Tipo Vehiculo'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
