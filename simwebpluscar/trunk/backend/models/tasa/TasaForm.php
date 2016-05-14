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
 *  @file TasaForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-04-2016
 *
 *  @class TasaForm
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

	namespace backend\models\tasa;

 	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\tasa\Tasa;

	/**
	* 	Clase
	*/
	class TasaForm extends Tasa
	{
		public $id_impuesto;				// Autonumerico
		public $id_codigo;
		public $impuesto;
		public $ano_impositivo;
		public $grupo_subnivel;
		public $codigo;
		public $descripcion;
		public $monto;
		public $tipo_rango;
		public $inactivo;
		public $cantidad_ut;





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
	        	'id_impuesto' => Yii::t('backend', 'Register Number'),
	        	'id_codigo' => Yii::t('backend', 'Code Preupuestario'),
	        	'impuesto' => Yii::t('backend', 'Impuesto'),
	        	'ano_impositivo' => Yii::t('backend', 'Year'),
	        	'grupo_subnivel' => Yii::t('backend', 'Grupo'),
	        	'codigo' => Yii::t('backend', 'Code'),
	        	'descripcion' => Yii::t('backend', 'Description'),
	        	'monto' => Yii::t('backend', 'Current'),
	        	'tipo_rango' => Yii::t('backend', 'Type Current'),
	        	'inactivo' => Yii::t('backend', 'Condition'),
	        	'cantidad_ut' => Yii::t('backend', 'Cantidad UT'),
	        ];
	    }




	    /**
	     * Metodo que busca el registro segun el identificador de la entidad.
	     * @param  Long $idImpuesto, [identificador del registro.
	     * @return ActiveRecord Retornara un model de tipo active record.
	     */
	    public function findTasa($idImpuesto)
	    {
	    	$modelFind = Tasa::findOne($idImpuesto);
	    	return isset($modelFind) ? $modelFind : null;
	    }



	    /***/
	    public function findTasaSegunParametros($idCodigo, $impuesto, $anoImpositivo, $grupoSubnivel, $codigo, $inactivo = 0)
	    {
	    	$modelFind = Tasa::find()->where('id_codigo =:id_codigo', [':id_codigo' => $idCodigo])
	    							 ->andWhere('impuesto =:impuesto', [':impuesto' => $impuesto])
	    							 ->andWhere('anoImpositivo =:anoImpositivo', [':anoImpositivo' => $anoImpositivo])
	    							 ->andWhere('grupoSubnivel =:grupoSubnivel', [':grupoSubnivel' => $grupoSubnivel])
	    							 ->andWhere('codigo =:codigo', [':codigo' => $codigo])
	    							 ->andWhere('inactivo =:inactivo', [':inactivo' => $inactivo]);

	    	return isset($modelFind) ? $modelFind : null;
	    }



	    /**
	     * Metodo que recibe el model y convierte el mismo en un arraeglo para retornarlo.
	     * @param Long $idImpuesto, identificador de la entidad.
	     * @return Array $parametros, retornara el arreglo con las columnas de la entidad.
	     */
	    public function getValoresTasa($idImpuesto)
	    {
	    	$parametros = null;
	    	$model = self::findTasa($idImpuesto);
	    	if ( isset($model) ) {
	    		$parametros = $model->toArray();
	    	}
	    	return $parametros;
	    }



	    /***/
	    public function determinarTasaParaLiquidar($idImpuesto)
	    {
	    	$idTasa = 0;
	    	$añoActual = date('Y');
	    	$result = $this->laTasaCorresponde($idImpuesto, $añoActual);
	    	if ( $result != null ) {
	    		if ( $result ) {
	    			
	    		}
	    	}
	    }



	    /**
	     * Metodo que permite determiinar si una tasa corresponde a un año especifico.
	     * @param  [type] $idImpuesto identificador de la tasa. Autoincremental de la entidad.
	     * @param  [type] $anoImpositivo año impositivo que se quiere determinar y que sera el
	     * parametro que corresponda con el año de la tasa.
	     * @return Boolean Retorna True si corresponde el año a la tasa, False en caso contrario.
	     * Si retorna Null, significa que la consulta del parametro $idImpuesto no arrojo ningun
	     * resultado.
	     */
	    public function laTasaCorresponde($idImpuesto, $anoImpositivo)
	    {
	    	$parametros = $this->getValoresTasa($idImpuesto);
	    	if ( count($parametros) > 0 ) {
	    		if ( $anoImpositivo == $parametros['ano_impositivo'] ) {
	    			// Esta es la tasa
	    			return true;
	    		} else {
	    			return false;
	    		}
	    	}
	    	return null;
	    }

	}
?>