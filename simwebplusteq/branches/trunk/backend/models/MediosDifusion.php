<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "medios_difusion".
 *
 * @property string $medio_difusion
 * @property string $descripcion
 */
class MediosDifusion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medios_difusion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medio_difusion' => Yii::t('backend', 'Medio Difusion'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
