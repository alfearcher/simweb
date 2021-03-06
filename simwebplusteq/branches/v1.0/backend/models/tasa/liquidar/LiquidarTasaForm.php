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
 *  @file LiquidarTasaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class Liquidar
 *  @brief Clase modelo del formulario que gestiona la liquidacion de las Tasas.
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

	namespace backend\models\tasa\liquidar;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\tasa\Tasa;
	use common\models\planilla\PagoDetalle;
	use backend\models\tasa\liquidar\LiquidarTasaSearch;

	/**
	* 	Clase
	*/
	class LiquidarTasaForm extends Tasa
	{

		public $id_impuesto;
		public $impuesto;
		public $ano_impositivo;
		public $id_codigo;
		public $grupo_subnivel;
		public $codigo;
		public $id_contribuyente;
		public $multiplicar_por;
		public $resultado;
		public $observacion;
		public $id_pago;



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
	        	[['impuesto', 'ano_impositivo',
	        	  'id_codigo', 'grupo_subnivel',
	        	   'codigo', 'id_contribuyente'],
	        	   'required',
	        	  'message' => Yii::t('backend', '{attribute} is required')],
	        	[['impuesto', 'ano_impositivo',
	        	  'id_codigo', 'grupo_subnivel',
	        	  'codigo', 'id_contribuyente',
	        	  'id_impuesto'],
	        	  'integer',
	        	  'message' => Yii::t('backend', '{attribute}' . ' es incorrecto')],
	        	[['observacion'], 'string'],
	        	[['observacion', 'multiplicar_por',
	        	  'resultado', 'id_pago', 'id_impuesto'],
	        	  'safe'],
	        	[['resultado'],
	        	  'double',
	        	  'message' => Yii::t('backend', 'Monto no valido')],
	        	// ['resultado',
	        	//  'compare',
	        	//  'compareValue' => 0,
	        	//  'operator' => '>',
	        	//  'message' => Yii::t('backend', 'Monto debe ser mayor a cero')],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_impuesto' => Yii::t('backend', 'Register Number'),
	        	'id_codigo' => Yii::t('backend', 'Code Preupuestario'),
	        	'impuesto' => Yii::t('backend', 'Impuesto'),
	        	'ano_impositivo' => Yii::t('backend', 'Year'),
	        	'grupo_subnivel' => Yii::t('backend', 'Grupo'),
	        	'codigo' => Yii::t('backend', 'Code'),
	        	'descripcion' => Yii::t('backend', 'Description'),
	        	'monto' => Yii::t('backend', 'Current'),
	        	'tipo_rango' => Yii::t('backend', 'Type Current'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        	'cantidad_ut' => Yii::t('backend', 'Cantidad UT'),
	        ];
	    }




	    /**
	     * Metodo que busca el registro segun el identificador de la entidad.
	     * @param  Long $idImpuesto, [identificador del registro.
	     * @return ActiveRecord Retornara un model de tipo active record.
	     */
	    public function findTasa($idImpuesto)
	    {
	    	$modelFind = Tasa::findOne($idImpuesto);
	    	return isset($modelFind) ? $modelFind : null;
	    }


	    /***/
	    public function afterValidate()
	    {
	    	$this->id_impuesto = 0;
	    	$searchLiquidar = New LiquidarTasaSearch();
	    	$model = $searchLiquidar->findIdImpuesto($this->impuesto, $this->ano_impositivo,
	    		                                     $this->id_codigo, $this->grupo_subnivel,
	    		                                     $this->codigo);
	    	if ( count($model) > 0 ) {
	    		$this->id_impuesto = $model[0]['id_impuesto'];
	    	}

	    	return $this->id_impuesto;
	    }



	}


?>