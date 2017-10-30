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
 *  @file AjustarNotaDebitoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-10-2017
 *
 *  @class AjustarNotaDebitoForm
 *  @brief Clase Modelo del formulario que se utilizara para buscar los registros
 *  que presentan dotas de debitos incorrectas en la entidad detalle de la planilla.
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

	namespace backend\models\ajuste\pago\notadebito;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use yii\helpers\ArrayHelper;



	/**
	* Clase modelo del formulario que se utilizara para buscar las notas de debitos
	* erradas que existen en el detalle de la panilla. Se muestra un formulario
	* con opciones de busqueda por fecha de pago y numero de recibo.
	*/
	class AjustarNotaDebitoForm extends Model
	{
		public $fecha_pago;
		public $recibo;

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}


    	/**
		 * Metodo que indica las reglas de validacion del formulario de consulta.
		 * @return array
		 */
	    public function rules()
	    {
	        return [
	        	['recibo',
	        	  'integer',
	        	  'message' => Yii::t('backend','Debe ser un numero entero')],
	        	['fecha_pago',
	        	 'date',
	        	 'message' => Yii::t('backend','Debe ser una fecha')],
	        	['recibo', 'fecha_pago', 'safe'],
	        ];
	    }


	    /***/
	    public function attributeLabels()
	    {
	        return [
	        	'recibo' => Yii::t('backend', 'Nro. de Recibo'),
	        	'fecha_pago' => Yii::t('backend', 'Fecha de Pago'),
	        ];
	    }


	    /***/
	    public function findPlanillaModel()
	    {

	    }
	}
?>