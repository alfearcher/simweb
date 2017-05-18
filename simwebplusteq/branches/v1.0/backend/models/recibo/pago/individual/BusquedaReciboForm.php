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
 *  @file BusquedaReciboForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class BusquedaReciboForm
 *  @brief Clase Modelo del formulario que permite buscar un recibo para iniciar el proceso de pago.
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

	namespace backend\models\recibo\pago\individual;

 	use Yii;
	use yii\base\Model;


	/**
	* 	Clase base del formulario
	*/
	class BusquedaReciboForm extends Model
	{
		public $recibo;			// Autoincremental



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
	        	[['recibo',],
	        	  'required',
	        	  'message' => Yii::t('backend', 'No ha indicado e numero de recibo')],
	        	[['recibo',],
	        	  'integer',
	        	  'message' => Yii::t('backend', 'El recibo no es valido')],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'recibo' => Yii::t('frontend', 'Nro. Recibo'),

	        ];
	    }



	    /***/
       	private function getUsuarioAutorizado()
       	{
       		return [
       			'adminteq',
       			'admin',
       		];
       	}



       	/***/
       	public function usuarioAutorizado($usuario)
       	{
       		$listaUsuario = self:: getUsuarioAutorizado();
       		foreach ( $listaUsuario as $key => $value ) {
       			if ( $usuario == $value ) {
       				return true;
       			}
       		}
       		return false;
       	}


	}
?>