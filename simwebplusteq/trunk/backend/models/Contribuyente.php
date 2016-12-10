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
class Contribuyente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            [['naturaleza', 'sexo'], 'string', 'max' => 1],
            [['nombres', 'apellidos', 'nit', 'casa_edf_qta_dom', 'reg_mercantil', 'tomo', 'folio', 'horario'], 'string', 'max' => 50],
            [['razon_social'], 'string', 'max' => 75],
            [['representante'], 'string', 'max' => 200],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25],
            [['domicilio_fiscal'], 'string', 'max' => 250],
            [['catastro', 'email'], 'string', 'max' => 60],
            [['tlf_hab', 'tlf_hab_otro', 'tlf_ofic', 'tlf_ofic_otro', 'tlf_celular', 'fax', 'id_sim'], 'string', 'max' => 15],
            [['nivel'], 'string', 'max' => 3],
            [['ruc'], 'string', 'max' => 20],
            [['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'unique', 'targetAttribute' => ['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'message' => 'The combination of Naturaleza, Cedula, Tipo, Tipo Naturaleza and Id Rif has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contribuyente' => Yii::t('backend', 'Id Contribuyente'),
            'ente' => Yii::t('backend', 'Ente'),
            'naturaleza' => Yii::t('backend', 'Naturaleza'),
            'cedula' => Yii::t('backend', 'Cedula'),
            'tipo' => Yii::t('backend', 'Tipo'),
            'tipo_naturaleza' => Yii::t('backend', 'Tipo Naturaleza'),
            'id_rif' => Yii::t('backend', 'Id Rif'),
            'id_cp' => Yii::t('backend', 'Id Cp'),
            'nombres' => Yii::t('backend', 'Nombres'),
            'apellidos' => Yii::t('backend', 'Apellidos'),
            'razon_social' => Yii::t('backend', 'Razon Social'),
            'representante' => Yii::t('backend', 'Representante'),
            'nit' => Yii::t('backend', 'Nit'),
            'fecha_nac' => Yii::t('backend', 'Fecha Nac'),
            'sexo' => Yii::t('backend', 'Sexo'),
            'casa_edf_qta_dom' => Yii::t('backend', 'Casa Edf Qta Dom'),
            'piso_nivel_no_dom' => Yii::t('backend', 'Piso Nivel No Dom'),
            'apto_dom' => Yii::t('backend', 'Apto Dom'),
            'domicilio_fiscal' => Yii::t('backend', 'Domicilio Fiscal'),
            'catastro' => Yii::t('backend', 'Catastro'),
            'tlf_hab' => Yii::t('backend', 'Tlf Hab'),
            'tlf_hab_otro' => Yii::t('backend', 'Tlf Hab Otro'),
            'tlf_ofic' => Yii::t('backend', 'Tlf Ofic'),
            'tlf_ofic_otro' => Yii::t('backend', 'Tlf Ofic Otro'),
            'tlf_celular' => Yii::t('backend', 'Tlf Celular'),
            'fax' => Yii::t('backend', 'Fax'),
            'email' => Yii::t('backend', 'Email'),
            'inactivo' => Yii::t('backend', 'Inactivo'),
            'cuenta' => Yii::t('backend', 'Cuenta'),
            'reg_mercantil' => Yii::t('backend', 'Reg Mercantil'),
            'num_reg' => Yii::t('backend', 'Num Reg'),
            'tomo' => Yii::t('backend', 'Tomo'),
            'folio' => Yii::t('backend', 'Folio'),
            'fecha' => Yii::t('backend', 'Fecha'),
            'capital' => Yii::t('backend', 'Capital'),
            'horario' => Yii::t('backend', 'Horario'),
            'extension_horario' => Yii::t('backend', 'Extension Horario'),
            'num_empleados' => Yii::t('backend', 'Num Empleados'),
            'tipo_contribuyente' => Yii::t('backend', 'Tipo Contribuyente'),
            'licencia' => Yii::t('backend', 'Licencia'),
            'agente_retencion' => Yii::t('backend', 'Agente Retencion'),
            'id_sim' => Yii::t('backend', 'Id Sim'),
            'manzana_limite' => Yii::t('backend', 'Manzana Limite'),
            'lote_1' => Yii::t('backend', 'Lote 1'),
            'lote_2' => Yii::t('backend', 'Lote 2'),
            'nivel' => Yii::t('backend', 'Nivel'),
            'lote_3' => Yii::t('backend', 'Lote 3'),
            'fecha_inclusion' => Yii::t('backend', 'Fecha Inclusion'),
            'fecha_inicio' => Yii::t('backend', 'Fecha Inicio'),
            'foraneo' => Yii::t('backend', 'Foraneo'),
            'no_declara' => Yii::t('backend', 'No Declara'),
            'econ_informal' => Yii::t('backend', 'Econ Informal'),
            'grupo_contribuyente' => Yii::t('backend', 'Grupo Contribuyente'),
            'fe_inic_agente_reten' => Yii::t('backend', 'Fe Inic Agente Reten'),
            'no_sujeto' => Yii::t('backend', 'No Sujeto'),
            'ruc' => Yii::t('backend', 'Ruc'),
        ];
    }
}
