<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "causas_desincorporaciones".
 *
 * @property string $causa_desincorporacion
 * @property string $descripcion
 * @property integer $inactivo
 */
class CausasDesincorporacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'causas_desincorporaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['causa_desincorporacion'], 'required'],
            [['causa_desincorporacion', 'inactivo'], 'integer'],
            [['descripcion'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'causa_desincorporacion' => Yii::t('backend', 'Causa Desincorporacion'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
            'inactivo' => Yii::t('backend', 'Inactivo'),
        ];
    }
}
