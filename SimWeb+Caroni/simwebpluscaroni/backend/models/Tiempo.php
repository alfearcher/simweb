<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tiempos".
 *
 * @property string $id_tiempo
 * @property string $descripcion
 */
class Tiempo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiempos';
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
            'id_tiempo' => Yii::t('backend', 'Id Tiempo'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
