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
 *  @file SeleccionLapsoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-10-2016
 *
 *  @class SeleccionLapsoForm
 *  @brief Clase Modelo del formulario que permite seleccionar un lapso
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

	namespace common\models\aaee\lapso;

 	use Yii;
	use yii\base\Model;


	/**
	* 	Clase base del formulario
	*/
	class SeleccionLapsoForm extends Model
	{
		public $id_contribuyente;
		public $ano_impositivo;
		public $exigibilidad_periodo;



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
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'exigibilidad_periodo',],
	        	  'required',
	        	  'message' => Yii::t('frontend', '{attribute} is required')],
	  			[['id_contribuyente', 'ano_impositivo', 'exigibilidad_periodo'], 'integer'],

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
	            'id_contribuyente' => Yii::t('backend', 'ID.'),
	            'ano_impositivo' => Yii::t('backend', 'Año'),
	            'exigibilidad_periodo' => Yii::t('backend', 'Periodo'),
	        ];
	    }





	}
?>