<?php
/**
 *	@copyright 2016 © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
  *
  *	> This library is free software; you can redistribute it and/or modify it under
  *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free
  *	> Software Foundation; either version 2 of the Licence, or (at your opinion)
  *	> any later version.
  *  >
  *	> This library is distributed in the hope that it will be usefull,
  *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
  *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence
  *	> for more details.
  *  >
  *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
  *
  */

 /**
  *	@file OperacionBase.php
  *
  *	@author Jose Rafael Perez Teran
  *
  *	@date 01-09-2015
  *
  *	@class OperacionBase
  *	@brief Clase modelo OperacionBase, que ejecutar algunas rutinas que
  *	@brief son repetitivas y basicas dentro del proyecto SIMWEBPLUS.
  *
  *
  *	@property
  *
  *
  *	@method
  *
  *
  *	@inherits
  *
  */
 	namespace backend\models\operacionbase;

	use yii\base\Model;
 	use yii\db\ActiveRecord;
 	use yii\db\Exception;
 	use common\conexion\ConexionController;
 	use common\models\planilla\PagoDetalle;

 	/**
 	*
 	*/
 	class OperacionBase extends ActiveRecord
 	{

 		public $msgError = '';


 		/*public function _construct()
 		{

 		}*/

		/**
		*	Metodo que retorna un array de planilla(s) que posea el objeto imponible.
		*	Las planillas seran aquellas que esten pendientes por pagar, (pago = 0),
		* 	y que sean del objeto imponible respectivo.
		*   @param $connLocal, instancia de tipo Connection.
		* 	Esto es algo como:
		* 	$conexion = New ConexionController();
		* 	$this->connLocal = $conexion->initConectar('db');
		* 	@param $impuesto, integer que identifica el impuesto al cual pertenece el objeto.
		* 	@param $idImpuesto, long que identifica al objeto imponible.
		* 	@return array de planillas que posea el objeto imponible.
		*/
		public function getListaPlanillaSegunObjeto($connLocal, $impuesto, $idImpuesto, $pago = 0)
		{
			if ( $impuesto > 0 and $idImpuesto > 0 ) {
				$sql = "SELECT distinct P.planilla from pagos as P
				        inner join pagos_detalle as D on P.id_pago=D.id_pago
				        WHERE D.pago=0 and D.trimestre=0 and D.impuesto={$impuesto} and D.id_impuesto={$idImpuesto}
				        order by P.planilla";

				$command = $connLocal->createCommand($sql);
				if ( !$command ) {
					//$this->addErrors('No se encontrarón planilla(s) para el objeto ' . $idImpuesto);
					return false;
				 }
				 return $command->queryAll();
			 } else {
			 	$this->addErrors('El identificador del impuesto y del objeto no estan definos.');
			 	return false;
			 }

		}




		/**
		*	Metodo que permite anular una planilla y agregarle una observacion a la misma.
		* 	Las planillas solo se pueden anular si cumplen con lo siguiente:
		* 	1. No puede estar asociada a un recibo pendiente o pagada.
		* 	2. No puede estar asociada a un convenio de pagos pendiente o pagado.
		* 	3. No puede estar asociada a una retencion declarada o compensada.
		* 	4. No puede contener en sus detalles montos por reconocimiento.
		* 	5.
		* 	La anulación se hará efectiva cuando se aplique el commit respectivo que el usuario
		* 	reciba como respuesta un true a su peticion de anulacion.
		* 	@param $connLocal, instancia de tipo Connection.
		* 	@param $planillas, array de planillas que seran anuladas, solo si estan pendientes. ( pago=0 ).
		* 	@param $observacion, string que sera agregado en el campo descripcion de la planilla.
		* 	@return true o false, si retorna true es porque anulo la o las planilla(s) enviadas,
		* 	lo cual indica que el proceso se completo sin problemas.
		*/
		public function anularPlanilla($connLocal, $planillas = [], $observacion = '')
		{
			if ( is_array($planillas) ) {

				$sql = "UPDATE pagos as P inner join pagos_detalle as D on P.id_pago=D.id_pago
				        SET P.status_pago = 9, D.pago = 9, D.descripcion=CONCAT(D.descripcion, ' / ','{$observacion}')
				        WHERE D.pago = 0 and ";

				foreach ( $planillas as $planilla ) {
					if ( is_numeric($planilla) ) {
						if ( $planilla > 0 ) {
							if ( $this->puedoAnularLaPlanilla($connLocal, $planilla) ) {
								$sqlUpdate = $sql . "P.planilla = {$planilla}";
								$command = $connLocal->createCommand($sqlUpdate)->execute();
								if ( !$command ) {
									return false;
								}
							} else {
								return false;
							}
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
				return true;
			} else {
				return false;
			}
		}





		/**
		*	Metodo que determina si una planilla especifica se puede anular, segun las politicas del negocio.
		* 	Se considera valida para la anulacion si la planilla no esta asociada a un proceso administrativo
		* 	anterior, o si no contempla montos por reconocimientos. Los monto por reconocimiento depende de un
		* 	proceso que permite suprimirmos en caso de que la planilla los contengan.
		* 	En caso de que la planilla no se pueda anular por estar relacionada alguno de los procesos descriptos
		* 	abajo, se debe inactivar dicho proceso o en su defecto suprimir la planilla de dicho proceso.
		* 	@param $connLocal, instancia de tipo Connection.
		*   @param $planilla, long que identifica el numero de planilla que debe ser anulada.
		* 	@return true o false, si retorna true significa que la planilla se puede anular.
		*/
		public function puedoAnularLaPlanilla($connLocal, $planilla = 0)
		{
			if ( $planilla > 0 ) {

				// Se verifica que la planilla no este asociada a un recibo pendiento o pagado.
				if ( $this->planillaAsociadaRecibo($connLocal, $planilla) ) {
					$this->addErrors('La Planilla Nro. ' . $planilla . ', esta asociada a un recibo.');
					return false;
				}

				// Se verifica que la planilla no este asociada a un convenio de pago.
				if ( $this->planillaAsociadaConvenio($connLocal, $planilla) ) {
					$this->addErrors('La Planilla Nro. ' . $planilla . ', esta asociada a un convenio.');
					return false;
				}

				// Se verifica que la planilla no este asociada a una retencion.
				if ( $this->planillaAsociadaRetencion($connLocal, $planilla) ) {
					$this->addErrors('La Planilla Nro. ' . $planilla . ', esta asociada a una retencion.');
					return false;
				}

				// Se verifica que la planilla no contenga monto por reconocimiento.
				if ( $this->planillaConReconocimiento($connLocal, $planilla) ) {
					$this->addErrors('La Planilla Nro. ' . $planilla . ' posee un reconocimiento.');
					return false;
				}

				return true;

			} else {
				$this->addErrors('La Planilla con valor menor a cero');
				return false;
			}
		}





		/**
		*	Metodo que determina si una planilla esta relacionada a un recibo pendiente o pagada.
		* 	@param $connLocal, instancia de tipo Connection.
		* 	@param $planilla, long que identifica el numero de planilla al cual se debe verificar
		* 	su relacion a otros proceso.
		*	@return true o false, si retorna true indica que la planilla esta asociada al proceso.
		*/
		public function planillaAsociadaRecibo($connLocal, $planilla = 0)
		{
			if ( $planilla > 0 ) {
				try {
					$whereCondicion = 'estatus != 9';
					$tableName = 'depositos_planillas';
					if ( $this->planillaExisteTabla($connLocal, $tableName, $planilla, $whereCondicion) ) { return true; }

					$tableName = 'planillas_aporte';
					if ( $this->planillaExisteTabla($connLocal, $tableName, $planilla, $whereCondicion) ) { return true; }

					$whereCondicion = '';
					$tableName = 'planillas_contables';
					if ( $this->planillaExisteTabla($connLocal, $tableName, $planilla, $whereCondicion) ) { return true; }
					return false;

				} catch (PDOExcepcion $e) {
					return true;
				}
			} else {
				return true;
			}
		}



		/**
		*	Metodo que determina si una planilla esta relacionada a un convenio pendiente o pagada.
		* 	@param $connLocal, instancia de tipo Connection.
		* 	@param $planilla, long que identifica el numero de planilla al cual se debe verificar
		* 	su relacion a otros proceso.
		*	@return true o false, si retorna true indica que la planilla esta asociada al proceso.
		*/
		public function planillaAsociadaConvenio($connLocal, $planilla = 0)
		{
			if ( $planilla > 0 ) {
				try {
					$whereAdicional = 'status_cuota != 9';
					$tableName = 'convenios_detalles';

					return $this->planillaExisteTabla($connLocal, $tableName, $planilla, $whereAdicional);

				} catch ( PDOExcepcion $e ) {
					return false;
				}
			} else {
				return false;
			}
		}






		/**
		*	Metodo que determina si una planilla esta relacionada a una retencion pendiente o pagada.
		* 	@param $connLocal, instancia de tipo Connection.
		* 	@param $planilla, long que identifica el numero de planilla al cual se debe verificar
		* 	su relacion a otros proceso.
		*	@return true o false, si retorna true indica que la planilla esta asociada al proceso.
		*/
		public function planillaAsociadaRetencion($connLocal, $planilla = 0)
		{
			if ( $planilla > 0 ) {
				try {
					$whereAdicional = 'estatus_reconocimiento != 9';
					$tableName = 'reconocimientos';

					return $this->planillaExisteTabla($connLocal, $tableName, $planilla, $whereAdicional);

				} catch (PDOExcepcion $e ) {
					return false;
				}
			} else {
				return false;
			}
		}





		/**
		*	Metodo que permite determinar si una planilla esta en una tabla indicada por el parametro $tableName.
		* 	@param $connLocal, instancia de tipo Connection.
		* 	@param $tableName, String que indica el nombre de la tabla.
		* 	@param $planilla, long que indica el numero de planilla que se buscara en la consulta.
		* 	@param $whereAdicional, String que indica parametros adicionales de busqueda que deben coincidir
		* 	con la planilla enviada.
		* 	@return true o false, retorna true indica que la planilla fue encontrada en la tabla señalada.
		*/
		public function planillaExisteTabla($connLocal, $tableName = '', $planilla = 0, $whereAdicional = '')
		{
			if ( $planilla > 0 ) {
				try {
					if ( trim( $whereAdicional ) != "" ) {
						$sql = "SELECT distinct planilla from {$tableName} WHERE planilla = {$planilla} and {$whereAdicional} limit 1";
					} else {
						$sql = "SELECT distinct planilla from {$tableName} WHERE planilla = {$planilla} limit 1";
					}

					$command = $connLocal->createCommand($sql)->execute();
					if ( !$command ) {
						return false;
					}
					return true;
				} catch (PDOExcepcion $e) {
					return false;
				}
			} else {
				return false;
			}
		}






		/**
		*	Metodo que determina si una planilla tiene en su contenido algun monto por reconocimiento.
		* 	@param $connLocal, instancia de tipo Connection.
		* 	@param $planilla, long que identifica el numero de planilla al cual se debe verificar
		* 	su relacion a otros proceso.
		*	@return true o false, si retorna true indica que la planilla tiene monto por reconocimiento.
		*/
		public function planillaConReconocimiento($connLocal, $planilla = 0)
		{
			if ( $planilla > 0 ) {
				try {
					$sql = "SELECT P.planilla from pagos as P inner join pagos_detalle as D on P.id_pago=D.id_pago
					        WHERE D.monto_reconocimiento > 0 and P.planilla = {$planilla} limit 1";

					$command = $connLocal->createCommand($sql)->execute();
					if ( $command ) {
						return true;
					}
					return false;
				} catch (PDOExcepcion $e) {
					return false;
				}
			} else {
				return false;
			}
		}



		/**
		*	Metodo que agrega un mensaje de error a una variable, este metodo permite identificar
		* 	el tipo de error que ocurre en el proceso de anulacion.
		* 	@param $msg, String que identifica el mensaje respectivo
		*/
		public function addErrors($msg = '')
		{
			$this->msgError = $msg;
		}


		/**
		*	Metodo que retorna el mensaje de error que este contenido en la variable.
		* 	@return String, retorna el mensaje de error ocurrido.
		*/
		public function getErrors()
		{
			return $msg = $this->msgError;
		}



		/**
		 * Metodo que determina la cantidad de registros de un objetos
		 * que estan pendientes por pagar (pago = 0).
		 * @param  long $idObjeto identificador del objeto. Depende de la entidad
		 * en la cual se creo.
		 * @param  integer $impuesto identificador dle impuesto al cual pertenece
		 * el objeto.
		 * @return integer retorna un entero indicando la cantidad de registros con deudas
		 * sino encuentra registros retorna cero (0).
		 */
		public function getRegistroConDeudaPendienteSegunObjeto($idObjeto, $impuesto)
		{
			$findModel = PagoDetalle::find()->where('id_impuesto =:id_impuesto',
			 											[':id_impuesto' => $idObjeto])
											->andWhere('impuesto =:impuesto',
											 			[':impuesto' => $impuesto])
											->andWhere('pago =:pago', [':pago' => 0])
											->andWhere('trimestre =:trimestre', [':trimestre' => 0])
											->count();
			return ( $findModel > 0 ) ? $findModel : 0;
		}




 	}
 ?>