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
 *  @file InscripcionSucursalForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-10-2015
 *
 *  @class InscripcionSucursalForm
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

	namespace backend\models\aaee\inscripcionsucursal;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\inscripcionsucursal\InscripcionSucursal;
	use common\models\contribuyente\ContribuyenteBase;

	/**
	* 	Clase base del formulario inscripcion-sucursal-form.
	*/
	class InscripcionSucursalForm extends InscripcionSucursal
	{
		public $inscripcion_sucursal;
		public $nro_solicitud;
		public $id_sede_principal;
		public $id_contribuyente;
		public $naturaleza;
		public $cedula;
		public $tipo;
		public $razon_social;
		public $domicilio_fiscal;
		public $id_sim;
		public $tlf_ofic;
		public $tlf_ofic_otro;
		public $email;
		public $tlf_celular;
		public $fecha_inicio;
		public $origen;						// Basicamente de donde se creo o quien creo el registro LAN o WEB
		public $fecha_hora;
		public $usuario;
		public $estatus;
		public $num_tlf_ofic;
		public $num_tlf_ofic_otro;
		public $num_celular;

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
	        	[['id_sede_principal', 'id_contribuyente', 'naturaleza', 'cedula', 'tipo', 'domicilio_fiscal',
	        	'tlf_ofic', 'tlf_celular', 'email', 'fecha_inicio', 'razon_social'], 'required', 'message' => Yii::t('backend','{attribute} is required')],
	        	[['id_sede_principal', 'id_contribuyente', 'cedula', 'tipo'], 'integer','message' => Yii::t('backend','{attribute}')],
	        	[['fecha_inicio'], 'date', 'format' => 'dd-MM-yyyy','message' => Yii::t('backend','formatted date no valid')],
	        	[['email'], 'email', 'message' => Yii::t('backend','{attribute} is email')],
	        	['email', 'filter','filter'=>'strtolower'],
	        	['razon_social', 'filter', 'filter' => 'strtoupper'],
	        	[['domicilio_fiscal', 'naturaleza', 'tlf_celular', 'tlf_ofic', 'tlf_ofic_otro', 'id_sim'], 'string'],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	          	['id_sim', 'unique'],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->username],
	     		['origen', 'default', 'value' => 'LAN'],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		['id_contribuyente', 'default', 'value' => 0],
	     		['razon_social', 'string', 'max' => 75],
	     		[['tlf_ofic', 'tlf_ofic_otro', 'tlf_celular'], 'match', 'pattern' => '/^[0-9]{4}-[0-9]{7}$/', 'message' => Yii::t('backend', '{attribute} not valid')],
	     		[['tlf_ofic', 'tlf_ofic_otro', 'tlf_celular'], 'string', 'max' => 12],
	     		[['tlf_ofic', 'tlf_celular'], 'string', 'min' => 12],
	     		['id_sede_principal', 'default', 'value' => $_SESSION['idContribuyente']],
	     		[['num_tlf_ofic', 'num_tlf_ofic_otro', 'num_celular'], 'integer', 'message' => Yii::t('backend', '{attribute} is integer')],
	        ];
	    }






	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_inscripcion_sucursal' => Yii::t('backend', 'Id. Sucursal'),
	        	'id_sede_principal' => Yii::t('backend', 'Id. sede'),
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('backend', 'Application Number'),
	            'naturaleza' => Yii::t('backend', 'RIF'),
	            'cedula' => Yii::t('backend', 'DNI'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'domicilio_fiscal' => Yii::t('backend', 'Home'),
	            'tlf_ofic' => Yii::t('backend', 'Office No Phone'),
	            'tlf_ofic_otro' => Yii::t('backend', 'Office No Phone'),
	            'tlf_celular' => Yii::t('backend', 'Office Celular No'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'email' => Yii::t('backend', 'email'),
	            'razon_social' => Yii::t('backend', 'Razon Social'),
	            'id_sim' => Yii::t('backend', 'Licence Number'),
	            'num_celular' => Yii::t('backend', 'Celular Number'),
	            'num_tlf_ofic' => Yii::t('backend', 'Office Number 1'),
	            'num_tlf_ofic_otro' => Yii::t('backend', 'Office Number 2'),

	        ];
	    }





	    /**
	     * Metodo que permite verificar si un contribuyente posee todos los datos necesarios
	     * del registro mercantil, esto permitira crear la sucursal y asumir los mismos valores
	     * que aparece en el registro mercantil de la sede principal.
	     * @param  $datos, array que posee los datos de la sede que se pretende tomar como principal.
	     * @return return boolean, true si todos los datos estan bien, false no se puede crear la sucursal.
	     */
	    public static function datosRegistroMercantilValido($datos)
	    {
	    	if ( is_array($datos) ) {
	    		if ( !isset($datos[0]['fecha']) ) { return false; }
	    		if ( !isset($datos[0]['reg_mercantil']) ) { return false; }
	    		if ( !isset($datos[0]['num_reg']) ) { return false; }
	    	} else {
	    		return false;
	    	}
	    	return true;
	    }



	    /**
	     * Metodo para determinar si un contribuyente es una sede principal.
	     * @param  $datos, array que posee los datos de la sede que se pretende tomar como principal.
	     * @return return boolean, true si es una sede principal, y false no lo es.
	     */
	    public static function sedePrincipal($datos)
	    {
	    	if ( is_array($datos) ) {
	    		if ( $datos[0]['id_rif'] == 0 ) {
	    			return true;
	    		}
	    	}
	    	return false;
	    }


	}
?>