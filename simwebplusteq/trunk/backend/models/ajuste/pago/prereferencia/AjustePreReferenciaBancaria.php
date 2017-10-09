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
 *  @file AjustePreReferenciaBancaria.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 06-10-2017
 *
 *  @class AjustePreReferenciaBancaria
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

	namespace backend\models\ajuste\pago\prereferencia;

 	use Yii;
	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;
	use common\conexion\ConexionController;



	/**
	 * Clase que ejecuta
	 */
	class AjustePreReferenciaBancaria
	{
		private $_conexion;
		private $_conn;
		private $_transaccion;
		private $_observacion = '';
		private $_tipo_ajuste;

		/**
		 * arreglo de mensajes
		 * @var array
		 */
		private $_errores = [];

		/**
		 * arreglo de modelo de las referencias procesadas.
		 * @var array
		 */
		private $_models = [];



		/**
		 * Metodo constructor de la clase
		 * @param integer $tipoAjuste entero que indica el metodo seleccionado para
		 * realizar el ajuste de los registros.
		 * @param string $observacion nota a colocar en campo "observacion" de la
		 * entidad "pre-referencias-planillas".
		 */
		public function __construct($tipoAjuste, $observacion = '')
		{
			$this->_tipo_ajuste = $tipoAjuste;
			$this->_observacion = $observacion;
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
		 * Metodo setter de los errores ocurridos durante el proceso de ajuste.
		 * @param string $mensajeError mensaje de error.
		 */
		private function setError($mensajeError)
		{
			$this->_errores[] = $mensajeError;
		}


		/**
		 * Metodo getter de los errores.
		 * @return array arreglo de mensajes.
		 */
		public function getErrores()
		{
			return $this->_errores;
		}



		/**
		 * Metodo setter de los id referencias procesados.
		 * @param PreReferenciaPlanilla $model modelo de datos
		 */
		private function setIdProcesado($model)
		{
			$this->_models[] = $model->toArray();
		}


		/**
		 * Metodo getter de kos id procesados.
		 * @return PreReferenciaPlanilla $model modelo de datos.
		 */
		public function getIdProcesado()
		{
			return $this->_models;
		}




		/**
		 * Metodo que inicia el proceso de ajuste de las referencia por ids de las referencias
		 * @param array|integer $IdsReferencias arreglo de id-referencia o en du defecto un
		 * entero del id-referencia.
		 * @return boolean
		 */
		public function iniciarAjuste($idReferencias)
		{
			self::setError('Error de prueba 1');
			self::setError('Error de prueba 2');
			$model = [];	// modelo de las pre-refrencias
			if ( (int)$this->_tipo_ajuste > 0 ) {
				if ( is_array($idReferencias) ) {
					foreach ($idReferencias as $key => $value) {
						$model = self::findPreReferenciaById($value);
						self::aplicarAjusteSegunTipo($model);
					}
				} elseif( is_integer($idReferencias) ) {
					$model = self::findPreReferenciaById($idReferencias);
					self::aplicarAjusteSegunTipo($model);
				}
			} else {
				self::setError(Yii::t('backend', 'No esta definido  el tipo de ajuste a realizar sobre los registros'));
				return false;
			}
			return;
		}


		/**
		 * Metodo que genera el modelo basico de consulta de la entidad "pre-referencias-planillas".
		 * @return PreReferenciaPlanilla.
		 */
		public function findPreReferenciaPlanillaModel()
		{
			return PreReferenciaPlanilla::find()->alias('P');
		}


		/**
		 * Metodo que realiza la busqueda del registro por id del mismo.
		 * @param  integer $idReferencia identificador del registro.
		 * @return array
		 */
		private function findPreReferenciaById($idReferencia)
		{
			return PreReferenciaPlanilla::findOne($idReferencia);
		}


		/**
		 * Metodo que bifurca hacia la metodologia que realizara el proceso de ajuste.
		 * El metodo retornara un true o false.
		 * @param  PreReferenciaPlanilla $model modelo de datos
		 * @return boolean
		 */
		public function aplicarAjusteSegunTipo(PreReferenciaPlanilla $model)
		{
			$result = false;
			if ( (int)$this->_tipo_ajuste == 1 ) {
				$result = self::ajustePorDiferenciaMonto($model);
			}
			return $result;
		}


		/**
		 * Metodo que aplica la metodologia de ajuste por diferencia de monto de las referencias
		 * contra los montos de las planillas. Retornara un boolean como resultado
		 * @param  PreReferenciaPlanilla $model modelo con la consulta realizada.
		 * @return boolean
		 */
		private function ajustePorDiferenciaMonto(PreReferenciaPlanilla $model)
		{
			$result = false;
			$arregloDato = [];
			$arregloCondicion = [
				'id_referencia' => $model['id_referencia'],
			];

			if ( (float)round($model['credito'], 2) >= 0 ) {
				if ( (float)round($model['credito'], 2) !== (float)round($model['monto_planilla'], 2) ) {
					$arregloDato = [
						'credito' => $model['monto_planilla'],
						'observacion' => $model['observacion'] . ' / ' . $this->_observacion,
					];
				}
			} elseif ( (float)round($model['credito'], 2) < 0 ) {
				if ( (float)round($model['credito'], 2) !== (float)round($model['monto_planilla'], 2) ) {
					$arregloDato = [
						'debito' => (-1) * $model['monto_planilla'],
						'credito' => $model['monto_planilla'],
						'observacion' => $model['observacion'] . ' / ' . $this->_observacion,
					];
				}
			}
			if ( count($arregloCondicion) > 0 && count($arregloDato) > 0 ) {
				$result = self::update($arregloCondicion, $arregloDato, $model);
			}
			return $result;
		}




		/**
		 * Metodo ejecuat el update
		 * @param array $arregloCondicion arreglo que contiene el where condition de la actualizacion.
		 * @param array $arregloDato datos que seran actualizados.
		 * @param PreReferencia $model modelo de datos de las PreReferencias.
		 * @return boolean
		 */
		private function update($arregloCondicion, $arregloDato, $model)
		{
			$result = false;
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			$result = $this->_conexion->modificarRegistro($this->_conn, $model->tableName(), $arregloDato, $arregloCondicion);

			if ( $result ) {
				$this->_transaccion->commit();
				self::setIdProcesado($model);
			} else {
				$this->_transaccion->rollBack();
				selt::setError(Yii::t('backend', 'Fallo la actualizacion del registro ') . ' id-referencia: '. $model['id_referencia'] . ' recibo: ' . $model['recibo']);
			}
			$this->_conn->close();
			return $result;
		}



	}
?>