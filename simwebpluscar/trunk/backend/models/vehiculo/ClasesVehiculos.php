<?php

namespace backend\models\vehiculo;

use Yii;

/**
 * This is the model class for table "clases_vehiculos".
 *
 * @property string $clase_vehiculo
 * @property string $descripcion
 */
class ClasesVehiculos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clases_vehiculos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clase_vehiculo'], 'required'],
            [['clase_vehiculo'], 'integer'],
            [['descripcion'], 'string', 'max' => 120]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clase_vehiculo' => Yii::t('backend', 'Clase Vehiculo'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
