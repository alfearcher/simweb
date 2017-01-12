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
 *  @file AnularReciboSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-11-2016
 *
 *  @class AnularReciboSearch
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

	namespace backend\models\recibo\recibo;

 	use Yii;
	use backend\models\recibo\deposito\Deposito;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use backend\models\recibo\recibo\AnularRecibo;




	/**
	* Clase
	*/
	class AnularReciboSearch extends AnularRecibo
	{
		private $_recibo;
		private $_id_contribuyente;
		private $_estatus;


		/***/
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
		}



		/**
		 * Metodo que realiza la consulta del recibo y devielve una instancio de la
		 * clase Deposito.
		 * @return Deposito retorna una instancia de la clase Deposito
		 */
		public function findDeposito()
		{
			$deposito = New Deposito();

			return $deposito->find()->where('recibo =:recibo',[':recibo' => $this->recibo])
							        ->joinWith('condicion C', true)
							        ->one();
		}




		/**
		 * Metodo que realiza la anulacion
		 * @param  ConexionController $conexion instancia de la clase.
		 * @param  connection $conn instancia de conexion.
		 * @return boolean retorna true si guarda, false en caso contrario.
		 */
		public function anularRecibo($conexion, $conn)
		{
			$result = false;
			$deposito = New Deposito();
			$tabla = $deposito->tableName();
			$usuario = Yii::$app->identidad->getUsuario();
			$fechaHora = date('Y-m-d H:i:s');

			// El campo a ser modifiado.
			$arregloDatos['estatus'] = 9;
			$arregloDatos['observacion'] = 'ANULADO POR EL USUARIO ' . $usuario . ' fecha y hora: ' . $fechaHora;

			// Condicion
			$arregloCondicion['recibo'] = $this->_recibo;

			$result = $conexion->modificarRegistro($conn, $tabla, $arregloDatos, $arregloCondicion);
			if ( $result ) {
				$result = self::anularReciboPlanilla($conexion, $conn);
			}
			return $result;
		}



		/**
		 * Metodo que realiza la anulacion
		 * @param  ConexionController $conexion instancia de la clase.
		 * @param  connection $conn instancia de conexion.
		 * @return boolean retorna true si guarda, false en caso contrario.
		 */
		private function anularReciboPlanilla($conexion, $conn)
		{
			$result = false;
			$depositoPlanilla = New DepositoPlanilla();
			$tabla = $depositoPlanilla->tableName();

			// Campos a modificar
			$arregloDatos['estatus'] = 9;

			// Condicion
			$arregloCondicion['recibo'] = $this->_recibo;

			$result = $conexion->modificarRegistro($conn, $tabla, $arregloDatos, $arregloCondicion);

			return $result;
		}








	}
?>