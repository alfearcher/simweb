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
 *  @file SolvenciaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2016
 *
 *  @class SolvenciaForm
 *  @brief Clase Modelo del formulario.
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

	namespace backend\models\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\solvencia\Solvencia;


	/**
	* Clase del formulario
	*/
	class SolvenciaForm extends Solvencia
	{
		public $id_solvencia;
		public $ente;
		public $id_contribuyente;
		public $ano_impositivo;
		public $impuesto;
		public $id_impuesto;
		public $serial_solvencia;
		public $fecha_emision;
		public $fecha_vcto;
		public $status_solvencias;
		public $observacion;
		public $nro_solvencia;




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
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'status_solvencias', 'serial_solvencia',
	        	  'id_impuesto', 'impuesto'],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['observacion', 'serial_solvencia'],
	        	  'string', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['status_solvencias'], 'default', 'value' => 0],
	        	[['ente'], 'default', 'value' => Yii::$app->ente->getEnte()],
	        	[['fecha_emision', 'fecha_vcto'], 'date'],

	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        ];
	    }



	}
?>