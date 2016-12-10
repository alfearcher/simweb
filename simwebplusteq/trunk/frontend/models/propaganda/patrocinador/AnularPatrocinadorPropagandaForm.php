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
use common\models\contribuyente\ContribuyenteBase;

/**
 * InmueblesSearch represents the model behind the search form about `backend\models\Inmuebles`.
 */
class AnularPatrocinadorPropagandaForm extends Model
{

    public $causa;
    public $observacion;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [[ 'causa', 'observacion'], 'required'],
            
            ];
    } 

    /**
     * @inheritdoc
     */
   

    public function attributeLabels()
    {
        return [
               
                'causa' => Yii::t('frontend', 'Causa'), 
                'observacion' => Yii::t('frontend', 'Observacion'),
              
        ];      
    }

    public function attributeSlAnulacionesPatrocinadores()
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
            'estatus',
            'user_funcionario',
            'fecha_hora_proceso',
            'fecha_funcionario',
            'origen',
            'id_patrocinador',
         

            
            
          
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
        
            $find = PropagandasPatrocinadores::find()
                                            ->where([
                                            'propagandas_patrocinadores.id_contribuyente' => $idContribuyente,
                                            'estatus' => 0,
                                            ])
                                            ->joinWith('propaganda')
                                            ->joinWith('contribuyente');
                                           
                                     
                                         
                        return $find;

       

    }

    public function getDataProviderRelacion($idContribuyente)
    {

        //die('llegue');
    

        $query = self::busquedaRelacionPropagandaPatrocinador($idContribuyente);

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->all();
                
           
            return $dataProvider;

        
    }

    public function verificarSolicitudPatrocinio($idConfig, $idImpuesto)
    {
        $buscar = SolicitudesContribuyente::find()
                                        ->where([ 
                                          'id_impuesto' => $idImpuesto,
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

    public function busquedaIdImpuesto($idPropaganda)
    {


        $find = PropagandasPatrocinadores::find()
                                            ->where([ 'IN' , 'id_propaganda_patrocinador', $idPropaganda])
                                            ->asArray()
                                            ->all();

                                            //die(var_dump($find));

                if($find == true){
                    return $find;
                }else{
                    return false;
                }
    }





      public function validarCheck($postCheck)
    {

        if (count($postCheck) > 0){

            return true;
        }else{
         // die('no selecciono nada');
            return false;
        }
    }





   

  
}
