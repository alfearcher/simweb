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
 *  @file UnidadTributariaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 24-03-2016
 *
 *  @class UnidadTributariaForm
 *  @brief Clase Modelo que maneja la politica
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

	namespace backend\models\utilidad\ut;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;

	/**
	* 	Clase
	*
	*/
	class UnidadTributariaForm extends UnidadTributaria
	{

		public $id_ut;
		public $ente;
		public $fecha_inicio;
		public $fecha_fin;
		public $monto_ut;
		public $ultimo;



		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario create-ut-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['fecha_inicio', 'fecha_fin', 'monto_ut'],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['monto_ut'], 'double', 'message' => Yii::t('backend','{attribute} must be a number')],
	     		['ultimo', 'default', 'value' => 0],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	            'id_ut' => Yii::t('backend', 'Register No.'),
	            'fecha_inicio' => Yii::t('backend', 'Date Begin'),
	            'fecha_fin' => Yii::t('backend', 'Date End'),
	            'monto_ut' => Yii::t('backend', 'Current'),
	            'ente' => Yii::t('backend', 'Ente'),
	            'ultimo' => Yii::t('backend', 'Last'),

	        ];
	    }




	    /***/
	    public function getUnidadTributaria($param)
	    {
	    	if ( is_integer($param) ) {
	    		$model = UnidadTributaria::find()->where(['Year(fecha_inicio)' => $param])->one();

	    	} elseif ( date($param) ) {

	    		//$model = Rubro::find()->where($fecha . ' between fecha_inicio and fecha_fin')->one();
	    		//$anoImpositivo = isset($fecha) ? date('Y', strtotime($fecha)) : 0;
	    	}
	    	if ( isset($model) ) {
	    		return $model->monto_ut;
	    	} else {
	    		return null;
	    	}
	    }



	    /**
	     * Metodo que determina el monto a aplicar dependiento del tipo de monto,
	     * lo retornado estara expresado en moneda nacional ( Bolivares ). Si el
	     * tipo de monto es diferente a cero (0) se debe determinar el equivalente
	     * en bolivares o moneda nacional.
	     * @param  Array $parametros, variable que debe estar estructurada de la siguiente
	     * manera:
	     * Array {
	     * 			$parametros['tipo_rango'] o $parametros['tipo_monto']
	     *   		$parametros['monto']
	     *   	 	$parametros['ano_impositivo']
	     *   	 	.....
	     *   	 	.....
	     *   	 	otros parametros
	     *   	}
	     * @return Double Retorna un monto expresado en moneda, sino se determina
	     * el monto retornara cero (0).
	     */
	    public function getMontoAplicar($parametros)
	    {
	    	$monto = 0;
	    	$tipo = 0;
	    	$continuo = false;
	    	if ( count($parametros) > 0 ) {
	    		if ( isset($parametros['tipo_rango']) ) {
	    			$tipo = $parametros['tipo_rango'];
	    			$continuo = true;

	    		} elseif ( isset($parametros['tipo_monto']) ) {
	    			$tipo = $parametros['tipo_monto'];
	    			$continuo = true;

	    		}
	    		if ( $continuo ) {
			    	if ( $tipo == 0 ) {					// Bolivares
			    		$monto = $parametros['monto'];

			    	} elseif ( $tipo == 1 ) {			// Unidad Tributaria
			    		$año = isset($parametros['ano_impositivo']) ? $parametros['ano_impositivo'] : null;
			    		settype($año, 'integer');
			    		$unidadTributaria = self::getUnidadTributaria($año);
			    		$monto = $parametros['monto'] * $unidadTributaria;
			    	}
			    }

		    }
		    return $monto;
	    }


	}

?>