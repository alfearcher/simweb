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
 *  @file CausaNegacionSolicitudForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-06-2016
 *
 *  @class CausaNegacionSolicitudForm
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

	namespace backend\models\utilidad\causanegacionsolicitud;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\utilidad\causanegacionsolicitud\CausaNegacionSolicitud;
	/**
	* 	Clase base del formulario
	*/
	class CausaNegacionSolicitudForm extends CausaNegacionSolicitud
	{
		public $causa;
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

	        ];
	    }



	    /***/
	    public function attributeLabels()
	    {
	    	return [
	    		'causa' => Yii::t('backend', 'Register No.'),
	    		'descripcion' => Yii::t('backend', 'Description'),
	    		'inactivo' => Yii::t('backend', 'Condition')
	    	];
	    }



	    /**
	     * Metodo que realiza una consulta sobre los registros de la entidad respectiva.
	     * "causas-negacion-solicitud".
	     * @param  integer $inactivo indica condicion del o los registros, 0 => Activo, 1 => Inactivo.
	     * @return Active Record.
	     */
	    public function findCausaNegacion($inactivo = 0)
	    {
    		$modelFind = CausaNegacionSolicitud::find()->where('inactivo =:inactivo', [':inactivo' => $inactivo])
    												   ->orderBy([
    												   		'descripcion' => SORT_ASC,
    												   	])
    												   ->all();

    		return isset($modelFind) ? $modelFind : null;
	    }



	    /**
	     * Metodo que permite generar un listado de las causas de negacion
	     * de una solicitud. Esto se puede utilizar en un formulario para
	     * mostrar un combo-lista.
	     * @return Array Retorna lista de causas de negacion de solicitudes.
	     */
	    public function getListaCausasNegacion($inactivo = 0)
	    {
	    	$model = $this->findCausaNegacion($inactivo);
	    	if ( isset($model) ) {
	    		$lista = ArrayHelper::map($model, 'causa', 'descripcion');
	    	} else {
	    		$lista = null;
	    	}
	    	return $lista;
	    }


	}
?>