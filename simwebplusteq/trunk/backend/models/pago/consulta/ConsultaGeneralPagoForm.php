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
 *  @file ConsultaGeneralPagoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-10-2017
 *
 *  @class ConsultaGeneralPagoForm
 *  @brief Clase Modelo general del formulario de condulta de pagos. Se busca
 *  crear un formulario general de consulta de pago por recibo, y rango de fecha.
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

	namespace backend\models\pago\consulta;

 	use Yii;
	use yii\base\Model;
	use backend\models\utilidad\banco\BancoSearch;



	/**
	* Clase
	*/
	class ConsultaGeneralPagoForm extends Model
	{

		public $recibo;
		public $fecha_desde;
		public $fecha_hasta;
		public $codigo_banco;
		public $cuenta_deposito;

		const SCENARIO_RECIBO = 'recibo';
		const SCENARIO_FECHA = 'fecha';



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
        		self::SCENARIO_FECHA => [
        				'fecha_desde',
        				'fecha_hasta',
        				'codigo_banco',
        				'cuenta_deposito',
        		],
        	];
    	}



		/**
		 * Metodo que indica las reglas de validacion del formulario de consulta.
		 * @return array
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
	        	  'required', 'on' => 'fecha',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	['fecha_desde' , 'rangoValido', 'on' => 'fecha'],
	        	[['cuenta_deposito'],
	        	  'required',
	        	  'when' => function($model) {
	        	  				if ( strlen($model->codigo_banco) > 0 ) {
	        						return true;
	        					} else {
	        						return false;
	        					}
	        	  			},
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
	     * Metodo que lista las cuentas recaudadoras
	     * @param array $postEnviado post enviado que debe contener al identificador
	     * del banco.
	     * @return array
	     */
        public function listarCuentaRecaudadora($postEnviado)
        {
        	$searchBanco = New BancoSearch();
        	$id = isset($postEnviado['id']) ? (int)$postEnviado['id'] : 0;
            $soloActivo = isset($postEnviado['soloActivo']) ? (int)$postEnviado['soloActivo'] : 1;
        	return $searchBanco->generarViewListaCuentaRecaudadora($id, $soloActivo);

        }


        /**
         * Metodo para obtener lista de banco
         * @param boolean $aoloActivo indica si quiere solo los registros que cumplan
         * una condicion.
         * @return array
         */
        public function listarBanco($aoloActivo = 1)
        {
        	$searchBanco = New BancoSearch();
        	return $listaBanco = $searchBanco->getListaBancoRelacionadaCuentaReceptora($aoloActivo);
        }


        /**
         * Metodo que formatea la fecha la stanfar americano
         * @param string $fecha fecha en formato dd-mm-yyyy
         * @return string fecha formateada 'yyyy-mm-dd'
         */
		public function formatFecha($fecha)
		{
			return date('Y-m-d', strtotime($fecha));
		}

	}
?>