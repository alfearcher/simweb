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
 *  @file LiquidarVehiculoForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class LiquidarVehiculoForm
 *  @brief Clase modelo para el formulario que permite la liquiadcion de los vehiculos.
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

	namespace backend\models\vehiculo\liquidar;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use common\models\planilla\PagoDetalle;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use backend\models\vehiculo\VehiculosForm;
	use yii\grid\GridView;
	use yii\helpers\Html;





	/**
	* Clase que gestiona la liquidacion de Vehiculos, donde los nuevos
	* periodos a liquidar se guardaran en una planilla. Se determina cual es el
	* ultimo lapso (año-periodo) liquidado y se determina la condicion del mismo,
	* si el ultimo lapso esta pagado se debe generar un nuevo numero de planilla
	* para guardar los nuevos lapsos, sino es asi, los nuevos lapsos se guardaran
	* en la utlima planilla que existe pendiente.
	*/
	class LiquidarVehiculoForm extends Model
	{

		public $id_contribuyente;
		public $id_impuesto;
		public $marca;
		public $modelo;
		public $color;
		public $placa;
		public $lapso;				// Para contener un string año - periodo - descripcion

		const IMPUESTO = 3;



		/**
		 * Metodo constructor de la clase.
		 * @param integer $idContribuyente identificador del contribuyente.
		 */
		public function __construct($idContribuyente)
		{
			$this->id_contribuyente = $idContribuyente;
		}


		/**
     	* @inheritdoc
     	*/
    	public function scenarios()
    	{
        	// bypass scenarios() implementation in the parent class
        	return Model::scenarios();
    	}



		/**
    	 *	Metodo que permite fijar la reglas de validacion del formulario inscripcion-act-econ-form.
    	 */
	    public function rules()
	    {
	        return [
	        	[['id_impuesto', 'placa',
	        	  'id_contribuyente', 'lapso',],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['id_impuesto', 'id_contribuyente'],
	        	  'integer',
	        	  'message' => Yii::t('backend','{attribute} must be a integer')],
	        	[['placa', 'lapso', 'marca',
	        	  'modelo', 'color'],
	        	  'string',
	        	  'message' => Yii::t('backend','{attribute} must be a string')],
	        ];
	    }




		/**
		 * Metodo que genera el modelo general de consulta de los vehiculos que le pertenecen
		 * a un contribuyente.
		 * @return VehiculosForm
		 */
		private function findVehiculoModel()
		{
			return VehiculosForm::find()->alias('V')
										->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->id_contribuyente])
										->andWhere('status_vehiculo =:status_vehiculo',
													[':status_vehiculo' => 0]);

		}



		/**
		 * Metodo que genera un proveedro de datos del tipo ArrayDataProvider, con
		 * la informacion basica del vehiculo. Ademas el proveedor contiene un atributo
		 * uqe contine la informacion del ultimo lapso liquidado de tenerlo, este
		 * informacion estara en formato de string: Año - periodo - descripcion lapso.
		 * @return ArrayDataProvider|null
		 */
		public function getDataProviderVehiculo($chkIdImpuesto = [])
		{
			$findModel = self::findVehiculoModel();
			if ( count($chkIdImpuesto) > 0 ) {
				$models = $findModel->andWhere(['IN', 'id_vehiculo', $chkIdImpuesto])
				                    ->asArray()->all();
			} else {
				$models = $findModel->asArray()->all();
			}

			$data = [];
			$provider = null;
			if ( count($models) > 0 ) {

				foreach ( $models as $model ) {
					$planilla = '';
					$idPago = '';
					$añoImpositivo = '';
					$periodo = '' ;
					$unidad = '' ;
					$condicion = '';

					$ultimo = self::getUltimoLapsoLiquidado($model['id_vehiculo']);
					if ( count($ultimo) > 0 ) {
						$planilla = $ultimo['pagos']['planilla'];
						$idPago = $ultimo['id_pago'];
						$añoImpositivo = $ultimo['ano_impositivo'];
						$periodo = $ultimo['trimestre'];
						$unidad = $ultimo['exigibilidad']['unidad'];
						$condicion = $ultimo['estatus']['descripcion'];
					}

					$data[$model['id_vehiculo']] = [
								'id_impuesto' => $model['id_vehiculo'],
								'placa' => $model['placa'],
								'marca' => $model['marca'],
								'modelo' => $model['modelo'],
								'color' => $model['color'],
								'planilla' => $planilla,
								'idPago' => $idPago,
								'añoImpositivo' => $añoImpositivo,
								'periodo' => $periodo,
								'unidad' => $unidad,
								'condicion' => $condicion,
					];
				}

				if ( count($data) > 0 ) {
					$provider = New ArrayDataProvider([
										'key' => 'id_impuesto',
										'allModels' => $data,
										'pagination' => false,
							]);
				}
			}

			return $provider;
		}



		/**
		 * Metodo que genera el modelo general de consulta de los lapsos.
		 * @return PagoDetalle.
		 */
		private function findLapsoModel()
		{
			return PagoDetalle::find()->alias('D')
									  ->where('D.impuesto =:impuesto',
									  				[':impuesto' => self::IMPUESTO])
									  ->andWhere('trimestre >:trimestre',
									  				[':trimestre' => 0])
									  ->andWhere(['IN', 'pago', [0, 1, 7]])
									  ->joinWith('pagos P', true, 'INNER JOIN')
									  ->joinWith('exigibilidad E', true, 'INNER JOIN')
									  ->joinWith('estatus S', true, 'INNER JOIN');

		}



		/**
		 * Metodo que obtine el ultimo lapso liquidado.
		 * @param  integer $idImpuesto identificador del vehiculo.
		 * @return array retorna un arreglo don la informacion del ultimo lapso liquidado.
		 */
		private function getUltimoLapsoLiquidado($idImpuesto)
		{
			$findModel = self::findLapsoModel();
			$model = $findModel->andWhere('id_impuesto =:id_impuesto',
												[':id_impuesto' => $idImpuesto])
							   ->orderBy([
									'ano_impositivo' => SORT_DESC,
									'trimestre' => SORT_DESC,
								])
							   ->asArray()
							   ->one();
			return $model;

		}



		/**
		 * Metodo que genera una descripcion de la informacion resumida del ultimo lapso.
		 * @param  integer $idImpuesto identificador del vehiculo.
		 * @return string
		 */
		public function getInfoUltimoLapsoLiquidado($idImpuesto)
		{
			$descripcion = '';
			$ultimo = self::getUltimoLapsoLiquidado($idImpuesto);
			if ( count($ultimo) > 0 ) {
				$descripcion = $ultimo['ano_impositivo'] . ' - ' . $ultimo['trimestre'] . ' - ' .
				               $ultimo['exigibilidad']['unidad'] . ' - ' . $ultimo['pagos']['id_pago'] . ' - ' .
				               $ultimo['id_pago'] . ' - ' . $ultimo['estatus']['descripcion'];
			}

			return $descripcion;
		}




		/***/
		public function getDataProviderDetalleLiquidacion($detalles)
		{
			return $provider = New ArrayDataProvider([
									'allModels' => $detalles,
									'pagination' => false,
							]);
		}




	}


?>