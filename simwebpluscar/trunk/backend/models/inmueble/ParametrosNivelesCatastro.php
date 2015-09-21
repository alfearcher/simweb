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
 *  @file ParametrosNivelesCatastro.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-08-2015
 * 
 *  @class ParametrosNivelesCatastro
 *  @brief Clase que permite acceder a los datos de la tabla municipios. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  tableName
 *  rules
 *  attributeLabels
 *
 *  @inherits
 *  
 */ 

namespace backend\models\inmueble;

use Yii;

/**
 * This is the model class for table "parametros_niveles_catastro".
 *
 * @property string $id_parametro
 * @property string $codigo
 * @property string $descripcion
 * @property string $desde
 * @property string $hasta
 * @property integer $inactivo
 */
class ParametrosNivelesCatastro extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'parametros_niveles_catastro';
    }

    
    public function rules()
    {
        return [
            [['codigo', 'descripcion', 'desde', 'hasta'], 'required'],
            [['inactivo'], 'integer'],
            [['codigo', 'desde', 'hasta'], 'string', 'max' => 2],
            [['descripcion'], 'string', 'max' => 45]
        ];
    }

    
    public function attributeLabels()
    {
        return [
            'id_parametro' => 'Id Parametro',
            'codigo' => 'Codigo',
            'descripcion' => 'Descripcion',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'inactivo' => 'Inactivo',
        ];
    }
}
