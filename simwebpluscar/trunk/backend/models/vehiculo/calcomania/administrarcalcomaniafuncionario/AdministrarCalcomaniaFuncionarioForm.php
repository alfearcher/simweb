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
 *  @file AdministrarCalcomaniaFuncionarioForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 03/05/2016
 * 
 *  @class AdministrarCalcomaniaFuncionarioForm
 *  @brief Clase que contiene las rules para la deshabilitacion de los lotes de calcomania
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
namespace backend\models\vehiculo\calcomania\administrarcalcomaniafuncionario;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\generarlote\LoteSearch;
use common\models\calcomania\calcomaniamodelo\Calcomania;




/**
 * FuncionarioSearch la clase que contiene el metodo que realiza la busqueda de los funcionarios activos
 */
class AdministrarCalcomaniaFuncionarioForm extends Model
{
    public $causa;
    public $observacion;
    
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             
        ]; 
    } 

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

   
        /**
     * [search description] metodo que realiza la busqueda en la tabla lote_calcomania
     * @return [type] [description] devuelve el modelo de la tabla si lo consigue, sino devuelve false
     */
    
    public function search()
    { 
  
        $query = LoteSearch::find();

                                
                             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           
        ]);
        $query->where([
            'ano_impositivo' => date('Y'),
            'inactivo' => 0,
            ])
  
        ->all();
       // die(var_dump($query));

        
        return $dataProvider;

       
    }

     public function validarCheck($postCheck)
    {
        //die($postCheck);
        
        if (count($postCheck) > 0){
            //die('lo selecciono');
            return true;
        }else{
            return false;
        }
    }

    public function buscarCalcomania($calcomanias)
    {
        $buscar = Calcomania::find()
                            ->where([
                                'nro_calcomania' => $calcomanias,
                                'ano_impositivo' => date('Y'),
                                'estatus' => 0,


                                ])
                            ->all();
            if($buscar == true){
                return true;
            }else{
                return false;
            }

    }

    public function attributeCalcomania()
    {
        return [
              'nro_calcomania',
              'fecha_creacion_lote',
              'ano_impositivo',
              'usuario_creacion_lote',
              'usuario_funcionario',
              'entregado',
              'estatus',
              'punto',
              'fecha_entrega',
              'id_vehiculo',
              'id_contribuyente',
              'placa',
              'planilla',
        ];
    }

    public function buscarLogin($id)
    {
        $buscar = Funcionario::find()
                            ->where([
                                'id_funcionario' => $id,
                                'status_funcionario' => 0,
                                   ])
                             ->all();

            if($buscar == true){
                return $buscar;
            }else{
                return false;
            }

    }

    public function busquedaCalcomaniaAsignada($anoImpositivo, $nroCalcomania)
    {
      $buscar =  calcomania::find()
                          ->where([
                          'ano_impositivo' => $anoImpositivo,
                          'nro_calcomania' => $nroCalcomania,

                          ])
                          ->andWhere([
                            'in', 'estatus', [0,1]

                            ])
                          ->all();
            
            if($buscar == true)
            {
              return true;
            }else{
              return false;
            }
    }



    

    



   
}
