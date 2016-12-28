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
 *  @file NivelFuncionarioForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-03-2016
 *
 *  @class NivelFuncionarioForm
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

	namespace backend\models\utilidad\nivelfuncionario;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\helpers\ArrayHelper;


	/**
	* 	Clase
	*
	*/
	class NivelFuncionarioForm extends NivelFuncionario
	{

		public $nivel;
		public $descripcion;



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

	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'nivel' => Yii::t('backend', ''),
	            'descripcion' => Yii::t('backend', ''),


	        ];
	    }




	    /***/
	    public function getListaNivel()
	    {
	    	$lista = [];
	    	$model = self::findNivel();
	    	if ( $model !== null ) {
	    		$lista = ArrayHelper::map($model, 'nivel', 'descripcion');
	    	}

	    	return $lista;
	    }




	    /***/
	    public function findNivel()
	    {
	    	return NivelFuncionario::find()->all();
	    }






	}

?>