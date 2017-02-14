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
 *  @file PagoReciboIndividualSearch.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 12-02-2017
 *
 *  @class PagoReciboIndividualSearch
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
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use backend\models\recibo\deposito\Deposito;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use common\models\planilla\PlanillaSearch;




	/**
	* Clase que permite gestionar el pago de un recibo por caja.
	*/
	class PagoReciboIndividualSearch
	{

		private $_recibo;


		/**
		 * Metodo constructor de la clase.
		 * @param integer $recibo numero del recibo de pago.
		 */
		public function __construct($recibo)
		{
			$this->_recibo = $recibo;
		}



		/**
		 * Metodo que genera el modelo principal de consulta sobre el recibo
		 * para el pago. Entidad "depositos"
		 * @return Deposito.
		 */
		private function findDepositoModel()
		{
			return Deposito::find()->alias('D')
			                       ->where('recibo =:recibo',
							     [':recibo' => $this->_recibo])
                                         ->joinWith('condicion C', true, 'INNER JOIN');
		}



		/**
		 * Metodo que genera el modelo de consulta para la entidad "depositos-planillas",
		 * planillas asociadas al recibo de pago.
		 * @return Deposito.
		 */
       	private function findDepositoPlanillaModel()
       	{
       		return $findModel = DepositoPlanilla::find()->alias('DP')
		        							  ->joinWith('deposito D', true, 'INNER JOIN')
									        ->where('D.recibo =:recibo',
									  		           [':recibo' => $this->_recibo])
										  ->orderBy([
										        'planilla' => SORT_ASC,
									         ]);
       	}




       	/**
       	 * Metodo que genera el proveedor de datos para el recibo.
       	 * @return ActiveDataProvider.
       	 */
       	public function getDataProviderDeposito()
       	{
       		$query = self::findDepositoModel();

       		$dataProvider = New ActiveDataProvider([
       			'query' => $query,
       		]);

       		$query->all();

       		return $dataProvider;
       	}



            /***/
            public function getDataProviderDepositoPlanilla()
            {
                  $query = self::findDepositoPlanillaModel();

                  $dataProvider = New ActiveDataProvider([
                        'query' => $query,
                  ]);

                  $query->all();

                  return $dataProvider;
            }




            /***/
            public function getDataProviders()
            {
                  return $dataProvider = [
                              self::getDataProviderDeposito(),
                              self::getDataProviderDepositoPlanilla(),
                        ];
            }




       	/**
       	 * Metodo que permite determinar si el recibo y las planillas asociadoas al mismo
       	 * estan en condicon de pendiente para ser pagados. Esta condicion es primordial
       	 * para poder pagar el recibo y sus respectivas planillas.
       	 * @param array $results arreglo con los datos del recibo y las planillas
       	 * (DepositoPlanilla::find()).
       	 * @return array
       	 */
       	public function condicionPendiente($results)
       	{
       		$mensaje = [];
       		if ( count($results) > 0 ) {

       			// Condicion del recibo.
       			if ( (int)$results[0]['deposito']['estatus'] !== 0 ) {
       				$mensaje[] = Yii::t('backend', 'El estatus del recibo no permite su pago, condición actual: ' . $results[0]['deposito']['estatus']);
       			}

				// Condicion de las planillas asociadas al recibo.
       			foreach ( $results as $result ) {
       				if ((int)$result['estatus'] !== 0 ) {
       					$mensaje[] = Yii::t('backend', 'El estatus de la planilla: ' . $result['planilla'] . ', no permite su pago, condición actual: ' . $result['estatus']);
       				}
       			}

       		} else {
       			// No existe el recibo.
       			$mensaje[] = Yii::t('backend', 'El recibo no existe');
       		}

       		return $mensaje;

       	}




       	/**
       	 * Metodo que determina si la suma de los detalles del recibo (planillas)
       	 * coinciden con el total del monto del recibo.
       	 * @param array $results arreglo con los datos del recibo y las planillas
       	 * (DepositoPlanilla::find()).
       	 * @return string
       	 */
       	public function montoCorrecto($results)
       	{
       		$mensaje = null;
       		$montoRecibo = $results[0]['deposito']['monto'];

       		$suma = 0;
       		foreach ( $results as $result ) {
       			$suma = $result['monto'] + $suma;
       		}

       		if ( (float)$montoRecibo !== (float)$suma ) {
       			$mensaje = Yii::t('backend','El monto del recibo no coincide con la suma de los montos de las planillas');
       		}

       		return $mensaje;
       	}




       	/**
       	 * Metodo que compara el monto de la planilla. Comparación entre el detalle
       	 * del recibo y el monto que posee en la entidad "pagos-detalle".
       	 * @param array $results arreglo con los datos del recibo y las planillas
       	 * (DepositoPlanilla::find()).
       	 * @return array
       	 */
       	public function montoCorrectoPlanilla($results)
       	{
       		$mensaje = [];
       		foreach ( $results as $result ) {
       			$suma = 0;
       			$planillaSearch = New PlanillaSearch($result['planilla']);
       			$detalles = $planillaSearch->getResumenGeneral();

       			if ( (int)$detalles['pago'] !== 0 ) {
       				$mensaje[] = Yii::t('backend', 'La planilla: ' . $detalles['planilla'] .  ', no esta disponible para su pago');
       			} else {
       				$suma = self::sumaDetellePlanilla($detalles);
       				if ( (float)$suma !== (float)$result['monto'] ) {
       					$mensaje[] = Yii::t('backend', 'El monto de la planilla:' . $result['planilla'] . ', no coincide con el registrado al momento de crear el recibo');
       				}
       			}
       		}

       		return $mensaje;

       	}




       	/**
       	 * Metodo que realiza la contabilizacin de los subtotales de la planilla.
       	 * @param array $detalles resultado de la coonsulta con la estructura:
       	 * array(12) {
  		 *	    ["planilla"]=>
  		 * 		string(6) "999999"
		 *	  	["id_contribuyente"]=>
		 *	  	string(3) "99999"
		 *	  	["sum_monto"]=>
		 *	  	string(6) "9999.99"
		 *	  	["sum_recargo"]=>
		 *	  	string(4) "0.00"
	     *		["sum_interes"]=>
		 *	  	string(4) "0.00"
		 *	  	["sum_descuento"]=>
		 *	  	string(4) "0.00"
		 *	  	["sum_monto_reconocimiento"]=>
		 *	  	string(4) "0.00"
		 *	  	["pago"]=>
		 *	  	string(1) "1"
		 *	  	["descripcion_impuesto"]=>
		 *	  	string(5) "TASAS"
		 *	  	["descripcion"]=>
		 *	  	string(70) "Descripcion."
		 *	  	["unidad"]=>
		 *	  	string(8) "Variable"
		 *	  	["estatus"]=>
		 *	  	string(6) "PAGADO"
		 *	}
       	 * @return double
       	 */
       	private function sumaDetellePlanilla($detalles)
       	{
       		return $suma = ( $detalles['sum_monto'] + $detalles['sum_recargo'] + $detalles['sum_interes'] ) - ( $detalles['sum_descuento'] + $detalles['sum_monto_reconocimiento'] );
       	}




       	/**
       	 * Metodo que determina si el recibo esta vencido.
       	 * * @param array $results arreglo con los datos del recibo y las planillas
       	 * (DepositoPlanilla::find()).
       	 * @return string
       	 */
       	public function reciboVencido($results)
       	{
       		$mensaje = null;
       		if ( $results[0]['deposito']['fecha'] !== date('Y-m-d') ) {
       			$mensaje = Yii::t('backend', 'El recibo se encuentra vencido. Fecha actual: ' . date('d-m-Y'));
       		}
       		return $mensaje;
       	}





       	/**
       	 * Metodo que permite aplicar las politicas de validacion del modulo
       	 * para evitar que el proceso no se ejecute correctamente.
       	 * @return array
     	       */
       	public function validarEvento()
       	{
       		$mensajes = [];
       		$results = self::findDepositoPlanillaModel()->asArray()->all();

                  if ( count($results) == 0 ) {
                        $mensajes[] = Yii::t('backend', 'El recibo no existe o sus detalles estan incompleto');
                  } else {
                        // Vencimiento del recibo
                        $mensajes[] = self::reciboVencido($results);


                        // Se compara el monto de la planilla que esta en el recibo y el
                        // monto que actualmente posee la planilla (pagos-detalle).
                        $loteMensajes = self::montoCorrectoPlanilla($results);
                        if ( count($loteMensajes) > 0 ) {
                              foreach ($loteMensajes as $key => $value ) {
                                    $mensajes[] = $value;
                              }
                        }


                        // Se controla el monto total del recibo contra la sumatoria de los
                        // montos de las planillas (depositos-planillas).
                        $mensajes[] = self::montoCorrecto($results);


                        // Se controla que el recibo y las planillas asociadas al mismo esten en un
                        // estatus de pendiente. estutus=0.
                        $loteMensajes = null;
                        $loteMensajes = self::condicionPendiente($results);
                        if ( count($loteMensajes) > 0 ) {
                              foreach ( $loteMensajes as $key => $value ) {
                                    $mensajes[] = $value;
                              }
                        }

                        $mensajes = self::depurarMensaje($mensajes);

                  }

                  return $mensajes;
       	}





            /***/
            private function depurarMensaje($mensajes)
            {
                  $mensajesDepurado = [];

                  foreach ( $mensajes as $key => $value ) {
                        if ( $value !== null ) {
                              $mensajesDepurado[] = $value;
                        }
                  }

                  return $mensajesDepurado;
            }






  //      	private function lizmar()
  //      	{

  //      		vawertyulnbvcdopzasqwddcddddbbbnmjkloyvbancodeficnvyhjh  my   name is lizmar hello hoy dire los colour en hopes
  // favachik . com popur en hoppes banco kiosko mmnah 04241571213 contadto principal en favachik .com mmnah names liz pao y vi
  // viki pa precidenta liz viceprecidenta del club viki secretaria del club cuantas participantes 4 top 12 club favachik. com
  // yenar planilla 2017

  // preguntas 9 interogacion hoy dia domingo 12 de febrero mañana 13 de febrero reunion a las 8 am de la manana

  // martes 14 de febrero dia de la amistad dia inportante para el club . y fin.

       	// }






	}

?>