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
 *  @file TarifaAvaluoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-04-2016
 *
 *  @class TarifaAvaluoForm
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

	namespace backend\models\utilidad\tarifa\inmueble;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\utilidad\tarifa\inmueble\TarifaAvaluo;

	/**
	* 	Clase
	*/
	class TarifaAvaluoForm extends TarifaAvaluo
	{
		public $id_tarifa_avaluo;				// Autonumerico
		public $manzana_limite;
		public $ano_impositivo;
		public $valor_terreno;
		public $valor_construccion;
		public $minimo;
		public $tasa_construccion;
		public $tasa_terreno;
		public $ut_construccion;
		public $ut_terreno;
		public $inactivo;




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
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_tarifa_avaluo' => Yii::t('backend', 'Registro'),
	        	'manzana_limite' => Yii::t('backend', 'Manzana Limite'),
	        	'ano_impositivo' => Yii::t('backend', 'Año'),
	        	'valor_terreno' => Yii::t('backend', 'Monto Aplicar'),
	        	'valor_construccion' => Yii::t('backend', 'Monto Adicional'),
	        	'minimo' => Yii::t('backend', 'Monto Deduccion'),
	        	'tasa_construccion' => Yii::t('backend', 'Tipo Monto'),
	        	'tasa_terreno' => Yii::t('backend', 'Clase Vehiculo'),
	        	'ut_construccion' => Yii::t('backend', 'Tipo Monto'),
	        	'ut_terreno' => Yii::t('backend', 'Clase Vehiculo'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        ];
	    }
	}
?>