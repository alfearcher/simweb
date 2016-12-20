<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contribuyentes".
 *
 * @property string $id_contribuyente
 * @property integer $ente
 * @property string $naturaleza
 * @property string $cedula
 * @property integer $tipo
 * @property integer $tipo_naturaleza
 * @property integer $id_rif
 * @property string $id_cp
 * @property string $nombres
 * @property string $apellidos
 * @property string $razon_social
 * @property string $representante
 * @property string $nit
 * @property string $fecha_nac
 * @property string $sexo
 * @property string $casa_edf_qta_dom
 * @property string $piso_nivel_no_dom
 * @property string $apto_dom
 * @property string $domicilio_fiscal
 * @property string $catastro
 * @property string $tlf_hab
 * @property string $tlf_hab_otro
 * @property string $tlf_ofic
 * @property string $tlf_ofic_otro
 * @property string $tlf_celular
 * @property string $fax
 * @property string $email
 * @property integer $inactivo
 * @property string $cuenta
 * @property string $reg_mercantil
 * @property string $num_reg
 * @property string $tomo
 * @property string $folio
 * @property string $fecha
 * @property double $capital
 * @property string $horario
 * @property integer $extension_horario
 * @property string $num_empleados
 * @property string $tipo_contribuyente
 * @property string $licencia
 * @property integer $agente_retencion
 * @property string $id_sim
 * @property string $manzana_limite
 * @property string $lote_1
 * @property string $lote_2
 * @property string $nivel
 * @property string $lote_3
 * @property string $fecha_inclusion
 * @property string $fecha_inicio
 * @property integer $foraneo
 * @property integer $no_declara
 * @property integer $econ_informal
 * @property string $grupo_contribuyente
 * @property string $fe_inic_agente_reten
 * @property integer $no_sujeto
 * @property string $ruc
 */
class ContribuyentesForm extends \yii\db\ActiveRecord
{
    
    public $naturalezaBuscar;
    public $cedulaBuscar;
    public $tipoBuscar;
    public $tipo_naturaleza;
    public $ano_traspaso;

    public static function tableName()
    {
        return 'contribuyentes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ente', 'naturaleza', 'cedula', 'tipo', 'id_cp', 'nit', 'tlf_hab', 'tlf_hab_otro', 'tlf_ofic', 'tlf_ofic_otro', 'tlf_celular', 'email', 'cuenta', 'num_reg', 'capital', 'num_empleados', 'tipo_contribuyente', 'licencia', 'id_sim', 'ruc'], 'required'],
            [['ente', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif', 'id_cp', 'inactivo', 'cuenta', 'num_reg', 'extension_horario', 'num_empleados', 'tipo_contribuyente', 'licencia', 'agente_retencion', 'manzana_limite', 'lote_1', 'lote_2', 'lote_3', 'foraneo', 'no_declara', 'econ_informal', 'grupo_contribuyente', 'no_sujeto'], 'integer'],
            [['fecha_nac', 'fecha', 'fecha_inclusion', 'fecha_inicio', 'fe_inic_agente_reten'], 'safe'],
            [['capital'], 'number'],
            [['naturaleza', 'sexo','tipoBuscar'], 'string', 'max' => 1],
            [['nombres', 'apellidos', 'nit', 'casa_edf_qta_dom', 'reg_mercantil', 'tomo', 'folio', 'horario'], 'string', 'max' => 50],
            [['razon_social'], 'string', 'max' => 75],
            [['representante'], 'string', 'max' => 200],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25],
            [['domicilio_fiscal'], 'string', 'max' => 250],
            [['catastro', 'email'], 'string', 'max' => 60],
            [['tlf_hab', 'tlf_hab_otro', 'tlf_ofic', 'tlf_ofic_otro', 'tlf_celular', 'fax', 'id_sim'], 'string', 'max' => 15],
            [['nivel'], 'string', 'max' => 3],
            [['ruc'], 'string', 'max' => 20],
            [['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'unique', 'targetAttribute' => ['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'message' => 'The combination of Naturaleza, Cedula, Tipo, Tipo Naturaleza and Id Rif has already been taken.'],
            
            [['cedulaBuscar','ano_traspaso','tipoBuscar','tipo_naturaleza','naturalezaBuscar'], 'string', 'max' => 8],
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contribuyente' => Yii::t('app', 'Id Taxpayer'),
            'ente' => Yii::t('app', 'Ente'),
            'naturaleza' => Yii::t('app', 'Naturaleza'),
            'cedula' => Yii::t('app', 'Identifications'),
            'tipo' => Yii::t('app', 'Tipo'),
            'tipo_naturaleza' => Yii::t('app', 'Tipo Naturaleza'),
            'id_rif' => Yii::t('app', 'Id Rif'),
            'id_cp' => Yii::t('app', 'Id Cp'),
            'nombres' => Yii::t('app', 'First Names'),
            'apellidos' => Yii::t('app', 'Last Names'),
            'razon_social' => Yii::t('app', 'Business Name'),
            'representante' => Yii::t('app', 'Representante'),
            'nit' => Yii::t('app', 'Nit'),
            'fecha_nac' => Yii::t('app', 'Fecha Nac'),
            'sexo' => Yii::t('app', 'Sexo'),
            'casa_edf_qta_dom' => Yii::t('app', 'Casa Edf Qta Dom'),
            'piso_nivel_no_dom' => Yii::t('app', 'Piso Nivel No Dom'),
            'apto_dom' => Yii::t('app', 'Apto Dom'),
            'domicilio_fiscal' => Yii::t('app', 'Domicilio Fiscal'),
            'catastro' => Yii::t('app', 'Catastro'),
            'tlf_hab' => Yii::t('app', 'Phone Room'),
            'tlf_hab_otro' => Yii::t('app', 'Tlf Hab Otro'),
            'tlf_ofic' => Yii::t('app', 'Tlf Ofic'),
            'tlf_ofic_otro' => Yii::t('app', 'Tlf Ofic Otro'),
            'tlf_celular' => Yii::t('app', 'Tlf Celular'),
            'fax' => Yii::t('app', 'Fax'),
            'email' => Yii::t('app', 'Email'),
            'inactivo' => Yii::t('app', 'Inactivo'),
            'cuenta' => Yii::t('app', 'Cuenta'),
            'reg_mercantil' => Yii::t('app', 'Reg Mercantil'),
            'num_reg' => Yii::t('app', 'Num Reg'),
            'tomo' => Yii::t('app', 'Tomo'),
            'folio' => Yii::t('app', 'Folio'),
            'fecha' => Yii::t('app', 'Fecha'),
            'capital' => Yii::t('app', 'Capital'),
            'horario' => Yii::t('app', 'Horario'),
            'extension_horario' => Yii::t('app', 'Extension Horario'),
            'num_empleados' => Yii::t('app', 'Num Empleados'),
            'tipo_contribuyente' => Yii::t('app', 'Tipo Contribuyente'),
            'licencia' => Yii::t('app', 'Licencia'),
            'agente_retencion' => Yii::t('app', 'Agente Retencion'),
            'id_sim' => Yii::t('app', 'Id Sim'),
            'manzana_limite' => Yii::t('app', 'Manzana Limite'),
            'lote_1' => Yii::t('app', 'Lote 1'),
            'lote_2' => Yii::t('app', 'Lote 2'),
            'nivel' => Yii::t('app', 'Nivel'),
            'lote_3' => Yii::t('app', 'Lote 3'),
            'fecha_inclusion' => Yii::t('app', 'Fecha Inclusion'),
            'fecha_inicio' => Yii::t('app', 'Fecha Inicio'),
            'foraneo' => Yii::t('app', 'Foraneo'),
            'no_declara' => Yii::t('app', 'No Declara'),
            'econ_informal' => Yii::t('app', 'Econ Informal'),
            'grupo_contribuyente' => Yii::t('app', 'Grupo Contribuyente'),
            'fe_inic_agente_reten' => Yii::t('app', 'Fe Inic Agente Reten'),
            'no_sujeto' => Yii::t('app', 'No Sujeto'),
            'ruc' => Yii::t('app', 'Ruc'),
        ];
    }
}
