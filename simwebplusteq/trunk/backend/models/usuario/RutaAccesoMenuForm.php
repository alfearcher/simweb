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

	namespace backend\models\usuario;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\usuario\RutaAccesoMenu;
	use yii\helpers\ArrayHelper;

	/**
	* 	Clase
	*/
	class RutaAccesoMenuForm extends RutaAccesoMenu
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
	        	[['ruta','menu'],'required','message' => Yii::t('backend','{attribute} is required')],
	        	[['id_ruta_acceso_menu'], 'integer', 'message' => Yii::t('backend','{attribute} must be a number')],
	        	[['menu'], 'string', 'message' => Yii::t('backend','{attribute} must be a string')],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_ruta_acceso_menu' => Yii::t('backend', 'Id menu acceso'),
	            'menu' => Yii::t('backend', 'Menu Acceso'),
	            'ruta' => Yii::t('backend', 'Ruta Acceso'),
	        ];
	    }


	    /**
	     * Metodo que retorna un dataProvider
	     * @param  array  $arrayImpuesto si el array esta vacio se aume que debe regeresar todos.
	     * @return [type]              [description]
	     */
	    public function getDataProvider($params)
	    {
	    	$dataProvider = null;

	    	$query = RutaAccesoMenu::find()->where(['inactivo'=>0]);

	    	$dataProvider = New ActiveDataProvider([
            	'query' => $query,

        	]);
        	$query->all();
        	$this->load($params);
        	// if ( is_array($params) ) {
        	// 	$query->where(['in', 'id_ruta_acceso_menu', $array]);
        	// }
		    return $dataProvider;
	    }



	    /**
	     * Metodo que permite obtener un o una lista de registro asociada
	     * a la entidad "rutas"
	     * @param  string|array $arrayImpuesto parametro que indica el registro
	     * a buscar, este parametro puede llegar como un entero o como un arreglo
	     * de enteros [1,2,..n].
	     * @return Active Record modelo de la entidad "rutas".
	     */
	    public function findRuta($array = '')
	    {
	    	if ( is_array($array) ) {
	    		if ( count($array) > 0 ) {
	    			$findModel = RutaAccesoMenu::findAll($array);
	    		} else {
	    			$findModel = RutaAccesoMenu::find()->where(['inactivo'=>0])->all();
	    		}
	    	} elseif ( is_int($array) ) {
	    		$findModel = RutaAccesoMenu::findOne($array);
	    	} else {
	    		$findModel = RutaAccesoMenu::find()->all();
	    	}

	    	return $findModel;
	    }



	    /**
	     * Metodo que permite obtener una lista de la entidad "rutas",
	     * para luego utilizarlo en lista de combo.
	     */
	    public function getListaRutaAcceso($inactivo = 0, $array = [])
	    {
	    	$lista = null;
	    	$model = $this->getDataProvider($array); //findRuta
	    	if ( isset($model) ) {
	    		// Se convierte el modelo encontrado en un arreglo de datos para facilitar pasarlo a una lista.
	    		if ( count($model) > 0 ) {
	    			$lista = ArrayHelper::map($model, 'ruta', 'menu');
	    		}
	    	}
	    	return $lista;
	    }

	    /**
	     * Metodo que permite obtener una lista de la entidad "rutas",
	     * para luego utilizarlo en lista de combo.
	     */
	    public function getListaRutaAccesoId($inactivo = 0, $array = [])
	    {
	    	$lista = null;
	    	$model = $this->getDataProvider($array); //findRuta
	    	
	    	if ( isset($model) ) {
	    		
	    		return $model;
	    	}
	    	return $lista;
	    }



	    /***/
	    public function getDescripcionRutaAcceso($id_ruta_acceso_menu)
	    {
	    	settype($id_ruta_acceso_menu, 'integer');
	    	$model = self::findImpuesto($id_ruta_acceso_menu);
			return $model->menu;
	    }

	     /***/
	    public function getRutaAccesoId($id)
	    {
	    	settype($id_ruta_acceso_menu, 'integer');
	    	$model = RutaAccesoMenu::find()->where(['inactivo'=>0,'id_ruta_acceso_menu'=>$id])->all();
	    	
			return $model[0]->ruta;
	    } //id_ruta_acceso_menu, menu, ruta, inactivo, usuario, fecha_hora, operacion

	}
?>