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
 *  @file TarifaVehiculoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-04-2016
 *
 *  @class TarifaVehiculoForm
 *  @brief Clase Modelo del formulario para
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *  @inherits
 *
 */

	namespace backend\models\utilidad\tarifa\vehiculo;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculo;

	/**
	* 	Clase
	*/
	class TarifaVehiculoForm extends TarifaVehiculo
	{
		public $id_tarifa_vehiculo;				// Autonumerico
		public $id_ordenanza;
		public $clase_vehiculo;
		public $ano_impositivo;
		public $monto_aplicar;
		public $monto_adicional;
		public $monto_deduccion;
		public $tipo_monto;
		public $inactivo;




		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_tarifa_vehiculo' => Yii::t('backend', 'Registro'),
	        	'id_ordenanza' => Yii::t('backend', 'Identificador de la Ordenanza'),
	        	'clase_vehiculo' => Yii::t('backend', 'Clase Vehiculo'),
	        	'ano_impositivo' => Yii::t('backend', 'Año'),
	        	'monto_aplicar' => Yii::t('backend', 'Monto Aplicar'),
	        	'monto_adicional' => Yii::t('backend', 'Monto Adicional'),
	        	'monto_deduccion' => Yii::t('backend', 'Monto Deduccion'),
	        	'tipo_monto' => Yii::t('backend', 'Tipo Monto'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }
	}
?>