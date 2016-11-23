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
 *  @file HistoricoBoletinForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @class HistoricoBoletinForm
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

	namespace backend\models\aaee\historico\boletin;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\historico\boletin\HistoricoBoletin;

	/**
	* 	Clase
	*/
	class HistoricoBoletinForm extends HistoricoBoletin
	{
		public $id_historico;
		public $id_contribuyente;
		public $ano_impositivo;
		public $periodo;
		public $tipo_boletin;
		public $nro_control;
		public $fuente;
		public $json_fuente;
		public $usuario;
		public $fecha_hora;
		public $inactivo;
		public $observacion;




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
	        	['inactivo', 'default', 'value' => 0],
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'periodo'], 'integer'],

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