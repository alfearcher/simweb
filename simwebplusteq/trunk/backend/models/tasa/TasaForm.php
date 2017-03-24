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
	    public function findTasaSegunAnoDescripcion($añoImpositivoLocal, $descripcionLocal)
	    {
	    	$modelFind = Tasa::find()->where('ano_impositivo =:ano_impositivo', [':ano_impositivo' => $añoImpositivoLocal])
	    							 ->andWhere('inactivo =:inactivo', [':inactivo' => 0])
	    							 ->andWhere(['LIKE', 'descripcion', $descripcionLocal])
	    							 ->all();

	    	return isset($modelFind) ? $modelFind : null;
	    }




	    /***/
	    public function findTasaSegunParametros($idCodigo, $impuesto, $anoImpositivo, $grupoSubnivel, $codigo, $inactivo = 0)
	    {
	    	$modelFind = Tasa::find()->where('id_codigo =:id_codigo', [':id_codigo' => $idCodigo])
	    							 ->andWhere('impuesto =:impuesto', [':impuesto' => $impuesto])
	    							 ->andWhere('ano_impositivo =:ano_impositivo', [':ano_impositivo' => $anoImpositivo])
	    							 ->andWhere('grupo_subnivel =:grupo_subnivel', [':grupo_subnivel' => $grupoSubnivel])
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



	    /**
	     * Metodo que permite definir el identificador de la tasa que se debe utilizar.
	     * Se utiliza un identificador inicial ($idImpuesto), para determinar si corresponde
	     * al año actual, de ser asi, se devuelve este identificador como la tasa que se debe
	     * utilizar, de no ser asi, se consultan los parametros que acompañan al identificador
	     * ($idImpuesto), para armar una consulta con estos parametros diferenciando en el año
	     * impositivo, el cual sera el año actual.
	     * @param  Long $idImpuesto identificador del registro en la entidad. Autoincremental.
	     * @return Long Retorna un valor que representa el identificador en la entidad respectiva
	     * sino encuentra el identificador devolvera cero (0).
	     */
	    public function determinarTasaParaLiquidar($idImpuesto)
	    {
	    	$idTasa = 0;
	    	$añoActual = date('Y');
	    	$result = $this->laTasaCorresponde($idImpuesto, $añoActual);

	    	if ( $result !== null ) {
	    		if ( $result ) {
	    			// La tasa coresponde con el año.
	    			$idTasa = $idImpuesto;
	    		} else {
	    			// Con el $idImpuesto se buscan los parametros adicionales que permitira
	    			// localizar otra tasa que corresponda a esos parametros pero diferenciando
	    			// el año, el cual sera el actual.
	    			$parametros = $this->getValoresTasa($idImpuesto);
	    			if ( count($parametros) > 0 ) {
	    				$idCodigo = $parametros['id_codigo'];
	    				$impuesto = $parametros['impuesto'];
	    				$añoImpositivo = $añoActual;
	    				$grupoSubnivel = $parametros['grupo_subnivel'];
	    				$codigo = $parametros['codigo'];

	    				$model = $this->findTasaSegunParametros($idCodigo, $impuesto, $añoImpositivo, $grupoSubnivel, $codigo);
	    				$valores = $model->asArray()->all();

	    				if ( count($valores) > 0 ) {
	    					$idTasa = $valores[0]['id_impuesto'];
	    				}
	    			}
	    		}
	    	}

	    	return $idTasa;
	    }



	    /**
	     * Metodo que permite determiinar si una tasa corresponde a un año especifico.
	     * @param  Long $idImpuesto identificador de la tasa. Autoincremental de la entidad.
	     * @param  Integer $anoImpositivo año impositivo que se quiere determinar y que sera el
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



	    /**
	     * Metodo que permite determinar el identificador real para un año especifico de la
	     * tasa, se tomara un identificador ($idImpuesto) y a partir de este, se buscara la informacion
	     * que se complementará con el año impositivo enviado. Esto se convinara para realizar una
	     * consulta encadenada con los datos recopilados.
	     * @param integer $idImpuesto identificador del registro en la entidad. Autoincremental.
	     * @param inetger $añoImpositivo año impositivo donde se desea encontrar el identificador de
	     * la tasa.
	     * @return integer retorna un valor que representa el identificador en la
	     * entidad respectiva sino encuentra el identificador devolvera cero (0).
	     */
	    public function determinarTasaRealSegunAnoImpositivo($idImpuesto, $añoImpositivo)
	    {
	    	$idTasa = 0;
	    	$result = self::laTasaCorresponde($idImpuesto, $añoImpositivo);

	    	if ( $result !== null ) {
	    		if ( $result ) {
	    			// La tasa coresponde con el año.
	    			$idTasa = $idImpuesto;
	    		} else {
	    			// Con el $idImpuesto se buscan los parametros adicionales que permitira
	    			// localizar otra tasa que corresponda a esos parametros pero diferenciando
	    			// el año, el cual sera el actual.
	    			$parametros = self::getValoresTasa($idImpuesto);
	    			if ( count($parametros) > 0 ) {
	    				$idCodigo = $parametros['id_codigo'];
	    				$impuesto = $parametros['impuesto'];
	    				$grupoSubnivel = $parametros['grupo_subnivel'];
	    				$codigo = $parametros['codigo'];

	    				$model = self::findTasaSegunParametros($idCodigo, $impuesto, $añoImpositivo, $grupoSubnivel, $codigo);
	    				$valores = $model->asArray()->all();

	    				if ( count($valores) > 0 ) {
	    					$idTasa = $valores[0]['id_impuesto'];
	    				}
	    			}
	    		}
	    	}

	    	return $idTasa;
	    }

	}
?>