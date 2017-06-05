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
 *  @file BusquedaMultipleReportesForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 13/10/2016
 * 
 *  @class BusquedaMultipleReportesForm
 *  @brief Clase contiene las rules y metodos para la busqueda multiple de reportes de tasas
 *  
 * 
 *  
 *  
 * @property
 *
 *  
 *  @method
 * rules
 * attributeLabels
 *
 *  
 *  @inherits
 *  
 */
namespace backend\models\tasas\reportes;

use Yii;
use yii\base\Model;
use common\models\calcomania\calcomaniamodelo\Calcomania;
use yii\data\ActiveDataProvider;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use backend\models\tasa\Tasa;




class BusquedaMultipleReportesForm extends Model
{



 
    public $ano_impositivo;
    public $ano_impositivo2;
     public $ano_impositivo3;
    public $impuesto;
    public $impuesto2;
    public $codigo;
    
    


    const SCENARIO_SEARCH_ANO = 'search_ano';
    const SCENARIO_SEARCH_ANO_IMPUESTO = 'search_ano_impuesto';
    const SCENARIO_SEARCH_ANO_IMPUESTO_CODIGO = 'search_ano_impuesto_codigo';
       


    public function scenarios()
        {
            //bypass scenarios() implementation in the parent class
            //return Model::scenarios();
            return [
                self::SCENARIO_SEARCH_ANO => [
                                'ano_impositivo',
                               
                ],
                
                self::SCENARIO_SEARCH_ANO_IMPUESTO => [
                                'ano_impositivo2',
                                'impuesto',
                                
                ],


                self::SCENARIO_SEARCH_ANO_IMPUESTO_CODIGO => [
                                'ano_impositivo3',
                                'impuesto2',
                                'codigo'
                                
                ],
              
               
            ];
        }
 
    
    public function rules()
    {   //validaciones requeridas para el formulario de registro de usuarios  

     

        return [
            [['ano_impositivo'],
                  'required', 'on' => 'search_ano', 'message' => Yii::t('backend', '{attribute} is required')],
             
             [['ano_impositivo2', 'impuesto'],
                  'required', 'on' => 'search_ano_impuesto', 'message' => Yii::t('backend', '{attribute} is required')],

             [['ano_impositivo3', 'impuesto2', 'codigo'],
                  'required', 'on' => 'search_ano_impuesto_codigo', 'message' => Yii::t('backend', '{attribute} is required')],
            

           
            
        ];
    } 

    
      
    
    
    // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'ano_impositivo' => Yii::t('backend', 'Año Impositivo'),
                 'ano_impositivo2' => Yii::t('backend', 'Año Impositivo'),
                  'ano_impositivo3' => Yii::t('backend', 'Año Impositivo'),
                'impuesto' => Yii::t('backend', 'Impuesto'), 
                 'impuesto2' => Yii::t('backend', 'Impuesto'), 
                'codigo' => Yii::t('backend', 'Codigo'), 
                

        ];
    }
    



    /**
     * [relacionReporteAno description] metodo que realiza la busqueda de la relacion
     * @param  [type] $modelo [description] modelo que contiene la informacion
     * @return [type]         [description] redirecciona al metodo que renderiza el gridview con el dataprovider
     */
    public function relacionReporteAno($ano){

     //   die(var_dump($modelo));

        $busqueda = Tasa::find()
                        ->where([
                           

                            'varios.ano_impositivo' => $ano,
                          //  die($modelo->ano_impositivo),
                           
                            'varios.inactivo' => 0,


                            ])
                        ->joinWith('codigoContable')
                        ->joinWith('impuestos')
                        ->joinWith('grupoSubNivel')
                        ->joinWith('tipoRango');

                       // die(var_dump($busqueda));

                        return $busqueda;
    }
    /**
     * [busquedaReporteAno description] metodo que redirecciona a otro metodo que realiza la busqueda de la relacion de la tabla tasas 
     * @param  [type] $modelo [description] datos para realizar la busqueda relacion
     * @return [type]         [description] retorna la realcion
     */
    public function busquedaReporteAno($ano){
        //die(var_dump($modelo));
               $query = self::relacionReporteAno($ano);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
        $query
        ->all();
         
          
        return $dataProvider;
    }


        /**
     * [relacionReporteAnoImpuesto description] metodo que realiza la busqueda de la relacion
     * @param  [type] $ano [description] modelo que contiene la informacion
     * @param  [type] $impuesto [description] modelo que contiene la informacion
     * @return [type]         [description] redirecciona al metodo que renderiza el gridview con el dataprovider
     */
    public function relacionReporteAnoImpuesto($ano, $impuesto){

     //   die(var_dump($modelo));

        $busqueda = Tasa::find()
                        ->where([
                           

                            'varios.ano_impositivo' => $ano,
                            'varios.impuesto' => $impuesto,
                          //  die($modelo->ano_impositivo),
                           
                            'varios.inactivo' => 0,


                            ])
                        ->joinWith('codigoContable')
                        ->joinWith('impuestos')
                        ->joinWith('grupoSubNivel')
                        ->joinWith('tipoRango');

                       // die(var_dump($busqueda));

                        return $busqueda;
    }
    /**
     * [busquedaReporteAnoImpuesto description] metodo que redirecciona a otro metodo que realiza la busqueda de la relacion de la tabla tasas 
    * @param  [type] $ano [description] modelo que contiene la informacion
     * @param  [type] $impuesto [description] modelo que contiene la informacion
     * @return [type]         [description] retorna la realcion
     */
    public function busquedaReporteAnoImpuesto($ano, $impuesto){
        //die(var_dump($modelo));
               $query = self::relacionReporteAnoImpuesto($ano, $impuesto);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
        $query
        ->all();
         
          
        return $dataProvider;
    }


            /**
     * [relacionReporteAnoImpuesto description] metodo que realiza la busqueda de la relacion
     * @param  [type] $ano [description] modelo que contiene la informacion
     * @param  [type] $impuesto [description] modelo que contiene la informacion
      * @param  [type] $codigo [description] modelo que contiene la informacion
     * @return [type]         [description] redirecciona al metodo que renderiza el gridview con el dataprovider
     */
    public function relacionReporteAnoImpuestoCodigo($ano, $impuesto, $codigo){

     //   die(var_dump($modelo));

        $busqueda = Tasa::find()
                        ->where([
                           

                            'varios.ano_impositivo' => $ano,
                            'varios.impuesto' => $impuesto,
                            'varios.codigo' => $codigo,
                          //  die($modelo->ano_impositivo),
                           
                            'varios.inactivo' => 0,


                            ])
                        ->joinWith('codigoContable')
                        ->joinWith('impuestos')
                        ->joinWith('grupoSubNivel')
                        ->joinWith('tipoRango');

                       // die(var_dump($busqueda));

                        return $busqueda;
    }
    /**
     * [busquedaReporteAnoImpuestoCodigo description] metodo que redirecciona a otro metodo que realiza la busqueda de la relacion de la tabla tasas 
    * @param  [type] $ano [description] modelo que contiene la informacion
     * @param  [type] $impuesto [description] modelo que contiene la informacion
     * @param  [type] $codigo [description] modelo que contiene la informacion
     * @return [type]         [description] retorna la realcion
     */
    public function busquedaReporteAnoImpuestoCodigo($ano, $impuesto, $codigo){
        //die(var_dump($modelo));
               $query = self::relacionReporteAnoImpuestoCodigo($ano, $impuesto,$codigo);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
        ]);
        $query
        ->all();
         
          
        return $dataProvider;
    }

   
    
    


   



    
      
    
    
    
   

    }