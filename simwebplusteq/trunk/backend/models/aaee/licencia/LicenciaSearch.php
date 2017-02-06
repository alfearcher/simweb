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
 *  @file LicenciaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @class LicenciaSearch
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

 	namespace backend\models\aaee\licencia;

 	use Yii;
	use yii\db\ActiveRecord;
	use backend\models\aaee\licencia\Licencia;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\aaee\actecon\ActEconSearch;
	use yii\base\ErrorException;
	use common\conexion\ConexionController;
	use backend\models\aaee\licencia\numerolicencia\NumeroLicenciaSearch;




	/**
	 * Clase que gestiona la generacion del numero de licencia.
	 */
	class LicenciaSearch extends Licencia
	{

		private $_id_contribuyente;
		private $_nro_licencia;
		private $_tipoLicencia = '';
		private $_observacion = '';
		private $_conexion;
		private $_conn;
		private $_transaccion;





		/**
		 * Metodo constructor de la clase.
		 * @param long $idContribuyente identificador del contribuyente.
		 * Valor unico dentro de la entidad correspondiente.
		 */
		public function __construct($idContribuyente, $conexion, $conn)
		{
			$this->_id_contribuyente = $idContribuyente;
			$this->_nro_licencia = 0;
			$this->_conexion = $conexion;
			$this->_conn = $conn;

		}



		/**
		 * Modelo principal de consulta sobre la entidad "licencias".
		 * @return active record.
		 */
		private function getLicenciaModel()
		{
			return $findModel = Licencia::find()->where('id_contribuyente =:id_contribuyente',
														[':id_contribuyente'=> $this->_id_contribuyente]);
		}



		/**
		 * Metodo que realiza una consulta sobre la entidad "licencias", para aquellos
		 * registros del contribuyente que esten activo (0) o inactivo (1),
		 * @return [type] [description]
		 */
		public function getRegistrosLicencia()
		{
			$findModel = self::getLicenciaModel();

			return $model = $findModel->andWhere(['IN', 'status_licencia', [0,1]])
							          ->orderBy([
							   				'status_licencia' => SORT_ASC,
							   				'ano_impositivo' => SORT_DESC,
							   			])
							          ->all();
		}



		/**
		 * Metodo que genera un numero de licencia, para un contribuyente que este asociado
		 * a Actividad Economica. Este numero de licencia es el generado por el sistema anterior
		 * (SIM vb), donde dicho numero era guradado en la entidad "licencias" ( campo serial-licencia )
		 * @return string retorna un cadena que representa el numero de licencia.
		 */
		public function determinarNroLicencia()
		{
			$this->_nro_licencia = '';
			$findModel = self::getLicenciaModel();
			if ( ContribuyenteBase::getTipoNaturalezaDescripcionSegunID($this->_id_contribuyente) == 'JURIDICO' ) {
				$contribuyente = ContribuyenteBase::findOne($this->_id_contribuyente);

				$this->_nro_licencia = $contribuyente->id_sim;
				// if ( strlen(trim($nro)) > 1 ) {
				// 	$this->_nro_licencia = $nro;
				// }
			}
		}




		/**
		 * Metodo que permite obtener el numero de licencia.
		 * @return string retorna numero de licencia del contribuyente juridico.
		 */
		public function getLicencia()
		{
			return $this->_nro_licencia;
		}



		/**
		 * Metodo que setea el numero de licencia
		 * @param strieng $nroLicencia numero de licencia.
		 */
		public function setLicencia($nroLicencia)
		{
			$this->_nro_licencia = $nroLicencia;
		}



		/**
		 * Metodo que setea el tipo de licencia.
		 * @param string $tipo tipo de licencia "NUEVA" o "RENOVADA"
		 */
		public function setTipoLicencia($tipo)
		{
			$this->_tipoLicencia = $tipo;
		}




		/**
		 * Metodo que retorna el tipo de licencia a guardar.
		 * @return string.
		 */
		public function getTipoLicencia()
		{
			return $this->_tipoLicencia;
		}




		/**
		 * Metodo que setea el contenido de la observacion que va en el registro.
		 * @param string $observacion nota a colocar en la licencia.
		 */
		public function setObservacion($observacion)
		{
			$this->_observacion = $observacion;
		}




		/**
		 * Metodo que retorna el contenido de la observacion.
		 * @return [type] [description]
		 */
		public function getObservacion()
		{
			return $this->_observacion;
		}



		/**
		 * Metodo que permite determinar el valor del atributo "serial-licencia"
		 * Se recibe el ultimo existente y se le agrega uno.
		 * @return integer retorna el valor del serial licencia que le corresponde.
		 */
		private function determinarSerialLicencia()
		{
			$findModel = self::getLicenciaModel();
			$model = $findModel->orderBy([
			                   		'ano_impositivo' => SORT_DESC,
			                   	])
							   ->asArray()
							   ->one();

			if ( count($model) > 0 ) {
				return (int)$model['serial_licencia'];
			} else {
				$serial = self::generarSerialLicencia();
				if ( $serial > 0 ) {
					return $serial + 1;
				}
			}

			return 0;
		}




		/**
		 * Metodo busca el ultimo valor existente del atributo "serial-licencia",
		 * en la entidad "licencias". Luego retorna dicho valor.
		 * @return integer retorna el mayor valor existente del atributo "serial-licencia"
		 */
		private function generarSerialLicencia()
		{
			$findModel = Licencia::find()->orderBy([
												'serial_licencia' => SORT_DESC
											])
										 ->one();
			if ( count($findModel) > 0 ) {
				return (int)$findModel->serial_licencia;
			}

			return 0;
		}




		/**
		 * Metodo que inserta un regsitro en la entidad "licencias".
		 * @return boolean retorna true si inserta satisfactoriamente, false en caso contrario.
		 */
		public function guardar()
		{
			$result = false;
			$model = New Licencia();
			$tabla = $model->tableName();

			try {
				$model->ente = Yii::$app->ente->getEnte();
				$model->id_contribuyente = $this->_id_contribuyente;
				$model->ano_impositivo = date('Y');

				$model->id_impuesto = self::getIdImpuesto(date('Y'));

				if ( $model->id_impuesto > 0 ) {
					$model->serial_licencia = self::determinarSerialLicencia();
					$model->fecha_emision = date('Y-m-d');
					$model->fecha_vcto = self::getFechaVencimiento();
					$model->status_licencia = 0;
					$model->licores = 0;
					$model->observacion = self::getObservacion();
					$model->licores2 = 0;
					$model->uso_licencia = 0;
					$model->serial_preimpreso = '';
					$model->tipo_licencia = self::getTipoLicencia();
					$model->nro_licencia = self::getLicencia();

					$result = self::inactivarRegistro();
					if ( $result ) {
						$result = $this->_conexion->guardarRegistro($this->_conn, $tabla, $model->attributes);
					}

				}

			} catch ( ErrorException $e ) {
				$result = false;
				Yii::warning('No se pudo gerera el registro para el nuemro de licencia');
			}

			return $result;
		}




		/**
		 * Metodo que inactiva los registros existentes en la entidad "licencias"
		 * para un contribuyente especifico.
		 * @return boolean
		 */
		public function inactivarRegistro()
		{
			$result = false;
			$model = New Licencia();
			$tabla = $model->tableName();

			$arregloCondicion = [
					'id_contribuyente' => $this->_id_contribuyente,
					'status_licencia' => 0,
			];
			$arregloDatos = [
					'status_licencia' => 1,
			];
			$result = $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDatos, $arregloCondicion);

			return $result;
		}




		/**
		 * Metodo que retorna el identificador de la entidad "act-econ", segun un año especifico
		 * @param  integer $añoImpositivo año impositivo de la declaracion.
		 * @return integer retorna el identificador de la entidad "act-econ".
		 */
		private function getIdImpuesto($añoImpositivo)
		{
			$searchActEcon = New ActEconSearch($this->_id_contribuyente);
			$identificador = $searchActEcon->getIdentificador($añoImpositivo);
			if ( $identificador !== false && $identificador !== null ) {
				return $identificador;
			}
			return 0;
		}




		/**
		 * Metodo que define la fecha de vencimiento de la licencia.
		 * @return date
		 */
		public function getFechaVencimiento()
		{
			return date('Y') . '-12-31';
		}




		/**
		 * Metodo que se encarga de inicializar las instancia de conexion y transaccion para
		 * indertar un registro.
		 * @param  string $db nombre de la instabcia de conexion a bd
		 * @return boolean retorna true
		 */
		public function generarNumeroLicencia($db = 'db')
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar($db);

			$this->_conn->open();

			$this->_transaccion = $this->_conn->beginTransaction();
			$result = self::guardar();
			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();

			return $result;
		}




		/***/
		public function getCorrelativoLicencia()
		{
			$correlativo = 0;
			$searchNumero = New NumeroLicenciaSearch();
			$searchNumero->init('db');
			$correlativo = $searchNumero->getGenerarNumeroLicencia();
			return $correlativo;
		}



	}
 ?>