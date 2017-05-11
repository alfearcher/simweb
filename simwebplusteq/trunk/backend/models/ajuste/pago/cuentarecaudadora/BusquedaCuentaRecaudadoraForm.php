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
 *  @file BusquedaCuentaRecaudadoraForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-05-2017
 *
 *  @class BusquedaCuentaRecaudadoraForm
 *  @brief Clase Modelo del formulario que permite buscar los registros
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

	namespace backend\models\ajuste\pago\cuentarecaudadora;

 	use Yii;
	use yii\base\Model;
	use backend\models\recibo\deposito\Deposito;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\depositodetalle\DepositoDetalle;


	/**
	* Clase base del formulario
	*/
	class BusquedaCuentaRecaudadoraForm extends Model
	{

		public $recibo;
		public $fecha_desde;
		public $fecha_hasta;
		public $codigo_banco;
		public $cuenta_deposito;
		public $cuenta_deposito_faltante;

		const SCENARIO_RECIBO = 'recibo';
		const SCENARIO_LOTE = 'lote';
		const SCENARIO_UDPATE = 'update';
		const SCENARIO_DEFAULT = 'default';

		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	//return Model::scenarios();
        	return [

        		self::SCENARIO_RECIBO => [
        				'recibo',
        		],
        		self::SCENARIO_LOTE => [
        				'fecha_desde',
        				'fecha_hasta',
        				'codigo_banco',
        				'cuenta_deposito',
        				'cuenta_deposito_faltante',
        		],
        		self::SCENARIO_UDPATE => [
        				'codigo_banco',
        				'cuenta_deposito',
        		],
        		self::SCENARIO_DEFAULT => [
        		],
        	];
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['recibo'],
	        	  'required', 'on' => 'recibo',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['recibo', 'codigo_banco',],
	        	  'integer'],
	        	[['fecha_desde', 'fecha_hasta'],
	        	  'required', 'on' => 'lote',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	['fecha_desde' , 'rangoValido', 'on' => 'lote'],
	        	[['codigo_banco', 'cuenta_deposito'],
	        	  'required', 'on' => 'update',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        ];
	    }



	    /**
	     * Metodo que permite la validacion del rango de las fechas.
	     * @return none
	     */
	    public function rangoValido()
	    {
	    	if ( trim($this->fecha_desde) !== '' && trim($this->fecha_hasta) !== '' ) {

	    		$fDesde = date('Y-m-d', strtotime($this->fecha_desde));
	    		$fHasta = date('Y-m-d', strtotime($this->fecha_hasta));

	    		if ( (int)date('Y', strtotime($fDesde)) == (int)date('Y', strtotime($fHasta)) ) {
	    			if ( (int)date('m', strtotime($fDesde)) == (int)date('m', strtotime($fHasta)) ) {
	    				if ( (int)date('d', strtotime($fDesde)) > (int)date('d', strtotime($fHasta)) ) {

	    					$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));

	    				}

	    			} elseif ( (int)date('m', strtotime($fDesde)) > (int)date('m', strtotime($fHasta)) ) {

	    				$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));

	    			}

	    		} elseif ( (int)date('Y', strtotime($fDesde)) < (int)date('Y', strtotime($fHasta)) ) {

	    			$this->addError('fecha_desde', Yii::t('backend', 'Rango de fecha no es valido'));
	    		}
	    	}
	    }




	    /**
	    * 	Lista de atributos con sus respectivas etiquetas (labels), las cuales son las que aparecen en las vistas
	    * 	@return returna arreglo de datos con los atributoe como key y las etiquetas como valor del arreglo.
	    */
	    public function attributeLabels()
	    {
	        return [
	        	'recibo' => Yii::t('frontend', 'Nro. Recibo'),
	        	'fecha_desde' => Yii::t('frontend', 'Fecha Desde'),
	        	'fecha_hasta' => Yii::t('frontend', 'Fecha Hasta'),
	        	'codigo_banco' => Yii::t('frontend', 'Banco'),
	        	'cuenta_deposito' => Yii::t('frontend', 'Cuenta Recaudadora'),
	        ];
	    }



	    /**
		 * Metodo que permite generar el modelo de consulta principal sobre las entidades
		 * "depositos" y "depositos-detalle".
		 * @return DepositoDetalle
		 */
		public function findDepositoDetalleModel()
		{
			$findModel = DepositoDetalle::find()->alias('B')
												->joinWith('deposito A', true, 'INNER JOIN')
												->where('A.estatus =:estatus',
															[':estatus' => 1]);

			return $findModel;
		}




		/***/
		public function getDataProviderSeleccion($seleccion)
		{
			$query = self::findDepositoDetalleModel();
			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);
			$query->andFilterWhere(['IN', 'linea', $seleccion]);
			$query->all();
			return $dataProvider;
		}




		/***/
		public function getDataProviderPago()
		{
			$query = self::findDepositoDetalleModel();
			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);

			if ( $this->recibo > 0 ) {
				$query->andFilterWhere([
			        'A.recibo' => $this->recibo,
			    ]);
			} elseif ( $this->fecha_desde !== '' && $this->fecha_hasta !== '' && $this->cuenta_deposito_faltante == 0 ) {
				$query->andFilterWhere(['=', 'codigo_banco', $this->codigo_banco])
			   		  ->andFilterWhere(['LIKE', 'trim(cuenta_deposito)', $this->cuenta_deposito])
				      ->andFilterWhere([
							'BETWEEN',
							'A.fecha',
			        		date('Y-m-d', strtotime($this->fecha_desde)),date('Y-m-d', strtotime($this->fecha_hasta)),
			    		]);

			} elseif ( $this->fecha_desde !== '' && $this->fecha_hasta !== '' && $this->cuenta_deposito_faltante == 1 ) {
				$query->andFilterWhere(['=', 'length(cuenta_deposito)', 0])
					  ->andFilterWhere([
							'BETWEEN',
							'A.fecha',
				        	date('Y-m-d', strtotime($this->fecha_desde)),date('Y-m-d', strtotime($this->fecha_hasta)),
				    	]);
			}
			$query->all();
			return $dataProvider;

		}


		/***/
		public static function getTableName()
		{
			return DepositoDetalle::tableName();
		}


	}
?>