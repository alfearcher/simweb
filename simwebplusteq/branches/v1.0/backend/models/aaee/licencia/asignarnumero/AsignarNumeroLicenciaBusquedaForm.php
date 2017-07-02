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
 *  @file AsignarNumeroLicenciaBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-06-2017
 *
 *  @class AsignarNumeroLicenciaBusquedaForm
 *  @brief Clase Modelo del formulario para buscar a los contribuyentes que no poseen
 *  numero de licencia valido.
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

	namespace backend\models\aaee\licencia\asignarnumero;

 	use Yii;
	use yii\base\Model;


	/**
	* Clase del formulario que permite gestionar la solicitud de emision de licencias.
	*/
	class AsignarNumeroLicenciaBusquedaForm extends Model
	{
		public $id_contribuyente;
		public $todos;			// CheckBox que indica que se muestren todo los contribuyentes.




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
	        	[['id_contribuyente'], 'required', 'when' => function($model) {
	        													if ( $model->todos == 0 ) {
	        														return true;
	        													} else {
	        														return false;
	        													}
	        												}
	        	],
	        	[['id_contribuyente', 'todos'],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['id_contribuyente', 'todos'],
	        	  'default',
	        	  'value' => 0],
	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_contribuyente' => Yii::t('backend','Id. Contribuyente'),
	        	'todos' => Yii::t('backend','Todos los Contribuyente'),
	        ];
	    }



	    /**
	     * Metodo donde se fijan los usuario autorizados para utilizar esl modulo.
	     * @return array
	     */
	    private function getListaFuncionarioAutorizado()
	    {
	    	return [
	    		'adminteq',
	    		'kperez',
	    		'pfranco'
	    	];
	    }



	    /**
	     * Metodo que permite determinar si un usuario esta autorizado para utilizar el modulo.
	     * @param  string $usuario usuario logueado
	     * @return booleam retorna true si lo esta, false en caso conatrio.
	     */
	    public function estaAutorizado($usuario)
	    {
	    	$listaUsuarioAutorizado = self::getListaFuncionarioAutorizado();
	    	if ( count($listaUsuarioAutorizado) > 0 ) {
	    		foreach ( $listaUsuarioAutorizado as $key => $value ) {
	    			if ( $value == $usuario ) {
	    				return true;
	    			}
	    		}
	    	}
	    	return false;
	    }

	}
?>