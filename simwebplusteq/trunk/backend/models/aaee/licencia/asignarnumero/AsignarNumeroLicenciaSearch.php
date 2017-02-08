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
 *  @file AsignarNumeroLicenciaSearchSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-02-2017
 *
 *  @class AsignarNumeroLicenciaSearchSearch
 *  @brief Clase Modelo principal que regula y controla la asignacion de los numeros de licencias.
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

 	namespace backend\models\aaee\licencia\asignarnumero;

 	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\data\ArrayDataProvider;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\acteconingreso\ActEconIngreso;
	use backend\models\aaee\actecon\ActEcon;
	use yii\helpers\ArrayHelper;
	use backend\models\aaee\licencia\numerolicencia\NumeroLicenciaSearch;
	use common\conexion\ConexionController;
	use backend\models\aaee\historico\licencia\HistoricoLicencia;



	/**
	 * Clase que gestiona la asignacion de los numeros de licencias a los contribuyentes
	 * de Actividades Economicas.
	 */
	class AsignarNumeroLicenciaSearch
	{

		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_nroLicencia;
		private $_correlativo;



		/***/
		public function __construct()
		{}





		/**
		 * Metodo que permite buscar a los contribuyentes juridico y activos que tengan
		 * cargados los rubros del año actual y que no posean licencia. Aqui la condicion
		 * de no tener licencia se establece consultando el contenido del atributo "id-sim"
		 * de la entidad "contribuyentes", si la longitud del contenido de ese atributo es
		 * menor a 2, se considera que el atributo esta vacio.
		 * El metodo retorna un arreglo con los datos que aparecen en el select de la consulta,
		 * en caso de no encontrar datos devolvera un arreglo vacio.
		 * @param  array $chkIdContribuyente arreglo de identificadores de contribuyente.
		 * @return Array.
		 */
		public function findContribuyenteJuridicoSinNumeroLicencia($chkIdContribuyente = [])
		{
			$añoActual = (int)date('Y');

			if ( count($chkIdContribuyente) == 0 ) {

				$results = ActEcon::find()->select([
												'C.id_contribuyente',
												'C.razon_social',
												'C.naturaleza',
												'C.cedula',
												'C.tipo',
												'C.id_rif',
												'C.id_sim',
											])
										  ->distinct('C.id_contribuyente')
				                          ->alias('A')
				                          ->joinWith('contribuyente C', false, 'INNER JOIN')
				                          ->joinWith('actividadDetalle I', false, 'INNER JOIN')
										  ->where('A.estatus =:estatus',
										 				[':estatus' => 0])
										  ->andWhere('C.inactivo =:inactivo',
										 				[':inactivo' => 0])
										  ->andWhere('tipo_naturaleza =:tipo_naturaleza',
										 				[':tipo_naturaleza' => 1])
										  ->andWhere('ano_impositivo =:ano_impositivo',
										 				[':ano_impositivo' => $añoActual])
										  ->andWhere('I.inactivo =:inactivo',
										 				[':inactivo' => 0])
										  ->andWhere(['length(C.id_sim)<' => 2])
				                          ->asArray()
				                          ->all();

			} else {

				$results = ActEcon::find()->select([
												'C.id_contribuyente',
												'C.razon_social',
												'C.naturaleza',
												'C.cedula',
												'C.tipo',
												'C.id_rif',
												'C.id_sim',
											])
										  ->distinct('C.id_contribuyente')
				                          ->alias('A')
				                          ->joinWith('contribuyente C', false, 'INNER JOIN')
				                          ->joinWith('actividadDetalle I', false, 'INNER JOIN')
										  ->where('A.estatus =:estatus',
										 				[':estatus' => 0])
										  ->andWhere('C.inactivo =:inactivo',
										 				[':inactivo' => 0])
										  ->andWhere('tipo_naturaleza =:tipo_naturaleza',
										 				[':tipo_naturaleza' => 1])
										  ->andWhere('ano_impositivo =:ano_impositivo',
										 				[':ano_impositivo' => $añoActual])
										  ->andWhere('I.inactivo =:inactivo',
										 				[':inactivo' => 0])
										  ->andWhere(['length(C.id_sim)<' => 2])
										  ->andWhere(['IN', 'C.id_contribuyente', $chkIdContribuyente])
				                          ->asArray()
				                          ->all();

			}

			return $results;
		}




		/**
		 * Metodo que inicia el proceso de genaracion del numero de licencia y la posterior
		 * actualizacion del atributo "id-sim" con el numero generado.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return boolean
		 */
		public function iniciarAsignacionNumeroLicencia($idContribuyente)
		{
			$this->_nroLicencia = 0;
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			$result = false;
			$result = self::updateLicenciaContribuyente($idContribuyente);
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return $result;
		}




		/**
		 * Metodo que setea las variables para las operaciones CRUD.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}




		/**
		 * Metodo que retorna el numero de licencia creado.
		 * @return string retorna numero de licencia creado.
		 */
		public function getNroLicencia()
		{
			return $this->_nroLicencia;
		}




		/**
		 * Metodo que retorna el correlativo generado al insertar en la entidad "numeros-licencias".
		 * @return integer retorna un autonumerico de la entidad.
		 */
		public function getCorrelativo()
		{
			return $this->_correlativo;
		}




		/**
		 * Metodo que arma el numero de la licencia del contribuyente segun la politiva de negocio.
		 * En este caso corresponde a concatenar un correlativo contra el año actual.
		 * Esquema de la licencia correlativo-año actual.
		 * @return
		 */
		private function armarNumeroLicencia()
		{
			$intentos = 10;
			$ct = 0;
			$this->_nroLicencia = '';
			$this->_correlativo = 0;

			while ( $ct <= $intentos ) {
				$this->_nroLicencia = '';
				$this->_correlativo = 0;

				$numeroSearch = New NumeroLicenciaSearch('db');

				// Genera el correlativo.
				$numeroSearch->getGenerarNumeroLicencia();
				$this->_correlativo = $numeroSearch->getLicencia();

				if ( $this->_correlativo > 0 ) {
					$nroGenerado = $this->_correlativo . '-' . date('Y');
					if ( !self::existeNroLicenciaArmada($nroGenerado) ) {
						$this->_nroLicencia = $this->_correlativo . '-' . date('Y');
						break;
					} else {
						$ct++;
					}
				} else {
					$ct++;
				}
			}

		}




		/**
		 * Metodo que permite determinar si un numero de licencia especifico ya esta asignado
		 * a un contribuyente.
		 * @param  string $nroLicencia numero de licencia generado.
		 * @return boolean
		 */
		private function existeNroLicenciaArmada($nroLicencia)
		{
			$result = self::findLicenciaEnContribuyente($nroLicencia);
			if ( $result ) { return $result; }

			$result = self::findLicenciaEnHistorico($nroLicencia);
			if ( $result ) { return $result; }

			return $result;
		}



		/**
		 * Metodo que busca el numero de licencia armado en la entidad "historico-licencias-sw"
		 * @param string $nroLicencia numero de licencia armado.
		 * @return boolean.
		 */
		private function findLicenciaEnHistorico($nroLicencia)
		{
			return $result = HistoricoLicencia::find()->where('licencia =:licencia',
			 													[':licencia' => trim($nroLicencia)])
											          ->exists();
		}



		/**
		 * Metodo que busca el numero de licencia armado en la entidad "contribuyentes"
		 * @param string $nroLicencia numero de licencia armado.
		 * @return boolean.
		 */
		private function findLicenciaEnContribuyente($nroLicencia)
		{
			return $result = ContribuyenteBase::find()->where('id_sim =:id_sim',
			 													[':id_sim' => trim($nroLicencia)])
											          ->exists();
		}





		/**
		 * Metodo que genera el data provider para el grid view.
		 * @param  array $chkIdContribuyente arreglo de identificadores de contribuyente.
		 * @return ArrayDataProvider.
		 */
		public function getDataProvider($chkIdContribuyente = [])
		{
			$data = [];
			$observacion = [];
			$bloquear = 0;

			$results = self::findContribuyenteJuridicoSinNumeroLicencia($chkIdContribuyente);

			foreach ( $results as $result ) {
				$data[$result['id_contribuyente']] = [
					'id_contribuyente' => $result['id_contribuyente'],
					'contribuyente' => $result['razon_social'],
					'rif' => $result['naturaleza'] . '-' . $result['cedula'] . '-' . $result['tipo'],
					'sucursal' => $result['id_rif'],
					'licencia' => $result['id_sim'],
					'bloquear' => $bloquear,
					'observacion' => $observacion,
				];
			}

			$dataProvider = New ArrayDataProvider([
				'key' => 'id_contribuyente',
				'allModels' => $data,
				'pagination' => false,
			]);

			return $dataProvider;
		}




		/***/
		public function getDataProviderContribuyenteActuqlizado($idsContribuyente)
		{
			$data = [];
			$results = ContribuyenteBase::find()->where(['IN', 'id_contribuyente', $idsContribuyente])
			                                    ->asArray()
			                                    ->all();

			foreach ( $results as $result ) {
				$data[$result['id_contribuyente']] = [
					'id_contribuyente' => $result['id_contribuyente'],
					'contribuyente' => $result['razon_social'],
					'rif' => $result['naturaleza'] . '-' . $result['cedula'] . '-' . $result['tipo'],
					'sucursal' => $result['id_rif'],
					'licencia' => $result['id_sim'],
				];
			}

			$dataProvider = New ArrayDataProvider([
				'key' => 'id_contribuyente',
				'allModels' => $data,
				'pagination' => false,
			]);

			return $dataProvider;
		}






		/**
		 * Metodo que actualiza el atributo "id-sim", con el numero de licencia creado.
		 * @param  integer $idContribuyente identificador del contribuyente.
		 * @return boolean
		 */
		private function updateLicenciaContribuyente($idContribuyente)
		{
			$result = false;
			$tabla = ContribuyenteBase::tableName();

			self::armarNumeroLicencia();
			$arregloDatos = [
				'id_sim' => self::getNroLicencia(),
			];

			$arregloCondicion = [
				'id_contribuyente' => $idContribuyente,
			];

			$result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);

			return $result;
		}




		/**
	     * Metodo donde se fijan los usuario autorizados para utilizar esl modulo.
	     * @return [type] [description]
	     */
	    private function getListaFuncionarioAutorizado()
	    {
	    	return [
	    		'admin',
	    		'jperez',
	    	];
	    }



	    /**
	     * Metodo que permite determinar si un usuario esta autorizado para utilizar el modulo.
	     * @param  string $usuario usuario logueado
	     * @return booleam retorna true si lo esta, false en caso conatrio.
	     */
	    public function estaAutorizado($usuario)
	    {
	    	$listaUsuarioAutorizado = self::getListaFuncionarioAutorizado();
	    	if ( count($listaUsuarioAutorizado) > 0 ) {
	    		foreach ( $listaUsuarioAutorizado as $key => $value ) {
	    			if ( $value == $usuario ) {
	    				return true;
	    			}
	    		}
	    	}
	    	return false;
	    }

	}
 ?>