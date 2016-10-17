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
 *  @file TipoDeclaracionForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 05-09-2016
 *
 *  @class TipoDeclaracionForm
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

	namespace backend\models\aaee\declaracion\tipodeclaracion;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\declaracion\tipodeclaracion\TipoDeclaracion;



	/**
	* 	Clase base del formulario
	*/
	class TipoDeclaracionForm extends TipoDeclaracion
	{
		public $tipo_declaracion;
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
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['tipo_declaracion', 'descripcion',],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['tipo_declaracion', 'inactivo'],
	        	  'integer',
	        	  'message' => Yii::t('backend','{attribute}')],
	          	['inactivo', 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'tipo_declaracion' => Yii::t('backend', 'Id. Register'),
	            'descripcion' => Yii::t('backend', 'description'),
	            'inactivo' => Yii::t('backend', 'Condition'),

	        ];
	    }



	    /**
	     * Metodo que realiza un find de los tipos de declaracion. Si se envia
	     * como parametro el asterisco ("*"), se asume que se quieren todos los
	     * registros. El find cuando se realiza con los identificadores se busca
	     * a los que estan activo solamente.
	     * @param  array $listaTipo arreglo de los identificadores de la entidad
	     * respectiva.
	     * @return active record retorna un modelo con la consulta generada, sino se
	     * ejecuta retorna un arreglo vacio.
	     */
	    public function getListaTipoDeclaracion($listaTipo)
	    {
	    	$findModel = [];
	    	if ( count($listaTipo) > 0 ) {
	    		if ( $listaTipo[0] == '*' ) {	// Quiere seleccionar todos.
	    			$findModel = TipoDeclaracion::findAll($listaTipo);
	    		} else {
	    			return $findModel = TipoDeclaracion::find()->where('inactivo =:inactivo',
	    															[':inactivo' => 0])
	    													   ->andWhere(['IN', 'tipo_declaracion', $listaTipo])
	    													   ->all();
	    		}
	    	}

	    	return $findModel;
	    }

	}
?>