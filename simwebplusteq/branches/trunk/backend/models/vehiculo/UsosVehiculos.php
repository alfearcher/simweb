<?php

namespace backend\models\vehiculo;

use Yii;

/**
 * This is the model class for table "usos_vehiculos".
 *
 * @property string $uso_vehiculo
 * @property string $descripcion
 */
class UsosVehiculos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usos_vehiculos';
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
            'uso_vehiculo' => Yii::t('backend', 'Uso Vehiculo'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
