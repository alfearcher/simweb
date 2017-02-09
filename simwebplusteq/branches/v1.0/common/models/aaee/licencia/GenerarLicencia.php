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
 *  @file GenerarLicencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19-09-2015
 *
 *  @class GenerarLicencia
 *  @brief Clase Modelo que maneja la politica de generarcion de la licencia (numero de licencia)
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

	namespace common\models\aaee\licencia;


 	use Yii;
 	use backend\models\aaee\licencia\LicenciaSearch;
 	use common\conexion\ConexionController;
 	use common\models\contribuyente\ContribuyenteBase;
 	use backend\models\aaee\licencia\LicenciaSolicitudSearch;


	/**
	* 	Clase
	*/
	class GenerarLicencia extends LicenciaSearch
	{

		private $_id_contribuyente;
		private $_nroLicencia;
		private $_nro_solicitud;
		private $_correlativo;
		private $_tipoLicencia;
		private $_observacion;
		private $_conexion;
		private $_conn;
		private $_transaccion;

		public  $procesoLocal = false;


		/**
		 * Contructor de la clase
		 * @param integer $idContribuyente identificador del contribuyente.
		 * @param integer $broSolicitud identificador de la solicitud creada para la licencia.
		 * @param ConexionController $conexion instancia de la clase.
		 * @param Connection $conn instancia de la clase.
		 */
		public function __construct($idContribuyente, $nroSolicitud, $conexion, $conn)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_correlativo = 0;
			$this->_nro_solicitud = $nroSolicitud;
			$this->_conexion = $conexion;
			$this->_conn = $conn;
			$this->_nroLicencia = '';

			// Se insertara en la entidad "licencias" con la misma instancia de conexion
			// que se esteutilizando para el procesamiento d ela solicitud.
			parent::__construct($idContribuyente, $this->_conexion, $this->_conn);

		}




		/**
		 * Metodo que inicia el proceso de generacion del numero de licencia
		 * @return [type] [description]
		 */
		public function iniciarGenerarLicencia()
		{
			$result = false;
			$this->determinarNroLicencia();
			$this->_nroLicencia = $this->getLicencia();

			if ( !isset($this->_conexion) || !isset($this->_conn) ) {
				self::setConexion();
				$this->_conn->open();
				$this->_transaccion = $this->_conn->beginTransaction();
				$this->procesoLocal = true;

			}

			if ( strlen($this->_nroLicencia) <= 2 ) {

				// Se debe generar un numero de licencia.
				self::getArmarNumeroLicencia();
				if ( strlen(self::getLicenciaGenerada()) > 2 ) {

					// Se setea el valor generado en la entidad "contribuyentes".
					$result = self::setNumeroLicenciaContribuyente(self::getLicenciaGenerada());
					if ( $result ) {
						$result = self::insertarLicencia();
					}
				}

			} else {
				// Ya tiene licencia.
				$result = self::insertarLicencia();
			}

			if ( $this->procesoLocal ) {
				if ( $result ) {
					$this->_transaccion->commit();
				} else {
					$this->_transaccion->rollBack();
				}
				$this->_conn->close();
			}

			return $result;

		}




		/**
		 * Metodo que retorna el numero de licencia generado, segun politica.
		 * @return string.
		 */
		public function getLicenciaGenerada()
		{
			return $this->_nroLicencia;
		}




		/**
		 * Metodo que retorna el correlatibo generado producto de la insercion en
		 * la entidad respectiva.
		 * @return integer.
		 */
		public function getCorrelativo()
		{
			return $this->_correlativo;
		}




		/**
		 * Metodo que setea las variables para la interaccion con la base de datos.
		 */
		private function setConexion()
		{
			$this->_conexion = new ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}




		/**
		 * Metodo que arma el numero de licencia segun la politica establecida para la
		 * conformacion del mismo. El correlativo es una insercion que se hace sobre la
		 * entidad "numeros-licencias".
		 * @return string
		 */
		private function getArmarNumeroLicencia()
		{
			$this->_nroLicencia = '';
			$this->_correlativo = $this->getCorrelativoLicencia();
			if ( $this->_correlativo > 0 ) {

				// Numero de licencia correlativo - año actual.
				$this->_nroLicencia = $this->_correlativo . '-' . date('Y');

			}
		}




		/**
		 * Metodo que actualiza el atributo "id-sim" en la entidad "contribuyentes".
		 * Esto implica la asignacion del numero de licencia al contribuyente.
		 * @param boolean.
		 */
		private function setNumeroLicenciaContribuyente($nroLicencia)
		{
			$result = false;

			$tabla = ContribuyenteBase::tableName();
			$arregloDatos['id_sim'] = $nroLicencia;
			$arregloCondicion['id_contribuyente'] = $this->_id_contribuyente;

			$result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);

			return $result;
		}



		/**
		 * Metodo que realiza la insercion en la entidad "licencias".
		 * @return boolean.
		 */
		private function insertarLicencia()
		{
			$tipo = self::determinarTipoLicencia();
			if ( !$tipo ) {
				$tipo = 'NO ESPECIFICADA';
			}
			$this->setTipoLicencia($tipo);
			$this->setObservacion('PRUEBA');
			$this->setLicencia(self::getLicenciaGenerada());
			return $result = $this->guardar();
		}




		/**
		 * Metodo que determina el tipo de licencia segun la solicitud de la misma.
		 * @return string.
		 */
		public function determinarTipoLicencia()
		{
			$solicitudLicencia = New LicenciaSolicitudSearch($this->_id_contribuyente);
			$findModel = $solicitudLicencia->findSolicitudLicencia($this->_nro_solicitud);

			$resultado = $findModel->asArray()->all();
			if ( count($resultado) > 0 ) {
				return $resultado[0]['tipo'];
			}
			return false;
		}

	}

?>