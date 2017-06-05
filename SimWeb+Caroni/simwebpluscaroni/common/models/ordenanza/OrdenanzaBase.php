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
 *  @file OrdenanzaBase.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-12-2015
 *
 *  @class OrdenanzaBase
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

 	namespace common\models\ordenanza;

 	use Yii;
	//use yii\base\Model;
	//use yii\db\ActiveRecord;
	use yii\db\Query;

	class OrdenanzaBase
	{


		/**
		 * [actionGetIdentificadorOrdenanza description]
		 * @param  [type] $anoOrdenanza [description]
		 * @param  [type] $impuesto     [description]
		 * @return [type]               [description]
		 */
		private static function getIdentificadorOrdenanza($anoOrdenanza, $impuesto)
		{

			$query = New Query();

			//$row = $query->select('*')->from('ordenanzas')->all();

			// Select ordenanzas_detalles.* from ordenanzas
			// INNER JOIN ordenazas_detalles on ordenanzas.id_ordenanza=ordenanzas_detalles.id_ordenanza

			$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
						 ->from('ordenanzas')
					     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
					     ->where('ordenanzas.ano_impositivo = :ano_impositivo', [':ano_impositivo' => $anoOrdenanza])
					     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
					     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
					     ->andWhere('ordenanzas_detalles.status_detalle = :status_detalle', [':status_detalle' => 0])
					     ->all();

			return $row;
		}



		/***/
		public static function getDetalleOrdenanza($idOrdenanza, $impuesto)
		{
			$query = New Query();
			$row = null;

			$row = $query->select('ordenanzas_detalles.*')
						 ->from('ordenanzas')
					     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
					     ->where('ordenanzas.id_ordenanza = :id_ordenanza', [':id_ordenanza' => $idOrdenanza])
					     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
					     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
					     ->andWhere('ordenanzas_detalles.status_detalle = :status_detalle', [':status_detalle' => 0])
					     ->one();

			return $row;
		}




		/**
		 * Metodo para determinar el identificador ( id ) de la ordenanza.
		 * @param $anoOrdenanza integer, año de creacion de la ordenanza. Formato de cuatro digitos, 9999.
		 * @param $impuesto integer, identificador del impuesto.
		 * @return retorna un array de datos, el array de datos contiene el id_ordenanza,
		 * impuesto y ano_impositivo.
		 */
		public static function getIdOrdenanza($anoOrdenanza, $impuesto)
		{
			if ( $anoOrdenanza > 0 && $impuesto > 0 ) {
				return $row = self::getIdentificadorOrdenanza($anoOrdenanza, $impuesto);
			} else {
				return false;
			}
		}





		/**
		 * Metodo que retorna el año de creacion de la ordenanza segun un año impositivo e impuesto.
		 * @param $anoImpositivo integer de cuatro digitos que identifica al año de un periodo cualquiera.
		 * @param $impuesto integer, identificador del impuesto.
		 * @return retorna un integer de cuatro digito si encuantra la ordenanza, es su defecto
		 * retornara cero (0). Este corresponde al año de creacion de la ordenanza.
		 */
		public static function getAnoOrdenanzaSegunAnoImpositivoImpuesto($anoImpositivo, $impuesto)
		{
			$result = [];
			$anoOrdenanza = 0;
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {
				// Primero se buscan las ordenanzas donde sus años sean menores o iguales
				// al año impositivo $anoImpositivo y correspondan al impuesto $impuesto.
				// Este año ( $anoImpositivo ), corresponde a cualquier periodo.

				$result = self::buscarAnoOrdenanza($anoImpositivo, $impuesto);
				if ( $result !== false ) {
					$anoOrdenanza = $result['ano_impositivo'];
				} else {
					// Si se llega aqui es porque el año impositivo es muy viejo y no existe
					// ninguna ordenanza que cubra ese periodo lo que significa que se debe
					// tomar al año ordenanza superior más próximo al año impositivo del periodo
					// a considerara.

					$result = self::buscarAnoOrdenanzaMenoresAnoImpositivo($anoImpositivo, $impuesto);
					if ( $result !== false ) {
						$anoOrdenanza = $result['ano_impositivo'];
					} else {
						$result = self::buscarAnoOrdenanzaMayoresAnoImpositivo($anoImpositivo, $impuesto);
						if ( $result !== false ) {
							$anoOrdenanza = $result['ano_impositivo'];
						}
					}
				}
			}
			return (int)$anoOrdenanza;
		}






		/**
		 * Metodo para localizar todos las ordenanzas cuyo año sean menores o iguales al año
		 * impositivo ( $anoImpositivo ) y que el impuesto sea igual a $impuesto, ordenado de
		 * forma ascendente por año impositivo.
		 * @param  [type] $anoImpositivo [description]
		 * @param  [type] $impuesto      [description]
		 * @return [type]                [description]
		 */
		private static function buscarAnoOrdenanza($anoImpositivo, $impuesto)
		{
			$query = New Query();

			// return del tipo row['parametro']
			$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
						 ->from('ordenanzas')
					     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
					     ->where('ordenanzas.ano_impositivo <= :ano_impositivo', [':ano_impositivo' => $anoImpositivo])
					     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
					     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
					     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
					     ->orderBy('ano_impositivo DESC')
					     ->one();

			return $row;
		}




		/***/
		private static function buscarAnoOrdenanzaMenoresAnoImpositivo($anoImpositivo, $impuesto)
		{
			$query = New Query();

			// return del tipo row['parametro']
			$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
						 ->from('ordenanzas')
					     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
					     ->where('ordenanzas.ano_impositivo < :ano_impositivo', [':ano_impositivo' => $anoImpositivo])
					     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
					     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
					     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
					     ->orderBy('ano_impositivo DESC')
					     ->one();

			return $row;
		}




		/***/
		private static function buscarAnoOrdenanzaMayoresAnoImpositivo($anoImpositivo, $impuesto)
		{
			$query = New Query();

			// return del tipo row['parametro']
			$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
						 ->from('ordenanzas')
					     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
					     ->where('ordenanzas.ano_impositivo > :ano_impositivo', [':ano_impositivo' => $anoImpositivo])
					     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
					     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
					     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
					     ->orderBy('ano_impositivo ASC')
					     ->one();

			return $row;
		}



		/***/
		private static function getBuscarAnoOrdenanzaDesdeAnoImpositivo($impuesto, $añoImpositivo)
		{
			$query = New Query();

			if ( $añoImpositivo > 0 ) {
				$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
							 ->from('ordenanzas')
						     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
						     ->where('ordenanzas.ano_impositivo >= :ano_impositivo', [':ano_impositivo' => $añoImpositivo])
						     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
						     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
						     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
						     ->orderBy('ano_impositivo ASC')
						     ->all();
			} else {
				$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
							 ->from('ordenanzas')
						     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
						     ->where('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
						     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
						     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
						     ->orderBy('ano_impositivo ASC')
						     ->all();

			}

			return $row;

		}




		/**
		 * Metodo que determina el año de vencimiento de una ordenanza a partir de un año impositivo
		 * cualquiera e impuesto, el metodo localiza la ordenanza correspondiente segun el año impositivo,
		 * para luego determinar el año de vencimiento de la misma. Lo que diferencia este metodo del otro
		 * es que este año impositivo ( $anoImpositivo ), es cualquier año entre los años de inicio y final
		 * de la ordenanza.
		 * @param $anoImpositivo inetger de cuatro digito, que especifica el año entre año inicial de la
		 * ordenanzay el año final de la misma, puede ser cualquiera de los dos año.
		 * @param $impuesto integer, identificador del impuesto.
		 * @return retorna integer, año de vencimiento de la ordenanza de cuatro digito si encuentra el año,
		 * sino solo retornara cero (0).
		 */
		public static function getAnoVencimientoOrdenanzaSegunAnoImpositivo($anoImpositivo, $impuesto)
		{
			$anoVenceOrdenanza = 0;
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {

				$anoOrdenanza = 0;

				$anoOrdenanza = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($anoImpositivo, $impuesto);
				if ( $anoOrdenanza > 0 ) {
					$anoVenceOrdenanza = self::anoVencimientoOrdenanza($anoOrdenanza, $impuesto);
				}
			}
			return $anoVenceOrdenanza;
		}




		/**
		 * Metodo que determina el año de vencimiento de la ordenanza, segun el año
		 * de creacion de la misma y el impuesto asociado. Se busca el inmediato
		 * superior al año ordenanza y se le resta uno al mismo.
		 * @param $anoOrdenanza integer, que indica el año de creacion de la ordenanza
		 * segun el impuesto.
		 * @param $impuesto integer, identificador del impuesto.
		 * @return return integer, el integer debe ser de cuatro digitos, 9999. Si no consigue
		 * nada se asume que la ordenanza todavia esta vigente y en su defecto retornara el año actaul.
		 */
		public static function anoVencimientoOrdenanza($anoOrdenanza, $impuesto)
		{
			$anoVencimiento = 0;
			$ordenanza = 0;
			if ( $anoOrdenanza > 0 && $impuesto > 0 ) {

				// Se debe verificar primero que la ordenanza existe.
				$ordenanza = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($anoOrdenanza, $impuesto);
				if ( $ordenanza == $anoOrdenanza ) {

					$query = New Query();

					$row = $query->select('ordenanzas.id_ordenanza, ordenanzas.ano_impositivo, ordenanzas_detalles.impuesto')
						    	 ->from('ordenanzas')
							     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
							     ->where('ordenanzas.ano_impositivo > :ano_impositivo', [':ano_impositivo' => $anoOrdenanza])
							     ->andWhere('ordenanzas_detalles.impuesto = :impuesto', [':impuesto' => $impuesto])
							     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
							     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
							     ->orderBy('ano_impositivo ASC')
							     ->one();

					if ( $row !== false ) {
						$anoVencimiento = $row['ano_impositivo'] - 1;
					} else {
						// Indica que no existen otras ordenanza vigentes despues de esta.
						// Se puede tomar como vigencia el año actual.
						$anoVencimiento = (int)date('Y');	// retorna el año actual.
					}
				}
			}
			return $anoVencimiento;
		}





		/***/
		public static function getIdOrdenanzaSegunAnoImpositivo($anoImpositivo, $impuesto)
		{
			$ordenanza = false;
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {

				$anoOrdenanza = 0;
				// Se determina primero el año de la ordenanza.
				$anoOrdenanza = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($anoImpositivo, $impuesto);
				if ( count($anoOrdenanza) > 0 ) {

					// Teniendo el año de creacion de la ordenanza ahora se identifica el id de la misma.
					// Aqui se obtiene un array con los valores del id ordenanza, ano_impositivo de creacion
					// e impuesto.
					$ordenanza = self::getIdOrdenanza($anoOrdenanza, $impuesto);
				}
			}
			// Returna una arreglo multidimimensional.
			// Ejemplo:
			/**
			 * 	array(1) {
			 *	  [0]=>
			 *	  array(3) {
			 *	    ["id_ordenanza"]=>
			 *	    string(1) "2"
			 *	    ["ano_impositivo"]=>
			 *	    string(4) "2006"
			 *	    ["impuesto"]=>
			 *	    string(1) "1"
			 *	  }
			 *	}
			 */
			return $ordenanza;
		}





		/***/
		public static function getExigibilidadLiquidacion($anoImpositivo, $impuesto)
		{
			$exigibilidadLiquidacion = false;		// retornara un array del tipo, ej: [campo, valor]
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {

				// Aqui reciben datos de la ordenanza, id, año de creacione impuesto.
				$ordenanza = self::getIdOrdenanzaSegunAnoImpositivo($anoImpositivo, $impuesto);
				if ( count($ordenanza) > 0 ) {

					$idOrdenanza = $ordenanza[0]['id_ordenanza'];

					$query = New Query();

					$row = $query->select('exigibilidades.*')
						    	 ->from('ordenanzas')
							     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
							     ->join('INNER JOIN', 'exigibilidades', 'ordenanzas_detalles.exigibilidad_liquidacion = exigibilidades.exigibilidad')
							     ->where('ordenanzas.id_ordenanza = :id_ordenanza', [':id_ordenanza' => $idOrdenanza])
							     ->andWhere('ordenanzas_detalles.impuesto = :impuesto',[':impuesto' => $impuesto])
							     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
							     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
							     ->orderBy('ano_impositivo ASC')
							     ->one();

					$exigibilidadLiquidacion = $row;
				}
			}

			/**
			 * Returna un arreglo. Ejemplo:
			 *	array(4) {
	  		 *		["exigibilidad"]=>
			 *		string(2) "12"
			 *		["lapso_declaracion"]=>
			 *		string(9) "Mensuales"
			 *		["unidad"]=>
			 *		string(3) "Mes"
			 *		["observaciones"]=>
		 	 *		string(18) "Doce pagos al año"
			 *	}
			 */
			return $exigibilidadLiquidacion;
		}





		/***/
		public static function getExigibilidadDeclaracion($anoImpositivo, $impuesto)
		{
			$exigibilidadDeclaracion = false;		// retornara un array del tipo, ej: [campo, valor]
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {

				// Aqui reciben datos de la ordenanza, id, año de creacione impuesto.
				$ordenanza = self::getIdOrdenanzaSegunAnoImpositivo($anoImpositivo, $impuesto);
				if ( $ordenanza !== false ) {

					$idOrdenanza = $ordenanza[0]['id_ordenanza'];

					$query = New Query();

					$row = $query->select('exigibilidades.*')
						    	 ->from('ordenanzas')
							     ->join('INNER JOIN', 'ordenanzas_detalles', 'ordenanzas.id_ordenanza = ordenanzas_detalles.id_ordenanza')
							     ->join('INNER JOIN', 'exigibilidades', 'ordenanzas_detalles.exigibilidad_declaracion = exigibilidades.exigibilidad')
							     ->where('ordenanzas.id_ordenanza = :id_ordenanza', [':id_ordenanza' => $idOrdenanza])
							     ->andWhere('ordenanzas_detalles.impuesto = :impuesto',[':impuesto' => $impuesto])
							     ->andWhere('ordenanzas.status_ordenanza = :status_ordenanza', [':status_ordenanza' => 0])
							     ->andWhere('ordenanzas_detalles.status_detalle=:status_detalle', [':status_detalle' => 0])
							     ->orderBy('ano_impositivo ASC')
							     ->one();

					$exigibilidadDeclaracion = $row;
				}
			}

			return $exigibilidadDeclaracion;
		}



	   /**
		* Metodo que permite obtener el periodo segun la exigibilidad de la ordenanza y la fecha
		* especifica
		* @param Integer $exigibilidad, Exigibilidad de Liquidacion o declaracion.
		* @param date $fecha, Fecha a consultar.
		* @return Integer, Retorna entero indicando el periodo para la fecha y exigibilidad.
		*/
		public static function getPeriodoSegunFecha($exigibilidad, $fecha)
		{
			$periodo = 0;
			if ( $exigibilidad > 0 && date($fecha) ) {
				$mes = date('n', strtotime($fecha));
				if ( $exigibilidad == 1 ) {
					$periodo = 1;
				} elseif ( $exigibilidad == 2 ) {
					switch ( $mes ) {
						case 1:
						case 2:
						case 3:
						case 4:
						case 5:
						case 6:
							$periodo = 1;
							break;

						case 7:
						case 8:
						case 9:
						case 10:
						case 11:
						case 12:
							$periodo = 2;
							break;

						default:
							$periodo = 0;
							break;
					}
				} elseif ( $exigibilidad == 3 ) {
					switch ( $mes ) {
						case 1:
						case 2:
						case 3:
						case 4:
							$periodo = 1;
							break;

						case 5:
						case 6:
						case 7:
						case 8:
							$periodo = 2;
							break;

						case 9:
						case 10:
						case 11:
						case 12:
							$periodo = 3;
							break;

						default:
							$periodo = 0;
							break;
					}

				} elseif ( $exigibilidad == 4 ) {
					switch ( $mes ) {
						case 1:
						case 2:
						case 3:
							$periodo = 1;
							break;

						case 4:
						case 5:
						case 6:
							$periodo = 2;
							break;

						case 7:
						case 8:
						case 9:
							$periodo = 3;
							break;

						case 10:
						case 11:
						case 12:
							$periodo = 4;
							break;

						default:
							$periodo = 0;
							break;
					}

				} elseif ( $exigibilidad == 6 ) {
					switch ( $mes ) {
						case 1:
						case 2:
							$periodo = 1;
							break;

						case 3:
						case 4:
							$periodo = 2;
							break;

						case 5:
						case 6:
							$periodo = 3;
							break;

						case 7:
						case 8:
							$periodo = 4;
							break;

						case 9:
						case 10:
							$periodo = 5;
							break;

						case 11:
						case 12:
							$periodo = 6;
							break;

						default:
							$periodo = 0;
							break;
					}

				} elseif ( $exigibilidad == 12 ) {
					$periodo = $mes;
				}
			}
			return $periodo;
		}



		/**
		 * Metodo que permite obetener el ultimo dia de un mes segun al año y mes.
		 * @param  integer $año año.
		 * @param  integer $mes mes.
		 * @return integer retorna el dia final del mes.
		 */
		public function getUltimoDia($año, $mes)
		{
			return date("d",(mktime(0,0,0,$mes+1,1,$año)-1));
		}




		/**
		 * Metodo que permite determinar el valor del paramatero añoDesde
		 * (año desde que se iniciara el calculo de la liquidacion). Utiliza
		 * la configuracion de la ordenanza del impuesto.
		 * @param $añoImpositivo, Variable que representa un año especifico.
		 * @param $impuesto, Identificador dle impuesto.
		 * @return Integer, Retorna un año de cuatro digito, sino encuentra
		 * el año retorna cero (0).
		 */
		public function determinarAnoDesde($añoImpositivo, $impuesto)
		{
			$añoDesde = 0;
			$añoInicio = 0;
			$añoActual = date('Y');

			//Yii::$app->lapso->anoLimiteNotificado();
			$a = Yii::$app->lapso->anoLimiteNotificado();	// Año actual - el 7 debe ser un valor parametrizable.
			if ( is_integer($añoImpositivo) ) {
				$añoInicio = $añoImpositivo;
				if ( $añoInicio > 0 ) {
					$añoOrdenanza = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($añoInicio, $impuesto);
					if ( $añoOrdenanza > 0 ) {
						if ( $añoInicio >= $añoOrdenanza ) {
							$añoDesde = $añoInicio;
						} elseif ( $añoInicio < $añoOrdenanza ) {
							$añoDesde = $añoOrdenanza;
						}
						if ( $añoDesde <= $a ) {
							$añoDesde = $a;
						}
					}
				}
			}
			return $añoDesde;
		}



		/***/
		public function getRangoAnoOrdenanzaSegunImpuesto($impuesto, $añoInicioActividad = 0)
		{
			$rango = [];
			if ( $añoInicioActividad > 0 ) {
				// Lo siguiente corresponde al primer año del rango.
				// Recibe un entero, 9999.
				$añoOrdenanzaInicial = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($añoInicioActividad, $impuesto);

				// Años ordenanza existentes desde año ordenanza inicial.
				// Si el año ordenanza inicial corresponde a la ordenanza
				// actual entonces el arreglo sera vacio.
				// -------------------------------------------------------
				// recibe un arreglo con:
				// El identificador de la ordenanza.
				// El Año impositivo  (año de la ordenanza).
				// El identificador del impuesto.
				$config = self::getBuscarAnoOrdenanzaDesdeAnoImpositivo($impuesto, $añoOrdenanzaInicial);

				if ( count($config) > 0 ) {
					foreach ( $config as $conf ) {
						$rango[$conf['ano_impositivo']] = self::getAnoVencimientoOrdenanzaSegunAnoImpositivo($conf['ano_impositivo'], $impuesto);
					}
				}
			} else {
				// Años ordenanza existentes desde la ordenanza inicial.
				// -------------------------------------------------------
				// recibe un arreglo con:
				// El identificador de la ordenanza.
				// El Año impositivo (año de la ordenanza).
				// El identificador del impuesto.
				$config = self::getBuscarAnoOrdenanzaDesdeAnoImpositivo($impuesto, $añoInicioActividad);

				if ( count($config) > 0 ) {
					foreach ( $config as $conf ) {
						$rango[$conf['ano_impositivo']] = self::getAnoVencimientoOrdenanzaSegunAnoImpositivo($conf['ano_impositivo'], $impuesto);
					}
				}
			}

			return $rango;
		}



		/**
		 * Metodo que determina si dos años especificos pertenecen a la misma ordenanza
		 * segun el impuesto.
		 * @param  integer $añoUno año impositivo uno de consulta.
		 * @param  integer $añoDos año impositivo dos de consulta.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return boolean retorna true si ambos años estan en la misma ordenanza, false
		 * en caso contrario.
		 */
		public function anoMismaOrdenanza($añoUno, $añoDos, $impuesto)
		{
			$result = false;
			$añoOrdenanzaUno = 0;
			$añoOrdenanzaDos = 0;

			$añoOrdenanzaUno = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($añoUno, $impuesto);

			$añoOrdenanzaDos = self::getAnoOrdenanzaSegunAnoImpositivoImpuesto($añoDos, $impuesto);

			if ( $añoOrdenanzaUno > 0 ) {
				if ( $añoOrdenanzaUno == $añoOrdenanzaDos ) {
					$result = true;
				}
			}

			return $result;
		}




		/**
		 * Metodo que determina las fechas de inicio de un lapso de tiempo, segun año
		 * impositivo, periodo y exigibilidad de liquidacion.
		 * @param  integer $añoImpositivo año del lapso.
		 * @param  integer $periodo periodo del lapso.
		 * @param  integer $exigibilidadLiquidacion cantidad de veces que se debe
		 * liquidar en un periodo.
		 * @param boolean $todas indica si se quiere todas las fechas de un año.
		 * @return string|array retorna fecha de inicio de cada periodo.
		 */
		public function getFechaInicioSegunPeriodo($añoImpositivo, $periodo, $exigibilidadLiquidacion, $todas = false)
		{
			$fechas = [];
			$fecha = '';

			if ( $exigibilidadLiquidacion ) {
				$resto = fmod(12, $exigibilidadLiquidacion);

				if ( $resto == 0 ) {		// Division exacta.
					$periodos = 12 / $exigibilidadLiquidacion;

					$m = 1;
					for ( $i = 1; $i <= $exigibilidadLiquidacion; $i++ ) {
						$mes = '0' . $m;
						$mes = substr(trim($mes), -2);
						$fechas[$i] = $añoImpositivo . '-' . $mes . '-01';

						$m = $m + $periodos;
					}
				}
			}

			if ( !$todas ) {
				return $fechas[$periodo];
			} else {
				return $fechas;
			}
		}



		/**
		 * Metodo que determina la fecha de vencimiento de un lapso. En un formato de
		 * YYYY-mm-dd.
		 * @param  integer $año año del lapso.
		 * @param  integer $periodo periodo del lapso.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return date retorna fecha o un string vacion.
		 */
		public function getFechaVencimientoLapso($año, $periodo, $impuesto)
		{
			$exigibilidadLiq = self::getExigibilidadLiquidacion($año, $impuesto);
			$fechaVcto = '';

			if ( count($exigibilidadLiq) > 0 ) {
				$e = (int)$exigibilidadLiq['exigibilidad'];

				// Se busca determinar el tiempo dentro de un lapso.
				$rango = 12/$e;
				if ( is_integer($rango) && $rango > 0 ) {
					$mes = $periodo * $rango;
					$dia = self::getUltimoDia($año, $mes);
					$fechaVcto = $año . '-' . $mes . '-' . $dia;
					$fechaVcto = date('Y-m-d', strtotime($fechaVcto));
				}
			}

			return $fechaVcto;

		}



		/**
		 * Metodo que determina la ultima fecha de una mes segun la fecha enviada.
		 * @param  date $fecha fecha consultada.
		 * @return date retorna una fecha en formato YYYY-mm-dd.
		 */
		public function getFechaVencimientoSegunFecha($fecha)
		{
			$a = date('Y', strtotime($fecha));
			$m = date('m', strtotime($fecha));
			$d = date('d', strtotime($fecha));

			$ultimo = self::getUltimoDia($a, $m);
			$fechaVcto = $a . '-' . $m .  '-' . $ultimo;
			return $fechaVcto = date('Y-m-d', strtotime($fechaVcto));
		}

	}
 ?>