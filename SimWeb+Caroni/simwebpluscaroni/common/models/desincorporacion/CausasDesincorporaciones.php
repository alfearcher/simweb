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
 *  @file CausasDesincorporaciones.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 30-03-2016
 * 
 *  @class CausasDesincorporaciones
 *  @brief Clase que permite acceder a los datos de la tabla causas_desincorporaciones. 
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

namespace common\models\desincorporacion;

use Yii;

/**
 * This is the model class for table "parametros_niveles_catastro".
 *
 * @property string $id_parametro
 * @property string $descripcion
 * @property integer $inactivo
 */
class CausasDesincorporaciones extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'causas_desincorporaciones';
    }

    
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['inactivo'], 'integer'],
            [['descripcion'], 'string', 'max' => 45]
        ];
    }

    
    public function attributeLabels()
    {
        return [
            'causas_desincorporaciones' => 'Id Parametro',
            'descripcion' => 'Descripcion',
            'inactivo' => 'Inactivo',
        ];
    }
}
