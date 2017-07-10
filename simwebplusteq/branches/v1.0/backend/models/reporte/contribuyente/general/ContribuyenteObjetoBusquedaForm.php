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
 *  @file ContribuyenteObjetoBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-07-2017
 *
 *  @class ContribuyenteObjetoBusquedaForm
 *  @brief Clase Modelo del formulario para buscar a los contribuyentes y los objetos.
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

	namespace backend\models\reporte\contribuyente\general;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\fecha\RangoFechaValido;



	/**
	* Clase que gestiona la politica y validacion del formulario que se utilizara para
	* la consulta y busqueda de los contribuyentes existentes que luego permitira
	* encontrar los objetos imponibles asocoados a el o los contribuyente(s).
	*/
	class ContribuyenteObjetoBusquedaForm extends Model
	{
		public $id_contribuyente;
		public $todos;					// CheckBox que indica que se muestren todo los contribuyentes.
		public $condicion_contribuyente;
		public $condicion_objeto;


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
	        	[['id_contribuyente'],
	        	  'required',
	        	  'when' => function($model) {
								if ( $model->todos == 0 ) {
									return true;
								} else {
									return false;
								}
							},
				  'message' => Yii::t('backend', '{attribute} is required')
	        	],
	        	[['condicion_contribuyente'],
	        	  'required' ,
	        	  'when' => function($model) {
								if ( $model->todos == 1 ) {
									return true;
								} else {
									return false;
								}
							},
				  'message' => Yii::t('backend', '{attribute} is required')
	        	],
	        	[['condicion_objeto'],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['id_contribuyente', 'condicion_contribuyente',
	        	  'condicion_objeto', 'todos'],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
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
	        	'condicion_contribuyente' => Yii::t('backend','Condicion'),
	        	'condicion_objeto' => Yii::t('backend','Condicion'),
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