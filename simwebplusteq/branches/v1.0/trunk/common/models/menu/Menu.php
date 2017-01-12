<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *      All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *	> This library is free software; you can redistribute it and/or modify it under 
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *	> any later version.
 *      > 
 *	> This library is distributed in the hope that it will be usefull, 
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *	> for more details.
 *      > 
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**	
 *	@file Menu.php
 *	
 *	@author Ronny Jose Simosa Montoya
 * 
 *	@date 10-07-2015
 * 
 *      @class Menu
 *	@brief Clase contiene las reglas de negocios ( Etiquetas, validaciones y busqueda ).
 * 
 *  
 *  
 *  @property
 *  
 *  @method
 *  
 *  @inherits
 *  
 */

namespace common\models\menu;
error_reporting(0);

use Yii;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function getDb()
    {
        return Yii::$app->db;
    }
    
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'menu';
    }
 }