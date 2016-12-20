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
 *  @file BusquedaRubroForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 16-10-2015
 *
 *  @class BusquedaRubroForm
 *  @brief Clase Modelo que controla el campo de busqueda del formulario de autorizar-ramo-form.
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

	namespace backend\models\aaee\autorizarramo;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;


	/**
	* 	Clase base del campo de busqueda del formulario autorizar-ramo-form.
	*/
	class BusquedaRubroForm extends Model
	{
		public $campo_busqueda;

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	['campo_busqueda', 'match', 'pattern' => '/^[0-9a-záéíóúñ\s]+$/i', 'message' => Yii::t('backend', 'input not valid.')],
	        ];
	    }





	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'campo_busqueda' => Yii::t('backend', 'Search'),

	        ];
	    }




	    /**
	     * [getProviderRubro description]
	     * @return [type] [description]
	     */
	    public function getProviderRubro()
	    {
	    	return RubroForm::getDataProviderRubro();
	    }

	}
?>