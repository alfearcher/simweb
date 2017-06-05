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
 *  @file SolvenciaActividadEconomicaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2016
 *
 *  @class SolvenciaActividadEconomicaForm
 *  @brief Clase Modelo del formulario para solicitar las licencias.
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

	namespace backend\models\aaee\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\solvencia\SolvenciaActividadEconomica;


	/**
	* Clase del formulario que permite gestionar la solicitud de emision de Solvencia.
	*/
	class SolvenciaActividadEconomicaForm extends SolvenciaActividadEconomica
	{
		public $id_solicitud;
		public $nro_solicitud;
		public $id_contribuyente;
		public $impuesto;
		public $id_impuesto;
		public $ano_impositivo;
		public $usuario;
		public $fecha_hora;
		public $origen;
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;
		public $observacion;
		public $ultimo_pago;




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
	        	[['id_contribuyente', 'ano_impositivo',
	        	  'estatus', 'nro_solicitud',
	        	  'id_impuesto', 'impuesto'],
	        	  'integer', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['origen', 'observacion', 'ultimo_pago'],
	        	  'string', 'message' => Yii::t('backend', 'Formato de valores incorrecto')],
	        	[['estatus'], 'default', 'value' => 0],
	        	[['usuario'], 'default', 'value' => Yii::$app->identidad->getUsuario()],
	        	[['fecha_hora'], 'default', 'value' => date('Y-m-d H:i:s')],

	        ];
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        ];
	    }




	    /**
	     * Metodo que retorna un arreglo de atributos que seran actualizados
	     * al momento de procesar la solicitud (aprobar o negar). Estos atributos
	     * afectaran a la entidad respectiva de la clase.
	     * @param String $evento, define la accion a realizar sobre la solicitud.
	     * - Aprobar.
	     * - Negar.
	     * @return Array Retorna un arreglo de atributos segun el evento.
	     */
	    public function atributosUpDateProcesarSolicitud($evento)
	    {
	    	$atributos = [
	    		Yii::$app->solicitud->aprobar() => [
	    						'estatus' => 1,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => Yii::$app->identidad->getUsuario(),

	    		],
	    		Yii::$app->solicitud->negar() => [
	    						'estatus' => 9,
	    						'fecha_hora_proceso' => date('Y-m-d H:i:s'),
	    						'user_funcionario' => Yii::$app->identidad->getUsuario(),

	    		],
	    	];

	    	return $atributos[$evento];
	    }


	}
?>