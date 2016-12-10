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
 *  @file ConfiguracionTiposSolicitudes.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 29-02-2016
 * 
 *  @class ConfiguracionTiposSolicitudes
 *  @brief Clase que permite acceder a los datos de la tabla config_tipos_solicitudes. 
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

namespace frontend\models\inmueble;

use Yii;

/**
 * This is the model class for table "parametros_niveles_catastro".
 *
 * @property string $id_tipo_solicitud
 * @property string $impuesto
 * @property string $descripcion
 * @property string $cont_mostrar
 * @property string $general
 * @property integer $controlador
 * @property integer $inactivo
 *
 */
class ConfiguracionTiposSolicitudes extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return 'config_tipos_solicitudes';
    }

    
    public function rules()
    {
        return [
            [['inactivo', 'impuesto', 'cont_mostrar', 'general', 'id_tipo_solicitud'], 'integer'],
            [['descripcion', 'controlador'], 'string', 'max' => 45]
        ];
    }

    
    public function attributeLabels()
    {
        return [
            'id_tipo_solicitud' => 'Id Tipo Solicitud',
            'impuesto' => 'Impuesto',
            'descripcion' => 'Descripcion',
            'cont_mostrar' => 'Cont Mostrar',
            'general' => 'General',
            'controlador' => 'Controlador',
            'inactivo' => 'Inactivo',
        ];
    }
}
