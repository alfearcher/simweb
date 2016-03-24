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
 *  @file UnidadTributariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-03-2016
 *
 *  @class UnidadTributariaForm
 *  @brief Clase Modelo que maneja la politica
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

	namespace backend\models\utilidad\ut;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;

	/**
	* 	Clase
	*
	*/
	class UnidadTributaria extends UnidadTributaria
	{

		public $id_ut;
		public $ente;
		public $fecha_inicio;
		public $fecha_fin;
		public $monto_ut;
		public $ultimo;



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario create-ut-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['fecha_inicio', 'fecha_fin', 'monto_ut'],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['monto_ut'], 'double', 'message' => Yii::t('backend','{attribute} must be a number')],
	     		['ultimo', 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_ut' => Yii::t('backend', 'Register No.'),
	            'fecha_inicio' => Yii::t('backend', 'Date Begin'),
	            'fecha_fin' => Yii::t('backend', 'Date End'),
	            'monto_ut' => Yii::t('backend', 'Current'),
	            'ente' => Yii::t('backend', 'Ente'),
	            'ultimo' => Yii::t('backend', 'Last'),

	        ];
	    }


	}

?>