<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "clases_propagandas".
 *
 * @property string $clase_propaganda
 * @property string $descripcion
 */
class ClasesPropaganda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clases_propagandas';
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
            'clase_propaganda' => Yii::t('backend', 'Clase Propaganda'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
        ];
    }

    
}
