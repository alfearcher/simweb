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
 *  @file HistoricoDeclaracionForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @class HistoricoDeclaracionForm
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

	namespace backend\models\aaee\historico\declaracion;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\historico\declaracion;

	/**
	* 	Clase
	*/
	class HistoricoDeclaracionForm extends HistoricoDeclaracion
	{
		public $id_historico;
		public $id_contribuyente;
		public $id_impuesto;
		public $ano_impositivo;
		public $periodo;
		public $nro_control;
		public $serial_control;
		public $json_rubro;
		public $tipo_declaracion;
		public $usuario;
		public $fecha_hora;
		public $inactivo;
		public $observacion;
		public $por_sustitutiva;




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
	        	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	        	[['inactivo', 'tipo_declaracion', 'ano_impositivo',
	        	  'periodo', 'id_impuesto', 'nro_control', 'por_sustitutiva',],
	        	  'default', 'value' => 0],
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'periodo', 'id_impuesto', 'nro_control',
	        	  'tipo_declaracion', 'por_sustitutiva'],
	        	  'integer'],
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