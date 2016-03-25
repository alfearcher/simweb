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




		/**
		 * Metodo para determinar el identificador ( id ) de la ordenanza.
		 * @param $anoOrdenanza integer, año de creacion de la ordenanza. Formato de cuatro digitos, 9999.
		 * @param $impuesto integer, identificador del impuesto.
		 * @return retorna un array de datos, el array de datos contiene el id_ordenanza,
		 * impuesto y ano_impositivo.
		 */
		public static function getetIdOrdenanza($anoOrdenanza, $impuesto)
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
				if ( $result != false ) {
					$anoOrdenanza = $result['ano_impositivo'];
				} else {
					// Si se llega aqui es porque el año impositivo es muy viejo y no existe
					// ninguna ordenanza que cubra ese periodo lo que significa que se debe
					// tomar al año ordenanza superior más próximo al año impositivo del periodo
					// a considerara.

					$result = self::buscarAnoOrdenanzaMayoresAnoImpositivo($anoImpositivo, $impuesto);
					if ( $result != false ) {
						$anoOrdenanza = $result['ano_impositivo'];
					}
				}
			}
			return $anoOrdenanza;
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

					if ( $row != false ) {
						$anoVencimiento = $row['ano_impositivo'] - 1;
					} else {
						// Indica que no existen otras ordenanza vigentes despues de esta.
						// Se puede tomar como vigencia el año actual.
						$anoVencimiento = date('Y');	// retorna el año actual.
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
				if ( $anoOrdenanza > 0 ) {

					// Teniendo el año de creacion de la ordenanza ahora se identifica el id de la misma.
					// Aqui se obtiene un array con los valores del id ordenanza, ano_impositivo de creacion
					// e impuesto.
					$ordenanza = self::getIdOrdenanza($anoOrdenanza, $impuesto);
				}
			}
			return $ordenanza;
		}





		/***/
		public static function getExigibilidadLiquidacion($anoImpositivo, $impuesto)
		{
			$exigibilidadLiquidacion = false;		// retornara un array del tipo, ej: [campo, valor]
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {

				// Aqui reciben datos de la ordenanza, id, año de creacione impuesto.
				$ordenanza = self::getIdOrdenanzaSegunAnoImpositivo($anoImpositivo, $impuesto);
				if ( $ordenanza != false ) {

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

			return $exigibilidadLiquidacion;
		}





		/***/
		public static function getExigibilidadDeclaracion($anoImpositivo, $impuesto)
		{
			$exigibilidadDeclaracion = false;		// retornara un array del tipo, ej: [campo, valor]
			if ( $anoImpositivo > 0 && $impuesto > 0 ) {

				// Aqui reciben datos de la ordenanza, id, año de creacione impuesto.
				$ordenanza = self::getIdOrdenanzaSegunAnoImpositivo($anoImpositivo, $impuesto);
				if ( $ordenanza != false ) {

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



	}
 ?>