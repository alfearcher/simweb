<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "medios_transportes".
 *
 * @property string $medio_transporte
 * @property string $descripcion
 */
class MediosTransporte extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'medios_transportes';
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
            'medio_transporte' => Yii::t('backend', 'Medio Transporte'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
