<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "unidades_departamentos".
 *
 * @property string $id_unidad
 * @property string $id_departamento
 * @property string $descripcion
 * @property integer $inactivo
 */
class UnidadDepartamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unidades_departamentos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_unidad'], 'required'],
            [['id_unidad', 'id_departamento', 'inactivo'], 'integer'],
            [['descripcion'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_unidad' => Yii::t('backend', 'Id Unidad'),
            'id_departamento' => Yii::t('backend', 'Id Departamento'),
            'descripcion' => Yii::t('backend', 'Descripcion'),
            'inactivo' => Yii::t('backend', 'Inactivo'),
        ];
    }
}
