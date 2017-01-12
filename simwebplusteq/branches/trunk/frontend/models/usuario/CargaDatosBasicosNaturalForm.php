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
 *  @file CargaDatosBasicosNaturalForm.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 13/01/2016
 *
 *  @class CargaDatosBasicosNaturalForm
 *  @brief Modelo del formulario de datos basicos de persona natural, en el estan las validaciones y textos necesarios.
 *   @property
 *
 *
 *  @method
 *
 *  tableName
 *  rules
 *  attributeLabels
 *  getGenderOptions
 *  dameIdRif
 *
 *  @inherits
 *
 */
namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
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
class CargaDatosBasicosNaturalForm extends CrearUsuarioNatural
{

        public $id_contribuyente;
        public $ente;
        public $naturaleza;
        public $cedula;
        public $tipo;
        public $tipo_naturaleza;            // 0 => NATURAL, 1 => JURIDICO.
        public $id_rif;
        public $id_cp;
        public $apellidos;
        public $nombres;
        public $razon_social;
        public $representante;
        public $nit;
        public $fecha_nac;
        public $sexo;
        public $casa_edf_qta_dom;
        public $piso_nivel_no_dom;
        public $apto_dom;
        public $domicilio_fiscal;
        public $catastro;
        public $tlf_hab;
        public $tlf_hab_otro;
        public $tlf_ofic;
        public $tlf_ofic_otro;
        public $tlf_celular;
        public $fax;
        public $email;
        public $inactivo;                   // 0 => ACTIVO, 1 => INACTIVO.
        public $cuenta;
        public $reg_mercantil;
        public $num_reg;
        public $tomo;
        public $folio;
        public $fecha;
        public $capital;
        public $horario;
        public $extension_horario;
        public $num_empleados;
        public $tipo_contribuyente;
        public $licencia;
        public $agente_retencion;
        public $id_sim;
        public $manzana_limite;
        public $lote_1;
        public $lote_2;
        public $nivel;
        public $lote_3;
        public $fecha_inclusion;
        public $fecha_inicio;
        public $foraneo;
        public $no_declara;
        public $econ_informal;
        public $grupo_contribuyente;
        public $fe_inic_agente_retencion;
        public $no_sujeto;
        public $ruc;
        public $naturaleza_rep;
        public $cedula_rep;
        public $codigo;
        


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

        
            [['ente','naturaleza', 'fecha_nac','cedula', 'tipo', 'email', 'nombres', 'apellidos',  'sexo', 'codigo', 'domicilio_fiscal', 'tlf_celular'],'required'],
               [['tlf_celular'], 'match' , 'pattern' => "/^.{7,7}$/", 'message' => Yii::t('frontend', 'Phone number must have 7 digits')],
               [['tlf_celular'], 'match' , 'pattern' => "/[0-9]+/", 'message' => Yii::t('frontend', '{attribute} must be an integer')],
              // ['tlf_celular', 'string', 'length' => [4,7]], 

            [['ente','cedula', 'tipo', 'tipo_naturaleza', 'id_rif', 'id_cp', 'inactivo', 'cuenta', 'num_reg', 'extension_horario', 'num_empleados', 'tipo_contribuyente', 'licencia', 'agente_retencion', 'manzana_limite', 'lote_1', 'lote_2', 'lote_3', 'foraneo', 'no_declara', 'econ_informal', 'grupo_contribuyente', 'no_sujeto'], 'integer'],
            [['fecha', 'fecha_inclusion', 'fecha_inicio', 'fe_inic_agente_reten'], 'safe'],
            //['fecha_nac','date'], 
           //[['fecha_nac'], 'default', 'value' => null],
            [['domicilio_fiscal'], 'match' , 'pattern' => "/[a-zA-Z0-9*#°.,-_]+/", 'message' => Yii::t('frontend', '{attribute} is sensitive to lower and upper case and some symbols(#*°.,-_)')],
            [['capital'], 'number'],
            [['naturaleza', 'sexo'], 'string', 'max' => 1],
            [['nombres', 'apellidos', 'nit', 'casa_edf_qta_dom', 'reg_mercantil', 'tomo', 'folio', 'horario'],  'string', 'max' => 50],
            [['razon_social'], 'string', 'max' => 75],
            [['sexo'],'default', 'value' => ''],
            [['razon_social', 'tlf_ofic', 'tlf_celular', 'domicilio_fiscal'], 'required', 'when' => function($model) {
                                                        return $model->tipo_naturaleza == 1;
            }],
            [['representante'], 'string', 'max' => 200],
            [['piso_nivel_no_dom', 'apto_dom'], 'string', 'max' => 25],
            [['domicilio_fiscal'], 'string', 'max' => 250],

            [['catastro', 'email'], 'string', 'max' => 60],
            [['tlf_hab', 'tlf_hab_otro', 'tlf_ofic', 'tlf_ofic_otro',  'fax', 'id_sim'], 'string', 'max' => 11],
            [['nivel'], 'string', 'max' => 3],
            [['ruc'], 'string', 'max' => 20],
            ['email', 'filter','filter'=>'strtolower'],
            ['email', 'email'],
            ['tlf_celular','string', 'max'=>7],
            ['ente', 'default', 'value' => Yii::$app->ente->getEnte()],
            [['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'unique', 'targetAttribute' => ['naturaleza', 'cedula', 'tipo', 'tipo_naturaleza', 'id_rif'], 'message' => 'This user has already been taken.'],

            [['id_rif'], 'default', 'value'=> function($model){
                                                    return self::dameIdRif($model);

          }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codigo' => Yii::t('frontend', 'Code'),
            'codigo2' => Yii::t('frontend', 'Code'),

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
    /**
     * [dameIdRif description] metodo que busca el rif ingresado por el usuario en la tabla contribuyentes
     * @param  [type] $model [description] modelo donde viene el rif enviado por el usuario
     * @return [type]        [description] retorna el id_rif encontrado y le suma uno automaticamente
     */
    public function dameIdRif($model){

        $modelFind = CrearUsuarioNatural::find()
                                         ->where([
                                        'naturaleza' => $model->naturaleza,
                                        'cedula' => $model->cedula,
                                        'tipo_naturaleza' => 0])
                                        ->orderBy(['id_rif' => SORT_DESC])->one();

        if(count($modelFind)>0){

            return $modelFind->id_rif +=1;
        } else {

            return 0;
        }


    }
}
