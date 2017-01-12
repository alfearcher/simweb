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
 *  @file TelefonoCodigo.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 22/07/2015
 * 
 *  @class TelefonoCodigo
 *  @brief Clase que permite la accesibilidad a la tabla telefonos_codigos
 *  @brief donde estan almacenados los prefijos correspondientes
 *  @property
 *
 *  
 *  @method
 *    
 *  @inherits
 *  
 */

namespace frontend\models\usuario;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "telefonos_codigos".
 *
 * @property string $codigo
 * @property string $descripcion
 * @property integer $inactivo
 * @property integer $is_celular
 */
class TelefonoCodigo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telefonos_codigos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'descripcion'], 'required'],
            [['inactivo'], 'integer'],
            [['codigo'], 'string', 'max' => 4],
            [['descripcion'], 'string', 'max' => 20],
            [['codigo'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codigo' => Yii::t('frontend', 'Code'),
            'descripcion' => Yii::t('frontend', 'Descripcion'),
            'inactivo' => Yii::t('frontend', 'Inactivo'),
        ];
    }

    // @param $is_celular, integer corto, indica si es celular(1) u oficina(0).
    // @return, array, lista de prefijos telefonicos.

    public function getListaTelefonoCodigo($is_celular)
    {
        $listaTelefonoCodigo = TelefonoCodigo::find()->where(['inactivo' =>0,'is_celular' =>$is_celular])->AsArray()->all();
        return ArrayHelper::map($listaTelefonoCodigo,'codigo', 'codigo');
    }
}
