<?php

namespace backend\models\registromaestro;

use Yii;

/**
 * This is the model class for table "tipo_naturaleza".
 *
 * @property integer $id_tipo_naturaleza
 * @property string $siglas_tnaturaleza
 * @property string $nb_naturaleza
 *
 * @property CondicionTipoNaturaleza[] $condicionTipoNaturalezas
 */
class TipoNaturaleza extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_naturaleza';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['siglas_tnaturaleza', 'nb_naturaleza'], 'required'],
            [['siglas_tnaturaleza'], 'string', 'max' => 2],
            [['nb_naturaleza'], 'string', 'max' => 45],
            [['siglas_tnaturaleza'], 'unique'],
            [['nb_naturaleza'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_naturaleza' => Yii::t('backend', 'Clave Primaria de la tabla'),
            'siglas_tnaturaleza' => Yii::t('backend', 'Abreviacion del tipo de naturaleza, ej: J, V, E, D, G ... etc.'),
            'nb_naturaleza' => Yii::t('backend', 'Nombre del tipo de naturaleza, ej: Venezolano, Extranjero, Juridico, Gubernamental...etc.'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCondicionTipoNaturalezas()
    {
        return $this->hasMany(CondicionTipoNaturaleza::className(), ['fk_tipo_naturaleza' => 'id_tipo_naturaleza']);
    }
}
