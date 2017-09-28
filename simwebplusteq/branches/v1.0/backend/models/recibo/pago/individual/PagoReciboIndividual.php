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
	use backend\models\recibo\depositodetalle\DepositoDetalleSearch;
	use backend\models\recibo\depositodetalle\DepositoDetalleUsuario;
	use backend\models\recibo\depositodetalle\VaucheDetalleUsuario;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use backend\models\recibo\planillaaporte\PlanillaAporte;
	use backend\models\recibo\planillacontable\PlanillaContableSearch;
	use backend\models\recibo\vauchedetalle\VaucheDetalle;
	use backend\models\recibo\vauchedetalle\VaucheDetalleSearch;
	use backend\models\recibo\pago\individual\SerialReferenciaUsuario;
	use common\models\planilla\Pago;
	use common\models\planilla\PagoDetalle;
	use common\models\planilla\PlanillaSearch;
	//use common\models\rafaga\GenerarRafagaPlanilla;						// planilla aporte.
	use  backend\models\recibo\prereferencia\PreReferenciaPlanillaSearch;
	 use backend\models\recibo\planillaaporte\PlanillaAporteSearch;
	use common\conexion\ConexionController;
	use yii\db\Exception;
	use yii\helpers\ArrayHelper;
	use backend\models\historico\rafaga\autorizar\HistoricoAutorizarRafaga;
	use backend\models\historico\rafaga\autorizar\HistoricoAutorizarRafagaSearch;




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
		public $_modelDepositoDetalle;
		private $_modelVaucheDetalleUsuario;

		private $_pagoSearch;	// Instancia de la clase PagoReciboIndividualSearch().

		private $_errores;
		private $_fecha_pago;
		private $_observacionPreReferencia;

		const PAGO = 1;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero de recibo de pago.
		 * @param string $fechaPago fecha de pago en formato americano, yyyy-mm-dd.
		 * @param string $observacion observacion de la pre-referncia.
		 */
		public function __construct($recibo, $fechaPago, $observacion = '')
		{
			$this->_recibo = $recibo;
			$this->_observacionPreReferencia = $observacion;
			$this->_fecha_pago = date('Y-m-d', strtotime($fechaPago));
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
				if ( is_array($model) ) {
					foreach ( $model as $key => $value) {
						if ( is_a($value, DepositoDetalle::className()) ) {
							$this->_modelDepositoDetalle[] = $value;
						} else {
							$this->_modelDepositoDetalle = null;
							break;
						}
					}
				} else {
					$this->_modelDepositoDetalle = null;
				}
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
				$this->_modelDepositoDetalle = self::definirDepositoDetalle();
			}

			if ( $this->_modelDepositoDetalle !== null && count($this->_modelDepositoDetalle) > 0 ) {
				if ( self::iniciarPago() ) {
					// Se guarda en la entidad "planillas-aporte"
					$result = self::generarRafagaPlanilla();
					if ( $result ) {
						$result = self::autorizarRafagaRecibo();
					}
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}





		/**
		 * Metodo que inicia los proceso individuales.
		 * @return [type] [description]
		 */
		private function iniciarPago()
		{
			$result = false;
			self::setConexion();
			$this->_transaccion = $this->_conn->beginTransaction();
			$this->_conn->open();

			if ( self::seteoRecibo() ) {
				$result = self::seteoPlanilla();
			}

			// Guardar detalle de como se pago el recibo; efectivo, cheque, deposito, etc.
			if ( $result ) {
				$result = self::guardarDetallePago();
			}

			// Distribucion de los ingresos presupuestarios.
			if ( $result ) {
				$planillaContableSearch = New PlanillaContableSearch($this->_recibo, $this->_conexion, $this->_conn, $this->_fecha_pago);
				$result = $planillaContableSearch->iniciarDistribucionPresupuestaria();
			}

			// Pre referencias bancarias.
			if ( $result ) {
				$usuario = Yii::$app->identidad->getUsuario();
				$modelSerial = SerialReferenciaUsuario::find()->where('recibo =:recibo',
																			[':recibo' => $this->_recibo])
															  ->andWhere('usuario =:usuario',
															  				[':usuario' => $usuario])
															  ->all();

				$modelSerial = ( count($modelSerial) == 0 ) ? null : $modelSerial;
				$referenciaSearch = New PreReferenciaPlanillaSearch($this->_recibo, $this->_conexion, $this->_conn, $modelSerial, $this->_observacionPreReferencia);
				$result = $referenciaSearch->iniciarPreReferencia();
			}

			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
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
		 * @return boolean.
		 */
		private function generarRafagaPlanilla()
		{
			self::setConexion();
			$this->_conn->open();
			$this->_transaccion = $this->_conn->beginTransaction();

			$aporteSearch = New PlanillaAporteSearch($this->_recibo, $this->_conexion, $this->_conn);
			$result = $aporteSearch->iniciarRafagaPlanilla();
			self::setError($aporteSearch->getError());

			if ( $result ) {
				$this->_transaccion->commit();
			} else {
				$this->_transaccion->rollBack();
			}
			$this->_conn->close();
			return $result;
		}


		/**
		 * Metodo que generar un registro para autorizar la rafaga del recibo
		 * @return boolean
		 */
		private function autorizarRafagaRecibo()
		{
			$historicoModel = New HistoricoAutorizarRafaga();
			$historicoModel->recibo = $this->_recibo;
			$historicoModel->usuario = Yii::$app->identidad->getUsuario();
			$historicoModel->fecha_hora = date('Y-m-d H:i:s');
			$historicoModel->autorizar = 1;
			$historicoModel->observacion = Yii::t('backend', 'Autorizacion por pago del recibo');

			// Si se quiere guardar un numero de control, desconmentar las siguientes lineas.
			//$numeroSearch = New NumeroControlSearch('db');
			//$control = $numeroSearch->generarNumeroControl();

			$control = 0;
			$historicoModel->nro_control = $control;

			$historicoSearch = New HistoricoAutorizarRafagaSearch($this->_recibo, $this->_conexion, $this->_conn);
			return $historicoSearch->guardar($historicoModel);

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
		 * Metodo que permite definir el modelo de los detalles del pago. A partir
		 * de los datos guardados en la entidad temporal.
		 * @return DepositoDetalle.
		 */
		public function definirDepositoDetalle()
		{
			$usuario = Yii::$app->identidad->getUsuario();
			$depositoDetalle = [];

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



		/**
		 * Metodo que realiza el seteo en la entidad "depositos".
		 * @return boolean.
		 */
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



		/**
		 * Metodo que realiza el seteo en la entidad "depositos-planillas".
		 * @param DepositoPlanilla $depositoPlanilla arreglo con los datos de la entidad
		 * "depositos-planillas" y "depositos"
		 * @return boolean.
		 */
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



		/**
		 * Metodo que realiza el seteo en la entidad "pagos".
		 * @param DepositoPlanilla $depositoPlanilla arreglo con los datos de la entidad
		 * "depositos-planillas" y "depositos"
		 * @return boolean.
		 */
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



		/**
		 * Metodo que realiza el seteo en la entidad "pagos-detalle".
		 * @param integer $idPago identificador de la entidad "pagos".
		 * @return boolean
		 */
		private function seteoPagoDetalle($idPago)
		{
			$tabla = PagoDetalle::tableName();

			$arregloDato = [
				'pago' => self::PAGO,
				'fecha_pago' => $this->_fecha_pago,
			];
			$arregloCondicion = [
				'id_pago' => $idPago,
			];
			return self::seteoEntidad($tabla, $arregloDato, $arregloCondicion);
		}



		/**
		 * Metodo que crea un clico con las planillas contenidas en el recibo para
		 * realizar los respectivos seteo.
		 * @return boolean.
		 */
		private function seteoPlanilla()
		{
			$result =  false;
			$planillas = $this->_pagoSearch->getDepositoPlanilla();
			foreach ( $planillas as $planilla ) {
				if ( self::seteoReciboPlanilla($planilla) ) {
					$result = self::seteoPago($planilla);
				} else {
					$result =  false;
					break;
				}
				if ( !$result ) { break; }
			}
			return $result;
		}





		/**
		 * Metodo que inicia el proceso para guardar los detalles del pago
		 * @return boolean.
		 */
		private function guardarDetallePago()
		{
			$result = false;
			if ( $this->_modelDepositoDetalle == null || count($this->_modelDepositoDetalle) == 0 ) {
				$result = false;
			} else {

				$depositoDetalleSearch = New DepositoDetalleSearch($this->_conexion, $this->_conn);
				foreach ( $this->_modelDepositoDetalle as $detalle ) {
					$idGenerado = 0;
					$result = $depositoDetalleSearch->guardar($detalle->toArray());
					$idGenerado = $depositoDetalleSearch->getIdGenerado();

					if ( $idGenerado > 0 && $detalle['id_forma'] == 2 ) {
						// Se pasa a guardar los detalle del vauche.
						$detallesVauche = VaucheDetalleUsuario::find()->where('recibo =:recibo',
							 												[':recibo' => (int)$detalle['recibo']])
															  ->andWhere('linea =:linea',
															  				[':linea' => (int)$detalle['linea']])
															  ->all();
						if ( count($detallesVauche) > 0 ) {
							$vaucheSearch = New VaucheDetalleSearch($this->_conexion, $this->_conn);
							foreach ( $detallesVauche as $vauche ) {
								$vauche['id_vauche'] = null;
								$vauche['linea'] = $idGenerado;
								$result = $vaucheSearch->guardar($vauche->toArray());
								if ( !$result ) {
									break;
								}
							}
						} else {
							$result = false;
							break;
						}
					} elseif ( $idGenerado == 0 ) {
						$result = false;
						break;
					}

				}
			}
			return $result;
		}

	}

?>