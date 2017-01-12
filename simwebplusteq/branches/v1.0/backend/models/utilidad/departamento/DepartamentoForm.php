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

	namespace backend\models\utilidad\departamento;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\utilidad\departamento\Departamento;

	/**
	* 	Clase
	*/
	class DepartamentoForm extends Departamento
	{
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
	            [['id_departamento'], 'required'],
	            [['id_departamento', 'inactivo'], 'integer'],
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
            	'id_departamento' => Yii::t('backend', 'Id Departamento'),
            	'descripion' => Yii::t('backend', 'Descripion'),
            	'inactivo' => Yii::t('backend', 'Inactivo'),
        	];
	    }




	    /**
	     * Metodo que permite obtener una lista de la entidad "departamentos",
	     * para luego utilizarlo en lista de combo.
	     */
	    public function getListaDepartamento($inactivo = 0)
	    {
	    	$listaDepartamento = null;
	    	$model = self::findDepartamento($inactivo);
	    	if ( $model !== null ) {
	    		// Se convierte el modelo encontrado en un arreglo de datos para
	    		// facilitar pasarlo a una lista.
	    		$listaDepartamento = ArrayHelper::map($model, 'id_departamento', 'descripcion');
	    	}
	    	return $listaDepartamento;
	    }



	    /**
	     * Busqueda del model de Departamento para obtener los registros de la entidad.
	     * @param Integer $inactivo, parametro que especifica la condicion del registro.
	     * @return ActiveRecord $model, Retorna una instancia Active Record con los registros
	     * encontrados.
	     */
	    public function findDepartamento($inactivo = 0)
	    {
	    	$model = Departamento::find()->where('inactivo =:inactivo', [':inactivo' => $inactivo])->all();
	    	return ( count($model) > 0 ) ? $model : null;
	    }



	    /***/
	    private function getDepartamento($idDepartamento)
	    {
	    	$model = Departamento::findOne($idDepartamento);
	    	return isset($model) ? $model : null;
	    }




	    /***/
	    public function getDescripcionDepartamento($idDepartamento)
	    {
	    	$model = self::getDepartamento($idDepartamento);
	    	if ( isset($model) ) {
	    		$modelDepartamento = $model;
	    		return isset($modelDepartamento) ? $modelDepartamento->descripcion : null;
	    	}
	    	return null;
	    }

	}
?>