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
 *  @file PlanillaConsultaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @class PlanillaConsultaForm
 *  @brief Clase Modelo que permite realizar las consultas de las planillas liquidadas pendientes de un
 * contribuyente y de sus objetos relacionados.
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

	namespace backend\models\planilla\consulta;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;


	/**
	* 	Clase
	*/
	class PlanillaConsultaForm extends Model
	{

		public $id_contribuyente;
		public $impuesto;
		public $id_impuesto;

		const SCENARIO_CONTRIBUYENTE = 'contribuyente';
		const SCENARIO_OBJETOS = 'objetos';




		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
	        	self::SCENARIO_CONTRIBUYENTE => [
	        				'id_contribuyente',
	        				'impuesto',
	        		],
	        	self::SCENARIO_OBJETOS => [
	        				'id_contribuyente',
	        				'impuesto',
	        				'id_impuesto',
	        		],
	        ];

    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-accionista-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente', 'impuesto',
	        	  'id_impuesto',],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['id_contribuyente', 'impuesto'],
	        	  'required', 'on' => 'contribuyente', 'message' => '{attribute} is required'],
	        	[['id_contribuyente', 'impuesto', 'id_impuesto'],
	        	  'required', 'on' => 'objetos', 'message' => '{attribute} is required'],
	        	[['usuario'], 'default', 'value' => Yii::$app->identidad->getUsuario()],
	        	[['fecha_hora'], 'default', 'value' => date('Y-m-d H:i:s')],
	        	[['id_contribuyente'], 'default', 'value' => $_SESSION['idContribuyente']],

	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'impuesto' => Yii::t('backend', 'Impuestos'),
	        	'id_impuesto' => Yii::t('backend', 'Id. Objeto'),
	        ];
	    }


	}

?>