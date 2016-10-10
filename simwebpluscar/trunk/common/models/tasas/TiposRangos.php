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
 *  @file TiposRangos.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 10/10/2016
 *
 *  @class TiposRangos
 *  @brief  Modelo que instancia la conexion a la base de datos para buscar datos de la tabla tipos_rangos.
 *
 *
 *
 *  @property
 *
 *
 *  @method
 *  rules
 *  attributeLabels
 *  scenarios
 *
 *
 *  @inherits
 *
 */

namespace common\models\tasas;

use Yii;
use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculo;

class TiposRangos extends \yii\db\ActiveRecord
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
        return 'tipos_rangos';
    }



    /**
     * Relacion con la entidada "Tarifas-Vehiculos"
     */
    public function getTarifaVehiculo()
    {
        return $this->hasMany(TarifaVehiculo::className(), ['clase_vehiculo' => 'clase_vehiculo']);
    }



}
