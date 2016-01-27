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
 *  @file CrearUsuarioJuridicoForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 21/12/15
 * 
 *  @class CrearUsuarioJuridicoForm
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

namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioNaturalForm;
use yii\data\ActiveDataProvider;


class CrearUsuarioNaturalForm extends CrearUsuarioNatural{
  
  public $naturaleza;
  public $cedula;
  public $tipo;
  public $email;
    


          public function scenarios()
      {
          // bypass scenarios() implementation in the parent class
          return Model::scenarios();
      }
   
     
    /**
       * [rules description]
       * @return [type] [description]
       */
      public function rules()
      {
        return [
          [['naturaleza','cedula'],'required','message' => Yii::t('frontend', '{attribute} is required')],
          ['cedula', 'existe'],
          [['tipo_naturaleza', 'tipo'],'default', 'value' => 0],
          [['cedula'], 'integer']
         
           // [['naturaleza','cedula','tipo'x],'unique', 'message' => 'Datos repetidos en la base de datos'],
        ];
          //['capital_new', 'format', Yii::$app->formatted->asDecimal($model->)]
      
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

  
   public function findRif($naturaleza, $cedula, $tipo)
   {

      $model = CrearUsuarioNatural::find()->where([
                    'naturaleza' => $naturaleza,
                    'cedula' => $cedula,
                    'tipo' => $tipo,
                    'tipo_naturaleza' => 0])->All();

     //die(var_dump($model));
      return isset($model) ? $model : false;
  }

public function findAfiliacion($idContribuyente)
   {

      $model = Afiliacion::findOne([
                    
                    'id_contribuyente' => $idContribuyente,
                    
                    ]);

     //die(var_dump($model));
      return isset($model) ? $model : false;
  }


   

    public function obtenerDataProviderRif($naturalezaLocal, $cedulaLocal, $tipoLocal)
   {
      if ( trim($naturalezaLocal) != '' && $cedulaLocal > 0 ) {
          if ( strlen($naturalezaLocal) == 1 ) {
            $query = CrearUsuarioNatural::find();
            $dataProvider = new ActiveDataProvider([
                  'query' => $query,
              ]);
            $query->where('naturaleza =:naturaleza and cedula =:cedula and tipo =:tipo and tipo_naturaleza =:tipo_naturaleza',[':naturaleza' => $naturalezaLocal,
                                    ':cedula' => $cedulaLocal,
                                    ':tipo' => $tipoLocal,
                                    ':tipo_naturaleza' => 0
                                     ])->all();

            return $dataProvider;
          }
        }
        return false;
      
    
  }


    public function findContribuyente($id)
   {

      $model = CrearUsuarioJuridico::find()->where([
                    'id_contribuyente' => $id,
                      ])->All();

     //die(var_dump($model));
      return isset($model) ? $model : false;
  }

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

   public function existe($attribute, $params){

    $buscar = CrearUsuarioNatural::find()
                                   ->where([

                                    'naturaleza' => $this->naturaleza,
                                    'cedula' => $this->cedula,
                                    'tipo_naturaleza' => 0,
                                    'inactivo' => 0,
                                    ])->one();

     

                                   
  //die($hola);

      if ($buscar  != null){ 
                             
      $buscar2 = Afiliacion::find()
                                    ->where([

                                   
                                     'id_contribuyente' => $buscar->id_contribuyente,
                                  
                                     'estatus' => 0,
                                    ])->one();
                                //die(var_dump($buscar2));

    if ($buscar2  != null){
      $this->addError($attribute, Yii::t('frontend', 'This user already exists '));
    }

    
    }
 }

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