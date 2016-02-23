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
 *  @file ImpuestoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-02-2016
 *
 *  @class ImpuestoForm
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

	namespace backend\models\impuesto;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\impuesto\Impuesto;

	/**
	* 	Clase
	*/
	class ImpuestoForm extends Impuesto
	{
		public $impuesto;
		public $descripcion;
		public $liquidacion_general;

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
	        	[['impuesto',
	        	  'descripcion', 'liquidacion_general'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['impuesto'], 'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['descripcion'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'impuesto' => Yii::t('backend', 'Tax'),
	            'descripcion' => Yii::t('backend', 'Type of Request'),
	            'liquidacion_general' => Yii::t('backend', ''),
	        ];
	    }


	    /**
	     * Metodo que retorna un dataProvider
	     * @param  array  $arrayImpuesto si el array esta vacio se aume que debe regeresar todos.
	     * @return [type]              [description]
	     */
	    public function getDataProvider($arrayImpuesto)
	    {
	    	$dataProvider = null;

	    	$query = Impuesto::find();

	    	$dataProvider = New ActiveDataProvider([
            	'query' => $query,
        	]);
        	if ( is_array($arrayImpuesto) ) {
        		$query->where(['in', 'impuesto', $arrayImpuesto]);
        	}
		    return $dataProvider;
	    }



	    /***/
	    public function findImpuesto($arrayImpuesto = [])
	    {
	    	if ( is_array($arrayImpuesto) ) {
	    		if ( count($arrayImpuesto) > 0 ) {
	    			$findModel = Impuesto::findAll($arrayImpuesto);
	    		} else {
	    			$findModel = Impuesto::find()->all();
	    		}
	    	} else {
	    		$findModel = Impuesto::find()->all();
	    	}

	    	return $findModel;
	    }

	}
?>