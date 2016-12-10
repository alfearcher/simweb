<?php
/**
 *	@copyright 2016 © by ASIS CONSULTORES 2012 - 2016
 * 	All rights reserved - SIMWebPLUS
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

 	namespace backend\controllers\operacionbase;

 	use Yii;
 	use backend\models\operacionbase\OperacionBase;
 	use common\conexion\ConexionController;
 	use yii\web\Controller;



 /**
  *	@file OperacionBaseController.php
  *
  *	@author Jose Rafael Perez Teran
  *
  *	@date 01-09-2015
  *
  * @class OperacionBaseController
  *	@brief Clase principal OperacionBaseController, que permite ejecutar algunas rutinas que
  * @brief son repetitivas y basicas dentro del proyecto SIMWEBPLUS. Este controlador interactua
  * @brief con el modelo respectivo.
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
 	class OperacionBaseController extends Controller
 	{

 		protected $msgError = '';


 		/**
 		*	@param $connLocal, instancia de conexion a base de datos.
 		* @param $observacion, string que sera incluido en el registros de la desincorporacion
 		* y se utilizara para actualizar el campo descripcion de la planilla.
 		*	@return retorna un array donde el primer elemento corresponde a un valor boolean que indica si
 		* la anulacion se realizo satisfactoriamente (true), el segundo elemento corresponde al
 		* mensaje de error que ocurrio y que no permitio la anulacion de la plainlla.
 		*/
 		public function anularEstasPlanillas($connLocal, $planillas = [], $observacion = '')
 		{
 			// Se crea una instancia del modelo para la anulacion de la planilla.
 			$operacion = new OperacionBase();
 			$result = $operacion->anularPlanilla($connLocal, $planillas, $observacion);
 			$this->setErrors($operacion->getErrors());


 			//die($this->getErrors());

 			return $result;
 		}



 		/**
 		*	Metodo que determina cuantas planillas posee un objeto.
 		* @param $connLocal, inatancia de tipo Connection.
 		* @param $impuesto, integer indica el identificador del tipo de impuesto, cero (0) no indica nada.
 		* @param $idImpuesto, long indica el identificador del objeto imponible.
 		* @return $b array con lista de numero de planilla(s).
 		*/
 		public function getPlanillaSegunObjeto($connLocal, $impuesto = 0, $idImpuesto = 0, $pago = 0)
 		{
 			if ( $connLocal ) {
	 			if ( $impuesto > 0 and $idImpuesto > 0 ) {

	 				$operacion = new OperacionBase();
	 				$arrayPlanilla = $operacion->getListaPlanillaSegunObjeto($connLocal, $impuesto, $idImpuesto, $pago);
	 				self::setErrors($operacion->getErrors());

	 				if ( count($arrayPlanilla) > 0 ) {

	 					// Se convierte el arrayPlanilla que es un array multidimensional en un unidimensional.
	 					$planilla = [];
	 					for ($j = 0; $j < count($arrayPlanilla); $j++) {
	 						$planilla[$j] = $arrayPlanilla[$j]['planilla'];
	 					}

	 					//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

	 					return $planilla;
	 				}
	 				return false;
	 			} else {
	 				return false;
	 			}
	 		} else {
	 			return false;
	 		}
 		}



 		/**
 		*
 		*/
 		public function setErrors($msg = '')
 		{
 			$this->msgError = $msg;
 		}



 		/**
 		*
 		*/
 		public function getErrors()
 		{
 			return $msg = $this->msgError;
 		}





 		public function actionIndex()
 		{

 			// $conn se enviaria por parametro, es decir, getPlanillaSegunObjeto recibiria esto como parametro.
			$conexion = new ConexionController();
			$conn = $conexion->initConectar('db');
			$conn->open();
			//

			$planillas = [];
 			$planillas = $this->getPlanillaSegunObjeto($conn, 4, 136136);

 			echo var_dump($planillas);
 			if ( count($planillas) > 0 ) {
 				//return var_dump($planillas);

 				$transaccion = $conn->beginTransaction();
 				$resultAnular = $this->anularEstasPlanillas($conn, $planillas, 'PRUEBA DE ANULACION PHP');
 				if ( $resultAnular ) {

					echo 'anulacion completada';
					$transaccion->commit();

 				} else {
 					if ( $this->getErrors() != '' ) {
 						echo ' operacion no realizada  ' .$this->getErrors();
 					}
 					$transaccion->rollBack();
 				}

 			} else {
 				return var_dump($planilla);
 				echo 'El objeto no tiene planilla';
 			}

 		}


 	}

 ?>