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
 *  @file BancoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-03-2017
 *
 *  @class BancoSearch
 *  @brief Clase
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

	namespace backend\models\utilidad\banco;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use backend\models\utilidad\banco\Banco;
	use backend\models\utilidad\banco\BancoCuentaReceptora;
	use yii\helpers\ArrayHelper;


	/**
	* Clase
	*/
	class BancoSearch extends Banco
	{



		/**
		 * Metodo que realiza una consulta de los registros activos en la entidad
		 * "bancos".
		 * @return Banco
		 */
		public function getBanco()
		{
			return $banco = Banco::find()->where('status =:status', [':status' => 1])->all();
		}


		/**
		 * Metodo que realiza una consulta, sobre la entidad "bancos", para localizar
		 * un registro por su identificador.
		 * @param integer $idBanco identificador de la entidad.
		 * @return Banco.
		 */
		public function findBanco($idBanco)
		{
			return $banco = Banco::find()->where('id_banco =:id_banco', [':id_banco' => $idBanco])->one();
		}




		/***/
		public function getBancoByCodigoCuenta($codigoBanco)
		{
			if ( is_array($codigoBanco) ) {
				return $banco = Banco::find()->where(['IN', 'codigo', $codigoBanco])->asArray()->all();
			} else {
				return $banco = Banco::find()->where('codigo =:codigo',
														[':codigo' => $codigoBanco])->asArray()->all();
			}

		}



		/**
		 * Metodo que permite obtener una lista de los registros de la entidad
		 * "bancos", esta lista se puede utilizar para los combos-listas. El key
		 * del arreglo corresponde al codigo del banco en el pais local y no
		 * corresponde al indice de la entidad.
		 * @param string $campoClave, campo que se utilizara como campo clave en
		 * el arreglo del listado. Por defecto se toma el campo codigo.
		 * @return array
		 */
		public function getListaBanco($campoClave = 'codigo')
		{
			if ( in_array($campoClave, ['codigo', 'id_banco']) ) {
				$model = self::getBanco();
				return $lista = ArrayHelper::map($model, $campoClave, 'nombre');
			}
			return null;
		}




		/**
		 * Metodo que realiza una consulta del tipo "LIKE" sobre la entidad
		 * "bancos", para encontrar aquellos que cumplan la cosdicion. La consulta
		 * se realizasobre los atributos "nombre" y "nombre_corto".
		 * @param string $params valor del aparametro que se utilizara en la consulta.
		 * @return Banco
		 */
		public function findBancoPorCaracteristica($params)
		{
			$results = Banco::find()->where('status =:status', [':status' => 1])
			                        ->orWhere(['LIKE', 'nombre', $params])
			                        ->orWhere(['LIKE', 'nombre_corto', $params])
			                        ->all();

			return $results;
		}



		/**
		 * Metodo que rezliza la consulta segun una condicion especifica y determina
		 * si existe el registro.
		 * @param array $arregloCondicion arreglo que especifica el atributo y el valor
		 * del mismo, este arreglo se utilizara en el where condition de la consulta.
		 * Esquema del arreglo:
		 * 	[
		 *  	atributo => valor del atributo,
		 *  ]
		 * @return boolean
		 */
		public function existeBanco($arregloCondicion)
		{
			return Banco::find()->where($arregloCondicion)->exists();
		}



		/**
		 * Metodo que genera un listado de bancos relacionados a las cuentas receptoras.
		 * El arreglo tiene la estructura:
		 * {
		 * 		'id_banco' => 'nombre'
		 * }
		 * @return array.
		 */
		public function getListaBancoRelacionadaCuentaReceptora($soloActivos = true)
		{
			$lista = null;
			if ( $soloActivos ) {
				$findModel = BancoCuentaReceptora::find()->alias('R')
			                          				 ->joinWith('banco', true, 'INNER JOIN');

			} else {
				$findModel = BancoCuentaReceptora::find()->alias('R')
			                          				 ->joinWith('banco', true, 'INNER JOIN')
			                          				 ->where('status =:status',
			                          				 			[':status' => 1])
			                          				 ->andWhere('R.inactivo =:inactivo',
			                          				 			[':inactivo' => 0]);
			}

			$registers = $findModel->all();
			if ( count($registers) > 0 ) {
				foreach ( $registers as $register ) {
					$listaBanco[] = [
						'id_banco' => $register->banco->id_banco,
						'nombre' => $register->banco->nombre,
					];
				}
				$lista = ArrayHelper::map($listaBanco, 'id_banco', 'nombre');
			}
			return $lista;
		}



		/**
		 * Metodo que genera un arrelo para los listados tipo combo-lista.
		 * El arreglo tiene la estruvtura:
		 * {
		 * 		'cuenta' => 'cuenta - observacion'
		 * }
		 * Este arreglo contiene las cuentas recaudadoras asociadas a un banco.
		 * @param integer $idBanco identificador del banco.
		 * @param integer $soloActivo si su valor es uno (1), solo se buscan los registros activos.
		 * En caso contrario se buscan todos los registros.
		 * @return array
		 */
		public function getListaCuentaRecaudadora($idBanco = 0, $soloActivo = 1)
		{
			$lista = [];
			$findModel = BancoCuentaReceptora::find()->alias('R');
			if ( $idBanco > 0 ) {
				if ( $soloActivo == 1 ) {
					$findModel = $findModel->joinWith('banco B', true, 'INNER JOIN')
				                           ->where('B.id_banco =:id_banco',
				                          			[':id_banco' => $idBanco])
				                           ->andWhere('R.inactivo =:inactivo',
				                          			[':inactivo' => 0]);
				} else {
					$findModel = $findModel->joinWith('banco B', true, 'INNER JOIN')
				                           ->where('B.id_banco =:id_banco',
				                          			[':id_banco' => $idBanco]);
				}

			} else {
				if ( $soloActivo == 1 ) {
					$findModel = $findModel->joinWith('banco B', true, 'INNER JOIN')
			                           ->where('status =:status',
			                          			[':status' => 1])
			                           ->andWhere('R.inactivo =:inactivo',
			                          			[':inactivo' => 0]);
				} else {
					$findModel = $findModel->joinWith('banco B', true, 'INNER JOIN');
				}

			}

			$registers = $findModel->all();

			if ( count($registers) > 0 ) {
				foreach ( $registers as $register ) {
					$lista[] = [
						'cuenta' => $register['cuenta'],
						'valor' => $register['cuenta'] . ' - ' . $register['observacion'],
					];
				}
			}
			return ArrayHelper::map($lista, 'cuenta', 'valor');
		}





		/***/
		public function generarViewListaCuentaRecaudadora($idBanco = 0, $soloActivo = 1)
	    {
	    	$lista = self::getListaCuentaRecaudadora($idBanco, $soloActivo);

	        if ( count($lista) > 0 ) {
	        	echo "<option value='0'>" . "Seleccione..." . "</option>";
	            foreach ( $lista as $key => $item ) {
	                echo "<option value='" . $key . "'>" . $item . "</option>";
	            }
	        } else {
	            echo "<option> - </option>";
	        }

	        return;
	    }



	    /**
	     * Metodo que arma una lista para un combo. utilizando los identificadores
	     * de los registros.
	     * @param array $arrayId arreglo de identificadores de la entidad (id-banco)
	     * @return array (ArrayHelper::map)
	     */
	    public function getListaBancoById($arrayId)
	    {
	    	if ( is_array($arrayId) ) {
	    		$registers = Banco::find()->where('status =:status',
	    												[':status' => 1])
	    							   	  ->andWhere(['IN', 'id_banco', $arrayId])
	    							      ->all();
	    		if ( count($registers) > 0 ) {
					return ArrayHelper::map($registers, 'id_banco', 'nombre');
				}
	    	}
	    	return null;
	    }
	}

?>