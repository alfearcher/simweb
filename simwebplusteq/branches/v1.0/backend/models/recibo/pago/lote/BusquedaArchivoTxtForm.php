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
 *  @file BusquedaArchivoTxtForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-04-2017
 *
 *  @class BusquedaArchivoTxtForm
 *  @brief Clase Modelo del formulario que permite buscar el archivo txt de pagos enviado por el banco.
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

	namespace backend\models\recibo\pago\lote;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\banco\BancoSearch;
	use backend\models\utilidad\fecha\RangoFechaValido;


	/**
	* Clase base del formulario
	*/
	class BusquedaArchivoTxtForm extends Model
	{
		public $id_banco;
		public $fecha_desde;
		public $fecha_hasta;
		public $fecha_pago;

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
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_banco', 'fecha_desde', 'fecha_hasta'],
	        	  'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['id_banco',],
	        	  'integer',
	        	  'message' => Yii::t('backend', 'El banco no es valido')],
	        	['fecha_desde',
	        	 'compare',
	        	 'compareAttribute' => 'fecha_hasta',
	        	 'operator' => '<=',
	        	 'enableClientValidation' => false
	        	],
	        	['fecha_desde' , 'validarRango'],
	        	[['fecha_pago',],'safe'],
	        ];
	    }



	    /**
	     * [validarRango description]
	     * @return [type] [description]
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



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_banco' => Yii::t('backend', 'Banco'),
	        	'fecha_desde' => Yii::t('backend', 'Desde'),
	        	'fecha_hasta' => Yii::t('backend', 'Hasta'),

	        ];
	    }



	    /**
	     * Metodo que retorna los identificadores de los bancos que envian los txt de pagos,
	     * estos identificadores se obtienen de la entidad "bancos" (id-banco). El valor retornado
	     * corresponde a una lista de enteros.
	     * @return array
	     */
	    public function getListaIdBancoTxt()
	    {
	    	// identificadores de los bancos que seran mostrados en el combo del formulario
	    	// de consulta. Estos identificadores corresponden a los bancos que envian
	    	// los archivos txt de pagos.
	    	return $listaIdBanco = [12, 16];
	    }



	    /**
	     * Metodo para generar el listado tipo combo-lista para mostrar en el formulario.
	     * @return ArrayHelper::map
	     */
	    public function getListaBancoRecaudador()
	    {
	    	$bancoSearch = New BancoSearch();
	    	return $bancoSearch->getListaBancoById(self::getListaIdBancoTxt());
	    }


	    /**
	     * [getUsuarioAutorizado description]
	     * @return [type] [description]
	     */
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