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
 *  @file PagoReciboLoteSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class PagoReciboLoteSearch
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

	namespace backend\models\recibo\pago\lote;

 	use Yii;
	use yii\helpers\ArrayHelper;
	use yii\data\ActiveDataProvider;
	use common\models\planilla\PlanillaSearch;
    use backend\models\recibo\depositodetalle\DepositoDetalle;
    use yii\data\ArrayDataProvider;
    use backend\models\recibo\pago\individual\PagoReciboIndividualSearch;
    use backend\models\recibo\pago\individual\PagoReciboIndividual;
    use backend\models\recibo\txt\RegistroTxtRecibo;
    use backend\models\utilidad\banco\BancoSearch;
    use common\models\numerocontrol\NumeroControlSearch;
    use common\conexion\ConexionController;


	/**
	* Clase que permite gestionar el pago en lote de los recibos contenidos
	* en el archivo txt de pago enviado por el banco. Se recibe los parametros
	* de localizacion del archivo (ruta y nombre del archivo). Se busca el archivo
	* luego se analizara el contenido del mismo para cerificar que se cumpla con
	* las especificaciones establecidas para su procesamiento. Esta especificaciones
	* son:
	* - Cantidad de columnas de ser igual a 16.
	* - Longitud del contenido de las columnas debe ser igaul a las especificaciones
	* indicadas en el documento enviado al banco.
	*
	* ==============================================================================================================
	* Campo 				Longitud 		Tipo 					Observacion
	* ==============================================================================================================
	* 1.Numero de Recibo   		10 		  Numerico 		Numero del recibo de pago. Auto-incremental de la entidad
	* --------------------------------------------------------------------------------------------------------------
	* 2.Monto del Recibo 			19 		  numerico 		Monto total del recibo.
	* --------------------------------------------------------------------------------------------------------------
	* 3.Fecha de Pago 			08 		  Numerico 		Fecha de pago
	* --------------------------------------------------------------------------------------------------------------
	* 4.Monto Efectivo 			19 		  Numerico		Monto por concepto de Efectivo, forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* 5.Monto Cheque 				19		  Numerico 		Monto por concepto de Cheque, forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* 6.Cta Cheque 				25 	      Numerico 		Numero de cuenta asociada al cheque.
	* --------------------------------------------------------------------------------------------------------------
	* 7.Nro. Cheque 			    15		  Numerico 		Numero del cheque. Forma de Pago
	* --------------------------------------------------------------------------------------------------------------
	* 8.Fecha Cheque  			08 		  Numerico 		Fecha de pago, registrado por el banco. ddmmyyyy
	* --------------------------------------------------------------------------------------------------------------
	* 9.Monto TDD 			    19 		  Numerico 		Monto por concepto de tarjeto de debito. Forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* 10.Nro. TDD 					19 		  Numerico 	    Numero de la tarjeta de debito
	* --------------------------------------------------------------------------------------------------------------
	* 11.Monto TDC                 19        Numerico 		Monto por concepto de tarjeta de credito. Forma de pago.
	* --------------------------------------------------------------------------------------------------------------
	* 12.Nro. TDC                  19		  Numerico 	    Numero de la tarjeta de credito.
	* --------------------------------------------------------------------------------------------------------------
	* 13.Monto Transferencia 		19 		  Numerico      Monto por concepto de transferencia. Forma de pago
	* --------------------------------------------------------------------------------------------------------------
	* 14.Nro. Transaccion          19        Numerico      Numero de la transferencia.
	* --------------------------------------------------------------------------------------------------------------
	* 15.Monto Total               19        Numerico      Monto total del registro. Sumatoria de las formas de pago.
	* --------------------------------------------------------------------------------------------------------------
	* 16.Nro. Cta. Recaudadora     25        Numerico 		Numero de la cuenta recaudadora asociada a la Alcaldia.
	* --------------------------------------------------------------------------------------------------------------
	*
	*/
	class PagoReciboLoteSearch
	{

		private $_nro_control;			// Numero de control de la operacion.
    	private $_mostarArchivo;		// Instancia de la clase MostrarArchivoTxt().

    	private $_lista_registro_txt_recibo;

    	/**
    	 * Variable que indica el path y el file utlizado en el proceso de pago.
    	 * @var string
    	 */
    	private $_ruta;

    	/**
    	 * Varible que inidica el nombre del archivo txt de pago.
    	 * El nombre debe contener la extencion .txt
    	 * @var string
    	 */
    	private $_archivo;


    	/**
    	 * Variable que indica el usuario que esta ejecutando el proceso de pago.
    	 * @var string
    	 */
    	private $_usuario;

    	private $_conn;
		private $_conexion;
		private $_transaccion;

		/**
		 * Lista de errores ocurridos
		 * @var array
		 */
		private $_errores = [];



		/**
		 * Metodo constructor de la clase.
		 * @param MostrarArchivoTxt $mostrarArchivo instancia de la clase.
		 */
		public function __construct(MostrarArchivoTxt $mostrarArchivo)
		{
			$this->_nro_control = 0;
			$this->_mostarArchivo = $mostrarArchivo;
			$this->_usuario = Yii::$app->identidad->getUsuario();
			$_lista_registro_txt_recibo = [];
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
		 * Metodo que inicia el proceso.
		 * @return none
		 */
		public function getArchivoTxtFormateado()
		{
			if ( self::validarArchivo() ) {
				self::generarNumeroControlOperacion();

				// Arreglo que se creo con el contenido del archivo de conciliacion txt
				// Cada linea del archivo contiene columnas que se convirtieron en un
				// arreglo de atributos, a su vez cada linea es un oten de un arreglo
				// mas global.
				$listaPagos = self::getListaRegistroPago();


				// Permite crear model de ReciboTxtArchivo.
				self::crearCicloPago($listaPagos);

				return $this->_lista_registro_txt_recibo;
			} else {
				return null;
			}
		}




		/**
		 * Metodo que genera el proveedor de datos para los registros que se encuentran
		 * en el archivo de conciliacion, pero los datos seran mostrados formateados.
		 * @return ArrayDataProvider
		 */
		public function getDataProviderArchivoFormateado()
		{
			$data = [];
			$models = self::getArchivoTxtFormateado();

			if ( $models !== null ) {
				foreach ( $models as $i => $model ) {
					$data[] = $model->toArray();
				}
			}

			$provider = New ArrayDataProvider([
				'allModels' => $data,
				'pagination' => false,
				// 'sort' => [
			 //        'attributes' => ['recibo'],
			 //    ],
			]);

			return $provider;
		}




		/**
		 * Metodo getter
		 * @return array
		 */
		public function getListaRegistroTxt()
		{
			return $this->_lista_registro_txt_recibo;
		}




		/**
		 * Metodo que inicia el proceso.
		 * @return none
		 */
		public function iniciarPagoReciboLote()
		{
			if ( self::validarArchivo() ) {
				self::generarNumeroControlOperacion();
				$listaPagos = self::getListaRegistroPago();
				self::crearCicloPago($listaPagos);
				if ( count($this->_lista_registro_txt_recibo) > 0 ) {
					foreach ( $this->_lista_registro_txt_recibo as $key => $model ) {
						$result = self::procesarRegistroTxt($model);
					}
				}
			}
		}





		/**
		 * Metodo que instancia la clase que genera el numero de control que se utilizara
		 * en la operacion de insercion de los registros existentes en el archivo txt de pagos.
		 * @return none
		 */
		private function generarNumeroControlOperacion()
		{
			$numeroControlSearch = New NumeroControlSearch();
			$this->_nro_control = $numeroControlSearch->generarNumeroControl();
		}



		/**
		 * Metodo getter del numero de control generado.
		 * @return integer.
		 */
		public function getNroControl()
		{
			return $this->_nro_control;
		}




		/**
		 * Metodo que verifica si la ruta y el nombre de archivo no genero error.
		 * @return boolean
		 */
		private function validarArchivo()
		{
			$this->_mostarArchivo->iniciarMostrarArchivo();
			if ( count($this->_mostarArchivo->getError()) == 0 ) {
				return true;
			} else {
				array_push($this->_errores, $this->_mostarArchivo->getError());
				return false;
			}

		}




		/**
		 * [getErrores description]
		 * @return array
		 */
		public function getError()
		{
			return $this->_errores;
		}


		/**
		 * Metodo que crea un clico con los registros del archivo txt de pago, donde cada
		 * item del ciclo corresponde a una linea del archivo txt.
		 * @param array $listaPagos arreglo de pagos existente en el archivo txt.
		 * @return none
		 */
		private function crearCicloPago($listaPagos)
		{
			if ( count($listaPagos) > 0 ) {
				foreach ( $listaPagos as $pago ) {

					self::armarRegistroTxtRecibo($pago);
				}
			}
		}




		/***/
		private function armarRegistroTxtRecibo($itemPago)
		{
			// item de pago efectuado en banco.
			$recibo = (int)$itemPago['recibo'];

			$pagoReciboSearch = New PagoReciboIndividualSearch($recibo);

			// Datos de la entidad "depositos" y "depositos-planillas".
			$registers = $pagoReciboSearch->getDepositoPlanilla();

			if ( count($registers) > 0 ) {
				$idContribuyente = $registers[0]['deposito']['id_contribuyente'];
				$mensajes1 = self::validarReciboContraBD($pagoReciboSearch, $registers);
				$mensajes2 = self::validarReciboContraTxt($registers, $itemPago);

				$mensajes = array_merge($mensajes1, $mensajes2);

				$estatus = 0;
				$observacion = '';
				if ( count($mensajes) > 0 ) {
					// Significa que existe un error en los datos del recibo o del txt del banco.
					$estatus = 5;
					$observacion = json_encode($mensajes);
				}

				$archivo = $this->_mostarArchivo->getNombre();

				$listaPlanilla = array_values(ArrayHelper::map($registers, 'planilla', 'planilla'));
				//$listaPlanilla = array_values($listaPlanilla);
				$listaJson = json_encode($listaPlanilla);

				$model = New RegistroTxtRecibo();
				$arregloDato = $model->attributes;

				foreach ( $model->attributes as $key => $value ) {
					if ( isset($itemPago[$key]) ) {
						$model->$key = self::convertir($key, $itemPago[$key]);
					} else {
						$model->$key = '0';
					}
				}
				$model->id_contribuyente = $idContribuyente;
				$model->planillas = $listaJson;
				$model->estatus = $estatus;
				$model->fecha_hora = date('Y-m-d H:i:s');
				$model->usuario = $this->_usuario;
				$model->observacion = $observacion;
				$model->archivo_txt = $this->_mostarArchivo->getNombre();
				$model->nro_control = self::getNroControl();

				self::addItemRegistroTxt($model);
			}
		}






		/**
		 * Metodo que realiza la validacion de los datos del recibo contra la base de datos.
		 * Debe existir coincidencia entre el recibo y las planillas contenidas en el mismo.
		 * @param  PagoReciboIndividualSearch $pagoSearch instancia de la clase
		 * @param  DepositoPlanilla::find() $registers registros de las entidades "depositos-planillas"
		 * y "depositos".
		 * @return array
		 */
		private function validarReciboContraBD(PagoReciboIndividualSearch $pagoSearch, $registers)
		{
			$mensaje = [];
			foreach ( $pagoSearch->contribuyenteCorrecto($registers) as $key => $value) {
			 	$mensaje[] = $value;
			}

			foreach ( $pagoSearch->condicionPendiente($registers) as $key => $value) {
			 	$mensaje[] = $value;
			}

			// Monto del recibo coincida con la sumatoria de las planillas en la entidad "depositos-planillas".
			foreach ( $pagoSearch->montoCorrecto($registers) as $key => $value) {
			 	$mensaje[] = $value;
			}

			// Monto de las planillas que se encuentran en la entidad "depositos-planillas" debe coincidir
			// con el detalle de "pagos-detalle".
			foreach ( $pagoSearch->montoCorrectoPlanilla($registers) as $key => $value) {
			 	$mensaje[] = $value;
			}

			return $mensaje;
		}




		/**
		 * [validarReciboContraTxt description]
		 * @param  DepositoPlanilla::find() $registers registros de las entidades "depositos-planillas"
		 * y "depositos".
		 * @param array $itemPagoTxt arreglo que indica un item de pago del archivo txt.
		 * @return array
		 */
		private function validarReciboContraTxt($registers, $itemPagoTxt)
		{
			$mensaje = [];

			// Vañidacion del monto.
			$montoReciboBD = (float)$registers[0]['deposito']['monto'];
			$montoReciboTxt = self::convertirMonto($itemPagoTxt['monto_total']);

			if ( $montoReciboBD !== $montoReciboTxt ) {
				$mensaje[] = Yii::t("backend", "Monto de recibo {$montoReciboBD}. Monto en txt {$montoReciboTxt}");
			}

			// Validacion de fecha
			$fechaRecibo = $registers[0]['deposito']['fecha'];
			$fechaPago = self::convertirFecha($itemPagoTxt['fecha_pago']);

			if ( $fechaRecibo !== $fechaPago ) {
				$mensaje[] = Yii::t("backend", "Fecha de recibo {$fechaRecibo}. Fecha de pago txt {$fechaPago}");
			}


			return $mensaje;

		}




		/**
		 * Metodo que convierte una cadena de digitos en un valor formateado, segun su tipo.
		 * @param string $campo descripcion del campo.
		 * @param string $valor cadena de digitos.
		 * @return string | integer | double
		 */
		private function convertir($campo, $valor)
		{
			$dato = '';
			switch ($campo) {
				case 'monto_recibo':
				case 'monto_total':
				case 'monto_efectivo':
				case 'monto_cheque':
				case 'monto_debito':
				case 'monto_credito':
				case 'monto_transferencia':
					$dato = self::convertirMonto($valor);
					break;

				case 'fecha_pago':
				case 'fecha_cheque':
					$dato = self::convertirFecha($valor);
					break;

				case 'recibo':
				case 'nro_transaccion':
					$dato = self::convertirEntero($valor);
					break;

				default:
					$dato = trim($valor);
					break;
			}

			return $dato;
		}




		/**
		 * Metodo que convierte el renglon monto que viene en el txt en formato
		 * numerico. Este renglon viene en la forma 00000099999, lo que se busca
		 * es convertir este string en un formato numerico del tipo 999.99.
		 * @param string $montoString cadena de digitos.
		 * @return float
		 */
		private function convertirMonto($montoString)
		{
			return ( (float)$montoString / 100 );
		}



		/**
		 * Metodo que convierte un string en un formato de fecha, este formato
		 * de string viene en el txt del banco y la mismo representa una fecha.
		 * El metodo recibe el string en formato 99999999, y lo debe regresar en
		 * formato 9999-99-99. En el string los primeros dos numeros representan
		 * al dia, los siguientes dos representan al mes y los cuatros ultimos al
		 * año.
		 * @param string $fechaString cadeba de digitos que representa a la fecha
		 * en formato ddmmyyyy.
		 * @return date en formato yyyy-mm-dd
		 */
		private function convertirFecha($fechaString)
		{
			if ( trim($fechaString) == '00000000' || strlen(trim($fechaString)) < 8 ) {
				return '0000-00-00';
			} else {
				$dia = substr($fechaString, 0, 2);
				$mes = substr($fechaString, 2, 2);
				$año = substr($fechaString, 4, 4);

				$fecha = $año . '-' . $mes . '-' . $dia;
				return date('Y-m-d', strtotime($fecha));
			}

		}




		/**
		 * Metodo que convierte una cadena de digitos en un entero sin ceros adelante.
		 * @param string $enteroString cadena de digitos
		 * @return integer, sin ceros adelante.
		 */
		private function convertirEntero($enteroString)
		{
			return (int)$enteroString;
		}




		/**
		 * Metodo que agrega un elemento en el arreglo que reprsenta el modelo de la entidad
		 * @param RegistroTxtRecibo $model
		 */
		private function addItemRegistroTxt($model)
		{
			$this->_lista_registro_txt_recibo[] = $model;
		}




		/**
		 * Metodo getter de los registros del archivo txt de pago.
		 * @return array
		 */
		private function getListaRegistroPago()
		{
			return $this->_mostarArchivo->getListaPago();
		}





		/**
		 * Metodo deonde se ejecutan os procesos de guardar registro txt y el proceso
		 * de pago del recibo con todos los precesos asociados al mismo.
		 * @param RegistroTxtRecibo $itemModelRegistroTxt instancia del modelo con datos.
		 * @return boolean.
		 */
		private function procesarRegistroTxt($itemModelRegistroTxt)
		{
			self::setConexion();
			$this->_transaccion = $this->_conn->beginTransaction();
			$this->_conn->open();
			$result = true;
			// Si se quiere inactivar los registros coincidentes con el recibo y fecha de pago, activar
			// la siguiente linea.
			//$result = self::inactivarRegistro($itemModelRegistroTxt->recibo, $itemModelRegistroTxt->fecha_pago);

			if ( $result ) {
				// Guardar en la entidad "registros-txt-recibos".
				$result = self::guardarItemTxtPago($itemModelRegistroTxt);
				if ( $result == true && $itemModelRegistroTxt->estatus == 0 ) {
					$result = self::pagarRecibo($itemModelRegistroTxt);
				}
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
		 * Metodo que inserta un registro en la entidad "registros-txt-recibos".
		 * @param  RegistroTxtRecibo $itemModelRegistroTxt modelo de la entidad "registros-txt-recibos".
		 * @return boolean.
		 */
		private function guardarItemTxtPago($itemModelRegistroTxt)
		{
			$tabla = $itemModelRegistroTxt->tableName();
			$arreglo = $itemModelRegistroTxt->toArray();
			return $this->_conexion->guardarRegistro($this->_conn, $tabla, $arreglo);
		}




		/**
		 * Metodo que realiza el pago del recibo.
		 * @param array $itemModelRegistroTxt item de la entidad "registros-txt-recibos". Contiene los
		 * campos y los valores de los mismos. Modelo
		 * @return boolean.
		 */
		private function pagarRecibo($itemModelRegistroTxt)
		{
			$result = false;
			$modelDeposito = self::crearModeloDetalleDeposito($itemModelRegistroTxt);
			$recibo = $itemModelRegistroTxt->recibo;
			$observacion = "SERIAL AUTOMATICO, REGISTROS DEL ARCHIVO TXT Cuenta Recaudadora: {$itemModelRegistroTxt->nro_cuenta_recaudadora}";
			$pagoRecibo = New PagoReciboIndividual($recibo, $itemModelRegistroTxt->fecha_pago, $observacion);
			$pagoRecibo->setDepositoDetalle($modelDeposito);
			return $result = $pagoRecibo->iniciarPagoRecibo();
		}




		/***/
		private function crearModeloDetalleDeposito($itemModelRegistroTxt)
		{
			$recibo = (int)$itemModelRegistroTxt->recibo;
			if ( $itemModelRegistroTxt->monto_efectivo > 0 ) {
				$model = New DepositoDetalle();
				$model->recibo = $recibo;
				$model->id_forma = 3;
				$model->deposito = 0;
				$model->fecha = $itemModelRegistroTxt->fecha_pago;
				$model->cuenta = '';
				$model->cheque = '';
				$model->monto = $itemModelRegistroTxt->monto_efectivo;
				$model->conciliado = 0;
				$model->estatus = 0;
				$model->codigo_banco = self::getIdBancoByCodigoBanco($itemModelRegistroTxt->nro_cuenta_recaudadora);
				$model->cuenta_deposito = $itemModelRegistroTxt->nro_cuenta_recaudadora;

				$modelDeposito[] = $model;
			}

			if ( $itemModelRegistroTxt->monto_cheque > 0 ) {
				$model = New DepositoDetalle();
				$model->recibo = $recibo;
				$model->id_forma = 1;
				$model->deposito = 0;
				$model->fecha = $itemModelRegistroTxt->fecha_pago;
				$model->cuenta = $itemModelRegistroTxt->cuenta_cheque;
				$model->cheque = $itemModelRegistroTxt->nro_cheque;
				$model->monto = $itemModelRegistroTxt->monto_cheque;
				$model->conciliado = 0;
				$model->estatus = 0;
				$model->codigo_banco = self::getIdBancoByCodigoBanco($itemModelRegistroTxt->nro_cuenta_recaudadora);
				$model->cuenta_deposito = $itemModelRegistroTxt->nro_cuenta_recaudadora;

				$modelDeposito[] = $model;
			}

			if ( $itemModelRegistroTxt->monto_debito > 0 ) {
				$model = New DepositoDetalle();
				$model->recibo = $recibo;
				$model->id_forma = 4;
				$model->deposito = 0;
				$model->fecha = $itemModelRegistroTxt->fecha_pago;
				$model->cuenta = $itemModelRegistroTxt->nro_debito;
				$model->cheque = 'DEBITO';
				$model->monto = $itemModelRegistroTxt->monto_debito;
				$model->conciliado = 0;
				$model->estatus = 0;
				$model->codigo_banco = self::getIdBancoByCodigoBanco($itemModelRegistroTxt->nro_cuenta_recaudadora);
				$model->cuenta_deposito = $itemModelRegistroTxt->nro_cuenta_recaudadora;

				$modelDeposito[] = $model;
			}

			if ( $itemModelRegistroTxt->monto_credito > 0 ) {
				$model = New DepositoDetalle();
				$model->recibo = $recibo;
				$model->id_forma = 4;
				$model->deposito = 0;
				$model->fecha = $itemModelRegistroTxt->fecha_pago;
				$model->cuenta = $itemModelRegistroTxt->nro_credito;
				$model->cheque = 'CREDITO';
				$model->monto = $itemModelRegistroTxt->monto_credito;
				$model->conciliado = 0;
				$model->estatus = 0;
				$model->codigo_banco = self::getIdBancoByCodigoBanco($itemModelRegistroTxt->nro_cuenta_recaudadora);
				$model->cuenta_deposito = $itemModelRegistroTxt->nro_cuenta_recaudadora;

				$modelDeposito[] = $model;
			}

			if ( $itemModelRegistroTxt->monto_transferencia > 0 ) {
				$model = New DepositoDetalle();
				$model->recibo = $recibo;
				$model->id_forma = 3;
				$model->deposito = 0;
				$model->fecha = $itemModelRegistroTxt->fecha_pago;
				$model->cuenta = '';
				$model->cheque = '';
				$model->monto = $itemModelRegistroTxt->monto_transferencia;
				$model->conciliado = 0;
				$model->estatus = 0;
				$model->codigo_banco = self::getIdBancoByCodigoBanco($itemModelRegistroTxt->nro_cuenta_recaudadora);
				$model->cuenta_deposito = $itemModelRegistroTxt->nro_cuenta_recaudadora;

				$modelDeposito[] = $model;
			}
			return $modelDeposito;
		}







		/**
		 * Metodo para la inactivacion de un registro en la entidad "registros-txt-recibos".
		 * Se arma los campos que seran utilizado en el where condition del sql a ejecutar.
		 * @param integer $recibo numero de recibo.
		 * @param string $fechaPago fecha de pago.
		 * @return boolean
		 */
		public function inactivarRegistro($recibo, $fechaPago)
		{
			$arregloCondicion = [
				'recibo' => $recibo,
				'fecha_pago' => date('Y-m-d', strtotime($fechaPago))
			];

			return self::inactivar($arregloCondicion);
		}



		/**
		 * Metodo que ejecuta el seteo del atributo "estatus" de la entidad "registros-txt-recibos"
		 * esto implica la inactivacion del registro.
		 * @param array $arregloCondicion where condition que se tomara en consideracion para
		 * la inactivacion del registro.
		 * @return boolean
		 */
		private function inactivar($arregloCondicion)
		{
			$tabla = RegistroTxtRecibo::tableName();
			$arregloDato = ['estatus' => 9];
			return $this->_conexion->modificarRegistro($this->_conn, $tabla, $arregloDato, $arregloCondicion);
		}




		/**
		 * Metodo que permite obtener el identificador del banco a partir del codigo de la cuenta
		 * de banco. Codigo de 4 digitos
		 * @param strind $nroCuentaRecaudadora numero de la cuenta recaudadora.
		 * @return integer
		 */
		private function getIdBancoByCodigoBanco($nroCuentaRecaudadora)
		{
			$codigoBanco = substr(trim($nroCuentaRecaudadora), 0, 4);
			$bancoSearch = New BancoSearch();
			$registers = $bancoSearch->getBancoByCodigoCuenta($codigoBanco);
			return isset($registers[0]['id_banco']) ? (int)$registers[0]['id_banco'] : 0;
		}



	}

?>