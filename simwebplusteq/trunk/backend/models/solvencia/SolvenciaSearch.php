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
 *  @file SolvenciaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-11-2016
 *
 *  @class SolvenciaSearch
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

	namespace backend\models\solvencia;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\solvencia\Solvencia;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\helpers\ArrayHelper;
	use common\models\ordenanza\OrdenanzaBase;


	/***/
	class SolvenciaSearch
	{
		private $_id_contribuyente;
		private $_licencia;



		/**
		 * Metodo constructor de la clase
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}




		/**
		 * Metodo que realiza una insercion en la entidad "solvencias",
		 * @param array $arregloDatos arreglo de datos que seran actualizados.
		 * @param  ConexionController $conexion instancia de la clase.
		 * @param  Connection $conn instancia de Connection
		 * @return boolean retorna true si realizo el update, false en caso contrario.
		 */
		public function guardar($arregloDatos, $conexion, $conn)
		{
			$result = false;

			$model = New Solvencia();
			$tabla = $model->tableName();

			foreach ( $model->attributes as $key => $value ) {
				if ( isset($arregloDatos[$key]) ) {
					$model[$key] = $arregloDatos[$key];
	    		} else {
	    			$model[$key] = 0;
	    		}
			}

			// Condiciones para inactivar los registros anteriores
			$arregloCondicion = [
					'id_contribuyente' => $model->id_contribuyente,
					'impuesto' => $model->impuesto,
					'id_impuesto' => $model->id_impuesto,
			];

			$arreglo['status_solvencias'] = 1;

			$result = self::update($arreglo, $arregloCondicion, $conexion, $conn);

			if ( $result ) {
				$result = $conexion->guardarRegistro($conn, $tabla, $arregloDatos);
			}

			return $result;
		}




		/**
		 * Metodo que realiza el update sobre la entidad "solvencias"
		 * @param array $arregloDatos arreglo de datos que seran actualizados.
		 * @param  array $arregloCondicion arreglo de datos que indica el where condition de la
		 * actualizacion
		 * @param  ConexionController $conexion instancia de la clase.
		 * @param  Connection $conn instancia de Connection
		 * @return boolean retorna true si realizo el update, false en caso contrario.
		 */
		public function update($arregloDatos, $arregloCondicion, $conexion, $conn)
		{
			$result = false;

			$model = New Solvencia();
			$tabla = $model->tableName();

			$result = $conexion->modificarRegistro($conn, $tabla, $arregloDatos, $arregloCondicion);

			return $result;
		}

	}

?>