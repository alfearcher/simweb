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
 *  @file TarifaVehiculoDetalleForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-04-2016
 *
 *  @class TarifaVehiculoDetalleForm
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
	use backend\models\utilidad\tarifa\vehiculo\TarifaVehiculoDetalle;

	/**
	* 	Clase
	*/
	class TarifaVehiculoDetalleForm extends TarifaVehiculoDetalle
	{
		public $id_tarifa_detalle;				// Autonumerico
		public $$id_tarifa_vehiculo;
		public $rango_desde;
		public $rango_hasta;
		public $monto_rango_directo;
		public $tipo_rango;
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
	        	'id_tarifa_detalle' => Yii::t('backend', 'Registro'),
	        	'id_tarifa_vehiculo' => Yii::t('backend', 'Tarifa'),
	        	'rango_desde' => Yii::t('backend', 'Rango desde'),
	        	'rango_hasta' => Yii::t('backend', 'Rango hasta'),
	        	'monto_rango_directo' => Yii::t('backend', 'Monto Directo'),
	        	'tipo_rango' => Yii::t('backend', 'Tipo Rango'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }
	}
?>