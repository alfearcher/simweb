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
 *  @file VerificarTransaccionForm.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 18/05/2016
 * 
 *  @class VerificarTransaccionForm
 *  @brief Clase que contiene las rules para la verificacion final del proceso de asignacion de calcomania a contribuyente
 *  
 * 
 *  
 *  
 *  @property
 *  
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
namespace backend\models\vehiculo\calcomania\asignarcalcomaniacontribuyente;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\funcionario\calcomania\FuncionarioCalcomania;
use backend\models\funcionario\Funcionario;
use backend\models\vehiculo\calcomania\generarlote\LoteSearch;



/**
 * FuncionarioSearch la clase que contiene el metodo que realiza la busqueda de los funcionarios activos
 */
class VerificarTransaccionForm extends Model
{
    public $nro_calcomania;
    public $marca;
    public $placa;
    public $modelo;

    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['nro_calcomania', 'marca', 'placa', 'modelo'], 'required'],
        ]; 
    }

        // nombre de etiquetas
    public function attributeLabels()
    {
        return [
                'nro_calcomania' => Yii::t('backend', 'Nro de Calcomania'), 
                'marca' => Yii::t('backend', 'Marca'), 
                'modelo' => Yii::t('backend', 'Modelo'), 
                'placa' => Yii::t('backend', 'Placa'), 
        ];
    } 

  


   


    



   
}
