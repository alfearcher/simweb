<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tarifas_apuestas".
 *
 * @property string $id_tarifa_apuesta
 * @property string $clase_apuesta
 * @property string $tipo_apuesta
 * @property string $ano_impositivo
 * @property double $porcentaje
 * @property double $monto_bs
 * @property double $monto_ut
 * @property integer $seguir_rango
 */
class TarifasApuesta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarifas_apuestas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clase_apuesta', 'tipo_apuesta', 'ano_impositivo', 'seguir_rango'], 'integer'],
            [['porcentaje', 'monto_bs', 'monto_ut'], 'number'],
            [['clase_apuesta', 'tipo_apuesta', 'ano_impositivo'], 'unique', 'targetAttribute' => ['clase_apuesta', 'tipo_apuesta', 'ano_impositivo'], 'message' => 'The combination of Clase Apuesta, Tipo Apuesta and Ano Impositivo has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tarifa_apuesta' => Yii::t('backend', 'Id Tarifa Apuesta'),
            'clase_apuesta' => Yii::t('backend', 'Clase Apuesta'),
            'tipo_apuesta' => Yii::t('backend', 'Tipo Apuesta'),
            'ano_impositivo' => Yii::t('backend', 'Ano Impositivo'),
            'porcentaje' => Yii::t('backend', 'Porcentaje'),
            'monto_bs' => Yii::t('backend', 'Monto Bs'),
            'monto_ut' => Yii::t('backend', 'Monto Ut'),
            'seguir_rango' => Yii::t('backend', 'Seguir Rango'),
        ];
    }
}
