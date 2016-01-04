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
 *  @class RegistrarUsuario
 *  @brief Modelo para crear usuario Juridico. 
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
 *  email_existe
 *  username_existe
 *  
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
          [['naturaleza','cedula', 'tipo'],'required','message' => Yii::t('frontend', '{attribute} is required')]];
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

      $model = CrearUsuarioJuridico::find()->where([
                    'naturaleza' => $naturaleza,
                    'cedula' => $cedula,
                    'tipo' => $tipo,
                    'tipo_naturaleza' => 1])->All();

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
}

 ?>