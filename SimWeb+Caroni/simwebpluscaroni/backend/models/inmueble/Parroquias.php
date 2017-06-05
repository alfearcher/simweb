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
 *  @file Municipios.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class Municipios
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
 *  getMunicipios
 *  getParroquias
 *
 *  @inherits
 *  
 */ 

 /**
 * This is the model class for table "municipios".
 *
 * @property integer $estado
 * @property integer $municipio
 * @property string $codigo
 * @property string $nombre
 * @property string $catastro
 *
 * @property Estados $estado0
 * @property Parroquias[] $parroquias
 */
 
namespace backend\models\inmueble;

use Yii;


class Parroquias extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'parroquias';
    }

   

    public function getEstado0()
    {
        return $this->hasOne(Estados::className(), ['estado' => 'estado']);
    }

   
    public function getParroquias()
    {
        return $this->hasMany(Parroquias::className(), ['estado' => 'estado', 'municipio' => 'municipio']);
    }
}
