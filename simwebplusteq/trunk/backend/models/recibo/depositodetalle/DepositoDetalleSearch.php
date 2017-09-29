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
 *  @file DepositoDetalleSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-02-2017
 *
 *  @class DepositoDetalleSearch
 *  @brief Clase Modelo
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

	namespace backend\models\recibo\depositodetalle;

 	use Yii;
	use backend\models\recibo\depositodetalle\DepositoDetalle;
	use backend\models\utilidad\banco\BancoCuentaReceptora;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;


	/**
	* Clase
	*/
	class DepositoDetalleSearch extends DepositoDetalle
	{

		private $_conn;
		private $_conexion;
		private $_model;			// Modelo DepositoDetalle
		private $_id_generado;
		private $_recibo;


		/**
		 * Metodo constructor de la clase.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param connection $conn
		 */
		public function __construct($conexion = null, $conn = null)
		{
			$this->_conexion = $conexion;
			$this->_conn = $conn;
			$this->_id_generado = 0;
		}



		/**
		 * Metodo getter del autoincremental generado por el insert
		 * @return integer autoincremental generado por el insert.
		 */
		public function getIdGenerado()
		{
			return $this->_id_generado;
		}



		/**
		 * Metodo que realiza un insert en la entidad respectiva.
		 * @param array $arregloDato arreglo de datos que se insertaran en la entidad.
		 * @return boolean.
		 */
		public function guardar($arregloDato)
		{
			$this->_id_generado = 0;
			if ( isset($arregloDato['linea']) ) {
				$arregloDato['linea'] = null;
			}

			// Se determina el identificador del banco de la cuenta recaudadora.
			if ( (int)$arregloDato['codigo_banco'] == 0 ) {
				$bancoCuenta = self::determinarBancoSegunCuentaReceptora($arregloDato['cuenta_deposito']);
				if ( count($bancoCuenta) > 0 ) {
					$arregloDato['codigo_banco'] = (int)$bancoCuenta['id_banco'];
				}
			}

			$result = false;
			$tabla = $this->tableName();
			$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $arregloDato);
			if ( $result ) {
				$result = true;
				$this->_id_generado = $this->_conn->getLastInsertID();
			}
			return $result;
		}



		/**
		 * Metodo que genera el modelo basico de consulta sobre la entidad "depositos-detalle"
		 * @param  integer $recibo identificador de la entidad "depositos"
		 * @return DepositoDetalle
		 */
		private function findDepositoDetalleModel($recibo)
		{
			return $this->find()->where('recibo =:recibo', [':recibo' => $recibo]);
		}



		/**
		 * Metodo que retorna un proveedro de datos, con la informacion detallada
		 * de las formas de pago del recibo.
		 * @param integer $recibo identificador de la entidad "depositos"
		 * @return ActivaDataProvider
		 */
		public function getDataProviderDepositoDetalle($recibo)
		{
			$findModel = self::findDepositoDetalleModel($recibo);
			$query = $findModel->joinWith('formaPago F', true, 'INNER JOIN');
			$dataProvider = New ActiveDataProvider([
				'query' => $query,
				'pagination' => false,
			]);
			$query->all();
			return $dataProvider;
		}



		/**
		 * Metodo que determinara el identificador de la entidad bancaria origen
		 * del deposito a traves de la cuenta recaudadora. Esta cuenta recaudadora
		 * es la que viene en el archivo de conciliacion y se tomara para determinar
		 * esta informacion. Se Hara una consulta global sobre la entidad que relaciona
		 * las cuentas recaudadoras y los bancos, con la cuenta recaudadora se filtra
		 * esta consulta para luego obtener el identificador del banco.
		 * @param string $cuentaReceptora cuenta recaudadora.
		 * @return array registro de la entidad "bancos-cuentas-receptoras".
		 */
		public function determinarBancoSegunCuentaReceptora($cuentaReceptora)
		{
			$bancoCuenta = [];
			$findModel = self::findBancoCuentaReceptoraModel();
			$registers = $findModel->where(['inactivo' => 0])->asArray()->all();
			if ( count($registers) > 0 ) {
				foreach ( $registers as $register ) {
					if ( strpos(trim($cuentaReceptora), trim($register['cuenta'])) ) {
						// significa que encontro el registro.
						$bancoCuenta = $register;
					}
				}
			}

			return $bancoCuenta;
		}



		/**
		 * Metodo que retorna el modelo de consulta de la clase BancoCuentaReceptora.
		 * @return BancoCuentaRecpetora.
		 */
		public function findBancoCuentaReceptoraModel()
		{
			return $findModel = BancoCuentaReceptora::find()->alias('C');
		}

	}

?>