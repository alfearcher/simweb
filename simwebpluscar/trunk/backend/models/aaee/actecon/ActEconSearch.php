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
 *  @file ActEconSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-10-2016
 *
 *  @class ActEconSearch
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

	namespace backend\models\aaee\actecon;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\aaee\actecon\ActEcon;
	use backend\models\utilidad\exigibilidad\Exigibilidad;


	/**
	* 	Clase
	*/
	class ActEconSearch
	{
		private $_id_contribuyente;



		/**
		 * Metodo constuctor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}



		/***/
		private function findActEconModel()
		{
			$findModel = ActEcon::find()->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->_id_contribuyente]);

			return $findModel;
		}



		/***/
		public function findActEconValidoSegunLapso($añoImpositivo)
		{
			$findModel = self::findActEconModel();
			$model = [];
			if ( count($findModel) > 0 ) {
				$model = $findModel->andWhere('ano_impositivo =:ano_impositivo',
												[':ano_impositivo' => $añoImpositivo])
								   ->andWhere('estatus =:estatus',
								   				[':estatus' => 0]);

			}

			return ( count($model) > 0 ) ? $model : [];
		}



		/***/
		public function getIdentificador($añoImpositivo)
		{
			$findModel = self::findActEconValidoSegunLapso($añoImpositivo);
			if ( count($findModel) > 0 ) {
				$result = $findModel->one();
				if ( count($result) > 0 ) {
					return (int)$result->id_impuesto;
				}
			}
			return false;
		}




		 /***/
	    public function guardar($arregloDatos, $conexion, $conn)
	    {
	    	$result = [
	    		'r' => null,
	    		'id' => 0,
	    	];
	    	$resultado = false;
	    	$id = 0;
	    	$model = New ActEcon();
	    	$tabla = $model->tableName();

	    	foreach ( $model->attributes as $key => $value ) {
	    		if ( isset($arregloDatos[$key]) ) {
	    			$model[$key] = $arregloDatos[$key];
	    		} else {
	    			$model[$key] = 0;
	    		}
	    	}
	    	$model['id_impuesto'] = null;

	    	// Se determina si existe el identificador para el año.
	    	if ( isset($model['ano_impositivo']) ) {
	    		$id = self::getIdentificador($model['ano_impositivo']);
	    		if ( $id == false ) {
	    			// Se asume que no existe el registro para el contribuyente y año descripto.
	    			// Se pasa a insertar un nuevo registro para generar un identificador nuevo.
	    			$resultado = $conexion->guardarRegistro($conn, $tabla, $model->attributes);
	    			if ( $resultado ) {
	    				$id = $conn->getLastInsertID();
	    				$result = [
	    					'r' => 'new',
	    					'id' => $id,
	    				];
	    			}

	    		} elseif ( $id > 0 ) {
	    			$model['id_impuesto'] = $id;
	    			$result = [
    					'r' => 'old',
    					'id' => $id,
    				];
	    		}
	    	}

	    	return $result;
	    }



	}

?>