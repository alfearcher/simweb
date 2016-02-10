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
 *  @file Deuda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-02-2016
 *
 *  @class Deuda
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

 	namespace common\models\deuda;

 	use Yii;
 	use yii\db\Exception;
 	use common\conexion\ConexionController;
	use yii\db\Query;
	use yii\db\Command;


	/**
	 * Clase que permite obtener informacion de las deuda de un objeto o de un contribuyente
	 */
	class Deuda
	{

		public $planilla;
		private $_impuesto;
		private $_idContribuyente;
		private $_idImpuesto;
		public $connLocal;			// Instancia de conexion.
		public $conexion;



		/**
		 * Contructor de la clase
		 * @param long $id, Identificador del contribuyente
		 */
		public function __construct($db)
		{

			$conexion = New ConexionController();
			$this->connLocal = $conexion->initConectar($db);
		}



		/**
		 * Metodo que setea la variable $id, identificador del contribuyente
		 * @param Long $id, Identificador del contribuyente.
		 */
		public function setIdContribuyente($id)
		{
			$this->_idContribuyente = $id;
		}


		/**
		 * Metodo que retorna el identificador del contribuyente.
		 * @return Long Identificador del contribuyente
		 */
		public function getIdContribuyente()
		{
			return $this->_idContribuyente;
		}


		/***/
		public function setConnection($instanceConn)
		{
			$this->connLocal = $instanceConn;
		}


		/***/
		public function getConnection()
		{
			return $this->connLocal;
		}


		public function setImpuesto($impuesto)
		{
			$this->_impuesto = $impuesto;
		}


		public function getImpuesto()
		{
			return $this->_impuesto;
		}


		public function setIdImpuesto($idObjeto)
		{
			$this->_idImpuesto = $idObjeto;
		}


		public function getIdImpuesto()
		{
			return $this->_idImpuesto;
		}



		/***/
		private function getPlanillaDelObjetoImponible()
		{
			$result = null;
			try {
				if ( $this->_impuesto == 1 ) {
					$sql = "SELECT P.planilla,
								   D.ano_impositivo,
								   D.trimestre,
								   E.unidad,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   D.referencia
						    FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN exigibilidades as E on D.exigibilidad_pago=E.exigibilidad
						    WHERE D.pago = 0 AND D.trimestre >= 0 AND D.impuesto =:impuesto
						    AND P.id_contribuyente =:id_contribuyente
						    ORDER BY D.referencia, D.ano_impositivo, D.trimestre";

					$command = $this->connLocal->createCommand($sql);
					$command->bindValues([
											':impuesto' => $this->_impuesto,
											':id_impuesto' => $this->_idImpuesto,
											':id_contribuyente' => $this->_idContribuyente
										]);
				} else {
					$sql = "SELECT P.planilla,
								   D.ano_impositivo,
								   D.trimestre,
								   E.unidad,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   D.referencia
						    FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN exigibilidades as E on D.exigibilidad_pago=E.exigibilidad
						    WHERE D.pago = 0 AND D.trimestre >= 0 AND D.impuesto =:impuesto
						    AND D.id_impuesto =:id_impuesto AND P.id_contribuyente =:id_contribuyente
						    ORDER BY D.referencia, D.ano_impositivo, D.trimestre";

					$command = $this->connLocal->createCommand($sql);
					$command->bindValues([
											':impuesto' => $this->_impuesto,
											':id_impuesto' => $this->_idImpuesto,
											':id_contribuyente' => $this->_idContribuyente
										]);
				}

				$result = $command->queryAll();

			} catch (Exception $e) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump($e->errorInfo);
			}
			return $result;
		}




		/**
		 * Metodo que returna un array de deuda por tipo de impuesto, para el caso del
		 * imuesto de Actividad Economica, el array retornado sera de planillas con sus
		 * totales.
		 * Para las deudas cuyo origen es un objeto imponibles, el array retornado sera
		 * los objetos con sus respetivos totales de deudas.
		 * @return [type] [description]
		 */
		private function getDeudaPorImpuestoEspecifico()
		{
			$result = null;
			try {
				if ( $this->_impuesto == 1 ) {				// Actividad Economica
					$sql = "SELECT P.planilla,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
							       D.impuesto
						    FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    WHERE D.pago = 0 AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY P.planilla ORDER BY P.planilla ASC";

				} elseif ( $this->_impuesto == 2 ) {			// Inmuebles Urbanos
					$sql = "SELECT I.id_impuesto,
								   I.direccion as descripcion,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   I.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN inmuebles as I on D.id_impuesto=I.id_impuesto AND P.id_contribuyente=I.id_contribuyente
						    WHERE D.pago = 0 AND D.trimestre > 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY I.id_impuesto
						    UNION
						    (SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto)";

				} elseif ( $this->_impuesto == 3 ) {			// Vehiculos
					$sql = "SELECT V.id_vehiculo as id_impuesto,
								   V.placa as descripcion,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   V.status_vehiculo as inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN vehiculos as V on D.id_impuesto=V.id_vehiculo AND P.id_contribuyente=V.id_contribuyente
						    WHERE D.pago = 0 AND D.trimestre > 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY V.id_vehiculo
						    UNION
						    (SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto)";

				} elseif ( $this->_impuesto == 4 ) {			// Propagandas
					$sql = "SELECT I.id_impuesto,
								   I.observacion as descripcion,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   I.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN propagandas as I on D.id_impuesto=I.id_impuesto AND P.id_contribuyente=I.id_contribuyente
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY I.id_impuesto
						    UNION
						    (SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto)";

				} elseif ( $this->_impuesto == 6 ) {				// Espectaculos Publicos
					$sql = "SELECT I.id_impuesto,
								   I.observacion as descripcion,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   I.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN espectaculos as I on D.id_impuesto=I.id_impuesto AND P.id_contribuyente=I.id_contribuyente
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY I.id_impuesto
						    UNION
						    (SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto)";


				} elseif ( $this->_impuesto == 7 ) {				// Apuestas Licitas
					$sql = "SELECT I.id_impuesto,
								   I.observacion as descripcion,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   I.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN apuestas as I on D.id_impuesto=I.id_impuesto AND P.id_contribuyente=I.id_contribuyente
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY I.id_impuesto
						    UNION
						    (SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto)";

				} elseif ( $this->_impuesto == 12 ) {				// Aseo
					$sql = "SELECT I.id_impuesto,
								   I.direccion as descripcion,
								   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   I.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN inmuebles as I on D.id_impuesto=I.id_impuesto
						    WHERE D.pago = 0 AND D.trimestre > 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY I.id_impuesto
						    UNION
						    (SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto)";

				} else {
					$sql = "SELECT X.id_impuesto,
						    	   X.descripcion,
						    	   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
								   P.id_contribuyente,
								   D.impuesto,
								   X.inactivo
								   FROM pagos as P
						    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
						    INNER JOIN varios as X on D.id_impuesto=X.id_impuesto AND D.impuesto=X.impuesto
						    WHERE D.pago = 0 AND D.trimestre = 0
						    AND D.impuesto =:impuesto AND P.id_contribuyente =:id_contribuyente
						    GROUP BY X.id_impuesto";
				}

				$command = $this->connLocal->createCommand($sql);
				$command->bindValues([
									  ':impuesto' => $this->_impuesto,
									  ':id_contribuyente' => $this->_idContribuyente
									 ]);

				$result = $command->queryAll();

			} catch (Exception $e) {
				echo var_dump($e->errorInfo);
			}
			return $result;
		}




		/**
		 * Metodo que retorna la deuda total de un contribuyente. Aqui se contabilizan todos los registros
		 * @return retorna un monto total de la deuda del contribuyente
		 */
		private function getDeudaGeneralContribuyente()
		{
			$result = null;
			try {
				$sql = "SELECT P.id_contribuyente,
							  (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda
					    FROM pagos as P
					    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
					    WHERE D.pago = 0 AND P.id_contribuyente =:id_contribuyente";

				$command = $this->connLocal->createCommand($sql);
				$command->bindValue(':id_contribuyente', $this->_idContribuyente);

				$result = $command->queryAll();

			} catch (Exception $e) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump($e->errorInfo);
			}
			return $result;
		}



		/**
		 * Metodo que retorna un array con la descripcion del impuesto y el monto total
		 * adeudado por el contribuyente en ese impuesto, en la contabilizacionse consideran
		 * todos los periodos, tanto los periodos mayores a cero (trimestre>0), como los
		 * periodos iguales a cero (trimestre=0).
		 * @return retorna un array con la estructrura:
		 * descripcion del impuesto => total de la deuda por el impuesto especifico.
		 */
		private function getDeudaGeneralPorImpuesto()
		{
			$result = null;
			try {
				$sql = "SELECT I.descripcion,
							   (sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as deuda,
							   I.impuesto,
							   P.id_contribuyente
					    FROM pagos as P
					    INNER JOIN pagos_detalle as D on P.id_pago=D.id_pago
					    INNER JOIN impuestos as I on D.impuesto=I.impuesto
					    WHERE D.pago = 0 AND P.id_contribuyente =:id_contribuyente GROUP BY I.descripcion";

				$command = $this->connLocal->createCommand($sql);
				$command->bindValue(':id_contribuyente', $this->_idContribuyente);

				$result = $command->queryAll();

			} catch (Exception $e) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump($e->errorInfo);
			}
			return $result;
		}




		public function getPlanillaSegunObjeto($id, $impuesto, $idImpuesto)
		{
			$this->setIdContribuyente($id);
			$this->setImpuesto($impuesto);
			$this->setIdImpuesto($idImpuesto);
			return $this->getPlanillaDelObjetoImponible();
		}


		public function getDeudaEspecificaSegunImpuesto($id, $impuesto)
		{
			$this->setIdContribuyente($id);
			$this->setImpuesto($impuesto);
			return $this->getDeudaPorImpuestoEspecifico();
		}


		public function getDeudaGeneral($id)
		{
			$this->setIdContribuyente($id);
			return $this->getDeudaGeneralContribuyente();
		}


		public function getDeudaPorImpuesto($id)
		{
			$this->setIdContribuyente($id);
			return $this->getDeudaGeneralPorImpuesto();
		}






		/***/
		public function getDeudaGeneral2()
		{
			// $query = New Query();

			// $query->select('P.planilla,
			//     		    P.id_contribuyente,
			// 			    D.*')
			// 	  ->from('pagos as P')
			// 	  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
			// 	  ->where('P.id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->idContribuyente])
			// 	  ->andWhere('D.pago =:pago', [':pago' => 0])
			// 	  ->orderBy([
			// 				'D.impuesto' => SORT_ASC,
			// 				'P.planilla' => SORT_ASC,
			// 				'D.ano_impositivo' => SORT_ASC,
			// 				'D.trimestre' => SORT_ASC
			// 			  ])
			// 	  ->all();

			// return $query;
		}




		public function getSumaDeudaTotal($impuesto)
		{
			// $query = New Query();

			// $result = $query->select('P.planilla,
			// 						  P.id_contribuyente,
			// 						  D.impuesto,
			// 						  D.trimestre,
			// 						  D.ano_impositivo,
			// 						  D.monto,
			// 						  D.recargo,
			// 						  D.interes,
			// 						  D.monto_reconocimiento')
			// 				->from('pagos as P')
			// 				->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
			// 				->where('P.id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->idContribuyente])
			// 				->andWhere('D.impuesto =:impuesto', [':impuesto' => $impuesto])
			// 				->andWhere('D.pago =:pago', [':pago' => 0])
			// 				->orderBy([
			// 							'D.impuesto' => SORT_ASC,
			// 							'P.planilla' => SORT_ASC,
			// 							'D.ano_impositivo' => SORT_ASC,
			// 							'D.trimestre' => SORT_ASC
			// 						  ])
			// 				->all();

			// //$suma = $query->sum('monto');
			// $suma = $query->count('*');


			$deuda = $this->getDeudaTotal();

			$suma = $deuda->sum('monto');
			return $suma;
		}


	}
 ?>