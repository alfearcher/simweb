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
		public $periodo;
		public $ano_hasta;
		public $fecha_desde;
		public $fecha_hasta;
		public $fecha_hora;
		public $usuario;
		public $estatus;
		public $origen;						// Basicamente de donde se creo o quien creo el registro LAN o WEB
		public $fecha_hora_proceso;
		public $user_funcionario;

		public $ano_catalogo;
		public $ano_vence_ordenanza;

		const SCENARIO_FRONTEND = 'frontend';
		const SCENARIO_BACKEND = 'backend';

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [
        		self::SCENARIO_FRONTEND => [
        					'id_contribuyente',
        					'fecha_inicio',
        					'ano_impositivo',
        					'periodo',
        					'ano_hasta',
        					'fecha_desde',
        					'fecha_hasta',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        					'ano_vence_ordenanza',

        		],
        		self::SCENARIO_BACKEND => [
        					'id_contribuyente',
        					'fecha_inicio',
        					'ano_impositivo',
        					'periodo',
        					'ano_hasta',
        					'fecha_desde',
        					'fecha_hasta',
        					'origen',
        					'fecha_hora',
        					'usuario',
        					'estatus',
        					'ano_vence_ordenanza',

        		]
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_contribuyente',
	        	  'fecha_inicio', 'ano_impositivo',
	        	  'periodo', 'ano_hasta',
	        	  'fecha_desde', 'fecha_hasta'],
	        	  'required', 'on' => 'frontend',
	        	  'message' => Yii::t('frontend','{attribute} is required')],
	        	[['id_contribuyente',
	        	  'fecha_inicio', 'ano_impositivo',
	        	  'periodo', 'ano_hasta',
	        	  'fecha_desde', 'fecha_hasta'],
	        	  'required', 'on' => 'backend',
	        	  'message' => Yii::t('frontend','{attribute} is required')],
	        	[['nro_solicitud', 'id_contribuyente',
	        	  'ano_impositivo', 'ano_hasta',
	        	  'periodo', 'estatus'],
	        	  'integer', 'message' => Yii::t('frontend','{attribute}')],
	        	[['fecha_inicio', 'fecha_desde', 'fecha_hasta'],
	        	  'date', 'format' => 'yyyy-MM-dd',
	        	  'message' => Yii::t('frontend','formatted date no valid')],
	     		['nro_solicitud', 'default', 'value' => 0],
	     		['id_contribuyente', 'default', 'value' => $_SESSION['idContribuyente']],
	     		['origen', 'default', 'value' => 'WEB', 'on' => 'frontend'],
	     		['origen', 'default', 'value' => 'LAN', 'on' => 'backend'],
	     		['fecha_hora', 'default', 'value' => date('Y-m-d H:i:s')],
	     		['fecha_hora_proceso', 'default', 'value' => '0000-00-00 00:00:00'],
	     		['estatus', 'default', 'value' => 0],
	     		['usuario', 'default', 'value' => Yii::$app->identidad->getUsuario()],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->login, 'on' => 'frontend'],
	     		//['usuario', 'default', 'value' => Yii::$app->user->identity->username, 'on' => 'backend'],
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
	            'nro_solicitud' => Yii::t('backend', 'Request'),
	            'fecha_inicio' => Yii::t('backend', 'Begin Date'),
	            'fecha_desde' => Yii::t('backend', 'Fiscal Start Date'),
	            'fecha_hasta' => Yii::t('backend', 'Fiscal End Date'),
	            'periodo' => Yii::t('backend', 'Period'),
	            'ano_impositivo' => Yii::t('backend', 'Fiscal Year'),
	            'ano_catalogo' => Yii::t('backend', 'Category Year'),
	            'rubro' => Yii::t('backend', 'Category'),


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



	    /***/
	    public function getAddRubro($arrayRubros)
	    {
	    	return RubroForm::getAddDataProviderRubro($arrayRubros);
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