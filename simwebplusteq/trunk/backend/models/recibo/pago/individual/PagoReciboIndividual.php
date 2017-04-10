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
 *  @file PagoReciboIndividual.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-04-2017
 *
 *  @class PagoReciboIndividual
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

	namespace backend\models\recibo\pago\individual;

 	use Yii;
	use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
	use backend\models\recibo\deposito\Deposito;
	use backend\models\recibo\depositodetalle\DepositoDetalle;
	use backend\models\recibo\depositodetalle\DepositoDetalleUsuario;
	use backend\models\recibo\depositodetalle\VaucheDetalleUsuario;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use backend\models\recibo\planillaaporte\PlanillaAporte;
	use backend\models\recibo\planillacontable\PlanillaContable;
	use backend\models\recibo\vauchedetalle\VaucheDetalle;
	use backend\models\recibo\pago\individual\SerialReferenciaUsuario;
	use common\models\planilla\Pago;
	use common\models\planilla\PagoDetalle;
	use common\models\planilla\PlanillaSearch;
	use common\models\distribucion\presupuesto\GenerarPlanillaPresupuesto;
	use common\models\rafaga\GenerarRafagaPlanilla;						// planilla aporte.
	use common\models\referencia\GenerarReferenciaBancaria;
	use common\conexion\ConexionController;
	use yii\db\Exception;
	use yii\helpers\ArrayHelper;




	/**
	 * Clase que permite realizar el pago efectivamente del recibo, esta clase se encarga
	 * de realizar los respectivos update sobre las entidades que requieren el seteo de
	 * algunos atributos, asi como la insercion en las entidades que lo reuieran. Se establece
	 * una diferencia entre los pagos que se realizan de manera manual por usuario y de aquellos
	 * pagos que se realizan via procesamiento de un archivo txt o proceso en lote de recibos.
	 * Si el pago es por via de un txt se debe setaer la variable $_modelDepositoDetalle
	 * con el modelo que contiene los detalle de como se pago el recibo.
	 */
	class PagoReciboIndividual extends PagoReciboIndividualSearch
	{

		private $_recibo;
		private $_conn;
		private $_conexion;
		private $_transaccion;
		private $_modelDepositoDetalle;
		private $_modelVaucheDetalleUsuario;

		private $_pagoSearch;	// Instancia de la clase PagoReciboIndividualSearch().

		private $_errores;

		const PAGO = 1;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero de recibo de pago.
		 */
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
			$this->_pagoSearch = New PagoReciboIndividualSearch($recibo);
			$this->_modelDepositoDetalle = null;
		}



		/**
		 * Metodo que setea un error ocurrido.
		 * @param srting $mensajeError descripcion del error ocurrido.
		 */
		public function setError($mensajeError)
		{
			$this->errores[] = $mensajeError;
		}



		/**
		 * Metodo getter de los errores existentes
		 * @return array
		 */
		public function getError()
		{
			return $this->errores;
		}



		/**
		 * Metodo que setea el modelo que contiene las formas de pago del recibo.
		 * Si el recibo se esta pagando de manera manual este se debe setear buscando
		 * los datos en la entidad temporal, si se esta pagando por un preceso en lote
		 * (txt), este valor debe setearse desde afuera.
		 * @param DepositoDetalle $model [description]
		 */
		public function setDepositoDetalle($model)
		{
			if ( is_a($model, DepositoDetalle::className()) ) {
				$this->_modelDepositoDetalle = $model;
			} else {
				$this->_modelDepositoDetalle = null;
			}
		}




		/**
		 * Metodo getter del modelo DepositoDetalle.
		 * @return DepositoDetalle.
		 */
		private function getDepositoDetalle()
		{
			return $this->_modelDepositoDetalle;
		}




		/**
		 * Metodo que inicia el proceso de pago
		 * @return boolean.
		 */
		public function iniciarPagoRecibo()
		{

			if ( $this->_modelDepositoDetalle == null ) {
				self::definirDepositoDetalle();
			}

			self::iniciarPago();
		}





		/***/
		private function iniciarPago()
		{
			$result = false;
			self::setConexion();
			$this->_transaccion = $this->_conn->beginTransaction();
			$this->_conn->open();

			if ( self::seteoRecibo() ) {
				$result = self::seteoPlanilla();
			}

			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollback();
			}

			$this->_conn->close();
			return $result;
		}




		/**
		 * Metodo que configura las variables que permitiran la interaccion
		 * con la base de datos.
		 */
		private function setConexion()
		{
			$this->_conexion = New ConexionController();
			$this->_conn = $this->_conexion->initConectar('db');
		}



		/**
		 * Metodo que invoca al generardor de la rafag de las planillas.
		 * @param integer $linea identificador generado de la entidad "depositos-detalle"
		 * una vez que se inserta un registro en dicha entidad. El mismo sirve para link
		 * entre las entidases "depositos-detalle" y "vauches-detalles" para aquellas
		 * formas de pago que tiene por valor el id-forma = 2 (vauches bancarios o depositos).
		 * @return array
		 */
		private function generarRafaga($linea)
		{
			$generar = New GenerarRafagaPlanilla($this->_recibo);
			$rafaga = $generar->iniciarRafaga();
			self::setError($generar->getError());
			return $rafaga;
		}



		/**
		 * Metodo que invoca el generador de la distribucion presupustaria.
		 * Se toman las planillas que contiene el recibo y se determina los
		 * codigos presupuestarios asociadas a las misma. Si existe un error
		 * en el proceso de seteara los mismos en la variable de errores.
		 * @return array
		 */
		private function generarDistribucionPresupuesto()
		{
			$generar = New GenerarPlanillaPresupuesto($this->_recibo);
			$distribucion = $generar->iniciarPlanillaPresupuesto();
			self::setError($generar->getError());
			return $distribucion;
		}



		/***/
		private function generarReferenciaBancaria()
		{
			$generar = New GenerarReferenciaBancaria($this->_recibo);
			// Determinar si existen registros en la entidad temporal del recibo
			// si es asi se asume que se esta pagando un recibo manualmente.
			//$referencia = $generar->iniciarPlanillaPresupuesto();
			self::setError($generar->getError());
			return $referencia;
		}


		/**
		 * [seteoEntidadd description]
		 * @param string $tabla nombre de la entidad.
		 * @param array $arregloDato arreglo de datos que contienen el atributo que se desea
		 * modificar y el nuevo valor de dicho atributo.
		 * @param array $arregloCondicion arreglo que contiene el filtro que se utilizara en
		 * el wher condition de la sentencia sql a ejecutar.
		 * @return boolean.
		 */
		private function seteoEntidad($tabla, $arregloDato, $arregloCondicion)
		{
			return $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDato, $arregloCondicion);
		}



		/**
		 * Metodo que se encargara de realizar la insercion del o los registro(s) en la
		 * entidad respectiva.
		 * @param modelo $model modelo de la entidad con sus respectivos datos.
		 * @return integer.
		 */
		private function insertarEntidad($model)
		{
			$idGenerado = 0; 	// Autoincremental de la entidad generado debido a la insercion.
			$result = false;

			$result = $this->_conexion->guardarRegistro($this->_conn, $model->tableName(), $model->attributes());
			if ( $result ) {
				$idGenerado = $this->_conn->getLastInsertID();
			}
			return $idGenerado;
		}



		/**
		 * Metodo que localiza los seriales manuales registrados para el recibo
		 * especifico. Si encuentra registro significa que le proceso de pago
		 * del recibo es manual.
		 * @return SerialReferenciaUsuario
		 */
		private function findSerialBancarioManual()
		{
			$registers = SerialReferenciaUsuario::find()->where('recibo =:recibo',
																	[':recibo' => $this->_recibo])
														->all();
			return $registers;
		}




		/**
		 * [findDepositoDetalle description]
		 * @return [type] [description]
		 */
		public function definirDepositoDetalle()
		{
			$usuario = Yii::$app->identidad->getUsuario();

			// Se busca los registros temporales de las detalles de pago.
			$registers = $this->_pagoSearch->findDepositoDetalleUsuarioTemp($usuario);

			if ( count($registers) > 0 && $registers !== null ) {

				foreach ( $registers as $key => $model ) {
					$depositoDetalle[$key] = New DepositoDetalle();
					foreach ( $depositoDetalle[$key]->attributes as $i => $value ) {
						if (isset($model[$i]) ) {
							$depositoDetalle[$key]->$i = $model[$i];
						}
					}
				}
			}
			return $depositoDetalle;

		}



		/***/
		private function seteoRecibo()
		{
			$tabla = Deposito::tableName();
			$arregloDato = [
				'estatus' => self::PAGO,
				'usuario' => Yii::$app->identidad->getUsuario(),
				'fecha_hora_proceso' => date('Y-m-d H:i:s'),
			];
			$arregloCondicion = [
				'recibo' => $this->_recibo,
			];

			return self::seteoEntidad($tabla, $arregloDato, $arregloCondicion);
		}



		/***/
		private function seteoReciboPlanilla($depositoPlanilla)
		{
			$tabla = DepositoPlanilla::tableName();
			$arregloDato = [
				'estatus' => self::PAGO,
			];
			$arregloCondicion = [
				'recibo' => $depositoPlanilla['recibo'],
				'planilla' => $depositoPlanilla['planilla'],
			];
			return self::seteoEntidad($tabla, $arregloDato, $arregloCondicion);
		}



		/***/
		private function seteoPago($depositoPlanilla)
		{
			$result= false;
			$pago = PagoDetalle::find()->alias('D')
								->joinWith('pagos P', true, 'INNER JOIN')
							    ->where('planilla =:planilla',
												[':planilla' => $depositoPlanilla['planilla']])
							    ->asArray()
								->one();

			$tabla = Pago::tableName();

			$arregloDato = [
				'recibo' => (int)$depositoPlanilla['recibo'],
			];
			$arregloCondicion = [
				'id_pago' => (int)$pago['id_pago'],
				'recibo' => 0,
			];

			if ( self::seteoEntidad($tabla, $arregloDato, $arregloCondicion) ) {
				$result = self::seteoPagoDetalle((int)$pago['id_pago']);
			}

			return $result;
		}



		/***/
		private function seteoPagoDetalle($idPago)
		{
			$tabla = PagoDetalle::tableName();

			$arregloDato = [
				'pago' => self::PAGO,
				'fecha_pago' => date('Y-m-d'),
			];
			$arregloCondicion = [
				'id_pago' => $idPago,
			];
			return self::seteoEntidad($tabla, $arregloDato, $arregloCondicion);
		}



		/***/
		private function seteoPlanilla()
		{
			$result =  false;
			$planillas = $this->_pagoSearch->getDepositoPlanilla();
			foreach ( $planillas as $planilla ) {
				if ( self::seteoReciboPlanilla($planilla) ) {
					$result = self::seteoPago($planilla);
				} else {
					break;
				}
				if ( !$result ) { break; }
			}
			return $result;
		}


	}

?>