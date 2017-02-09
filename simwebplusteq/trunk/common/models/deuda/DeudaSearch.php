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
 *  @file DeudaSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-10-2016
 *
 *  @class DeudaSearch
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
	use common\models\planilla\Pago;
	use common\models\planilla\PagoDetalle;
	use yii\helpers\ArrayHelper;
	use yii\data\ArrayDataProvider;
	use backend\models\propaganda\Propaganda;


	/**
	 * Clase que permite obtener informacion de las deuda de un objeto o de un contribuyente
	 */
	class DeudaSearch
	{

		private $_id_contribuyente;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->_id_contribuyente = $idContribuyente;
		}




		/**
		 * Metodo que crear el modelo general de consulta, para las deudas.
		 * @return model retorna active record
		 */
		private function getModelGeneral()
		{

			$findModel = PagoDetalle::find()->alias('D')
										    ->where('P.id_contribuyente =:id_contribuyente',
														[':id_contribuyente' => $this->_id_contribuyente])
											->andWhere('D.pago =:pago',
														[':pago' => 0]);

			return ( count($findModel) > 0 ) ? $findModel : [];
		}




		/**
		 * Metodo que retorna el resulta de la consulta de la deuda por impuestos.
		 * @return array retorna arreglo con la contabilizacion de la deuda por impuesto, o en
		 * su defecto un arreglo vacio.
		 */
		public function getDeudaGeneralPorImpuesto()
		{

			return self::deudaGeneralPorImpuesto();
		}




		/**
		 * Metodo que realiza una consulta de todas las deudas del contribuyente por cada
		 * impuesto. Se consideran los periodos mayores e iguales a cero ( p>=0 ). El resultado
		 * se representara en un arreglo con la siguiente estructura:
		 * {
		 * 		[0] => {
		 * 	   		['impuesto'] => integer (identificador del impuesto),
		 *       	['descripcion'] => descripcion el impuesto,
		 *        	['t'] => (sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)),
		 * 		}
		 *   	.
		 *    	.
		 *     	.
		 *   	[N] => {
		 * 	   		['impuestoN'] => integer (identificador del impuesto),
		 *       	['descripcion'] => descripcion del impuesto,
		 *        	['t'] => (sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)),
		 * 		}
		 * }
		 *
		 * @return array retorna arreglo con los atributos colocados en el select. Si la consulta
		 * no encuentra registros, devuelve un arreglo vacio.
		 */
		private function deudaGeneralPorImpuesto()
		{
			//ie('aqui estoy');
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t'
								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->groupBy('D.impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}




		/**
		 * Metodo que arma el resultado de la consulta de las deudas del contribuyente para un
		 * impuesto especifico. Se realizan dos clases de consulta.
		 * Una donde los periodos de las deudas son mayores a cero (0) y otra donde los periodos
		 * son iguales a cero (0). Segun el impuesto enviado. El resultado de la consultas se
		 * combinan en un solo arreglo. El arreglo que se retorna tiene la estructura:
		 * {
		 * 		[0] => {
		 * 	   		['impuesto'] => integer (identificador del impuesto),
		 *       	['descripcion'] => descripcion el impuesto,
		 *        	['t'] => (sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)),
		 *         	['tipo'] => identifica el tipo de periodo que se considero en la consulta.
		 *      }
		 * }
		 * Por cada consulta retornara un arreglo como el de arriba descripto.
		 * @param  integer $impuesto  identificador del impuesto.
		 * @return array retorna un arreglo de datos.
		 */
		public function getDeudaPorImpuestoPeriodo($impuesto)
		{

			// Deuda con periodos mayores a cero.
			$deudaMayor = self::deudaPeriodoMayorCero($impuesto);

			// Deuda con periodos iguales a cero.
			$deudaIgual = self::deudaPeriodoIgualCero($impuesto);

			$deuda = array_merge($deudaMayor, $deudaIgual);

			return $deuda;
		}




		/**
		 * Metodo que especificala consulta por impuesto y cuyos periodos seran mayores
		 * a cero.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return array retorna un arreglo con datos o vacio.
		 */
		private function deudaPeriodoMayorCero($impuesto)
		{
			return self::deudaPorImpuestoPeriodo($impuesto);
		}



		/**
		 * Metodo que especificala consulta por impuesto y cuyos periodos seran iguales
		 * a cero.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return array retorna un arreglo con datos o vacio.
		 */
		private function deudaPeriodoIgualCero($impuesto)
		{
			return self::deudaPorImpuestoPeriodo($impuesto, '=');
		}



		/**
		 * Metodo que especificala consulta por impuesto y cuyos periodos seran mayores
		 * o iguales a cero.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return array retorna un arreglo con datos o vacio.
		 */
		private function deudaPeriodoMayorIgualCero($impuesto)
		{
			return self::deudaPorImpuestoPeriodo($impuesto, '>=');
		}



		/**
		 * Metodo que realiza la consulta para determinar la deuda que posee un contribuyente
		 * en aquel impuesto cuya caracteristica es que los periodos (trimestre) sean iguales
		 * o mayores a cero (0). Utilizando como parametro adicional de consulta el identificador
		 * del impuesto.
		 * @param  integer $impuesto [description]
		 * @param  string $tipoPeriodo indica el tipo de condicionante que deben tener los periodos
		 * que se buscaran.
		 * tipo aceptados:
		 * - > (por defecto).
		 * - >=
		 * - =
		 * ----------------------------------------------------------------------------------------
		 * El arreglo que retorna este metodo tiene la siguiente estructura:
		 * {
		 * 		[0] => {
		 * 	   		['impuesto'] => integer (identificador del impuesto),
		 *       	['descripcion'] => descripcion el impuesto,
		 *        	['t'] => (sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)),
		 *         	['tipo'] => identifica el tipo de periodo que se considero en la consulta.
		 *      }
		 * }
		 * @return array|null retorna un arreglo si realiza la consulta, el arreglo puedo contener
		 * informacion o estar vacion. Sino entra en la condicion que exige el tipoPeriodo el valor
		 * retornado sera null.
		 */
		private function deudaPorImpuestoPeriodo($impuesto, $tipoPeriodo = '>')
		{
			$deuda = null;
			$arregloTipo = ['>', '>=', '='];	// > 0, >=0, =0
			if ( in_array($tipoPeriodo, $arregloTipo) ) {

				$findModel = self::getModelGeneral();

				$deuda = $findModel->select([
										'D.id_impuesto',
										'D.impuesto',
										'I.descripcion',
										'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',
										'CONCAT("periodo","' . $tipoPeriodo . '","0") as tipo',
									])
								   ->joinWith('pagos P', false, 'INNER JOIN')
								   ->joinWith('impuestos I', false, 'INNER JOIN')
								   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
								   ->andWhere('trimestre ' . $tipoPeriodo . ':trimestre',
								   					[':trimestre' => 0])
								   ->groupBy('D.impuesto')
								   ->asArray()
								   ->all();

			}

			return $deuda;

		}




		/**
		 * Metodo que permite renderizar al metodo que listara los objetos segun el impuesto,
		 * y por cada objeto mostrara la deuda que posee.
		 * @param  integer $impuesto identificador del impuesto.
		 * @return array retorna un arreglo con la informacion de los objetos y su respectiva
		 * deuda.
		 */
		public function getDeudaPorListaObjeto($impuesto)
		{
			$deuda = null;
			if ( $impuesto == 2 ) {

				// Deuda de Inmuebles Urbanos.
				$deuda = self::deudaPorListaInmueble();

			} elseif ( $impuesto == 3 ) {

				// Deuda de Vehiculos.
				$deuda = self::deudaPorListaVehiculo();

			} elseif ( $impuesto == 4 ) {

				// Deuda de Propaganda Comercial.
				$deuda = self::deudaPorListaPropaganda();

			} elseif ( $impuesto == 12 ) {

				// Deuda de Aseo.
				$deuda = self::deudaPorListaAseo();

			}

			return $deuda;
		}




		/**
		 * Metodo que realiza la consulta y busca las deudas de los inmuebles pertenecientes
		 * al contribuyente, los inmuebles deben estar activos. El arreglo contiene los atributos
		 * que se encuentran en el select. Estructura del arreglo:
		 * {
		 * 		[0] => {
		 *   		['impuesto'] => identificador del impuesto,
		 *     		['descripcion'] => descripcion del impuesto,
		 *       	['id_impuesto'] => identificador del inmueble,
		 *        	['direccion'] => direccion del inmueble,
		 *         	['t'] => monto de la deuda del inmueble.
		 * 		}
		 * }
		 * Por cada inmmueble retorna una estructura similar a la descripta arriba.
		 * @return array retorna un arreglo.
		 */
		private function deudaPorListaInmueble()
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.direccion',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('inmueble A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 2])
							   ->andWhere('A.inactivo =:inactivo',[':inactivo' => 0])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}




		/**
		 * Metodo que realiza la consulta y busca las deudas de los vehiculos pertenecientes
		 * al contribuyente, los vehiculos deben estar activos. El arreglo contiene los atributos
		 * que se encuentran en el select. Estructura del arreglo:
		 * {
		 * 		[0] => {
		 *   		['impuesto'] => identificador del impuesto,
		 *     		['descripcion'] => descripcion del impuesto,
		 *       	['id_vehiculo'] => identificador del vehiculo,
		 *        	['placa'] => placa del vehiculo,
		 *         	['t'] => monto de la deuda del vehiculo.
		 * 		}
		 * }
		 * Por cada vehiculo retorna una estructura similar a la descripta arriba.
		 * @return array retorna un arreglo.
		 */
		private function deudaPorListaVehiculo()
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'V.id_vehiculo',
									'V.placa',
									'P.id_contribuyente',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('vehiculo V', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 3])
							   ->andWhere('V.status_vehiculo =:status_vehiculo',
							   					[':status_vehiculo' => 0])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->groupBy('V.id_vehiculo')
							   ->asArray()
							   ->all();

			return $deuda;
		}





		/**
		 * Metodo que realiza la consulta y busca las deudas de laas propagandas pertenecientes
		 * al contribuyente, las propagandas deben estar activas. El arreglo contiene los atributos
		 * que se encuentran en el select. Estructura del arreglo:
		 * {
		 * 		[0] => {
		 *   		['impuesto'] => identificador del impuesto,
		 *     		['descripcion'] => descripcion del impuesto,
		 *       	['id_impuesto'] => identificador de la propaganda,
		 *        	['observacion'] => observacion colocad en la propaganda,
		 *         	['t'] => monto de la deuda de la propaganda.
		 * 		}
		 * }
		 * Por cada propaganda retorna una estructura similar a la descripta arriba.
		 * @return array retorna un arreglo.
		 */
		private function deudaPorListaPropaganda()
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.observacion',
									'A.nombre_propaganda',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('propaganda A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 4])
							   ->andWhere('A.inactivo =:inactivo',[':inactivo' => 0])
							   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}




		/**
		 * Metodo que realiza la consulta y busca las deudas de los inmuebles pertenecientes
		 * al contribuyente, los inmuebles deben estar activos. El arreglo contiene los atributos
		 * que se encuentran en el select. Estructura del arreglo:
		 * {
		 * 		[0] => {
		 *   		['impuesto'] => identificador del impuesto,
		 *     		['descripcion'] => descripcion del impuesto,
		 *       	['id_impuesto'] => identificador del inmueble,
		 *        	['direccion'] => direccion del inmueble,
		 *         	['t'] => monto de la deuda del inmueble.
		 * 		}
		 * }
		 * Por cada inmmueble retorna una estructura similar a la descripta arriba.
		 * @return array retorna un arreglo.
		 */
		private function deudaPorListaAseo()
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.direccion',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('inmueble A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 12])
							   ->andWhere('A.inactivo =:inactivo',[':inactivo' => 0])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}



		/***/
		private function deudaPorListaEspectaculo()
		{}



		/***/
		private function deudaPorListaApuesta()
		{}




		/**
		 * Metodo que renderiza al metodo que realice la consulta de la deuda del objeto
		 * segun el impuesto. El retorno es un arreglo con los datos del select. La deuda
		 * se muestra general del objeto.
		 * @param  integer $impuesto identificador del impuesto.
		 * @param  integer $idImpuesto identificador del objeto.
		 * @return array retorna un arreglo.
		 */
		public function getDeudaPorObjetoEspecifico($impuesto, $idImpuesto)
		{
			$deuda = null;
			if ( $impuesto == 2 ) {

				$deuda = self::deudaPorInmuebleEspecifico($idImpuesto);

			} elseif ( $impuesto == 3 ) {

				$deuda = self::deudaPorVehiculoEspecifico($idImpuesto);

			} elseif ( $impuesto == 4 ) {

				$deuda = self::deudaPorPropagandaEspecifica($idImpuesto);

			} elseif ( $impuesto == 6 ) {

				$deuda = self::deudaPorEspectaculoEspecifico($idImpuesto);

			} elseif ( $impuesto == 7 ) {

				$deuda = self::deudaPorApuestaEspecifica($idImpuesto);

			} elseif ( $impuesto == 12 ) {

				$deuda = self::deudaPorAseoEspecifico($idImpuesto);

			}

			return $deuda;
		}



		/**
		 * Metodo que contabiliza la deuda que posee un inmueble especifico.
		 * @param  integer $idImpuesto identificador del inmueble.
		 * @return array retorna un aareglo con los atributos que aparecen en el select.
		 */
		private function deudaPorInmuebleEspecifico($idImpuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.direccion',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('inmueble A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 2])
							   ->andWhere('D.id_impuesto =:id_impuesto',
							   						[':id_impuesto' => $idImpuesto])
							   ->andWhere('A.inactivo =:inactivo',[':inactivo' => 0])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}




		/**
		 * Metodo que contabiliza la deuda que posee un vehiculo especifico.
		 * @param  integer $idImpuesto identificador del vehiculo.
		 * @return array retorna un aareglo con los atributos que aparecen en el select.
		 */
		private function deudaPorVehiculoEspecifico($idImpuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'V.id_vehiculo',
									'V.placa',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('vehiculo V', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 3])
							   ->andWhere('D.id_impuesto =:id_impuesto',
							   						[':id_impuesto' => $idImpuesto])
							   ->andWhere('V.status_vehiculo =:status_vehiculo',
							   						[':status_vehiculo' => 0])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->groupBy('V.id_vehiculo')
							   ->asArray()
							   ->all();

			return $deuda;
		}



		/**
		 * Metodo que contabiliza la deuda que posee una propaganda especifica.
		 * @param  integer $idImpuesto identificador de la propaganda.
		 * @return array retorna un aareglo con los atributos que aparecen en el select.
		 */
		private function deudaPorPropagandaEspecifica($idImpuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.observacion',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('propaganda A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 4])
							   ->andWhere('D.id_impuesto =:id_impuesto',
							   						[':id_impuesto' => $idImpuesto])
							   ->andWhere('A.inactivo =:inactivo',[':inactivo' => 0])
							   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}





		/**
		 * Metodo que contabiliza la deuda que posee un inmueble especifico.
		 * @param  integer $idImpuesto identificador del inmueble.
		 * @return array retorna un aareglo con los atributos que aparecen en el select.
		 */
		private function deudaPorAseoEspecifico($idImpuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'D.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.direccion',
									'(sum(monto+recargo+interes)-sum(descuento+monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('inmueble A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 12])
							   ->andWhere('D.id_impuesto =:id_impuesto',
							   						[':id_impuesto' => $idImpuesto])
							   ->andWhere('A.inactivo =:inactivo',[':inactivo' => 0])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->asArray()
							   ->all();

			return $deuda;
		}






		/***/
		private function deudaPorEspectaculoEspecifico($idImpuesto)
		{}


		/***/
		private function deudaPorApuestaEspecifica($idImpuesto)
		{}





		/***/
		public function getDeudaPorListaTasa($impuesto)
		{
			return self::deudaPorListaTasa($impuesto);
		}



		/**
		 * Metodo que crea un arreglo de deudas por tipos de tasas, muestra la totalizacion
		 * por tipo de tasa dentro del mismo impuesto. Se agrupa la deuda por tipos de tasas
		 * dentro de cada impuesto.
		 * @return array retorna arreglo.
		 */
		private function deudaPorListaTasa($impuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'I.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.ano_impositivo as a',
									'A.id_codigo',
									'A.grupo_subnivel',
									'A.codigo',
									'A.descripcion as concepto',
									'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',

								])

							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('tasa A', false, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
							   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
							   ->groupBy('A.id_impuesto')
							   ->orderBy([
							   		'I.impuesto' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;
		}





		/***/
		public function getDeudaPorImpuestoTasa()
		{
			return self::deudaPorImpuestoTasa();
		}


		/**
		 * Metodo que realiza la contabilizacion de la deuda por tipo de impuesto tasa.
		 * Se agrupa la deuda por el impuesto de tasa.
		 * @return array retorna un arreglo.
		 */
		private function deudaPorImpuestoTasa()
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->select([
									'I.impuesto',
									'I.descripcion',
									'A.id_impuesto',
									'A.ano_impositivo as a',
									'A.id_codigo',
									'A.grupo_subnivel',
									'A.codigo',
									'A.descripcion as concepto',
									'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',

								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', false, 'INNER JOIN')
							   ->joinWith('tasa A', false, 'INNER JOIN')
							   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
							   ->groupBy('A.impuesto')
							   ->orderBy([
							   		'I.impuesto' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;
		}



		/***/
		public function getDetalleDeudaPorObjeto($impuesto, $idImpuesto)
		{

			return self::detalleDeudaPorObjeto($impuesto, $idImpuesto);
		}



		/***/
		private function detalleDeudaPorObjeto($impuesto, $idImpuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->joinWith('pagos P', true, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->joinWith('exigibilidad E', true, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
							   ->andWhere('D.id_impuesto =:id_impuesto',
							   						[':id_impuesto' => $idImpuesto])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->orderBy([
							   		'D.ano_impositivo' => SORT_ASC,
							   		'D.trimestre' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;
		}




		/***/
		public function getDetalleDeudadActividadEconomica()
		{
			return self::detalleDeudaActividadEconomica();
		}


		/***/
		private function detalleDeudaActividadEconomica()
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->joinWith('pagos P', true, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->joinWith('exigibilidad E', true, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => 1])
							   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
							   ->orderBy([
							   		'D.ano_impositivo' => SORT_ASC,
							   		'D.trimestre' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;
		}



		/***/
		public function getDetalleDeudaTasa($impuesto)
		{
			return self::detalleDeudaTasa($impuesto);
		}



		/***/
		private function detalleDeudaTasa($impuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->joinWith('pagos P', true, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->joinWith('exigibilidad E', true, 'INNER JOIN')
							   ->joinWith('tasa A', true, 'INNER JOIN')
							   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
							   ->orderBy([
							   		'D.impuesto' => SORT_ASC,
							   		'D.id_impuesto' => SORT_ASC,
							   		'D.ano_impositivo' => SORT_ASC,
							   		'D.trimestre' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;
		}






		/***/
		public function getDetalleDeudaPorTasaEspecifica($impuesto, $idImpuesto)
		{
			return self::detalleDeudaPorTasaEspecifica($impuesto, $idImpuesto);
		}




		/***/
		private function detalleDeudaPorTasaEspecifica($impuesto, $idImpuesto)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->joinWith('pagos P', true, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->joinWith('tasa A', true, 'INNER JOIN')
							   ->joinWith('exigibilidad E', true, 'INNER JOIN')
							   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
							   ->andWhere('D.id_impuesto =:id_impuesto',
							   						[':id_impuesto' => $idImpuesto])
							   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
							   ->asArray()
							   ->all();

			return $deuda;
		}





		/**
		 * Mtodo que retorna la deuda agrupada por planilla segun un objetoe impuesto.
		 * @param  integer $impuesto identificador del impuesto.
		 * @param  integer $idImpuesto identificador del objeto.
		 * @return array
		 */
		public function getDetalleDeudaObjetoPorPlanilla($impuesto, $idImpuesto = 0, $periodo = '>')
		{

			if ( in_array($periodo, ['>', '=']) ) {
				return self::detalleDeudaObjetoPorPlanilla($impuesto, $idImpuesto, $periodo);
			}
			return null;
		}





		/**
		 * Metodo agrupa la deuda por planilla, segun el impuesto y el identificador
		 * del objeto.
		 * @param  integer $impuesto identificador del impuesto.
		 * @param  integer $idImpuesto identificador del objeto.
		 * @return array retorna un arreglo con los atributos que aparecen en elselect.
		 */
		private function detalleDeudaObjetoPorPlanilla($impuesto, $idImpuesto, $periodo = '')
		{

			$findModel = self::getModelGeneral();
			$deuda = [];

			if ( $idImpuesto == 0 && $impuesto == 1 && $periodo == '>' ) {

				$deuda = $findModel->select([
										'P.planilla',
                    					'P.id_contribuyente',
										'P.id_pago',
										'P.ente',
										'sum(D.monto) as tmonto',
										'sum(D.recargo) as trecargo',
										'sum(D.interes) as tinteres',
										'sum(D.descuento) as tdescuento',
										'sum(D.monto_reconocimiento) as tmonto_reconocimiento',
										'D.descripcion',
                    					'D.ano_impositivo',
										'D.id_impuesto',
										'D.impuesto',
										'I.descripcion as descripcion_impuesto',
										])
								   ->joinWith('pagos P', false, 'INNER JOIN')
								   ->joinWith('impuestos I', true, 'INNER JOIN')
								   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
								   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
								   ->groupBy('P.planilla')
								   ->orderBy([
								   		'D.ano_impositivo' => SORT_ASC,
								   		'D.trimestre' => SORT_ASC,

								   	])
								   ->asArray()
								   ->all();


			} elseif ( $idImpuesto > 0 && $impuesto > 1 && $periodo = '>' ) {


				$deuda = $findModel->select([
										'P.planilla',
										'P.id_contribuyente',
										'P.id_pago',
										'P.ente',
										'sum(D.monto) as tmonto',
										'sum(D.recargo) as trecargo',
										'sum(D.interes) as tinteres',
										'sum(D.descuento) as tdescuento',
										'sum(D.monto_reconocimiento) as tmonto_reconocimiento',
										'D.descripcion',
                    					'D.ano_impositivo',
										'D.id_impuesto',
										'D.impuesto',
										'I.descripcion as descripcion_impuesto',
										])
							       ->joinWith('pagos P', false, 'INNER JOIN')
								   ->joinWith('impuestos I', true, 'INNER JOIN')
								   ->andWhere('trimestre >:trimestre',[':trimestre' => 0])
								   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
								   ->andWhere('D.id_impuesto =:id_impuesto',[':id_impuesto' => $idImpuesto])
								   ->groupBy('P.planilla')
								   ->orderBy([
								   		'D.ano_impositivo' => SORT_ASC,
								   		'D.trimestre' => SORT_ASC,

								   	])
								   ->asArray()
								   ->all();

			} elseif ( $idImpuesto == 0 && $impuesto > 0 && $periodo == '=' ) {

				$deuda = $findModel->select([
										'P.planilla',
										'P.id_contribuyente',
										'P.id_pago',
										'P.ente',
										'sum(D.monto) as tmonto',
										'sum(D.recargo) as trecargo',
										'sum(D.interes) as tinteres',
										'sum(D.descuento) as tdescuento',
										'sum(D.monto_reconocimiento) as tmonto_reconocimiento',
										'D.descripcion',
                    					'D.ano_impositivo',
										'D.impuesto',
										'I.descripcion as descripcion_impuesto',
										])
							       ->joinWith('pagos P', true, 'INNER JOIN')
								   ->joinWith('impuestos I', true, 'INNER JOIN')
								   ->andWhere('trimestre =:trimestre',[':trimestre' => 0])
								   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
								   ->groupBy('P.planilla')
								   ->orderBy([
								   		'D.ano_impositivo' => SORT_ASC,
								   		'D.trimestre' => SORT_ASC,

								   	])
								   ->asArray()
								   ->all();

			}

			return $deuda;
		}



		/**
		 * Metodo retorna el resultado de la consulta
		 * @param  integer $planilla numero de planilla generado em la liquidacion.
		 * @return array con todos los atributos de las entidades, pagos y pagos-detalle.
		 */
		public function getDeudaPorPlanilla($planilla)
		{
			return self::detalleDeudaPorPlanilla($planilla);
		}



		/**
		 * Metodo que retorna el detalle de una deuda segun el numero de planilla que
		 * la contiene. El numero de planilla es utilizado para la busqueda general.
		 * @param  integer $planilla numero de planilla generado em la liquidacion.
		 * @return array retorna una arreglo que contiene todos los atributos de las
		 * entidades pagos y pagos-detalle.
		 */
		private function detalleDeudaPorPlanilla($planilla)
		{
			$findModel = self::getModelGeneral();

			$deuda = $findModel->joinWith('pagos P', true, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->joinWith('exigibilidad E', true, 'INNER JOIN')
							   ->andWhere('P.planilla =:planilla',[':planilla' => $planilla])
							   ->orderBy([
							   		'D.ano_impositivo' => SORT_ASC,
							   		'D.trimestre' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;
		}




		/**
		 * Metodo que busca las deudas de un grupo de planillas y las agrupas segun el select
		 * de la consulta.
		 * @param  array $planillas arreglo de numeros de planillas.
		 * @return array.
		 */
		public function getAgruparDeudaPorPlanillas($planillas)
		{
			$findModel = self::getModelGeneral();
			$deuda = [];

			$deuda = $findModel->select([
									'P.planilla',
									'P.id_contribuyente',
									'P.id_pago',
									'P.ente',
									'sum(D.monto) as tmonto',
									'sum(D.recargo) as trecargo',
									'sum(D.interes) as tinteres',
									'sum(D.descuento) as tdescuento',
									'sum(D.monto_reconocimiento) as tmonto_reconocimiento',
									'D.descripcion',
									'D.impuesto',
									'I.descripcion as descripcion_impuesto',
								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->andWhere(['IN', 'planilla', $planillas])
							   ->groupBy('P.planilla')
							   ->orderBy([
							   		'D.impuesto' => SORT_ASC,
							   		'P.planilla' => SORT_ASC,
							   		//'D.trimestre' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();

			return $deuda;

		}




		/***/
		public function getAgruparDeudaPorImpuestoAnoImpositivo($año, $operador)
		{
			$findModel = self::getModelGeneral();
			$deuda = [];

			if ( in_array($operador, ['>', '=', '<', '>=', '<=']) ) {
				$deuda = $findModel->select([
									'P.id_contribuyente',
									'P.id_pago',
									'P.ente',
									'sum(D.monto) as tmonto',
									'sum(D.recargo) as trecargo',
									'sum(D.interes) as tinteres',
									'sum(D.descuento) as tdescuento',
									'sum(D.monto_reconocimiento) as tmonto_reconocimiento',
									'D.impuesto',
									'I.descripcion as descripcion_impuesto',
								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->andWhere('D.ano_impositivo ' . $operador .  ':ano_impositivo',
							   					[':ano_impositivo' => $año])
							   ->groupBy('D.impuesto')
							   ->orderBy([
							   		'D.impuesto' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();
			}
			return $deuda;
		}




		/**
		 * Metodo que seprar la deuda por impuesto segun la condicional de los
		 * añoss y las planillas. Agrupa las deudas por impuestos y que cumplan
		 * la condicion año-operador enviado mas la lista de planillas.
		 * @param  integer $año año impositivo.
		 * @param  string $operador operador.
		 * @param  array $planillas arreglo de numero de planillas;
		 * @return array.
		 */
		public function getAgruparDeudaPorImpuestoAnoImpositivoPlanilla($año, $operador, $planillas)
		{
			$findModel = self::getModelGeneral();
			$deuda = [];

			if ( in_array($operador, ['>', '=', '<', '>=', '<=']) ) {
				$deuda = $findModel->select([
									'P.id_contribuyente',
									'P.id_pago',
									'P.ente',
									'sum(D.monto) as tmonto',
									'sum(D.recargo) as trecargo',
									'sum(D.interes) as tinteres',
									'sum(D.descuento) as tdescuento',
									'sum(D.monto_reconocimiento) as tmonto_reconocimiento',
									'D.impuesto',
									'I.descripcion as descripcion_impuesto',
								])
							   ->joinWith('pagos P', false, 'INNER JOIN')
							   ->joinWith('impuestos I', true, 'INNER JOIN')
							   ->andWhere('D.ano_impositivo ' . $operador .  ':ano_impositivo',
							   					[':ano_impositivo' => $año])
							   ->andWhere(['IN', 'planilla', $planillas])
							   ->groupBy('D.impuesto')
							   ->orderBy([
							   		'D.impuesto' => SORT_ASC,

							   	])
							   ->asArray()
							   ->all();
			}
			return $deuda;
		}



		/**
		 * Metodo que permite obtener los identificadores de los impuestos que poseen deudas
		 * segun el contribuyente. Se creara un array con los identificadores de impuestos
		 * encontrados.
		 * Retorna una estructura:
		 * array {
		 * 		[impuesto] => 'Descripcion del impuesto'
		 * }
		 * @return array|null retorna un arreglo de enteros donde cada entero representa un identificador
		 * de un impuesto.
		 */
		public function getImpuestoConDeuda()
		{
			$results = self::deudaGeneralPorImpuesto();
			if ( is_array($results) ) {
				return ArrayHelper::map($results, 'impuesto', 'descripcion');
			}
			return null;

		}




		/**
		 * Metodo que genera un arreglo donde se obtiene un listado de planillas agrupadas
		 * por impuesto. Se contabiliza la deuda por planilla y se muestra por impuestos.
		 * @param integer $impuesto identificador del impuesto.
		 * @param string $tipoPeriodo descripcion del tipo de periodo a consultar.
		 * @return array retorna un arreglo
		 */
		public function getPlanillaConDeudaPorImpuesto($impuesto, $idImpuesto, $tipoPeriodo)
		{
			$findModel = self::getModelGeneral();
			$planillas = [];

			if ( in_array($tipoPeriodo, ['=', '>', '>=']) ) {
				if ( $impuesto > 0 ) {
					if ( $impuesto == 1 ) {
						$planillas = $findModel->select([
													'P.id_pago',
													'P.ente',
													'P.planilla',
													'P.id_contribuyente',
													'D.trimestre',
													'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',
													'D.impuesto',
													'D.id_impuesto',
													'I.descripcion as descripcion_impuesto',
													'D.descripcion',
												])
											   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
											   ->andWhere('trimestre ' . $tipoPeriodo . ':trimestre',[':trimestre' => 0])
											   ->joinWith('pagos P', false, 'INNER JOIN')
											   ->joinWith('impuestos I', true, 'INNER JOIN')
											   ->groupBy('P.planilla')
											   ->orderBy([
											   		'D.impuesto' => SORT_ASC,

											   	])
											   ->asArray()
											   ->all();

					} else {
						if ( trim($tipoPeriodo == '=') && ( $idImpuesto == 0 ) ) {

							$listaIdImpuesto = self::listaObjetoExcluir($impuesto);
							if ( count($listaIdImpuesto) == 0 ) {
								$listaIdImpuesto[] = 0;
							}

							$planillas = $findModel->select([
														'P.id_pago',
														'P.ente',
														'P.planilla',
														'P.id_contribuyente',
														'D.trimestre',
														'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',
														'D.impuesto',
														'D.id_impuesto',
														'I.descripcion as descripcion_impuesto',
														'D.descripcion',
													])
												   ->andWhere('D.impuesto =:impuesto',
												   				[':impuesto' => $impuesto])
												   ->andWhere('trimestre ' . $tipoPeriodo . ':trimestre',
												   				[':trimestre' => 0])
												   ->andWhere(['NOT IN', 'id_impuesto', $listaIdImpuesto])
												   ->joinWith('pagos P', false, 'INNER JOIN')
												   ->joinWith('impuestos I', true, 'INNER JOIN')
												   ->groupBy('P.planilla')
												   ->orderBy([
												   		'D.impuesto' => SORT_ASC,

												   	])
												   ->asArray()
												   ->all();

						} elseif ( trim($tipoPeriodo) == '>' && ( $idImpuesto > 0 ) ) {
							$planillas = $findModel->select([
														'P.id_pago',
														'P.ente',
														'P.planilla',
														'P.id_contribuyente',
														'D.trimestre',
														'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',
														'D.impuesto',
														'D.id_impuesto',
														'I.descripcion as descripcion_impuesto',
														'D.descripcion',
													])
												   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
												   ->andWhere('D.id_impuesto =:id_impuesto',[':id_impuesto' => $idImpuesto])
												   ->andWhere('trimestre ' . $tipoPeriodo . ':trimestre',[':trimestre' => 0])
												   ->joinWith('pagos P', false, 'INNER JOIN')
												   ->joinWith('impuestos I', true, 'INNER JOIN')
												   ->groupBy('P.planilla')
												   ->orderBy([
												   		'D.impuesto' => SORT_ASC,

												   	])
												   ->asArray()
												   ->all();

						} elseif ( trim($tipoPeriodo) == '=' && ( $idImpuesto > 0 ) ) {

							$planillas = $findModel->select([
														'P.id_pago',
														'P.ente',
														'P.planilla',
														'P.id_contribuyente',
														'D.trimestre',
														'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',
														'D.impuesto',
														'D.id_impuesto',
														'I.descripcion as descripcion_impuesto',
														'D.descripcion',
													])
												   ->andWhere('D.impuesto =:impuesto',[':impuesto' => $impuesto])
												   ->andWhere('D.id_impuesto =:id_impuesto',[':id_impuesto' => $idImpuesto])
												   ->andWhere('trimestre ' . $tipoPeriodo . ':trimestre',[':trimestre' => 0])
												   ->joinWith('pagos P', false, 'INNER JOIN')
												   ->joinWith('impuestos I', true, 'INNER JOIN')
												   ->groupBy('P.planilla')
												   ->orderBy([
												   		'D.impuesto' => SORT_ASC,

												   	])
												   ->asArray()
												   ->all();

						}
					}
				} elseif ( $impuesto == 0 ) {

					$planillas = $findModel->select([
												'P.id_pago',
												'P.ente',
												'P.planilla',
												'P.id_contribuyente',
												'D.trimestre',
												'(sum(D.monto+D.recargo+D.interes)-sum(D.descuento+D.monto_reconocimiento)) as t',
												'D.impuesto',
												'D.id_impuesto',
												'I.descripcion as descripcion_impuesto',
												'D.descripcion',
											])
										   ->andWhere('trimestre ' . $tipoPeriodo . ':trimestre',[':trimestre' => 0])
										   ->joinWith('pagos P', false, 'INNER JOIN')
										   ->joinWith('impuestos I', true, 'INNER JOIN')
										   ->groupBy('P.planilla')
										   ->orderBy([
										   		'D.impuesto' => SORT_ASC,

										   	])
										   ->asArray()
										   ->all();

				}
			}

			return $planillas;
		}



		/**
		 * [getProviderDeudaPlanillaPorImpuesto description]
		 * @param  [type]  $impuesto    [description]
		 * @param  [type]  $idImpuesto  [description]
		 * @param  [type]  $tipoPeriodo [description]
		 * @param  boolean $objeto indicador para propaganda, apuesta, espectaculo
		 * @return [type]               [description]
		 */
		public function getProviderDeudaPlanillaPorImpuesto($impuesto, $idImpuesto, $tipoPeriodo, $objeto = false)
		{
			$provider = null;
			$planillas = self::getPlanillaConDeudaPorImpuesto($impuesto, $idImpuesto, $tipoPeriodo);

			if ( count($planillas) > 0 ) {
				foreach ( $planillas as $planilla ) {
					$data[$planilla['planilla']] = [
						'planilla' => $planilla['planilla'],
						'total' => $planilla['t'],
						'periodo' => ( $planilla['trimestre'] > 0 ) ? 1 : 0,
						'impuesto' => $planilla['impuesto'],
						'id_impuesto' => $planilla['id_impuesto'],
						'impuestoDescripcion' => $planilla['descripcion_impuesto'],
						'observacion' => $planilla['descripcion'],
						'objeto' => (boolean) $objeto,
					];
				}
				$provider = New ArrayDataProvider([
								'key' => 'planilla',
								'allModels' => $data,
								'pagination' => false,
						]);
			}

			return $provider;
		}




		/***/
		private function listaObjetoExcluir($impuesto)
		{
			$listaIdImpuesto = [];
			if ( $impuesto == 4 ) {
				$findModel = Propaganda::find()->where('id_contribuyente =:id_contribuyente',
														['id_contribuyente' => $this->_id_contribuyente])
											   ->andWhere('inactivo =:inactivo',
											   			[':inactivo' => 0])
											   ->asArray()
											   ->all();
				if ( count($findModel) > 0 ) {
					foreach ( $findModel as $reg ) {
						$listaIdImpuesto[] = $reg['id_impuesto'];
					}
					//$listaIdImpuesto = array_column($findModel, 'id_impuesto');
				}

			}

			return $listaIdImpuesto;
		}




		/**
		 * Metodo que permite localizar periodo liqquidados sin pagar.
		 * Definiendo un lapso de tiempo en años. Esto solo aplica para
		 * aquellas deudas que se generan con periodos mayores a cero.
		 * @param integer $impuesto identificador del impuesto.
		 * @param  integer $idImpuesto identificador del objeto (si es nencesario)
		 * @return PagoDetalle.
		 */
		public function getPeriodoPendiente($impuesto, $idImpuesto = 0)
		{
			$añoLimite = Yii::$app->lapso->anoLimiteNotificado();
			$añoActual = (int)date('Y');

			$findModel = self::getModelGeneral();
			$model = $findModel->joinWith('impuestos I', true, 'INNER JOIN')
			                   ->andWhere('D.impuesto =:impuesto',
												[':impuesto' => $impuesto])
							   ->andWhere(['IN', 'ano_impositivo', [$añoLimite, $añoActual - 1]])
							   ->andWhere('D.trimestre >:trimestre',
							   					[':trimestre' => 0]);

			if ( $idImpuesto > 0 ) {
				$model = $model->andWhere('D.id_impuesto =:id_impuesto',
												[':id_impuesto' => $idImpuesto]);
			}

			return $model->asArray()->all();

		}



	}



 ?>
