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
 *  @file DeshabilitarForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 25/04/2016
 * 
 *  @class DeshabilitarForm
 *  @brief Clase que contiene las rules para la verificacion de la deshabilitacion de los funcionarios
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
namespace backend\models\vehiculo\calcomania\deshabilitarfuncionario;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\LoteCalcomaniaForm;


/**
 * FuncionarioSearch la clase que contiene el metodo que realiza la busqueda de los funcionarios activos
 */
class DeshabilitarForm extends Model
{
    public $id_funcionario;
    
   
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

     public function verificarFuncionarioLote($login)
     {
      //die(var_dump($login).'hola');
         $buscar = LoteCalcomaniaForm::find()
                                ->where([
                                'usuario' => $login,
                                'inactivo' => 0,

                                    ])
                                ->all();

                if($buscar == true){
                   // die('consiguio');
                    return true;
                }else{
                    return false;
                }
     }

    public function busqueda($id)
    {   
        //die(var_dump($id).'hla');
        $buscar = Funcionario::find()
                            ->where([
                                'id_funcionario' => $id,
                                'status_funcionario' => 0,

                                ])
                            ->all();
            if ($buscar == true){
                return $buscar;
            }else{
                return false;
            }
            
    }

   
  

    



   
}
