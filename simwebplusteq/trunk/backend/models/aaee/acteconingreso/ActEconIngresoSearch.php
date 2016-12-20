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
 *  @file ActEconIngresoSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-08-2016
 *
 *  @class ActEconIngresoSearch
 *  @brief Clase Modelo principal
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

 	namespace backend\models\aaee\acteconingreso;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ActiveDataProvider;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\rubro\Rubro;



	/***/
	class ActEconIngresoSearch extends ActEconIngreso
	{

		private $_id_contribuyente;


		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 * Valor unico dentro de la entidad correspondiente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}




		/**
		 * Metodo que realiza la consulta por el id de la entidad.
		 * @param  integer $idIngreso identificador de la entidad.
		 * @return array retorna un active record con los datos de la entidad.
		 */
		public function findActEconDetallePorId($idIngreso)
		{
			return ActEconIngreso::findOne($idIngreso);
		}


		/**
		 * Metodo que determina si existe un rubro registrado para un periodo
		 * especifico.
		 * @param  long $idRubro identificador del rubro.
		 * @param  integer $periodo periodo especifico de la declaracion.
		 * @return boolean retorna true si encuentra registros con los parametros
		 * especificados.
		 */
		public function findExisteRubroActivo($idRubro, $periodo)
		{
			$findModel = ActEconIngreso::find()->where('id_contribuyente =:id_contribuyente',
				 											[':id_contribuyente' => $this->_id_contribuyente])
											   ->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
											    			[':exigibilidad_periodo' => $periodo])
											   ->andWhere('inactivo =:inactivo',
											    			[':inactivo' => 0])
											   ->andWhere('id_rubro =:id_rubro',
											    			[':id_rubro' => $idRubro])
											   ->joinWith('actividadEconomica')
											   ->count();

			return ( $findModel > 0 ) ? true : false;
		}




		/***/
		public function findRubroRegistrado($idImpuesto)
		{
			$findModel = ActEconIngreso::findOne($idImpuesto)->where('id_contribuyente =:id_contribuyente',
				 											[':id_contribuyente' => $this->_id_contribuyente])
											    ->andWhere('ano_impositivo =:ano_impositivo',
											    			[':ano_impositivo' => $añoImpositivo])
											   ->andWhere('exigibilidad_periodo =:exigibilidad_periodo',
											    			[':exigibilidad_periodo' => $periodo])
											   ->andWhere('inactivo =:inactivo',
											    			[':inactivo' => 0])
											   ->andWhere('estatus =:estatus',
											    			[':estatus' => 0])
											   ->joinWith('actividadEconomica');
											   //->all();

			return isset($findModel) ? $findModel : null;
		}




		/***/
		public function findRubroFiltrado($listaIdRubro = [])
		{
			$findModel = Rubro::findAll($listaIdRubro)->orderBy([
															'descripcion' => SORT_ASC,
														]);
		}



		/***/
		public function getDataProviderSegunRubro($listaIdRubro = [])
		{
			$query = self::findRubroFiltrado($listaIdRubro);

			$dataProvider = New ActiveDataProvider([
						'query' => $query,
				]);

			return $dataProvider;
		}




	    /**
	     * Metodo que determina el identificador que corresponde segun el año impositivo
	     * para el rubro. Se utilizar el $idRubro para buscar los valores de ese registro
	     * y obtener el valor del rubro, este valor (rubro) se convina con el $añoImpositivo
	     * para realizar una busqueda añoImpositivo-rubro. Esta busqueda debe resultar en un
	     * registro donde el identificador del mismo es el valor buscado como idRubro del año
	     * impositivo.
	     * @param  long $idRubro identificador del rubro.
	     * @param  inetger $añoImpositivo año impositivo del catalogo de rubro que quiere consultar
	     * el $idRubro no deberia corresponder al del año impositivo.
	     * @return long retonra un identificador del rubro para el año impositivo $añoimpositivo.
	     */
	    public function getIdRubro($idRubro, $añoImpositivo)
	    {
	    	$idRubroEncontrado = 0;
	    	$findModelRubro = Rubro::findOne($idRubro);
	    	if ( isset($findModelRubro) ) {
	    		$rubro = $findModelRubro->rubro;
	    		$findModelNew = Rubro::find()->where('ano_impositivo =:ano_impositivo',
	    													[':ano_impositivo' => $añoImpositivo])
	    									 ->andWhere('rubro =:rubro', [':rubro' => $rubro])
	    									 ->andWhere('inactivo =:inactivo',['inactivo' => 0])
	    									 ->one();
	    		if ( isset($findModelNew) ) {
	    			$idRubroEncontrado = $findModelNew->id_rubro;
	    		}
	    	}
	    	return $idRubroEncontrado;
	    }




	    /***/
	    public function getListaRubro($chkSeleccion, $añoImpositivo)
	    {
	    	$listaIdRubro = [];
	    	$id = 0;
	    	foreach ( $chkSeleccion as $key => $value ) {
	    		$id = self::getIdRubro($value, $añoImpositivo);
	    		if ( $id > 0 ) {
	    			$listaIdRubro[] = $id;
	    		} else {
	    			$listaIdRubro = null;
	    			break;
	    		}
	    	}
	    	return $listaIdRubro;
	    }





	    /***/
	    public function guardar($arregloDatos, $conexion, $conn)
	    {
	    	$result = false;
	    	$tabla = $this->tableName();
	    	$arreglo = $this->attributes;

	    	foreach ( $arreglo as $key => $value ) {
	    		if ( isset($arregloDatos[$key]) ) {
	    			$arreglo[$key] = $arregloDatos[$key];
	    		} else {
	    			$arreglo[$key] = 0;
	    		}
	    	}

	    	$result = $conexion->guardarRegistro($conn, $tabla, $arreglo);

	    	return $result;
	    }



	    /**
	     * Metodo que anula el registro maestro de la declracion. Solo aplicaria
	     * a los registros que se encuentran activo.
	     * @param  integer $idImpuesto identificador del año de la declaracion. Entidad
	     * "act-econ".
	     * @param  conexioncontroller $conexion  instancia de la clase especifica.
	     * @param  connection $conn instancia de connection.
	     * @return boolean retorna true si ejecuta todo bien, de lo contrario flase.
	     */
	    public function inactivarAll($idImpuesto, $conexion, $conn)
	    {
	    	$result = false;
	    	$tabla = $this->tableName();

	    	$arregloCondicion = [
	    		'id_impuesto' => $idImpuesto,
	    		'inactivo' => 0,
	    	];

	    	$arregloDatos = [
	    		'inactivo' => 1,
	    	];

	    	$result = $conexion->modificarRegistro($conn, $tabla, $arregloDatos, $arregloCondicion);

	    	return $result;
	    }



	    /**
	     * Metodo que anula un registro especifico. Solo aplicaria a los registros
	     * que se encuentran activo.
	     * @param  integer $idImpuesto identificador del año de la declaracion. Entidad
	     * "act-econ".
	     * @param  integer $idRubro identificador del rubro. Entidad "rubros".
	     * @param  integer $periodo periodo de la declaracion.
	     * @param  conexioncontroller $conexion instancia de la clase especifica.
	     * @param  connection $conn instancia de connection.
	     * @return boolean retorna true si ejecuta todo bien, de lo contrario flase.
	     */
	    public function inactivarItem($idImpuesto, $idRubro, $periodo, $conexion, $conn)
	    {
	    	$result = false;
	    	$tabla = $this->tableName();

	    	$arregloCondicion = [
	    		'id_impuesto' => $idImpuesto,
	    		'id_rubro' => $idRubro,
	    		'exigibilidad_periodo' => $periodo,
	    		'inactivo' => 0,
	    	];

	    	$arregloDatos = [
	    		'inactivo' => 1,
	    	];

	    	$result = $conexion->modificarRegistro($conn, $tabla, $arregloDatos, $arregloCondicion);

	    	return $result;
	    }



	    /**
	     * Metodo que determina si un grupo de identificadores de rubros, corresponden
	     * al mismo codigo de rubro.
	     * @param  array $arregloIdRubro arreglo de identificadores de rubros, son enteros.
	     * @return boolaen retorna true si el conjunto de identificadores corresponde al
	     * mismo codigo de rubro, en caso contrario retornara false.
	     */
	    public function rubroSimilar($arregloIdRubro)
	    {
	    	$result = false;
	    	$rubro = [];
	    	$cancel = false;
	    	if ( count($arregloIdRubro) > 0 ) {
	    		foreach ( $arregloIdRubro as $key => $value ) {
	    			$rubro[$key] = Rubro::findOne($value);
	    			if ( !isset($rubro[$key]) ) {
	    				$result = false;
	    				$cancel = true;
	    				break;
	    			}
	    		}

	    		if ( !$cancel ) {
		    		$rubroMaestro = $rubro[0];
		    		foreach ( $rubro as $key => $value ) {
		    			if ( $rubroMaestro !== $value ) {
		    				$result = false;
		    				break;
		    			} else {
		    				$result = true;
		    			}
		    		}
		    	}
	    	}

	    	return $result;
	    }

	}
 ?>