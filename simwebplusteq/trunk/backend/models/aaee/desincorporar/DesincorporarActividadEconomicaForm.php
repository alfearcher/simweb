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
 *  @file DesincorporarActividadEconomicaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-05-2017
 *
 *  @class DesincorporarActividadEconomicaForm
 *  @brief Clase Modelo del formulario
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

	namespace backend\models\aaee\desincorporar;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\desincorporar\DesincorporarActividadEconomica;
	use backend\models\aaee\desincorporaramo\DesincorporarRamoSearch;



	/**
	* 	Clase base del formulario desincorporacion de actividad economica.
	*/
	class DesincorporarActividadEconomicaForm extends DesincorporarActividadEconomica
	{
		public $id_desincorporar_aaee;
		public $nro_solicitud;
		public $id_contribuyente;
		public $ult_declaracion;
		public $ult_pago;
		public $fecha_hora;
		public $usuario;
		public $origen;						// Basicamente de donde se creo o quien creo el registro LAN o WEB
		public $estatus;
		public $fecha_hora_proceso;
		public $user_funcionario;



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
	        	[['id_contribuyente'],
	        	  'required',
	        	  'message' => Yii::t('frontend','{attribute} is required')],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		['id_contribuyente', 'default', 'value' => $_SESSION['idContribuyente']],
	     		//['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
	     		//['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
	     		['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['fecha_hora_proceso', 'default', 'value' => '0000-00-00 00:00:00'],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		[['ult_declaracion', 'ult_pago', 'origen'], 'safe'],
	        ];
	    }





	    /**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_desincorporar_ramo' => Yii::t('frontend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('frontend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('frontend', 'Request'),
	            'razon_social' => Yii::t('frontend', 'Company Name'),
	            'domicilio_fiscal' => Yii::t('frontend', 'Addrres Office'),
	            'id_sim' => Yii::t('frontend', 'License'),
	            'ult_declaracion' => Yii::t('frontend', 'Ultima Declaracion'),
	            'ult_pago' => Yii::t('frontend', 'Ultimo Pago'),
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