<?php

namespace backend\models\registromaestro;

use Yii;

/**
 * This is the model class for table "condicion_tipo_naturaleza".
 *
 * @property integer $id_condicion_tipo_naturaleza
 * @property string $nb_condicion
 * @property integer $fk_tipo_naturaleza
 *
 * @property TipoNaturaleza $fkTipoNaturaleza
 */
class CondicionTipoNaturaleza extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'condicion_tipo_naturaleza';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_tipo_naturaleza'], 'integer'],
            [['nb_condicion'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_condicion_tipo_naturaleza' => Yii::t('backend', 'Id Condicion Tipo Naturaleza'),
            'nb_condicion' => Yii::t('backend', 'Nb Condicion'),
            'fk_tipo_naturaleza' => Yii::t('backend', 'Fk Tipo Naturaleza'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkTipoNaturaleza()
    {
        return $this->hasOne(TipoNaturaleza::className(), ['id_tipo_naturaleza' => 'fk_tipo_naturaleza']);
    }
}
