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
 *  @file GererarRafagaRecibo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 02-08-2017
 *
 *  @class GererarRafagaRecibo
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

	namespace common\models\rafaga;

 	use Yii;
 	use backend\models\recibo\deposito\Deposito;
 	use backend\models\historico\rafaga\autorizar\HistoricoAutorizarRafagaSearch;
 	use backend\models\recibo\planillaaporte\PlanillaAporte;
 	use common\models\contribuyente\ContribuyenteBase;



	/**
	 * Clase que permite generar la informacion que se utilizara en la
	 * rafaga del recibo, esta informacion estara contenida en un arreglo
	 * donde el indice del arreglo contendra el item respectivo: ejemplo
	 * alcaldia, id_contribuyente, contribuyente, etc.
	 */
	class GenerarRafagaRecibo
	{

		private $_recibo;
		public $_historicoSearch;	// Instancia de la clase HistoricoAutorizarRafagaSearch.
		public $_errores = [];
		private $_deposito; 		// Imstancia de la clase Deposito.
		private $_rafaga = [];


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 */
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
			$this->_rafaga = [];
		}



		/**
		 * Metodo que inicia la clase y permite retornar un arreglo con la informacion
		 * de la rafaga. Esta informacion estara formada en un arreglo.
		 * @return array
		 */
		public function getRafaga()
		{
			$historicoSearch = New HistoricoAutorizarRafagaSearch($this->_recibo);
			if ( $historicoSearch->estaHabilitado() ) {
				self::armarRafaga();
			} else {
				self::setErrores(Yii::t('backend', "El recibo Nro. {$this->_recibo}, no esta habilitado para la impresion de la rafaga"));
			}
			return $this->_rafaga;
		}


		/**
		 * Metodo setter
		 * @param string $mensaje mensaje de error ocurrido.
		 */
		public function setErrores($mensaje)
		{
			$this->_errores[] = $mensaje;
		}


		/**
		 * Metodo getter
		 * @return array, retorna arreglo de mensajes.
		 */
		public function getErrores()
		{
			return $this->_errores;
		}



		/**
		 * Metodo que genera el modelo del recibo.
		 * @return string
		 */
		private function findReciboModel()
		{
			return Deposito::find()->alias('D')
								   ->where('recibo =:recibo',
												[':recibo' => $this->_recibo]);
		}


		/**
		 * Metodo que permite obtener la informacion basica del recibo.
		 * @return array
		 */
		private function armarRafaga()
		{
			$findModel = self::findReciboModel();
			$registers = $findModel->asArray()->one();
			if ( count($registers) > 0 ) {
				$this->_rafaga = [
					'alcaldia' => Yii::$app->ente->getAlcaldia(),
					'recibo' => $registers['recibo'],
					'fecha' => $registers['fecha'],
					'monto' => $registers['monto'],
					'id_contribuyente' => $registers['id_contribuyente'],
					'contribuyente' => ContribuyenteBase::getContribuyenteDescripcionSegunID((int)$registers['id_contribuyente']),
					'usuario' => Yii::$app->identidad->getUsuario(),
					'fecha_hora' => date('Y-m-d H:i:s'),
					'forma_pago' => self::infoPlanillaAporte(),
				];
			} else {
				self::setErrores(Yii::t('backend', 'No existe el recibo Nro. ') . $this->_recibo);
			}
		}



		/**
		 * Metodo que genera el modelo basico de consulta sobre la entidad que
		 * contiene la informacion basica de la rafaga
		 * @return PlanillaAporte
		 */
		private function findPlanillaAporteModel()
		{
			return PlanillaAporte::find()->alias('A')
										 ->where('recibo =:recibo',
														[':recibo' => $this->_recibo]);
		}



		/**
		 * Metodo que ejecuta la consulta para obtener una sumatoria agrupada
		 * por las formas de pagos.
		 * @return array
		 */
		private function infoPlanillaAporte()
		{
			$findModel = self::findPlanillaAporteModel();
			$registers = $findModel->select([
										'A.id_forma',
										'descripcion',
										'sum(monto_aporte) as aporte'])
								   ->joinWith('formaPago F', false, 'INNER JOIN')
			       				   ->groupBy(['id_forma'])
			       				   ->asArray()
			       				   ->all();
			if ( count($registers) > 0 ) {
				return $registers;
			} else {
				self::setErrores(Yii::t('backend', 'No existe la forma de pago del recibo Nro. ') . $this->_recibo);
				return [];
			}

		}


	}

?>