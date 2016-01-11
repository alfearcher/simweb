<?php
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *  > This library is free software; you can redistribute it and/or modify it under 
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *  > any later version.
 *  > 
 *  > This library is distributed in the hope that it will be usefull, 
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *  > for more details.
 *  > 
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**    
 *  @file DatosBasicoForm.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 21/07/2015
 * 
 *  @class DatosBasicoForm
 *  @brief Modelo del formulario de datos basicos, en el estan las validaciones y textos necesarios.
*   @property
 *
 *  
 *  @method
 *    
 *  @inherits
 *  
 */
namespace frontend\models\usuario;

use Yii;

/**
 * This is the model class DatosBasicoForm from table "contribuyentes".
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
class CargaDatosBasicosForm extends \yii\db\ActiveRecord
{
    public $cedula;
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
            [['ente','naturaleza', 'cedula', 'tipo',  'tlf_hab', 'email'],'required'],
            [['ente','cedula', 'tipo', 'tipo_naturaleza', 'id_rif', 'id_cp', 'inactivo', 'cuenta', 'num_reg', 'extension_horario', 'num_empleados', 'tipo_contribuyente', 'licencia', 'agente_retencion', 'manzana_limite', 'lote_1', 'lote_2', 'lote_3', 'foraneo', 'no_declara', 'econ_informal', 'grupo_contribuyente', 'no_sujeto'], 'integer'],
            [['fecha_nac', 'fecha', 'fecha_inclusion', 'fecha_inicio', 'fe_inic_agente_reten'], 'safe'],            
            [['capital'], 'number'],            
            [['naturaleza', 'sexo'], 'string', 'max' => 1],
            [['nombres', 'apellidos', 'nit', 'casa_edf_qta_dom', 'reg_mercantil', 'tomo', 'folio', 'horario'], 'string', 'max' => 50],
            [['razon_social'], 'string', 'max' => 75],
            [['sexo'],'default', 'value' => ''],
            [['razon_social', 'tlf_ofic'], 'required', 'when' => function($model) {
                                                        return $model->tipo_naturaleza == 1;
            }],
            [['nombres', 'apellidos', 'tlf_celular'], 'required', 'when' => function($model) {
                                                        return $model->tipo_naturaleza == 0;
            }],
            [['representante'], 'string', 'max' => 200],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25],
            [['domicilio_fiscal'], 'string', 'max' => 250],
            [['catastro', 'email'], 'string', 'max' => 60],
            [['tlf_hab', 'tlf_hab_otro', 'tlf_ofic', 'tlf_ofic_otro', 'tlf_celular', 'fax', 'id_sim'], 'string', 'max' => 15],
            [['nivel'], 'string', 'max' => 3],
            [['ruc'], 'string', 'max' => 20],
            ['email', 'filter','filter'=>'strtolower'],
            ['email', 'email'],
            ['tlf_ofic_otro','string', 'max'=>12],
            ['ente', 'default', 'value' => Yii::$app->ente->getEnte()],
            [['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'unique', 'targetAttribute' => ['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'message' => 'The combination of Naturaleza, Cedula, Tipo, Tipo Naturaleza and Id Rif has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contribuyente' => Yii::t('frontend', 'Id Contribuyente'),
            'ente' => Yii::t('frontend', 'Ente'),
            'naturaleza' => Yii::t('frontend', 'Natural'),
            'cedula' => Yii::t('frontend', 'Cedula'),
            'tipo' => Yii::t('frontend', 'Type'),
            'tipo_naturaleza' => Yii::t('frontend', 'Natural type'),
            'id_rif' => Yii::t('frontend', 'Id Rif'),
            'id_cp' => Yii::t('frontend', 'Id Cp'),
            'nombres' => Yii::t('frontend', 'Name'),
            'apellidos' => Yii::t('frontend', 'Lastname'),
            'razon_social' => Yii::t('frontend', 'Business Name'),
            'representante' => Yii::t('frontend', 'Representant'),
            'nit' => Yii::t('frontend', 'Nit'),
            'fecha_nac' => Yii::t('frontend', 'Birthday'),
            'sexo' => Yii::t('frontend', 'Gender'),
            'casa_edf_qta_dom' => Yii::t('frontend', 'House Edf Qta Dom'),
            'piso_nivel_no_dom' => Yii::t('frontend', 'Flat Level No Dom'),
            'apto_dom' => Yii::t('frontend', 'Apto Dom'),
            'domicilio_fiscal' => Yii::t('frontend', 'Offices'),
            'catastro' => Yii::t('frontend', 'Catastro'),
            'tlf_hab' => Yii::t('frontend', 'Tlf Hab'),
            'tlf_hab_otro' => Yii::t('frontend', 'Tlf Hab Otro'),
            'tlf_ofic' => Yii::t('frontend', 'Office Phone'),
            'tlf_ofic_otro' => Yii::t('frontend', 'Other Office Phone'),
            'tlf_celular' => Yii::t('frontend', 'Mobile Phone'),
            'fax' => Yii::t('frontend', 'Fax'),
            'email' => Yii::t('frontend', 'Email'),
            'inactivo' => Yii::t('frontend', 'Inactivo'),
            'cuenta' => Yii::t('frontend', 'Account'),
            'reg_mercantil' => Yii::t('frontend', 'Reg Mercantil'),
            'num_reg' => Yii::t('frontend', 'Num Reg'),
            'tomo' => Yii::t('frontend', 'Tomo'),
            'folio' => Yii::t('frontend', 'Folio'),
            'fecha' => Yii::t('frontend', 'Fecha'),
            'capital' => Yii::t('frontend', 'Capital'),
            'horario' => Yii::t('frontend', 'Schedule'),
            'extension_horario' => Yii::t('frontend', 'Extension Horario'),
            'num_empleados' => Yii::t('frontend', 'Num Empleados'),
            'tipo_contribuyente' => Yii::t('frontend', 'Taxpayer type'),
            'licencia' => Yii::t('frontend', 'Licencia'),
            'agente_retencion' => Yii::t('frontend', 'Retention agent'),
            'id_sim' => Yii::t('frontend', 'Id Sim'),
            'manzana_limite' => Yii::t('frontend', 'Manzana Limite'),
            'lote_1' => Yii::t('frontend', 'Lote 1'),
            'lote_2' => Yii::t('frontend', 'Lote 2'),
            'nivel' => Yii::t('frontend', 'Nivel'),
            'lote_3' => Yii::t('frontend', 'Lote 3'),
            'fecha_inclusion' => Yii::t('frontend', 'Fecha Inclusion'),
            'fecha_inicio' => Yii::t('frontend', 'Fecha Inicio'),
            'foraneo' => Yii::t('frontend', 'Foraneo'),
            'no_declara' => Yii::t('frontend', 'No Declara'),
            'econ_informal' => Yii::t('frontend', 'Econ Informal'),
            'grupo_contribuyente' => Yii::t('frontend', 'Grupo Contribuyente'),
            'fe_inic_agente_reten' => Yii::t('frontend', 'Fe Inic Agente Reten'),
            'no_sujeto' => Yii::t('frontend', 'No Sujeto'),
            'ruc' => Yii::t('frontend', 'Ruc'),
        ];
    }

    public function getGenderOptions(){
        return array('M' => 'Masculino', 'F' => 'Femenino');
    }
}
