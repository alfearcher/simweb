<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "usos_propagandas".
 *
 * @property string $uso_propaganda
 * @property string $descripcion
 */
class UsosPropaganda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usos_propagandas';
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
            'uso_propaganda' => Yii::t('backend', 'Uso Propaganda'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }
}
