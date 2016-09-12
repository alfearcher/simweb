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
 *  @file AnularPatrocinadorPropagandaForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 12/09/2016
 * 
 *  @class AnularPatrocinadorPropagandaForm
 *  @brief Clase que contiene las rules para validacion de la anulacion de los patrocinadores de las propagandas
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
namespace frontend\models\propaganda\patrocinador;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\propaganda\Propaganda;
use common\models\solicitudescontribuyente\SolicitudesContribuyente;
use common\models\propaganda\patrocinador\PropagandasPatrocinadores;
use yii\db\Query;
use yii\data\SqlDataProvider;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class AnularPatrocinadorPropagandaForm extends Model
{
    




    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            //[[ 'ano_impositivo'], 'required'],
            
            ];
    } 

    /**
     * @inheritdoc
     */
   

    public function attributeLabels()
    {
        return [
               
                //'ano_impositivo' => Yii::t('frontend', 'Año Impositivo'), 
              
        ];      
    }

    public function attributeSlPropagandasPatrocinadores()
    {

        return [
            'nro_solicitud',
            'id_contribuyente',
            'id_impuesto',
            'id_patrocinador',
            'origen',
            'usuario',
            'fecha_hora',
            'estatus',
            'fecha_hora_proceso',
            'user_funcionario',
         

            
            
          
        ];
    }


    public function attributePropagandasPatrocinadores()
    {

        return [
            'id_contribuyente',
            'id_impuesto',
            'id_patrocinador',
            'estatus',
     
          
        ];
    }

       /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function busquedaRelacionPropagandaPatrocinador($idContribuyente)
    { 
   

          $dataProvider = new SqlDataProvider([
          
            'sql' => "SELECT * FROM propagandas_patrocinadores INNER JOIN propagandas ON propagandas_patrocinadores.id_impuesto = propagandas.id_impuesto INNER JOIN contribuyentes ON propagandas_patrocinadores.id_patrocinador = contribuyentes.id_contribuyente  WHERE propagandas_patrocinadores.id_contribuyente = '$idContribuyente'",
      
       
        ]);
        return $dataProvider;

    }

    public function busquedaPropaganda($anoImpo, $idContribuyente)
    {
     // die('llegue a search');

        $idContribuyente = yii::$app->user->identity->id_contribuyente;


        $query = Propaganda::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // die(var_dump($dataProvider)),
        ]);
        $query->where([
           'ano_impositivo' => $anoImpo,
           'id_contribuyente' => $idContribuyente,
           'inactivo' => 0,
            
            ])
        ->all();
        
        return $dataProvider;

        
    }

    public function verificarSolicitud($idConfig, $idPropaganda)
    {
        $buscar = SolicitudesContribuyente::find()
                                        ->where([ 
                                          'id_impuesto' => $idPropaganda,
                                          'id_config_solicitud' => $idConfig,
                                          'estatus' => 0,
                                        ])
                                      ->all();

            if($buscar == true){
                //die('encontro');
             return true;
            }else{
               // die('no encontro');
             return false;
            }
        
    }

    public function getClase($dato)
    {
       $datos = propaganda::find('clase_propaganda')
       ->where([
        'id_impuesto' => $dato,


        ])
       ->all();
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
