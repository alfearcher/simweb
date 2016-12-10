<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "niveles".
 *
 * @property string $nivel
 * @property string $descripcion
 */
class Nivel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'niveles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nivel', 'descripcion'], 'required'],
            [['nivel'], 'integer'],
            [['descripcion'], 'string', 'max' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nivel' => Yii::t('backend', 'Nivel'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
