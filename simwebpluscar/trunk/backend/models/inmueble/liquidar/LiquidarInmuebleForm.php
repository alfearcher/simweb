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
 *  @file LiquidarInmuebleForm.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-12-2016
 *
 *  @class LiquidarInmuebleForm
 *  @brief Clase modelo para el formulario que permite la liquiadcion de los inmuebles.
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

	namespace backend\models\inmueble\liquidar;

 	use Yii;
 	use yii\base\Model;
	use yii\db\ActiveRecord;
	use yii\web\NotFoundHttpException;
	use common\models\planilla\PagoDetalle;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\data\ArrayDataProvider;
	use backend\models\inmueble\InmueblesConsulta;
	use yii\grid\GridView;
	use yii\helpers\Html;





	/**
	* Clase que gestiona la liquidacion de Inmuebles Urbanos, donde los nuevos
	* periodos a liquidar se guardaran en una planilla. Se determina cual es el
	* ultimo lapso (año-periodo) liquidado y se determina la condicion del mismo,
	* si el ultimo lapso esta pagado se debe generar un nuevo numero de planilla
	* para guardar los nuevos lapsos, sino es asi, los nuevos lapsos se guardaran
	* en la utlima planilla que existe pendiente.
	*/
	class LiquidarInmuebleForm extends Model
	{

		public $id_contribuyente;
		public $id_impuesto;
		public $direccion;
		public $catastro;
		public $lapso;				// Para contener un string año - periodo - descripcion

		const IMPUESTO = 2;



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
	        	[['id_impuesto', 'direccion',
	        	  'id_contribuyente', 'lapso',],
	        	  'required',
	        	  'message' => Yii::t('backend','{attribute} is required')],
	        	[['id_impuesto', 'id_contribuyente'],
	        	  'integer',
	        	  'message' => Yii::t('backend','{attribute} must be a integer')],
	        	[['direccion', 'lapso', 'catastro',],
	        	  'string',
	        	  'message' => Yii::t('backend','{attribute} must be a string')],
	        ];
	    }




		/**
		 * Metodo que genera el modelo general de consulta de los inmuebles que le pertenecen
		 * a un contribuyente.
		 * @return InmueblesConsulta
		 */
		private function findInmuebleModel()
		{
			return InmueblesConsulta::find()->alias('V')
										    ->where('id_contribuyente =:id_contribuyente',
													[':id_contribuyente' => $this->id_contribuyente])
										    ->andWhere('inactivo =:inactivo',
													[':inactivo' => 0]);

		}



		/**
		 * Metodo que genera un proveedro de datos del tipo ArrayDataProvider, con
		 * la informacion basica del inmueble. Ademas el proveedor contiene un atributo
		 * que contine la informacion del ultimo lapso liquidado de tenerlo, este
		 * informacion estara en formato de string: Año - periodo - descripcion lapso.
		 * @return ArrayDataProvider|null
		 */
		public function getDataProviderInmueble($chkIdImpuesto = [])
		{
			$findModel = self::findInmuebleModel();
			if ( count($chkIdImpuesto) > 0 ) {
				$models = $findModel->andWhere(['IN', 'id_impuesto', $chkIdImpuesto])
				                    ->asArray()->all();
			} else {
				$models = $findModel->asArray()->all();
			}

			$data = [];
			$privider = null;
			if ( count($models) > 0 ) {

				foreach ( $models as $model ) {
					$planilla = '';
					$idPago = '';
					$añoImpositivo = '';
					$periodo = '' ;
					$unidad = '' ;
					$condicion = '';

					$ultimo = self::getUltimoLapsoLiquidado($model['id_impuesto']);
					if ( count($ultimo) > 0 ) {
						$planilla = $ultimo['pagos']['planilla'];
						$idPago = $ultimo['id_pago'];
						$añoImpositivo = $ultimo['ano_impositivo'];
						$periodo = $ultimo['trimestre'];
						$unidad = $ultimo['exigibilidad']['unidad'];
						$condicion = $ultimo['estatus']['descripcion'];
					}

					$data[$model['id_impuesto']] = [
								'id_impuesto' => $model['id_impuesto'],
								'direccion' => $model['direccion'],
								'catastro' => $model['catastro'],
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
		 * @param  integer $idImpuesto identificador del inmueble.
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
		 * @param  integer $idImpuesto identificador del inmueble.
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