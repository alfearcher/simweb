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
 *  @file PreguntasSeguridad.php
 *  
 *  @author Alvaro Jose Fernandez Archer
 * 
 *  @date 17-06-2015
 * 
 *  @class PreguntasSeguridad
 *  @brief Clase que permite establecer la conexion con la base de datos y tomar los datos de la
 *  tabla preguntas_seguridad. 
 * 
 *  
 * 
 *  
 *  
 *  @property
 *
 *  
 *  @method
 *  rules
 *  attributeLabels
 *  tableName
 *  
 *
 *  @inherits
 *  
 */ 
namespace backend\models;

use Yii;

/**
 * This is the model class for table "preguntas_seguridad".
 *
 * @property integer $id_pregunta
 * @property string $pregunta
 * @property integer $estatus
 * @property string $respuesta
 */
class PreguntasSeguridad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preguntas_seguridad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pregunta', 'estatus', 'respuesta'], 'required'],
            [['estatus'], 'integer'],
            [['pregunta', 'respuesta'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_pregunta' => 'Id Pregunta',
            'pregunta' => 'Pregunta',
            'estatus' => 'Estatus',
            'respuesta' => 'Respuesta',
        ];
    }
}
