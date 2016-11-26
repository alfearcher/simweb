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
	use backend\models\aaee\solvencia\Solvencia;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\helpers\ArrayHelper;
	use common\models\ordenanza\OrdenanzaBase;


	/**
	* Clase donde se controla la politica de negocio para realizar la solicitudes
	* de solvencias de Actividad Economicas.
	*/
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




		/***/
		public function guardar($arregloDatos, $conexion, $conn)
		{
			$result = false;

			$model = New Solvencia()
			$tabla = $model->tableName();

			foreach ( $model->attributes as $key => $value ) {
				if ( isset($arregloDatos[$key]) ) {
					$model[$key] = $arregloDatos[$key];
	    		} else {
	    			$model[$key] = 0;
	    		}
			}


			$result = $conexion->guardarRegistros($conn, $tabla, $arregloDatos);

			return $result;
		}



	}

?>