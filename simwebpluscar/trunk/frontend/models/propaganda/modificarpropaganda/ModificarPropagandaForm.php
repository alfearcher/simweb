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
 *  @file ModificarPorpagandaForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 19/07/2016
 * 
 *  @class ModificarPropagandaForm
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
namespace frontend\models\propaganda\modificarpropaganda;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\propaganda\PropagandaForm;



/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class ModificarPropagandaForm extends Model
{
    public $nombre_propaganda;
    public $ano_impositivo;
    public $clase_propaganda;
    public $uso_propaganda;
    public $fecha_inicial;
    public $cantidad_base;
    public $cigarrillos;
    public $cantidad_tiempo;
    public $base_calculo;
    public $bebidas_alcoholicas;
    public $tiempo; 
    public $idioma;
    public $fecha_fin;
    public $id_sim;
    public $tipo_propaganda;
    public $materiales;
    public $medio_transporte;
    public $direccion;
    public $observacion;
    public $unidad;
    public $alto;
    public $ancho;
    public $profundidad; 



    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['nombre_propaganda','cantidad_base', 'base_calculo','observacion','direccion' ,'clase_propaganda', 'fecha_fin', 'cantidad_tiempo', 'tiempo', 'fecha_inicial', 'uso_propaganda', 'tipo_propaganda'], 'required'],
            
            [['cantidad_base', 'base_calculo','observacion','direccion' ,'clase_propaganda','cigarrillos', 'bebidas_alcoholicas', 'idioma'  ,'fecha_fin', 'cantidad_tiempo', 'tiempo', 'fecha_inicial', 'uso_propaganda', 'tipo_propaganda', 'materiales', 'medio_transporte', 'id_sim'], 'default', 'value' => 0],

            [['alto','ancho'],'required','when' => function($model) {
                                                                            if ( $model->base_calculo == 2 ) {
                                                                                return true; }
                                                                            }],
           
           [['alto','ancho', 'profundidad'],'required','when' => function($model) {
                                                                            if ( $model->base_calculo == 12 ) {
                                                                                return true; }
                                                                            }],

            [['unidad'],'required','when' => function($model) {
                                                                        if ( $model->base_calculo != 2 and $model->base_calculo != 12  ) {
                                                                                return true; }
                                                                            }],

          
            
            [['unidad', 'cantidad_tiempo'], 'integer'],

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
                'fecha_inicial' => Yii::t('frontend', 'Fecha de Inscripcion'),
                'cantidad_base' => Yii::t('frontend', 'Cantidad Base'),
                'cigarrillos' => Yii::t('frontend', 'Cigarrillos'),
                'cantidad_tiempo' => Yii::t('frontend', 'Cantidad de Tiempo'),
                'base_calculo' => Yii::t('frontend', 'Base de Calculo'),
                'bebidas_alcoholicas' => Yii::t('frontend', 'Bebidas Alcoholicas'),
                'tiempo' => Yii::t('frontend', 'Tiempo'),
                'idioma' => Yii::t('frontend', 'Idiomas'),
                'fecha_fin' => Yii::t('frontend', 'Fecha Fin  '),
                'id_sim' => Yii::t('frontend', 'Id Sim'),
                'tipo_propaganda' => Yii::t('frontend', 'Tipo de Propaganda'),
                'material' => Yii::t('frontend', 'Medio de Difusion'),
                'medio_transporte' => Yii::t('frontend', 'Medio de Transporte'),
                'direccion' => Yii::t('frontend', 'Direccion'),
                'observacion' => Yii::t('frontend', 'Mensaje de la Propaganda'),
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
            
          
        ];
    }

       /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search()
    { 
       // die('llegue a search');

        $idContribuyente = yii::$app->user->identity->id_contribuyente;


        $query = PropagandaForm::find();

                                
                             
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
        $busqueda = PropagandaForm::find()
                                ->where([
                                    'id_contribuyente' => $idContribuyente,
                                    'id_impuesto' => $idPropaganda,
                                    'inactivo' => 0,

                                
                               
                                ])
                                ->all();

            if ($busqueda == true){
                return $busqueda;
            }else{
                return false;
            }
    }


   

  
}
