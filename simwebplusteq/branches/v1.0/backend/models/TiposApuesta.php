<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tipos_apuestas".
 *
 * @property string $tipo_apuesta
 * @property string $clase_apuesta
 * @property string $descripcion
 * @property integer $inactivo
 */
class TiposApuesta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_apuestas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_apuesta'], 'required'],
            [['tipo_apuesta', 'clase_apuesta', 'inactivo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['tipo_apuesta'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipo_apuesta' => 'Tipo Apuesta',
            'clase_apuesta' => 'Clase Apuesta',
            'descripcion' => 'Descripcion',
            'inactivo' => 'Inactivo',
        ];
    }
}
