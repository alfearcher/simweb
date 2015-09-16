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
 *  @file VehiculosCambioPlacaForm.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 03-08-2015
 * 
 *  @class VehiculosCambioPlacaForm
 *  @brief Modelo del formulario de inscripcion de vehiculos, en el estan las validaciones y textos necesarios
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
namespace backend\models\vehiculo;

use Yii;

/**
 * This is the model class for table "vehiculos".
 *
 * @property string $id_vehiculo
 * @property string $id_contribuyente
 * @property string $placa
 * @property string $marca
 * @property string $modelo
 * @property string $color
 * @property string $uso_vehiculo
 * @property double $precio_inicial
 * @property string $fecha_inicio
 * @property string $ano_compra
 * @property string $ano_vehiculo
 * @property string $no_ejes
 * @property integer $liquidado
 * @property integer $status_vehiculo
 * @property double $exceso_cap
 * @property string $medida_cap
 * @property double $capacidad
 * @property string $nro_puestos
 * @property double $peso
 * @property string $clase_vehiculo
 * @property string $tipo_vehiculo
 * @property string $serial_motor
 * @property string $serial_carroceria
 * @property string $nro_calcomania
 */
class VehiculosCambioPlacaForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vehiculos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['placa'], 'required'],
            ['placa', 'filter','filter'=>'strtoupper'],
            [['placa'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_vehiculo' => Yii::t('backend', 'Id Vehiculo'),
            'id_contribuyente' => Yii::t('backend', 'Id Contribuyente'),
            'placa' => Yii::t('backend', 'License Plate'),
            'marca' => Yii::t('backend', 'Trademark'),
            'modelo' => Yii::t('backend', 'Model'),
            'color' => Yii::t('backend', 'Color'),
            'uso_vehiculo' => Yii::t('backend', 'Vehicle Use'),
            'precio_inicial' => Yii::t('backend', 'Starting Bid'),
            'fecha_inicio' => Yii::t('backend', 'Start Date'),
            'ano_compra' => Yii::t('backend', 'Year Purchase'),
            'ano_vehiculo' => Yii::t('backend', 'Vehicle Year'),
            'no_ejes' => Yii::t('backend', 'N° Axes'),
            'liquidado' => Yii::t('backend', 'Liquidated'),
            'status_vehiculo' => Yii::t('backend', 'Inactive'),
            'exceso_cap' => Yii::t('backend', 'Excess Capacity'),
            'medida_cap' => Yii::t('backend', 'Capacity Measure'),
            'capacidad' => Yii::t('backend', 'Capacitance'),
            'nro_puestos' => Yii::t('backend', 'N° Stand'),
            'peso' => Yii::t('backend', 'Weight (Kgs.)'),
            'clase_vehiculo' => Yii::t('backend', 'Vehicle Class'),
            'tipo_vehiculo' => Yii::t('backend', 'Vehicle Type'),
            'serial_motor' => Yii::t('backend', 'Serial Engine'),
            'serial_carroceria' => Yii::t('backend', 'Serial Body'),
            'nro_calcomania' => Yii::t('backend', 'N° Sticker'),
        ];
    }
}
