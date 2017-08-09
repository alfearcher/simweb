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
 *  @file RegistroTxtSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 03-04-2017
 *
 *  @class RegistroTxtSearch
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

	namespace backend\models\recibo\txt;

 	use Yii;
	use yii\base\Model;
	use common\models\planilla\Pago;
	use yii\data\ArrayDataProvider;
	use yii\data\ActiveDataProvider;
	use yii\helpers\ArrayHelper;
	use backend\models\recibo\prereferencia\PreReferenciaPlanilla;
	use backend\models\recibo\pago\individual\SerialReferenciaUsuario;
	use backend\models\recibo\txt\RegistroTxtRecibo;


	/**
	* Clase
	*/
	class RegistroTxtReciboSearch extends RegistroTxtRecibo
	{

		private $_fecha_pago;
		private $_planilla;
		private $_recibo;



		/**
		 * Metodo que setea le valor del atributo fecha de pago.
		 * @param string $fechaPago fecha de pago.
		 */
		public function setFechaPago($fechaPago)
		{
			$this->_fecha_pago = self::formatearFechaPago($fechaPago);
		}


		/**
		 * Metodo getter de la fecha de pago.
		 * @return string retorna fecha de pago.
		 */
		public function getFechaPago()
		{
			return $this->_fecha_pago;
		}



		/**
		 * Metodo que formatea el atributo fecha de pago a la forma Y-m-d.
		 * @param string $fechaPago fecha de pago.
		 * @return string retorna la fecha con el formato Y-m-d.
		 */
		public function formatearFechaPago($fechaPago)
		{
			return date('Y-m-d', strtotime($fechaPago));
		}


		/**
		 * Metodo que setea el valor del atributo planilla.
		 * @param integer $planilla numero de planilla.
		 */
		public function setPlanilla($planilla)
		{
			$this->_planilla = $planilla;
		}



		/**
		 * Metodo getter de la planilla.
		 * @return integer retorna numero de planilla.
		 */
		public function getPlanilla()
		{
			return $this->_planilla;
		}



		/**
		 * Metodo que realiza la consulta de la entidad por id.
		 * @param integer $idRegistro identificador de la entidad.
		 * @return RegisterTxt.
		 */
		public function findRegistroTxtById($idRegistro)
		{
			return $this->findOne($idRegistro);
		}




		/**
		 * Metodo que realiza la consulta de los registros que esta relacionados a una
		 * fecha especifica de pago.
		 * @return RegistroTxt.
		 */
		public function findRegistroTxtByFecha()
		{
			return $this->find()->where('fecha_pago =:fecha_pago',
												[':fecha_pago' => $this->_fecha_pago])
								->orderBy([
									'planilla' => SORT_ASC,
								])
							    ->all();
		}



		/**
		 * Metodo que realiza una consulta sobre las pre-referencias segun una fecha de pago
		 * especifica.
		 * @param array $estatus arreglo de entero que especifica los valores que asumira
		 * el atributo en la consulta.
		 * @return PreReferenciaPlanilla
		 */
		public function findPreReferenciaPlanillaByFecha($estatus = [])
		{
			if ( count($estatus) > 0 ) {
				return $registers = PreReferenciaPlanilla::find()->where('fecha =:fecha',
																			[':fecha' => $this->_fecha_pago])
																 ->andWhere(['IN', 'estatus', $estatus])
														  		 ->orderBy([
														  			  'planilla' => SORT_ASC,
														  		  ])
														  		 ->all();
			} else {
				return $registers = PreReferenciaPlanilla::find()->where('fecha =:fecha',
																			[':fecha' => $this->_fecha_pago])
														  		 ->orderBy([
														  			  'planilla' => SORT_ASC,
														  		  ])
														  		 ->all();
			}
		}



		/**
		 * Metodo que realiza la consulta de las referencias por el numero de planilla
		 * @return PreReferenciaPlanilla.
		 */
		public function findPreReferenciaPlanillaByPlanilla()
		{
			return $registers = PreReferenciaPlanilla::find()->where('planilla =:planilla',
																			[':planilla' => $this->_planilla])
														  		 ->orderBy([
														  			  'planilla' => SORT_ASC,
														  		  ])
														  		 ->all();
		}




		/**
		 * Metodo que genera un listado de planillas existentes el las referencias.
		 * @param PreRefernciaPlanilla $model instancia de la clase
		 * @param string $nombreKey nombre del elemento clave del arreglo.
		 * @param string $nombreValor nombre del elemento valor del arreglo.
		 * @return array
		 */
		public function getListaPlanillaReferencia($model, $nombreKey, $nombreValor)
		{
			return ArrayHelper::map($model, $nombreKey, $nombreValor);
		}





		/**
		 * Metodo que arma un listado con las referencias ya agregadas a la entidad temporal
		 * para que no puedan aparecer en el listado de las planillas sin referencias. Esto
		 * con el objeto de que no se tomen nuevamente del listado de planillas sin referencias.
		 * Se buscan los registros agregados en "seriales-referencias-usuarios" relacionados
		 * a una fecha especifica.
		 * @return array
		 */
		public function getListaPlanillaYaSeleccionada()
		{
			$registers = SerialReferenciaUsuario::find()->where('fecha_edocuenta =:fecha_edocuenta',
																		[':fecha_edocuenta' => $this->_fecha_pago])
													    ->all();

			$listaSerial = self::getListaPlanillaReferencia($registers, 'serial', 'serial');

			return $lista =  array_values($listaSerial);
		}




		/**
		 * Metodo que generar la data para el data provider que se utilizara para el listado
		 * del grid de planilla sin referencia. Se realiza una serie de consultas para
		 * utilizarlas como filtro, esto con la intencion de no mostrar planillas que ya
		 * tengan asociadas referencias o que ya fueron seleccionadas en este proceso.
		 * @return RegistroTxt
		 */
		private function getDataModelPlanillaSinReferencia()
		{
			// registros de las referencias existentes para una fecha especifica.
			// Se buscan los registros que no esten anulados, para que sirvan de
			// filtro para la entidad "registros-txt".
			$referencias = self::findPreReferenciaPlanillaByFecha([0,1]);

			// Lista de recibo registrados con referencia.
			$listaReferencia = self::getListaPlanillaReferencia($referencias, 'recibo', 'recibo');

			// arreglo donde el valor delo mismo es el numero de recibo.
			$listaPlanilla = array_values($listaReferencia);

			// Se buscan los recibos ya agregadas en la entidad temporal.
			$listaYaAgregada = self::getListaPlanillaYaSeleccionada();

			// Listado que se utilizara para filtar la consulta, con el objeto que no
			// aparezcan en el listado nuevamente.
			$listaFiltro = array_merge($listaPlanilla, $listaYaAgregada);

			// Ahora se debe buscar en la entidad "registros-txt-recibos", los recibos que aun
			// no estan en las referencias. Se debe realizar una consulta excluyente de
			// conjunto.
			$model = $this->find()->where('fecha_pago =:fecha_pago',
													[':fecha_pago' => $this->_fecha_pago])
									  ->andWhere(['NOT IN', 'recibo', $listaFiltro]);

			return $model;
		}





		/**
		 * Metodo que genera el data provider para el grid del lsitado de
		 * planillas sin referencias relacionadas, segun una fecha especifica.
		 * @return ActiveDataProvider.
		 */
		public function getDataProviderPlanillaSinRferenciaByFecha()
		{

			$query = self::getDataModelPlanillaSinReferencia();
			$provider = New ActiveDataProvider([
				'key' => 'id_registro_recibo',
				'query' => $query,
				'pagination' => false,
			]);
			$query->all();
			return $provider;
		}




	}

?>