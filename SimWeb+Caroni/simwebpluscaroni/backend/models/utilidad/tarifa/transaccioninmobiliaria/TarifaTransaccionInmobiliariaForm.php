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
 *  @file TarifaTransaccionInmobiliariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-06-2016
 *
 *  @class TarifaTransaccionInmobiliariaForm
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

	namespace backend\models\utilidad\tarifa\transaccioninmobiliaria;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\tarifa\inmueble\TarifaTransaccionInmobiliaria;

	/**
	* 	Clase
	*/
	class TarifaTransaccionInmobiliariaForm extends TarifaTransaccionInmobiliaria
	{
		public $id_tarifa_transaccion;
		public $ano_impositivo;
		public $id_cp;
		public $tipo_transaccion;
		public $monto_desde;
		public $monto_hasta;
		public $tipo_rango;
		public $monto_aplicar;
		public $tipo_monto;
		public $porc_descuento;
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
	        	'id_tarifa_transaccion' => Yii::t('backend', 'Registro'),
	        	'ano_impositivo' => Yii::t('backend', 'Año'),
	        	'id_cp' => Yii::t('backend', 'Ubication'),
	        	'tipo_transaccion' => Yii::t('backend', 'Tipo Transaccion'),
	        	'monto_desde' => Yii::t('backend', 'Monto Desde'),
	        	'monto_hasta' => Yii::t('backend', 'Monto Hasta'),
	        	'tipo_rango' => Yii::t('backend', 'Tipo Rango'),
	        	'monto_aplicar' => Yii::t('backend', 'Monto Aplicar'),
	        	'tipo_monto' => Yii::t('backend', 'Tipo Monto'),
	        	'porc_descuento' => Yii::t('backend', '% Descuento'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }
	}
?>