<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tipos_propagandas".
 *
 * @property string $tipo_propaganda
 * @property string $descripcion
 * @property integer $inactivo
 * @property string $base_calculo
 */
class TiposPropaganda extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_propagandas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inactivo', 'base_calculo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipo_propaganda' => Yii::t('backend', 'Tipo Propaganda'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
            'inactivo' => Yii::t('backend', 'Inactivo'),
            'base_calculo' => Yii::t('backend', 'Base Calculo'),
        ];
    }
}
