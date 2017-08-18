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
		public $fecha_desde;
		public $fecha_hasta;

		private $rangoValido;


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
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'required',
	        	  'when' => function($model) {
								if ( strlen($model->fecha_desde) > 0 || strlen($model->fecha_hasta) > 0 ) {
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
	        	['fecha_desde' , 'validarRango'],
	        	[['fecha_desde', 'fecha_hasta'], 'safe'],

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
	        	'fecha_desde' => Yii::t('backend','Desde'),
	        	'fecha_hasta' => Yii::t('backend','Hasta'),
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



	    /**
	     * Metodo que permite validar el rango de fecha.
	     * @return none
	     */
	    public function validarRango()
	    {
	    	$this->rangoValido = false;
	    	$validarRango = New RangoFechaValido($this->fecha_desde, $this->fecha_hasta);
	    	if ( !$validarRango->rangoValido() ) {
	    		$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    	} else {
	    		$this->rangoValido = true;
	    	}
	    }

	}
?>