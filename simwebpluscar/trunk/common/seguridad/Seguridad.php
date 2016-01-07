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
 *  @file Seguridad.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 07/01/2016
 * 
 *  @class Seguridad
 *  @brief Modelo que genera digitos de seguridad . 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  
 *  randKey
 *  
 * 
 *  
 *
 *  @inherits
 *  
 */ 

namespace common\seguridad;

use Yii;
use yii\base\Model;
use common\models\Users;
use yii\db\ActiveRecord;


class Seguridad extends Model
{

   
  
    public function randKey($long=0)
    {

        $str = "0123456789abcdef";

        $key = null;
        $str = str_split($str);
        $start = 0;
        $limit = count($str)-1;
        for($x=0; $x<$long; $x++)
        {
            $key .= $str[rand($start, $limit)];
        }
         return $key;
     } 

   


     
  
}

 ?>