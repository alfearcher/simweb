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
 *  @file CrearUsuarioNaturalForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class CrearUsuarioNaturalForm
 *  @brief Modelo para controlar las rules del formulario de busqueda de persona Juridica. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  scenarios
 *  findRif
 *  findAfiliacion
 *  obtenerDataProviderRif
 *
 *  @inherits
 *  
 */ 

namespace frontend\models\vehiculo\cambiopropietario;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioNatural;
use yii\data\ActiveDataProvider;


class FormularioNaturalForm extends CrearUsuarioNatural{
  
  public $naturaleza;
  public $cedula;
  public $tipo;
 
    


          public function scenarios()
      {
          // bypass scenarios() implementation in the parent class
          return Model::scenarios();
      }
   
     
    /**
       * [rules description] reglas de validacion del formulario para crear usuario natural
       * @return [type] [description]
       */
      public function rules()
      {

       // die('llegue a las rules');
        return [
          [['naturaleza','cedula'],'required','message' => Yii::t('frontend', '{attribute} is required')],
          ['cedula','integer'],
         
         // ['cedula', 'existe'],
          [['tipo_naturaleza', 'tipo'],'default', 'value' => 0],
          [['cedula'], 'integer'],
          [['cedula'], 'validarLongitud'],
         
           // [['naturaleza','cedula','tipo'x],'unique', 'message' => 'Datos repetidos en la base de datos'],
        ];
          //['capital_new', 'format', Yii::$app->formatted->asDecimal($model->)]
      
      }

      public function validarLongitud($attribute, $params){

        $longitud = strlen($this->naturaleza.$this->cedula);

          if ($longitud >9){
            $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 9 characters'));
          }
      }

/**
      * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
      * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
      */
      public function attributeLabels()
      {
          return [
            'natuleza' => Yii::t('frontend', 'Nature'),
              'cedula' => Yii::t('frontend', 'ID.'),
               'tipo' => Yii::t('frontend', 'Kind of Taxpayer'),
              
              

          ];
      }

      /**
       * [attributeAfiliacion description] metodo que contiene los nombres de los campos de la tabla afiliacion
       * @return [type] [description]
       */
      public function attributeAfiliacion()
      {

          return ['id_contribuyente',
              'login',
              'password',
              'fecha_hora_afiliacion',
              'via_sms',
              'via_email',
              'via_tlf_fijo',
              'via_callcenter',
              'estatus',
              'nivel',
              'confirmar_email',

          ];
      }
     
      
      /**
       * [attributeContribuyentes description] metodo que contiene los nombres de los campos de la tabla contribuyentes
       * @return [type] [description]
       */
      public function attributeContribuyentes()
      {

            return ['id_contribuyente',
            'ente',
            'naturaleza',
            'cedula',
            'tipo',
            'tipo_naturaleza',
            'id_rif',
            'id_cp',
            'nombres',
            'apellidos',
            'razon_social',
            'representante',
            'nit',
            'fecha_nac',
            'sexo',
            'casa_edf_qta_dom',
            'piso_nivel_no_dom',
            'apto_dom',
            'domicilio_fiscal',
            'catastro',
            'tlf_hab',
            'tlf_hab_otro',
            'tlf_ofic',
            'tlf_ofic_otro',
            'tlf_celular',
            'fax',
            'email',
            'inactivo',
            'cuenta',
            'reg_mercantil',
            'num_reg',
            'tomo',
            'folio',
            'fecha',
            'capital',
            'horario',
            'extension_horario',
            'num_empleados',
            'tipo_contribuyente',
            'licencia',
            'agente_retencion',
            'id_sim',
            'manzana_limite',
            'lote_1',
            'lote_2',
            'nivel',
            'lote_3',
            'fecha_inclusion',
            'fecha_inicio',
            'foraneo',
            'no_declara',
            'econ_informal',
            'grupo_contribuyente',
            'fe_inic_agente_reten',
            'no_sujeto',
            'ruc',
            'naturaleza_rep',
            'cedula_rep',
            

            ];
      }

  
  
}

 ?>