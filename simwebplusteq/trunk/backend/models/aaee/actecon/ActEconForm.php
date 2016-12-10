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
 *  @file ActEconForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-10-2015
 *
 *  @class ActEconForm
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

	namespace backend\models\aaee\actecon;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\actecon\ActEcon;

	/**
	* 	Clase
	*/
	class ActEconForm extends ActEcon
	{
		public $id_impuesto;				// Autonumerico
		public $ente;
		public $id_contribuyente;
		public $ano_impositivo;
		public $liquidado;
		public $exigibilidad_declaracion;
		public $islr;
		public $pp_industria;
		public $pagos_retencion;
		public $iva_total;
		public $iva_enero;
		public $iva_febrero;
		public $iva_marzo;
		public $iva_abril;
		public $iva_mayo;
		public $iva_junio;
		public $iva_julio;
		public $iva_agosto;
		public $iva_septiembre;
		public $iva_octubre;
		public $iva_noviembre;
		public $iva_diciembre;
		public $estatus;
		public $condicion_estatus;




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
	        	[['liquidado', 'ano_impositivo',
	        	  'exigibilidad_declaracion', 'estatus',
	        	  'condicion_estatus', 'ente',
	        	  'id_contribuyente',],
	        	  'integer'],
	        	[['liquidado', 'ano_impositivo',
	        	  'exigibilidad_declaracion', 'estatus',
	        	  'condicion_estatus'],
	        	  'default', 'value' => 0],
	        	[['ente'], 'default', 'value' => Yii::$app->ente->getEnte()],
	        	[['islr', 'pp_industria', 'pagos_retencion',
	        	  'iva_enero', 'iva_febrero', 'iva_marzo',
	        	  'iva_abril', 'iva_mayo', 'iva_junio',
	        	  'iva_julio', 'iva_agosto', 'iva_septiembre',
	        	  'iva_octubre', 'iva_noviembre', 'iva_diciembre'],
	        	  'default', 'value' => 0],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        ];
	    }




	}
?>