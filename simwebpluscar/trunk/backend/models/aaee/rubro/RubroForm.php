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
 *  @file RubroForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-10-2015
 *
 *  @class RubroForm
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

	namespace backend\models\aaee\rubro;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\rubro\Rubro;
	use backend\models\utilidad\ut\UnidadTributariaForm;



	/**
	* 	Clase base del formulario rubro-form.
	*/
	class RubroForm extends Rubro
	{
		public $id_rubro;
		public $ente;
		public $ano_impositivo;
		public $rubro;
		public $descripcion;
		public $alicuota;
		public $minimo;
		public $minimo_ut;
		public $licores;
		public $divisor_alicuota;
		public $id_rubro_aseo;
		public $monto_aseo;
		public $tipo_monto;
		public $calculo_por_unidades;
		public $id_metodo;

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
	        	[['ente', 'rubro', 'ano_impositivo', 'descripcion', 'alicuota', 'divisor_alicuota', 'minimo', 'minimo_ut'], 'required', 'message' => Yii::t('backend','{attribute} is required')],
	        	[['ente', 'ano_impositivo'], 'integer','message' => Yii::t('backend','{attribute}')],
	          	['ente', 'default', 'value' => Yii::$app->ente->getidente],
	        ];
	    }



	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'id_rubro' => Yii::t('backend', 'Id. Rubro'),
	            'rubro' => Yii::t('backend', 'Rubro'),
	            'ano_impositivo' => Yii::t('backend', 'Fiscal Year'),
	            'descripcion' => Yii::t('backend', 'descripcion'),
	            'alicuota' => Yii::t('backend', 'Alicuota'),
	            'minimo' => Yii::t('backend', 'Minimo'),
	            'minimo_ut' => Yii::t('backend', 'Minimo Ut'),
	            'licores' => Yii::t('backend', 'Licor'),
	            'divisor_alicuota' => Yii::t('backend', 'Divisor Alicuota'),
	            'id_rubro_aseo' => Yii::t('backend', 'Id Rubro Aseo'),
	            'monto_aseo' => Yii::t('backend', 'Monto Aseo'),
	            'tipo_monto' => Yii::t('backend', 'Tipo de Monto'),
	            'calculo_por_unidades' => Yii::t('backend', 'Calculo Por Unidades'),
	            'id_metodo' => Yii::t('backend', 'Metodo de determinacion del impuesto'),

	        ];
	    }





	    /**
	     * [getPrimerAnoCatalogoRubro description]
	     * @return return un array con el formato campo => valor, solo una linea.
	     */
	    public static function getPrimerAnoCatalogoRubro()
	    {
	    	// select distinct ano_impositivo from rubros order by asc ano_impositivo limit 1
	    	$ano = Rubro::find()->select('ano_impositivo')
	    					    ->distinct()
	    					    ->orderBy(['ano_impositivo' => SORT_ASC])
	    					    ->one();

	    	$anoImpositivo = $ano->toArray(['ano_impositivo']);
	    	return $anoImpositivo['ano_impositivo'];
	    }




	    /**
	     * [getUltimoAnoCatalogoRubro description]
	     * @return return un array con el formato campo => valor, solo una linea.
	     */
	     public static function getUltimoAnoCatalogoRubro()
	    {
	    	// select distinct ano_impositivo from rubros order by desc ano_impositivo limit 1
	    	$ano = Rubro::find()->select('ano_impositivo')
	    			     		 ->distinct()
	    					     ->orderBy(['ano_impositivo' => SORT_DESC])
	    					     ->one();

	    	$anoImpositivo = $ano->toArray(['ano_impositivo']);
	    	return $anoImpositivo['ano_impositivo'];
	    }





	    /**
	     * [getDataProviderRubro description]
	     * @param  [type] $anoImpositivo [description]
	     * @param  string $params        [description]
	     * @return [type]                [description]
	     */
	    public function getDataProviderRubro($anoImpositivo, $params = '', $exceptoIdRubro = [])
	    {

	    	// Se crea una instancia del tipo yii\db\ActiveQueryInterface
	    	$query = Rubro::find();

	    	$dataProvider = new ActiveDataProvider([
	            'query' => $query,
	        ]);

	    	$query->where('ano_impositivo =:ano_impositivo', [':ano_impositivo' => $anoImpositivo])
	    		  ->andWhere('inactivo =:inactivo',[':inactivo' => 0]);

	    	if ( trim($params) !== '' ) {
	    		$primerCaracter = mb_substr($params, 0, 1);
	    		if ( is_numeric($primerCaracter) ) {
	    			$arrayCodigos = explode(',', $params);
	    			$params = '';
	    			foreach ( $arrayCodigos as $key => $value ) {
	    				$arrayCodigo[$key] = (int)$value;
	    			}
	    			$query->andFilterWhere(['in', 'rubro', $arrayCodigo]);
	    		} else {
	    			$query->andFilterWhere(['like', 'descripcion', $params]);
	    		}
	   		}

	   		if ( count($exceptoIdRubro) > 0 ) {
	   			$query->andFilterWhere(['not in', 'id_rubro', $exceptoIdRubro]);
	   		}

	        return $dataProvider;
	    }




	    /***/
	    public function getAddDataProviderRubro($arrayRubros)
	    {
	    	// Se crea una instancia del tipo yii\db\ActiveQueryInterface
	    	$query = Rubro::find();

	    	$dataProvider = new ActiveDataProvider([
	            'query' => $query,
	        ]);

	    	//$query->where('id_rubro = :id_rubro', [':id_rubro' => $idRubro]);
	    	$query->where(['in', 'id_rubro', $arrayRubros]);

	        return $dataProvider;
	    }




	    /***/
	    public function getMinimoTributableRubro($idRurbo)
	    {
	    	$minimoTributable = 0;
	    	$rubro = Rubro::find()->where(['id_rubro' => $idRurbo])
	    						  ->one();
	    	if ( count($rubro) > 0 ) {
	    		if ( $rubro->minimo > 0 ) {				// El minimo a considerar esta en bolivares.
	    			$minimoTributable = $rubro->minimo;

	    		} elseif ( $rubro->minimo_ut > 0 ) {	// El minimo s considerar esta en Unidad Tributaria.
	    			$unidadTributaria = self::getUnidadTributariaPorAnoImpositivo($rubro->ano_impositivo);
	    			$minimoTributable = $unidadTributaria * $rubro->minimo_ut;
	    		}
	    	}
	    	return $minimoTributable;
	    }




	    /***/
	    public function getUnidadTributariaPorAnoImpositivo($añoImpositivo)
	    {
	    	settype($añoImpositivo, 'integer');
	    	$ut = New UnidadTributariaForm();
	    	$montoUT = $ut->getUnidadTributaria($añoImpositivo);
	    	if ( isset($montoUT) ) {
	    		return $montoUT;
	    	}
	    	return 0;
	    }

	}
?>