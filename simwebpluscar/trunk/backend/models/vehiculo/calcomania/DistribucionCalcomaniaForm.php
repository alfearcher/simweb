<?php

namespace backend\models\vehiculo\calcomania;

use Yii;

/**
 * This is the model class for table "distribucion_calcomania".
 *
 * @property integer $id_distribucion_calcomania
 * @property integer $id_funcionario_calcomania
 * @property integer $id_lote_calcomania
 * @property string $rango_inicial
 * @property string $rango_final
 * @property integer $estatus
 * @property string $observacion
 */
class DistribucionCalcomaniaForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'distribucion_calcomania';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_funcionario_calcomania', 'id_lote_calcomania', 'rango_inicial', 'rango_final', 'estatus'], 'integer'],
            [['observacion'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_distribucion_calcomania' => Yii::t('backend', 'Id Distribucion Calcomania'),
            'id_funcionario_calcomania' => Yii::t('backend', 'Id Funcionario Calcomania'),
            'id_lote_calcomania' => Yii::t('backend', 'Id Lote Calcomania'),
            'rango_inicial' => Yii::t('backend', 'Rango Inicial'),
            'rango_final' => Yii::t('backend', 'Rango Final'),
            'estatus' => Yii::t('backend', 'Estatus'),
            'observacion' => Yii::t('backend', 'Observacion'),
        ];
    }
}
