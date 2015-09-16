<?php

namespace backend\models\vehiculo\calcomania;

use Yii;

/**
 * This is the model class for table "lote_calcomania".
 *
 * @property integer $id_lote_calcomania
 * @property integer $ano_impositivo
 * @property string $rango_inicial
 * @property string $rango_final
 * @property string $observacion
 * @property string $causa
 * @property string $estatus
 * @property integer $inactivo
 */
class LoteCalcomaniaForm extends \yii\db\ActiveRecord
{
    public $accion;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lote_calcomania';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rango_inicial', 'rango_final'], 'required'],
            [['ano_impositivo', 'rango_inicial', 'rango_final', 'inactivo', 'accion'], 'integer'],
            [['ano_impositivo'], 'unique'],
            [['observacion', 'causa'], 'string'],
            [['rango_final'], 'compararRango' ,'when' => function($model) {return $model->rango_final <= $model->rango_inicial;}],

            [['causa'], 'required' ,'when' => function($model) {return $model->inactivo==1;}],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_lote_calcomania' => Yii::t('backend', 'Id Lote Calcomania'),
            'ano_impositivo' => Yii::t('backend', 'Year Impositive'),
            'rango_inicial' => Yii::t('backend', 'Rango Inicial'),
            'rango_final' => Yii::t('backend', 'Rango Final'),
            'observacion' => Yii::t('backend', 'Observacion'),
            'causa' => Yii::t('backend', 'Causa'),
            'inactivo' => Yii::t('backend', 'Inactivar'),
            'estatus' => Yii::t('backend', 'Estatus'),
        ];
    }

    public function compararRango($attribute, $params)
    {
        $this->addError($attribute, Yii::t('backend', 'El rango final no puede ser menor o igual al rango inicial')); 
     }
}
