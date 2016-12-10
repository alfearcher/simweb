<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "departamentos".
 *
 * @property string $id_departamento
 * @property string $descripion
 * @property integer $inactivo
 */
class Departamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departamentos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_departamento'], 'required'],
            [['id_departamento', 'inactivo'], 'integer'],
            [['descripion'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_departamento' => Yii::t('backend', 'Id Departamento'),
            'descripion' => Yii::t('backend', 'Descripion'),
            'inactivo' => Yii::t('backend', 'Inactivo'),
        ];
    }

    
    }
