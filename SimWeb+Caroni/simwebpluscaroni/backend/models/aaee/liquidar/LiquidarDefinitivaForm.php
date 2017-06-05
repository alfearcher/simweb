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
 *  @file LiquidarDefinitivaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class LiquidarDefinitivaForm
 *  @brief Clase Modelo del formulario
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

	namespace backend\models\aaee\liquidar;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use common\models\planilla\PagoDetalle;



	/**
	* 	Clase base del formulario
	*/
	class LiquidarDefinitivaForm extends PagoDetalle
	{



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();

    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
    	 */
	    public function rules()
	    {
	        return [
				[['id_pago','impuesto','id_impuesto',
				  'monto', 'trimestre', 'ano_impositivo',
				  'recargo', 'interes', 'monto_reconocimiento',
				  'descuento', 'fecha_emision', 'fecha_pago',
				  'fecha_vcto', 'pago', 'fecha_desde',
				  'fecha_hasta', 'referencia', 'descripcion',
				  'exigibilidad_pago'
				],'safe'],
				[['id_pago'], 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales
	    * son las que aparecen en las vistas.
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas
	    * como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        ];
	    }


	}
?>