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
 *  @file CalculoMetodoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-04-2016
 *
 *  @class CalculoMetodoForm
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

	namespace backend\models\utilidad\metodocalculo\inmueble;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\metodocalculo\inmueble\CalculoMetodo;

	/**
	* 	Clase
	*/
	class CalculoMetodoForm extends CalculoMetodo
	{
		public $id_metodo;				// Autonumerico
		public $calculo;
		public $metodo;
		public $descripcion;
		public $por_localizacion;
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
	        	'id_metodo' => Yii::t('backend', 'Registro'),
	        	'calculo' => Yii::t('backend', 'Calculo'),
	        	'metodo' => Yii::t('backend', 'Metodo'),
	        	'descripcion' => Yii::t('backend', 'Descrption'),
	        	'por_localizacion' => Yii::t('backend', 'Por localizacion'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }




	    /***/
	    public function findMetodoCalculo($idMetodo = 0)
	    {
	    	$modelFind = null;
	    	if ( $idMetodo > 0 ) {
	    		$modelFind = CalculoMetodo::findOne($idMetodo);
	    	} else {
	    		$modelFind = CalculoMetodo::find()->all();
	    	}

	    	return isset($modelFind) ? $modelFind : null;
	    }
	}
?>