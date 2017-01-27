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
 *  @file DesincorporarPropagandaForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 11/08/2016
 * 
 *  @class DesincorporarPropagandaForm
 *  @brief Clase que contiene las rules para validacion del formulario de la modificacion de propaganda
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
 *  scenarios
 *  search
 *
 *  
 *
 *  @inherits
 *  
 */ 
namespace frontend\models\propaganda\desincorporarpropaganda;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\propaganda\Propaganda;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;




/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class DesincorporarPropagandaForm extends Model
{

    public $causa;
    public $observacion;
    //public $impuesto;



    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

             [['causa','observacion'], 'required'],
            
           //  [['cantidad_base', 'base_calculo','observacion','direccion' ,'clase_propaganda','cigarrillos', 'bebidas_alcoholicas', 'idioma'  ,'fecha_fin', 'cantidad_tiempo', 'tiempo', 'fecha_inicial', 'uso_propaganda', 'tipo_propaganda', 'medio_difusion', 'medio_transporte', 'id_sim'], 'default', 'value' => 0],

           //  [['alto','ancho'],'required','when' => function($model) {
           //                                                                  if ( $model->base_calculo == 2 ) {
           //                                                                      return true; }
           //                                                                  }],
           
           // [['alto','ancho', 'profundidad'],'required','when' => function($model) {
           //                                                                  if ( $model->base_calculo == 12 ) {
           //                                                                      return true; }
           //                                                                  }],

           //  [['unidad'],'required','when' => function($model) {
           //                                                              if ( $model->base_calculo != 2 and $model->base_calculo != 12  ) {
           //                                                                      return true; }
           //                                                                  }],

          
            
           //  [['unidad', 'cantidad_tiempo'], 'integer'],

             ]; 
    } 

    /**
     * @inheritdoc
     */
   

    public function attributeLabels()
    {
        return [
                'nombre_propaganda' => Yii::t('frontend', 'Nombre de la propaganda'), 
                'ano_impositivo' => Yii::t('frontend', 'Año Impositivo'), 
                'clase_propaganda' => Yii::t('frontend', 'Clase de Propaganda'),
                'uso_propaganda' => Yii::t('frontend', 'Uso de Propaganda'),
                'fecha_desde' => Yii::t('frontend', 'Fecha de Inscripcion'),
                'cantidad_base' => Yii::t('frontend', 'Cantidad Base'),
                'cigarros' => Yii::t('frontend', 'Cigarrillos'),
                'cantidad_tiempo' => Yii::t('frontend', 'Cantidad de Tiempo'),
                'base_calculo' => Yii::t('frontend', 'Base de Calculo'),
                'bebidas_alcoholicas' => Yii::t('frontend', 'Bebidas Alcoholicas'),
                'id_tiempo' => Yii::t('frontend', 'Tiempo'),
                'idioma' => Yii::t('frontend', 'Idiomas'),
                'fecha_fin' => Yii::t('frontend', 'Fecha Fin  '),
                'id_sim' => Yii::t('frontend', 'Id Sim'),
                'tipo_propaganda' => Yii::t('frontend', 'Tipo de Propaganda'),
                'medio_difusion' => Yii::t('frontend', 'Medio de Difusion'),
                'medio_transporte' => Yii::t('frontend', 'Medio de Transporte'),
                'direccion' => Yii::t('frontend', 'Direccion'),
                'observacion' => Yii::t('frontend', 'Mensaje de la Propaganda'),
                'unidad' => Yii::t('frontend', 'Unidad'),
        ];      
    }

    public function attributeSlPropagandas()
    {

        return [
            'nombre_propaganda',
            'id_impuesto',
            'nro_solicitud',
            'id_contribuyente',
            'ano_impositivo',
            'direccion',
            'id_cp',
            'clase_propaganda',
            'tipo_propaganda',
            'uso_propaganda',
            'medio_difusion',
            'medio_transporte',
            'fecha_desde',
            'cantidad_tiempo',
            'id_tiempo',
            'inactivo',
            'id_sim',
            'cantidad_base',
            'base_calculo',
            'cigarros',
            'bebidas_alcoholicas',
            'cantidad_propagandas',
            'planilla',
            'idioma',
            'observacion',
            'fecha_fin',
            'fecha_guardado',
            'fecha_hora',
            'usuario',
            'user_funcionario',
            'fecha_hora_proceso',
            'estatus',
            'alto',
            'ancho',
            'profundidad',
            'unidad',
            
          
        ];
    }


    public function attributePropagandas()
    {

        return [
            'nombre_propaganda',
            'id_impuesto',
            'id_contribuyente',
            'ano_impositivo',
            'direccion',
            'id_cp',
            'clase_propaganda',
            'tipo_propaganda',
            'uso_propaganda',
            'medio_difusion',
            'medio_transporte',
            'fecha_desde',
            'cantidad_tiempo',
            'id_tiempo',
            'inactivo',
            'id_sim',
            'cantidad_base',
            'base_calculo',
            'cigarros',
            'bebidas_alcoholicas',
            'cantidad_propagandas',
            'planilla',
            'idioma',
            'observacion',
            'fecha_fin',
            'fecha_guardado',
            'alto',
            'ancho',
            'profundidad',
            'unidad',
            
          
        ];
    }

        public function attributeSldesincorporaciones()
    {


    return [  

              'nro_solicitud',
              'id_contribuyente',
              'id_impuesto',
              'impuesto',
              'causa_desincorporacion',
              'observacion',
              'usuario',
              'fecha_hora',
              'inactivo',
              
              

              ];
      
    }

       /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPropaganda()
    { 
       // die('llegue a search');

        $idContribuyente = yii::$app->user->identity->id_contribuyente;


        $query = Propaganda::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->where([
            'id_contribuyente' =>  $idContribuyente,
            'inactivo' => 0,
            ]);
        
        return $dataProvider;

       
    }

    public function busquedaPropaganda($idPropaganda, $idContribuyente)
    {
        //
        $busqueda = Propaganda::find()
                                ->where([
                                    'id_contribuyente' => $idContribuyente,
                                    'id_impuesto' => $idPropaganda,
                                    'inactivo' => 0,

                                
                               
                                ])
                                ->one();
                                //die(var_dump($busqueda));

            if ($busqueda == true){
                return $busqueda;
            }else{
                return false;
            }
    }

    public function verificarSolicitudPropaganda($idPropaganda, $idConfig)
    {
        $buscar = SolicitudesContribuyente::find()
                                        ->where([ 
                                          'id_impuesto' => $idPropaganda,
                                          'id_config_solicitud' => $idConfig,
                                          'estatus' => 0,
                                        ])
                                      ->all();

            if($buscar == true){
             return true;
            }else{
             return false;
            }
        
    }

       public function validarCheck($postCheck)
    {
        if (count($postCheck) > 0){

            return true;
        }else{
            return false;
        }
    }



   

  
}
