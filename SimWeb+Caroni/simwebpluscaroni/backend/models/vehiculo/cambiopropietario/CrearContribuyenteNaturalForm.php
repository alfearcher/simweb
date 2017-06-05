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
 *  @file CrearContribuyenteNaturalForm.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 08/04/2016
 *
 *  @class CrearContribuyenteNaturalForm
 *  @brief Modelo del formulario de datos basicos de persona natural
 *   @property
 *
 *
 *  @method
 *
 *  tableName
 *  rules
 *  attributeLabels
 * 
 *
 *  @inherits
 *
 */
namespace backend\models\vehiculo\cambiopropietario;

use Yii;
use yii\base\Model;

 
class CrearContribuyenteNaturalForm extends Model
{

        public $nombre;
        public $apellido;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contribuyentes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        
            [['nombre', 'apellido'],'required'],
            [['nombre'], 'match' , 'pattern' => "/[a-zA-Z]+/", 'message' => Yii::t('frontend', 'Color must have only letters')],
            [['apellido'], 'match' , 'pattern' => "/[a-zA-Z]+/", 'message' => Yii::t('frontend', 'Color must have only letters')],
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            

           
            'nombre' => Yii::t('frontend', 'Name'),
            'apellido' => Yii::t('frontend', 'Last Name'),
        ];
    }

    
    

    
}
