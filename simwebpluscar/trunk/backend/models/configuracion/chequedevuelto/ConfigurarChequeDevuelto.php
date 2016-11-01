<?php

namespace backend\models\configuracion\convenios;

use Yii;

/**
 * This is the model class for table "config_convenios".
 *
 * @property string $id_config_convenio
 * @property string $impuesto
 * @property double $monto_minimo
 * @property string $tipo_monto
 * @property string $ano_ut
 * @property integer $solo_deuda_morosa
 * @property integer $tipo_periodo
 * @property double $monto_inicial
 * @property double $porcentaje_inicial
 * @property integer $nro_max_cuotas
 * @property integer $lapso_tiempo
 * @property string $id_tiempo
 * @property integer $vcto_dif_ano
 * @property integer $aplicar_interes
 * @property double $interes
 * @property string $id_impuesto
 * @property string $usuario
 * @property string $fecha_hora
 * @property integer $inactivo
 */
class ConfigurarChequeDevuelto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config_convenios';
    }

    /**
     * @inheritdoc     
     */
    public function rules()
    {
        return [
            [['id_config_convenio'], 'required'],
            [['id_config_convenio', 'impuesto', 'tipo_monto', 'ano_ut', 'solo_deuda_morosa', 'tipo_periodo', 'nro_max_cuotas', 'lapso_tiempo', 'id_tiempo', 'vcto_dif_ano', 'aplicar_interes', 'id_impuesto', 'inactivo'], 'integer'],
            [['monto_minimo', 'monto_inicial', 'porcentaje_inicial', 'interes'], 'number'],
            [['fecha_hora'], 'safe'],
            [['usuario'], 'string', 'max' => 80]   
        ];              
    }      

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_config_convenio' => Yii::t('app', 'Id Config Convenio'),
            'impuesto' => Yii::t('app', 'Impuesto'),
            'monto_minimo' => Yii::t('app', 'Monto Minimo'),
            'tipo_monto' => Yii::t('app', 'Tipo Monto'),
            'ano_ut' => Yii::t('app', 'Ano Ut'),
            'solo_deuda_morosa' => Yii::t('app', 'Solo Deuda Morosa'),
            'tipo_periodo' => Yii::t('app', 'Tipo Periodo'),
            'monto_inicial' => Yii::t('app', 'Monto Inicial'),
            'porcentaje_inicial' => Yii::t('app', 'Porcentaje Inicial'),
            'nro_max_cuotas' => Yii::t('app', 'Nro Max Cuotas'),
            'lapso_tiempo' => Yii::t('app', 'Lapso Tiempo'),
            'id_tiempo' => Yii::t('app', 'Id Tiempo'),
            'vcto_dif_ano' => Yii::t('app', 'Vcto Dif Ano'),
            'aplicar_interes' => Yii::t('app', 'Aplicar Interes'),
            'interes' => Yii::t('app', 'Interes'),  
            'id_impuesto' => Yii::t('app', 'Id Impuesto'),
            'usuario' => Yii::t('app', 'Usuario'),
            'fecha_hora' => Yii::t('app', 'Fecha Hora'),
            'inactivo' => Yii::t('app', 'Inactivo'),
        ];    
    }

    /**
     * @inheritdoc
     * @return ConfigConveniosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigConveniosQuery(get_called_class());
    }
}
