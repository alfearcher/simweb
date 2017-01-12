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
		private $_concepto = -1;
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


		public function setConcepto($concepto)
		{
			$this->_concepto = $concepto;
		}


		public function getConcepto()
		{
			return $this->_concepto;
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
		private function getDeudaPorImpuestoEspecifico2()
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
		private function getDeudaGeneralContribuyente2()
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
		private function getDeudaGeneralPorImpuesto2()
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



///////////////////////////////////////////////////////

		/**funciona*/
		private function getDeudaGeneralPorImpuesto()
		{
			$result = null;
			try {
				$query = New Query();

				$query->select('I.descripcion,P.id_contribuyente,SUM(D.monto) as tmonto,
					           SUM(D.recargo) as trecargo,SUM(D.interes) as tinteres,
					           SUM(D.descuento) as tdescuento,SUM(D.monto_reconocimiento) as tmonto_reconocimiento')
					  ->from('pagos as P')
					  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
					  ->join('INNER JOIN', 'impuestos as I', 'D.impuesto=I.impuesto')
					  ->where('P.id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->_idContribuyente])
					  ->andWhere('D.pago =:pago',[':pago' => 0])
					  ->groupBy('D.impuesto')
					  ->orderBy([
					  		'D.impuesto' => SORT_ASC,
					  	]);

				$result = $query->all();

			} catch (Exception $e) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump($e->errorInfo);

			}

			return $result;
		}


		/**funciona*/
		private function getDeudaPorImpuestoEspecifico()
		{
			$result = null;
			try {
				$query1 = New Query();

				$query1->select('CONCAT("Objetos Imponibles") as concepto,' . 'D.impuesto,
						   	    P.id_contribuyente,
							    SUM(D.monto) as tmonto,
					            SUM(D.recargo) as trecargo,
					            SUM(D.interes) as tinteres,
					            SUM(D.descuento) as tdescuento,
					            SUM(D.monto_reconocimiento) as tmonto_reconocimiento')
					   ->from('pagos as P')
					   ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
					   ->join('INNER JOIN', 'impuestos as I', 'D.impuesto=I.impuesto')
					   ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
					   ->andWhere('D.pago =:pago',[':pago' => 0])
					   ->andWhere('D.trimestre >:trimestre',[':trimestre' => 0])
					   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);

				$query2 = New Query();

				$query2->select('CONCAT("Otros Conceptos") as concepto,' . 'D.impuesto,
						  	    P.id_contribuyente,
							    SUM(D.monto) as tmonto,
					            SUM(D.recargo) as trecargo,
					            SUM(D.interes) as tinteres,
					            SUM(D.descuento) as tdescuento,
					            SUM(D.monto_reconocimiento) as tmonto_reconocimiento')
					   ->from('pagos as P')
					   ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
					   ->join('INNER JOIN', 'impuestos as I', 'D.impuesto=I.impuesto')
					   ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
					   ->andWhere('D.pago =:pago',[':pago' => 0])
					   ->andWhere('D.trimestre =:trimestre',[':trimestre' => 0])
					   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);

				$result = $query1->union($query2)
								 ->groupBy('P.id_contribuyente')
								 ->orderBy([
					  		 			'P.id_contribuyente' => SORT_ASC,
					  				])
								 ->all();

			} catch (Exception $e) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump(var_dump($e->errorInfo));

			}

			return $result;
		}



		/***/
		private function getDeudaDetallePorImpuestoEspecifico()
		{
			$result = null;
			try {

				if ( $this->_concepto == 1 ) {
					if ( $this->_impuesto == 1 ) {
						$result = $this->getDeudaDetallePorActividadEconomica();
					} elseif ( $this->_impuesto == 2 ) {
						$result = $this->getDeudaDetallePorInmueblesUrbanos();
					} elseif ( $this->_impuesto == 3 ) {

					} elseif ( $this->_impuesto == 4 ) {

					} elseif ( $this->_impuesto == 5 ) {

					} elseif ( $this->_impuesto == 6 ) {

					} elseif ( $this->_impuesto == 7 ) {

					} elseif ( $this->_impuesto == 12 ) {
						$result = $this->getDeudaDetallePorAseoUrbano();
					} else {

					}
				} elseif ( $this->_concepto == 0 ) {
					if ( $this->_impuesto == 1 ) {

					} elseif ( $this->_impuesto == 2 ) {

					} elseif ( $this->_impuesto == 3 ) {

					} elseif ( $this->_impuesto == 4 ) {

					} elseif ( $this->_impuesto == 5 ) {

					} elseif ( $this->_impuesto == 6 ) {

					} elseif ( $this->_impuesto == 7 ) {

					} elseif ( $this->_impuesto == 12 ) {

					} else {

					}
				}

			} catch ( Exception $e ) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump(var_dump($e->errorInfo));

			}

			return $result;
		}




		/***/
		private function getDeudaDetallePorActividadEconomica()
		{
			$result = null;
			try {
				$query = new Query();
				if ( $this->_concepto == 1 ) {
					$query->select('P.planilla,
						            D.ano_impositivo,
								    D.trimestre,
								    E.unidad,
								    if(D.referencia=1,CONCAT("DEFINITIVA"),CONCAT("ESTIMADA")) as tipo,
								    SUM(D.monto) as tmonto,
							        SUM(D.recargo) as trecargo,
							        SUM(D.interes) as tinteres,
							        SUM(D.descuento) as tdescuento,
							        SUM(D.monto_reconocimiento) as tmonto_reconocimiento,
							        D.impuesto,
							        I.descripcion,
						            P.id_contribuyente')
						  ->from('pagos as P')
					      ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
						  ->join('INNER JOIN', 'impuestos as I', 'D.impuesto=I.impuesto')
						  ->join('INNER JOIN', 'exigibilidades as E', 'D.exigibilidad_pago=E.exigibilidad')
						  ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
						  ->andWhere('D.pago =:pago',[':pago' => 0])
					      ->andWhere('D.trimestre >:trimestre',[':trimestre' => 0])
					      ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);

				} elseif ( $this->_concepto == 0 ) {
					$query->select('P.planilla,
						            D.ano_impositivo,
								    D.trimestre,
								    E.unidad,
								    if(D.referencia=1,CONCAT("DEFINITIVA"),CONCAT("ESTIMADA")) as tipo,
								    SUM(D.monto) as tmonto,
							        SUM(D.recargo) as trecargo,
							        SUM(D.interes) as tinteres,
							        SUM(D.descuento) as tdescuento,
							        SUM(D.monto_reconocimiento) as tmonto_reconocimiento,
							        D.impuesto,
							        I.descripcion,
						            P.id_contribuyente')
						  ->from('pagos as P')
					      ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
						  ->join('INNER JOIN', 'impuestos as I', 'D.impuesto=I.impuesto')
						  ->join('INNER JOIN', 'exigibilidades as E', 'D.exigibilidad_pago=E.exigibilidad')
						  ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
						  ->andWhere('D.pago =:pago',[':pago' => 0])
					      ->andWhere('D.trimestre =:trimestre',[':trimestre' => 0])
					      ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);

				}
			} catch ( Exception $e ) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump(var_dump($e->errorInfo));
			}

			$result = $query->groupBy('P.planilla')
							->orderBy([
				  		 			'P.planilla' => SORT_ASC,
				  		 			'D.referencia' => SORT_ASC,
				  		 			'D.ano_impositivo' => SORT_ASC,
				  		 			'D.trimestre' => SORT_ASC
				  				])
							->all();

			return $result;
		}




		/**funciona*/
		private function getDeudaDetallePorInmueblesUrbanos()
		{
			$resul = null;
			try {
				$query = new Query();
				if ( $this->_concepto == 1 ) {
					$query->select('U.id_impuesto,
									U.direccion,
									(SUM(D.monto+D.recargo+D.interes)-SUM(D.descuento+D.monto_reconocimiento)) as total,
									P.id_contribuyente,D.impuesto')
						  ->from('pagos as P')
						  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
						  ->join('INNER JOIN', 'inmuebles as U', 'D.id_impuesto=U.id_impuesto')
						  ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
						  ->andWhere('D.pago =:pago',[':pago' => 0])
						  ->andWhere('D.trimestre >:trimestre',[':trimestre' => 0])
						  ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);

				} elseif ( $this->_concepto == 0 ) {
					$query->select('U.id_impuesto,
									U.direccion,
									(SUM(D.monto+D.recargo+D.interes)-SUM(D.descuento+D.monto_reconocimiento)) as total,
									P.id_contribuyente,D.impuesto')
						  ->from('pagos as P')
						  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
						  ->join('INNER JOIN', 'inmuebles as U', 'D.id_impuesto=U.id_impuesto')
						  ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
						  ->andWhere('D.pago =:pago',[':pago' => 0])
						  ->andWhere('D.trimestre =:trimestre',[':trimestre' => 0])
						  ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);
				}
			} catch ( Exception $e ) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump(var_dump($e->errorInfo));
			}

			$result = $query->groupBy('D.id_impuesto')
							->orderBy([
				  		 			'D.id_impuesto' => SORT_ASC
				  				])
							->all();

			return $result;
		}



		/***/
		private function getDeudaDetallePorAseoUrbano()
		{
			$resul = null;
			try {
				$query = new Query();
				if ( $this->_concepto == 1 ) {
					$query->select('U.id_impuesto,
									U.direccion,
									(SUM(D.monto+D.recargo+D.interes)-SUM(D.descuento+D.monto_reconocimiento)) as total,
									P.id_contribuyente,D.impuesto')
						  ->from('pagos as P')
						  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
						  ->join('INNER JOIN', 'inmuebles as U', 'D.id_impuesto=U.id_impuesto')
						  ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
						  ->andWhere('D.pago =:pago',[':pago' => 0])
						  ->andWhere('D.trimestre >:trimestre',[':trimestre' => 0])
						  ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);

				} elseif ( $this->_concepto == 0 ) {
					$query->select('U.id_impuesto,
									U.direccion,
									(SUM(D.monto+D.recargo+D.interes)-SUM(D.descuento+D.monto_reconocimiento)) as total,
									P.id_contribuyente,D.impuesto')
						  ->from('pagos as P')
						  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago=D.id_pago')
						  ->join('INNER JOIN', 'inmuebles as U', 'D.id_impuesto=U.id_impuesto')
						  ->where('P.id_contribuyente =:id_contribuyente',[':id_contribuyente' => $this->_idContribuyente])
						  ->andWhere('D.pago =:pago',[':pago' => 0])
						  ->andWhere('D.trimestre =:trimestre',[':trimestre' => 0])
						  ->andWhere('D.impuesto =:impuesto',[':impuesto' => $this->_impuesto]);
				}
			} catch ( Exception $e ) {
				//$e->errorInfo, muestra un array indicando el error ocurrido
				//$e->errorInfo[2] muestra la descripcion del mensaje
				echo var_dump(var_dump($e->errorInfo));
			}

			$result = $query->groupBy('D.id_impuesto')
							->orderBy([
				  		 			'D.id_impuesto' => SORT_ASC
				  				])
							->all();

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

		public function getDeudaDetalleSegunImpuesto($id, $impuesto, $concepto)
		{
			$this->setIdContribuyente($id);
			$this->setImpuesto($impuesto);
			$this->setConcepto($concepto);
			return $this->getDeudaDetallePorImpuestoEspecifico();
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
			$query = New Query();

			$query->select('P.planilla,
			    		    P.id_contribuyente,
						    (sum(D.monto+D.recargo)-sum(D.descuento+D.monto_reconocimiento)) as t')
				  ->from('pagos as P')
				  ->join('INNER JOIN', 'pagos_detalle as D', 'P.id_pago = D.id_pago')
				  ->where('P.id_contribuyente =:id_contribuyente', [':id_contribuyente' => $this->_idContribuyente])
				  ->andWhere('D.pago =:pago', [':pago' => 0])
				  ->groupBy('P.planilla')
				  ->orderBy([
							'D.impuesto' => SORT_ASC,
							'P.planilla' => SORT_ASC,
							'D.ano_impositivo' => SORT_ASC,
							'D.trimestre' => SORT_ASC
						  ]);

			return $query->all();
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