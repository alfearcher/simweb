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
 *  @file GrupoSubnivel.php
 *
 *  @author Manuel Alejandro Zapata Canelon
 *
 *  @date 29/02/2016
 *
 *  @class GrupoSubnivel
 *  @brief  Modelo que instancia la conexion a la base de datos para buscar datos de la tabla grupos_subniveles.
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
use backend\models\tasa\Tasa;

class GrupoSubnivel extends \yii\db\ActiveRecord
{
    public $grupo;

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
        return 'grupos_subniveles';
    }



    /**
     * Relacion con la entidada "Tarifas-Vehiculos"
     */
    public function getTarifaVehiculo()
    {
        return $this->hasMany(TarifaVehiculo::className(), ['clase_vehiculo' => 'clase_vehiculo']);
    }

    public function getGrupoSubnivel($grupo)
    {
      //die($grupo);
       $datos = GrupoSubnivel::find()
       ->where([
        'grupo_subnivel' => $grupo,


        ])
       ->all();
       //die(var_dump($datos));

       return $datos[0]->descripcion;
    }



    /**
     * Relacion eon la entidad "varios"
     * @return Tasa
     */
    public function getTasa()
    {
        return $this->hasOne(Tasa::className(), ['grupo_subnivel' => 'grupo_subnivel']);
    }


}
