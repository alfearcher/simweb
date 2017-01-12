<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "bases_calculos".
 *
 * @property string $base_calculo
 * @property string $descripcion
 * @property string $alias
 */
class BasesCalculos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bases_calculos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_calculo', 'descripcion'], 'required'],
            [['base_calculo'], 'integer'],
            [['descripcion'], 'string', 'max' => 45],
            [['alias'], 'string', 'max' => 12]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'base_calculo' => Yii::t('backend', 'Base Calculo'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
            'alias' => Yii::t('backend', 'Alias'),
        ];
    }
}
