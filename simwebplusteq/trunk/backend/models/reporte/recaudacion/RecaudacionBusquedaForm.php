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
 *  @file RecaudacionBusquedaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-06-2017
 *
 *  @class RecaudacionBusquedaForm
 *  @brief Clase Modelo del formulario que permite la consulta de la recaudacion.
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

	namespace backend\models\reporte\recaudacion;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\fecha\RangoFechaValido;
	use yii\helpers\ArrayHelper;



	/**
	* Clase que gestiona la politica y validacion del formulario que se utilizara para
	* la consulta y busqueda de la recaudacion de ingresos.
	*/
	class RecaudacionBusquedaForm extends Model
	{
		public $fecha_desde;
		public $fecha_hasta;
		public $tipo_recaudacion;

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
    	 *	Metodo que permite fijar la reglas de validacion del formulario.
    	 */
	    public function rules()
	    {
	        return [
	        	[['fecha_desde', 'fecha_hasta', 'tipo_recaudacion'],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['tipo_recaudacion'],
	        	  'default', 'value' => null],
	        	[['tipo_recaudacion'],
	        	  'string',
	        	  'message' => Yii::t('backend', '{attribute} not is valid')],
	        	['fecha_desde',
	        	 'compare',
	        	 'compareAttribute' => 'fecha_hasta',
	        	 'operator' => '<=',
	        	 'enableClientValidation' => false],
	        	['fecha_desde' , 'validarRango'],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'default',
	        	  'value' => '0000-00-00'],
	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'fecha_desde' => Yii::t('backend', 'Fecha Desde'),
	        	'fecha_hasta' => Yii::t('backend', 'Fecha Hasta'),
	        	'tipo_recaudacion' => Yii::t('backend', 'Tipo de Recaudacion'),
	        ];
	    }



	    /***/
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


	    /**
	     * Metodo que retorna una variable que indica si el rango de fechas evaluadas es valido.
	     * @return boolean
	     */
	    public function getRangoValido()
	    {
	    	return $this->rangoValido;
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
	    		'pfranco',
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
	     * Metodo que permite obtener la ista de los tipos de recaudacion, esta lista
	     * se utilizara en el formulario de consulta de recaudacion de ingresos.
	     * @return array
	     */
	    public function getListaTipoRecaudacion()
	    {
	    	$listaRecaudacion = [
	      		['tipo' => 0, 'descripcion' => 'DETALLADA'],
	      		['tipo' => 1, 'descripcion' => 'GENERAL'],
	      	];
	      	return ArrayHelper::map($listaRecaudacion, 'descripcion', 'descripcion');
	    }


	}
?>