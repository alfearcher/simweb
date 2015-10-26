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
 *  @file AutorizarRamoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-10-2015
 *
 *  @class AutorizarRamoForm
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

	namespace backend\models\aaee\autorizarramo;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\autorizarramo\AutorizarRamo;
	use backend\models\aaee\rubro\RubroForm;



	/**
	* 	Clase base del formulario autorizar-ramo-form.
	*/
	class AutorizarRamoForm extends AutorizarRamo
	{
		public $id_rubro_aprobado;
		public $nro_solicitud;
		public $id_contribuyente;
		public $fecha_inicio;
		public $ano_impositivo;
		public $id_rubro;
		public $fecha_hora;
		public $usuario;
		public $estatus;
		public $origen;						// Basicamente de donde se creo o quien creo el registro LAN o WEB


		public $ano_catalogo;
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
	        	[['nro_solicitud', 'id_contribuyente', 'fecha_inicio', 'ano_impositivo', 'id_rubro'], 'required', 'message' => Yii::t('backend','{attribute} is required')],
	        	[['nro_solicitud', 'id_contribuyente', 'ano_impositivo', 'id_rubro'], 'integer','message' => Yii::t('backend','{attribute}')],
	        	[['fecha_inicio'], 'date', 'format' => 'dd-MM-yyyy','message' => Yii::t('backend','formatted date no valid')],
	          	['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->user->identity->username],
	     		['origen', 'default', 'value' => 'LAN'],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		['id_contribuyente', 'default', 'value' => $_SESSION['idContribuyente']],
	        ];
	    }





	    /**
	    * Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * @return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_rubro_aprobado' => Yii::t('backend', 'Id. Record'),
	            'id_contribuyente' => Yii::t('backend', 'Id. Taxpayer'),
	            'nro_solicitud' => Yii::t('backend', 'Application Number'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'ano_impositivo' => Yii::t('backend', 'Fiscal Year'),
	            'ano_catalogo' => Yii::t('backend', 'Category Year'),

	        ];
	    }




	    /**
	     * [determinarPrimerAnoCatalogoRubro description]
	     * @return retorna un integer con 4 digitos.
	     */
	    public function determinarPrimerAnoCatalogoRubro()
	    {
	    	return $anoInicioCatalogo = RubroForm::getPrimerAnoCatalogoRubro();
	    }




	    /**
	     * [determinarUltimoAnoCatalogoRubro description]
	     * @return @return retorna un integer con 4 digitos.
	     */
	    public function determinarUltimoAnoCatalogoRubro()
	    {
	    	return $anoFinalCatalogo = RubroForm::getUltimoAnoCatalogoRubro();
	    }




	    /**
	     * Metodo que permite determinar el año del catalogo de rubro que le corresponde
	     * según el año de inicio de actividades del contribuyente juridico, la intencion
	     * es determinar el año del catalogo de rubros para listarlos.
	     * @param $anoInicio, integer que define el año de inicio de actividades, esto deriva
	     * de la fecha de inicio del contribuyente.
	     * @return returna integer, año de 4 digitos o cero (0) si no logra determinar el año.
	     */
	    public function determinarAnoCatalogoSegunAnoInicio($anoInicio)
	    {
	    	if ( $anoInicio > 0 ) {
	    		$primerAnoCatalogo = self::determinarPrimerAnoCatalogoRubro();
	    		$ultimoAnoCatalogo = self::determinarUltimoAnoCatalogoRubro();
	    		if ( $primerAnoCatalogo > 0 && $ultimoAnoCatalogo > 0 ) {
	    			if ( $anoInicio == $primerAnoCatalogo ) {
	    				return $primerAnoCatalogo;

	    			} elseif ( $anoInicio < $primerAnoCatalogo ) {
	    				return $primerAnoCatalogo;

	    			} elseif ( $anoInicio > $primerAnoCatalogo ) {
	    				for ($i = $primerAnoCatalogo; $i <= $ultimoAnoCatalogo; $i++ ) {
	    					if ( $i == $anoInicio ) {
	    						return $anoInicio;
	    					}
	    				}
	    			}
	    		}
	    	}
	    	return 0;
	    }






	    /**
	     * Metodo que permite obtener un dataProvider que permite generar un catalogo de los
	     * rubros según un año y paramatros adicionales.
	     * @param  [type] $anoImpositivo [description]
	     * @param  string $params        [description]
	     * @return returna un a instancia de tipo dataProvider.
	     */
	    public function searchRubro($anoImpositivo, $params = '')
	    {
	    	return RubroForm::getDataProviderRubro($anoImpositivo, $params);
	    }




	    public function getAddRubro($arrayRubros)
	    {
	    	return RubroForm::getAddDataProviderRubro($arrayRubros);
	    }

	}
?>