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
 *  @file NivelAprobacionForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-03-2016
 *
 *  @class NivelAprobacionForm
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

	namespace backend\models\configuracion\nivelaprobacion;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\configuracion\nivelaprobacion\NivelAprobacion;
	use yii\helpers\ArrayHelper;


	/**
	* 	Clase
	*/
	class NivelAprobacionForm extends NivelAprobacion
	{
		public $nivel_aprobacion;
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
	        	[['nivel_aprobacion', 'descripcion'],
	        	  'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['nivel_aprobacion', 'inactivo',],
	        	  'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['descripcion'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	     		[['inactivo'], 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'nivel_aprobacion' => Yii::t('backend', 'Register No.'),
	        	'descripcion' => Yii::t('backend', 'Description'),
	            'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }


	    /***/
	    public function findNivelAprobacion($inactivo = 0)
	    {
	    	$findModel = NivelAprobacion::find()->where(['inactivo' => $inactivo])->all();
	    	return $findModel;
	    }


	    /***/
	    public function getDataProvider()
	    {
	    	$query = $this->findNivelAprobacion();

	    	$dataProvider = New ActiveDataProvider([
	    		'query' => $query,
	    	]);

	    	return $dataProvider;
	    }


	    /***/
	    public function getListaNivelAprobacion()
	    {
	    	$model = $this->findNivelAprobacion();
	    	if ( isset($model) ) {
	    		$lista = ArrayHelper::map($model, 'nivel_aprobacion', 'descripcion');
	    	} else {
	    		$lista = null;
	    	}
	    	return $lista;
	    }



	    /***/
	    public function findNivelAprobacionDeterminada($nivel)
	    {
	    	$modelFind = NivelAprobacion::findOne($nivel);
	    	return isset($modelFind) ? $modelFind : null;
	    }



	    /**
	     * Metodo que permite obtener la descripcion de un nivel de aprobacion
	     * segun el parametro "nivel-aprobacion".
	     * @param  Integer $nivel identificador del registro.
	     * @return String Retorna una cadena que muestra la descripcion del
	     * identificador.
	     */
	    public function getDescripcionNivelAprobacion($nivel)
	    {
	    	$model = self::findNivelAprobacionDeterminada($nivel);
	    	return isset($model->descripcion) ? $model->descripcion : '';
	    }


	}
?>