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
      use backend\models\recibo\depositodetalle\DepositoDetalleUsuario;
      use backend\models\recibo\depositodetalle\DepositoDetalle;
      use yii\data\ArrayDataProvider;
      use backend\models\utilidad\tipotarjeta\TipoTarjetaSearch;
      use backend\models\recibo\depositodetalle\VaucheDetalleUsuario;


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
                        'key' => 'planilla',
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
             * Metodo que determina si el identificador del contribuyente que se encuentra
             * registrado en el recibo, es igual al que se encuentra en cada una de las
             * planillas contenida en el recibo.
             * @param array $results arreglo con los datos del recibo y las planillas
             * (DepositoPlanilla::find()).
             * @return array
             */
            public function contribuyenteCorrecto($results)
            {
                  $mensaje = [];
                  $idContribuyente = (int)$results[0]['deposito']['id_contribuyente'];
                  $recibo = $results[0]['deposito']['recibo'];

                  foreach ( $results as $result ) {
                        $planilla = $result['planilla'];
                        $planillaSearch = New PlanillaSearch($planilla);
                        $condicionPlanilla = $planillaSearch->condicionPlanilla();
                        if ( count($condicionPlanilla) > 0 ) {
                              if ( (int)$condicionPlanilla[0]['id_contribuyente'] !== $idContribuyente ) {
                                    $id = (int)$condicionPlanilla[0]['id_contribuyente'];
                                    $mensaje[] = Yii::t("backend", "El id de la planilla {$planilla}, ({$id}); no coincide con el del recibo {$recibo}, ({$idContribuyente})");
                              }
                        }
                  }
                  return $mensaje;
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
       		$mensaje = [];
       		$montoRecibo = $results[0]['deposito']['monto'];

       		$suma = 0;
       		foreach ( $results as $result ) {
       			$suma = $result['monto'] + $suma;
       		}

       		if ( (float)$montoRecibo !== (float)$suma ) {
       			$mensaje[] = Yii::t('backend','El monto del recibo no coincide con la suma de los montos de las planillas');
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
                        $loteMensajes = null;
                        $loteMensajes = self::montoCorrecto($results);
                        if ( count($loteMensajes) > 0 ) {
                              foreach ($loteMensajes as $key => $value ) {
                                    $mensajes[] = $value;
                              }
                        }


                        // Se controla que el recibo y las planillas asociadas al mismo esten en un
                        // estatus de pendiente. estutus=0.
                        $loteMensajes = null;
                        $loteMensajes = self::condicionPendiente($results);
                        if ( count($loteMensajes) > 0 ) {
                              foreach ( $loteMensajes as $key => $value ) {
                                    $mensajes[] = $value;
                              }
                        }


                        // Validar el identificador del contribuyente entre el recibo y las planillas
                        // contenidas en el mismo.
                        $loteMensajes = null;
                        $loteMensajes = self::contribuyenteCorrecto($results);
                        if ( count($loteMensajes) > 0 ) {
                              foreach ( $loteMensajes as $key => $value ) {
                                    $mensajes[] = $value;
                              }
                        }


                        $mensajes = self::depurarMensaje($mensajes);

                  }

                  return $mensajes;
       	}





            /**
             * Metodo que limpia el arreglo de mensajes de aquellos vallores con null.
             * @param array $mensajes arreglo de mensajes
             * @return array
             */
            public function depurarMensaje($mensajes)
            {
                  $mensajesDepurado = [];

                  foreach ( $mensajes as $key => $value ) {
                        if ( $value !== null ) {
                              $mensajesDepurado[] = $value;
                        }
                  }

                  return $mensajesDepurado;
            }




            /**
             * Metodo que ccontabliza el monto total por recibo y por planillas
             * contenidos en el recibo. Para esto utiliza los providers de datos
             * creados en la consulta del recibo. El resultado obtenido es un arreglo
             * de totales donde el valor del indice cero (0), es el total del recibo
             * y el elemento de indice uno (1), es el total de la suma de las planillas
             * contenidas en el recibo.
             * @param  array $providers arregglo de ActiveDataProvider del recibo y de
             * las planillas contenidas en el recibo.
             * @return array.
             */
            public function getTotalesReciboPlanilla($providers)
            {
                  $totales = [];

                  foreach ( $providers as $provider ) {
                        $totales[] = self::contabilizarProvider($provider);
                  }

                  return $totales;
            }



            /**
             * Metodo que contabiliza el monto de un provider especifico.
             * @param ActiveDataProvider $provider data provider de depositos o
             * de depositos-planillas.
             * @return float.
             */
            private function contabilizarProvider($provider)
            {
                  $suma = 0;
                  foreach ( $provider->getModels() as $model ) {
                        $suma = $suma + $model->monto;
                  }

                  return $suma;
            }



            /**
             * Metodo que retorna el registro de la consulta realizada sobre la entidad
             * "depositos".
             * @return Deposito
             */
            public function getDeposito()
            {
                  return self::findDepositoModel()->asArray()->all();
            }



            /**
             * Metodo que retorna los registros de la consulta realizada sobre la entidad
             * "depositos-planillas".
             * @return DepositoPlanilla.
             */
            public function getDepositoPlanilla()
            {
                  return self::findDepositoPlanillaModel()->asArray()->all();
            }



            /**
             * Metodo que permite generar el data provider de los pagos
             * registrados por el usuario para pagar un recibo, especifico.
             * Cada recibo se le asociara un formas de pagos que estara guardada
             * temporalmente hasta su salvado final. Estos registros estaran relacionados
             * al recibo-usuario.
             * @param string $usuario usuario que esta realizando laoperacion
             * de salvado del registro.
             * @return ArrayDataProvider
             */
            public function getDataProviderRegistroTemp($usuario)
            {
                  $data = [];
                  $results = self::findDepositoDetalleUsuarioTemp($usuario);

                  if ( count($results) > 0 ) {

                        foreach ( $results as $result ) {
                              $codigo_cuenta = '';
                              if ( $result['id_forma'] == 1 ) {
                                    $codigo_cuenta = $result['codigo_cuenta'];

                              } elseif ( $result['id_forma'] == 4 ) {
                                    $codigo_cuenta = $result['codigo_cuenta'];

                              } else {
                                    $codigo_cuenta = '';
                              }
                              $data[$result['linea']] = [
                                    'linea' => $result['linea'],
                                    'recibo' => $result['recibo'],
                                    'id_forma' => $result['id_forma'],
                                    'deposito' => $result['deposito'],
                                    'fecha' => $result['fecha'],
                                    'cuenta' => $result['cuenta'],
                                    'cheque' => $result['cheque'],
                                    'monto' => $result['monto'],
                                    'usuario' => $usuario,
                                    'forma' => $result['formaPago']['descripcion'],
                                    'codigo_cuenta' => $codigo_cuenta,
                                    'id_banco' => $result['id_banco'],

                              ];
                        }
                  }

                  $provider = New ArrayDataProvider([
                        'key' => 'linea',
                        'allModels' => $data,
                        'pagination' => false,
                  ]);

                  return $provider;
            }






            /**
             * Metodo que permite generar el data provider de los pagos
             * registrados por el usuario para pagar un recibo, especifico.
             * Cada recibo se le asociara un formas de pagos que estara guardada
             * temporalmente hasta su salvado final. Estos registros estaran relacionados
             * al recibo-usuario.
             * @param string $usuario usuario que esta realizando laoperacion
             * de salvado del registro.
             * @return ArrayDataProvider
             */
            public function getDataProviderRegistroVaucheTemp($usuario, $linea)
            {
                  $data = [];
                  $results = self::findVaucheDetalleUsuarioTemp($usuario, $linea);

                  if ( count($results) > 0 ) {

                        foreach ( $results as $result ) {
                              $codigo_cuenta = '';
                              if ( $result['tipo'] == 2 ) {
                                    $codigo_cuenta = $result['codigo_cuenta'];

                              } else {
                                    $codigo_cuenta = '';
                              }
                              $data[$result['id_vauche']] = [
                                    'id_vauche' => $result['id_vauche'],
                                    'linea' => $result['linea'],
                                    'recibo' => $result['recibo'],
                                    'tipo' => $result['tipo'],
                                    'deposito' => $result['deposito'],
                                    //'fecha' => $result['fecha'],
                                    'cuenta' => $result['cuenta'],
                                    'cheque' => $result['cheque'],
                                    'monto' => $result['monto'],
                                    'usuario' => $usuario,
                                    'forma' => $result['tipoDeposito']['descripcion'],
                                    'codigo_cuenta' => $codigo_cuenta,
                                    'banco' => 0,

                              ];
                        }
                  }

                  $provider = New ArrayDataProvider([
                        'key' => 'id_vauche',
                        'allModels' => $data,
                        'pagination' => false,
                  ]);

                  return $provider;
            }





            /**
             * Metodo que genera el modelo principal de consulta de la entidad
             * "depositos-detalles-usuarios".
             * @return DepositoDetalleUsuario.
             */
            private function findDepositoDetalleUsuarioModel()
            {
                  return $findModel = DepositoDetalleUsuario::find()->alias('A')
                                                                    ->where('recibo =:recibo',
                                                                              [':recibo' => $this->_recibo]);
            }


            /**
             * Metodo que genera el modelo principal de consulta de la entidad
             * "vauches-detalles-usuarios".
             * @return VaucheDetalleUsuario.
             */
            private function findVaucheDetalleUsuarioModel()
            {
                  return $findModel = VaucheDetalleUsuario::find()->alias('A')
                                                                    ->where('recibo =:recibo',
                                                                              [':recibo' => $this->_recibo]);
            }



            /**
             * Metodo que genera el modelo principal de consulta de la entidad
             * "depositos-detalle"
             * @return DepositoDetalle.
             */
            private function findDepositoDetalleModel()
            {
                  return $findModel = DepositoDetalleUsuario::find()->alias('A');
            }





            /**
             * Metodo que determina si un numero de cheque ya esta registrado en la entidad
             * final (depositos-detalle).
             * @param string $nroCuenta numero de cuenta donde se registra el cheque,
             * esta variables esta conformada por el codigo-del-banco + el-numero-cuenta
             * propiamente dicho.
             * @param string $cheque nuemro del cheque.
             * @return boolean.
             */
            public function existeChequeEnBD($nroCuenta, $cheque)
            {
                  $findModel = self::findDepositoDetalleModel();
                  $registers = $findModel->where('cheque =:cheque',
                                                      [':cheque' => $cheque])
                                       ->andWhere('id_forma =:id_forma',
                                                      [':id_forma' => 1])
                                       ->all();
                  if ( $registers ) {
                        foreach ( $registers as $register ) {
                              if ( trim($register->cuenta) === trim($nroCuenta) ) {
                                    return true;
                              }
                        }
                  }
                  return false;
            }



            /**
             * Metodo que determina si un numero de cheque ya esta registrado en la entidad
             * final (depositos-detalle).
             * @param string $nroCuenta numero de cuenta donde se registra el cheque,
             * esta variables esta conformada por el codigo-del-banco + el-numero-cuenta
             * propiamente dicho.
             * @param string $cheque nuemro del cheque.
             * @return boolean.
             */
            public function existeChequeTemporal($nroCuenta, $cheque)
            {
                  $findModel = self::findDepositoDetalleUsuarioModel();
                  $registers = $findModel->where('cheque =:cheque',
                                                      [':cheque' => $cheque])
                                       ->andWhere('id_forma =:id_forma',
                                                      [':id_forma' => 1])
                                       ->all();
                  if ( $registers ) {
                        foreach ( $registers as $register ) {
                              if ( trim($register->cuenta) === trim($nroCuenta) ) {
                                    return true;
                              }
                        }
                  }
                  return false;
            }




            /**
             * Metodo que determina si un numero de cheque ya se encuentra registrado
             * en la temporal o en la base de datos final. Realiza una doble consulta
             * en dos entidades diferentes.
             * @param string $nroCuenta numero de cuenta donde se registra el cheque,
             * esta variables esta conformada por el codigo-del-banco + el-numero-cuenta
             * propiamente dicho.
             * @param string $cheque nuemro del cheque.
             * @return boolean
             */
            public function existeCheque($nroCuenta, $cheque)
            {
                  if ( self::existeChequeEnBD($nroCuenta, $cheque) ) {
                        return true;
                  } elseif ( self::existeChequeTemporal($nroCuenta, $cheque) ) {
                        return true;
                  }
                  return false;
            }




            /**
             * Metodo que realiza la consulta sobre la temporal por medio del identificador
             * de la entidad.
             * @param string $linea identificador de la entidad temporal.
             * @return array.
             */
            public function findEspecificoDepositoDetalleUsuarioTemp($linea)
            {
                  $findModel = self::findDepositoDetalleUsuarioModel();
                  $model = $findModel->andWhere('linea =:linea',
                                                      [':linea' => $linea])
                                     ->joinWith('formaPago F', true, 'INNER JOIN');

                  return $results = $model->one();
            }




            /***/
            public function findEspecificoDetalleVauche($idVauche)
            {
                  $findModel = self::findVaucheDetalleUsuarioModel();
                  $model = $findModel->andWhere('id_vauche =:id_vauche',
                                                      ['id_vauche' => $idVauche]);
                  return $model->one();
            }




            /**
             * Metodo que realiza la consulta y devuelve los registros guardados
             * temporalmente. Estods registros indican la forma de pago conque
             * se pagara el recibo especifico.
             * @param string $usuario usuario que esta realizando laoperacion
             * de salvado del registro.
             * @return array.
             */
            public function findDepositoDetalleUsuarioTemp($usuario)
            {
                  $findModel = self::findDepositoDetalleUsuarioModel();
                  $model = $findModel->andWhere('usuario =:usuario',
                                                      [':usuario' => $usuario])
                                     ->joinWith('formaPago F', true, 'INNER JOIN');

                  return $results = $model->asArray()->all();
            }




            /**
             * Metodo que realiza la consulta y devuelve los registros guardados
             * temporalmente. Estos registros indican la forma de pago con que
             * se pagara el recibo especifico. Informacion que estara en el vauchers.
             * @param string $usuario usuario que esta realizando laoperacion
             * de salvado del registro.
             * @return array.
             */
            public function findVaucheDetalleUsuarioTemp($usuario, $linea)
            {
                  $findModel = self::findVaucheDetalleUsuarioModel();
                  $model = $findModel->andWhere('usuario =:usuario',
                                                      [':usuario' => $usuario])
                                     ->andWhere('linea =:linea',
                                                      [':linea' => $linea])
                                     ->joinWith('tipoDeposito T', true, 'INNER JOIN');

                  return $results = $model->asArray()->all();
            }







            /**
             * Metodo que determina el total agregado para un recibo
             * @param string $usuario usuario que esta realizando laoperacion
             * de salvado del registro.
             * @return double retorna monto total guardado.
             */
            public function getTotalFormaPagoAgregado($usuario)
            {
                  $total = 0;
                  $results = self::findDepositoDetalleUsuarioTemp($usuario);
                  if ( count($results) > 0 ) {
                        foreach ( $results as $result ) {
                              $total = $total + $result['monto'];
                        }
                  }

                  return $total;
            }





            /**
             * Metodo que realiza la consulta por recibo, usuario y forma de pago.
             * @param integer $idForma identificador de la forma de pago.
             * @param string $usuario usuario que realiza la operacion de registro.
             * @return array.
             */
            public function findFormaPago($idForma, $usuario)
            {
                  $findModel = self::findDepositoDetalleUsuarioModel();
                  $model = $findModel->andWhere('usuario =:usuario',
                                                      [':usuario' => $usuario])
                                     ->andWhere('id_forma =:id_forma',
                                                      [':id_forma' => $idForma]);

                  return $results = $model->asArray()->all();

            }



            /***/
            public function getDescripcionTipoTarjeta($tipo)
            {
                  $tarjetaSearch = New TipoTarjetaSearch();
                  return $descripcion = $tarjetaSearch->getDescripcionTarjeta($tipo);
            }




            /**
             * Metodo que contaviliza el total de los montos registrados para un vaucher.
             * Segun el recibo, usuario y deposito.
             * @param string $usuario usuario que esta realizando la carga de los registros
             * del vaucher.
             * @param integer $deposito numero del vaucher.
             * @return double retorna el monto contabilizado que posee el vaucher.
             */
            public function contabilizarVaucheDetalleDepositoUsuario($usuario, $deposito)
            {
                  $suma = 0;
                  $findModel = self::findVaucheDetalleUsuarioModel();
                  $model = $findModel->andWhere('usuario =:usuario',
                                                      [':usuario' => $usuario])
                                     ->andWhere('deposito =:deposito',
                                                      [':deposito' => $deposito])
                                     ->all();

                  if ( count($model) ) {
                        foreach ( $model as $r ) {
                              $suma = $suma + $r->monto;
                        }
                  }
                  return $suma;
            }
	}

?>