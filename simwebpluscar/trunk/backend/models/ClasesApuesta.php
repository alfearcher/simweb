<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "clases_apuestas".
 *
 * @property string $clase_apuesta
 * @property string $descripcion
 */
class ClasesApuesta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clases_apuestas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clase_apuesta'], 'required'],
            [['clase_apuesta'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['clase_apuesta'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'clase_apuesta' => 'Clase Apuesta',
            'descripcion' => 'Descripcion',
        ];
    }
}