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
 *  findContribuyente
 *  attributeAfiliacion
 *  attributeContribuyentes
 *
 *  @inherits
 *  
 */ 

namespace frontend\models\usuario;

use Yii;
use yii\base\Model;
use frontend\models\usuario\CrearUsuarioJuridicoForm;
use yii\data\ActiveDataProvider;


class CrearUsuarioJuridicoForm extends CrearUsuarioJuridico{
  
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
       * [rules description] reglas de validacion del formulario para crear usuario juridico
       * @return [type] [description]
       */
      public function rules()
      {
        return [
          [['naturaleza','cedula', 'tipo'],'required','message' => Yii::t('frontend', '{attribute} is required')],
          [['tipo_naturaleza'],'default', 'value' => 1],
          [['cedula', 'tipo'], 'integer'],
          ['cedula', 'integer'],
          // [['cedula'], 'validarLongitud'],
       //   ['cedula', 'string', 'max' => 2],

         
           // [['naturaleza','cedula','tipo'x],'unique', 'message' => 'Datos repetidos en la base de datos'],
        ];
          //['capital_new', 'format', Yii::$app->formatted->asDecimal($model->)]
      
      }

      public function validarLongitud($attribute, $params)
      {

      //  $longitud = strlen($this->naturaleza.$this->cedula.$this->tipo);


      // // die($longitud));

      //   if ($longitud > 10){
      //     $this->addError($attribute, Yii::t('frontend', 'The rif must not have more than 10 characters'));
       
      //   }
      //   
           if (!preg_match('/^[0-9]{8}$/', $this->$attribute)) {
        $this->addError($attribute, 'must contain exactly 8 digits.');
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
       * [findRif description] metodo que busca en la tabla contribuyente el rif ingresado por el usuario
       * @param  [type] $naturaleza [description] naturaleza del rif
       * @param  [type] $cedula     [description] cedula
       * @param  [type] $tipo       [description] tipo 
       * @return [type]             [description] retorna una respuesta con los datos solicitados
       */
      public function findRif($naturaleza, $cedula, $tipo)
      {

          $model = CrearUsuarioJuridico::find()->where([
                    'naturaleza' => $naturaleza,
                    'cedula' => $cedula,
                    'tipo' => $tipo,
                    'tipo_naturaleza' => 1])->All();

     
      return isset($model) ? $model : false;
      }
      /**
       * [findAfiliacion description] metodo que busca registros en la tabla afiliacion
       * @param  [type] $idContribuyente [description] parametro para realizar la busqueda en la tabla
       * @return [type]                  [description] retorna una respuesta con los datos solicitados
       */
      public function findAfiliacion($idContribuyente)
      {

          $model = Afiliacion::findOne([
                    
                    'id_contribuyente' => $idContribuyente,
                    
                    ]);

    
          return isset($model) ? $model : false;
      }

      /**
       * [obtenerDataProviderRif description] metodo que obtiene datos de la tabla attributeContribuyentes
       * @param  [type] $naturalezaLocal [description] naturaleza del ususario
       * @param  [type] $cedulaLocal     [description] cedula del usuario
       * @param  [type] $tipoLocal       [description] tipo de la cedula del usuario
       * @return [type]                  [description] retorna una respuesta con los datos solicitados
       */
      public function obtenerDataProviderRif($naturalezaLocal, $cedulaLocal, $tipoLocal)
      {
      
          if ( trim($naturalezaLocal) != '' && $cedulaLocal > 0 ) {
              if ( strlen($naturalezaLocal) == 1 ) {
                  $query = CrearUsuarioJuridico::find();
                  $dataProvider = new ActiveDataProvider([
                      'query' => $query,
                      ]);
                  $query->where('naturaleza =:naturaleza and cedula =:cedula and tipo =:tipo and tipo_naturaleza =:tipo_naturaleza',[':naturaleza' => $naturalezaLocal,
                                    ':cedula' => $cedulaLocal,
                                    ':tipo' => $tipoLocal,
                                    ':tipo_naturaleza' => 1
                                     ])->all();

                  return $dataProvider;
              }
          }
          return false;
      
    
      }

      /**
       * [findContribuyente description] metodo que busca el id del contribuyente en la tabla contribuyentes
       * @param  [type] $id [description] id del contribuyente para realizar la busqueda en la tabla
       * @return [type]     [description] retorna una respuesta con el id del contribuyente en caso de encontrarlo
       */
      public function findContribuyente($id)
      {

          $model = CrearUsuarioJuridico::find()->where([
                    'id_contribuyente' => $id,
                      ])->All();

     
      return isset($model) ? $model : false;
      }
      /**
       * [attributeAfiliacion description] metodo que contiene los nombres de los campos de la tabla afiliacion
       * 
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