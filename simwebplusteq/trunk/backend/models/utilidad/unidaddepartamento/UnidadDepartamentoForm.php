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
 *  @file DepartamentoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-08-2015
 *
 *  @class DepartamentoForm
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

	namespace backend\models\utilidad\unidaddepartamento;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\unidaddepartamento\UnidadDepartamento;
	use yii\helpers\ArrayHelper;

	/**
	* 	Clase
	*/
	class UnidadDepartamentoForm extends UnidadDepartamento
	{
		public $id_unidad;
		public $id_departamento;
		public $descripcion;
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
	            [['id_unidad', 'id_departamento', 'descripcion'], 'required'],
	            [['id_unidad', 'id_departamento', 'inactivo'], 'integer'],
	            [['descripion'], 'string', 'max' => 45]
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_unidad' => Yii::t('backend', 'Id Unidad'),
            	'id_departamento' => Yii::t('backend', 'Id Departamento'),
            	'descripion' => Yii::t('backend', 'Descripion'),
            	'inactivo' => Yii::t('backend', 'Inactivo'),
        	];
	    }


	    /***/
	    private function getUnidadDepartamento($idUnidad)
	    {
	    	$model = UnidadDepartamento::findOne($idUnidad);
	    	return isset($model) ? $model : null;
	    }




	    /***/
	    public function getDescripcionUnidadDepartamento($idUnidad)
	    {
	    	$model = self::getUnidadDepartamento($idUnidad);
	    	if ( isset($model) ) {
	    		$modelUnidad = $model;
	    		return isset($modelUnidad) ? $modelUnidad->descripcion : null;
	    	}
	    	return null;
	    }


	    /***/
	    public function getListaUnidadSegunDepartamento($idDepartamento)
	    {
	    	$lista = [];
	    	$model = self::findUnidadSegunDepartamento($idDepartamento);
	    	if ( $model !== null ) {
	    		$lista = ArrayHelper::map($model, 'id_unidad', 'descripcion');
	    	}

	    	return $lista;
	    }



	    /***/
	    public function findUnidadSegunDepartamento($idDepartamento)
	    {
	    	return UnidadDepartamento::find()->where('id_departamento =:id_departamento',
	    												[':id_departamento' => $idDepartamento])
	    									 ->all();
	    }


	}
?>