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
 *  @file CodigoValidadorPruebaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-02-2017
 *
 *  @class CodigoValidadorPruebaForm
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

	namespace backend\models\prueba\cvb\recibo;

 	use Yii;
	use yii\base\Model;

	/**
	* 	Clase
	*/
	class CodigoValidadorPruebaForm extends Model
	{
		public $recibo;
		public $id_contribuyente;
		public $fecha;
		public $monto;


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
	        	[['recibo', 'fecha', 'monto'], 'required'],
	        	[['recibo',], 'integer'],
	        	['fecha', 'date', 'format' => 'dd-MM-yyyy'],
	        	[['monto'], 'double'],
	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'recibo' => Yii::t('backend', 'Numero de recibo'),
	        	'fecha' => Yii::t('backend', 'Fecha de vcto'),
	        	'monto' => Yii::t('backend', 'Monto a pagar del recibo'),
	        ];
	    }




	}
?>