<?php
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
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
 *	@file PruebaModuloOnceController.php
 *
 *	@author Jose Rafael Perez Teran
 *
 *	@date 29-08-2016
 *
 *  @class PruebaModuloOnceController
 *	@brief Clase RubroController, aprobacion de rubros
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


 	namespace backend\controllers\cvb;


 	use Yii;
	use yii\filters\AccessControl;
	use yii\web\Controller;
	use yii\filters\VerbFilter;
	use yii\widgets\ActiveForm;
	use yii\web\Response;
	use yii\helpers\Url;
	use yii\web\NotFoundHttpException;
	use common\conexion\ConexionController;
	use backend\controllers\mensaje\MensajeController;
	use common\models\calculo\cvb\CodigoValidadorBancario;
	use backend\models\recibo\deposito\Deposito;
	use yii\data\ActiveDataProvider;
	use arturoliveira\ExcelView;
	use moonland\phpexcel\Excel;
	use backend\models\recibo\depositoplanilla\DepositoPlanilla;
	use common○\models\planilla\Pago;


	session_start();		// Iniciando session

	/**
	 *
	 */
	class PruebaModuloOnceController extends Controller
	{
		public $layout = 'layout-main';				//	Layout principal del formulario

		public $connLocal;
		public $conexion;
		public $transaccion;




		public function actionIndex()
		{
			//$findModel = self::findDeposito();

			$dataProvider = self::getDataProvider();
			return $this->render('/recibo/prueba-cvb/prueba-cvb', [
										// 'model' => $findModel,
										'dataProvider' => $dataProvider,
				]);

		}



		public function actionExport()
		{
			$findModel = self::findDeposito()->all();
			Excel::widget([
    			'models' => $findModel,
    			'format' => 'Excel2007',
                'properties' => [

                ],
    			'mode' => 'export', //default value as 'export'
    			'columns' => [
    				[
    					'attribute' => 'idAlcadia',
    					'value' => function() {
    						return Yii::$app->ente->getEnte();
    					},
    				],
    				[
    					'attribute' => 'idContribuyente',
    					'value' => function($model) {
    						return self::findContribuyenteSegunRecibo($model->recibo);
    					},
    				],
    				[
    					'attribute' => 'codigoControlContribuyente',
    					'value' => function($model) {
    						return $model->getCodigoControl((int)self::findContribuyenteSegunRecibo($model->recibo));
    					},
    				],
    				[
    					'attribute' => 'longitudIdContribuyente',
    					'value' => function($model) {
    						$id = self::findContribuyenteSegunRecibo($model->recibo);
    						$long = self::getDigitoConcatenar($id);
    						return $long;
    					},
    				],
    				'recibo',
    				[
    					'attribute' => 'codigoControlRecibo',
    					'value' => function($model) {
    						return $model->getCodigoControl($model->recibo);
    					},
    				],
    				[
    					'attribute' => 'longRecibo',
    					'value' => function($model) {
    						return strlen($model->recibo);
    					},
    				],
    				'monto',
    				[
    					'attribute' => 'codigoControlMonto',
    					'value' => function($model) {
    						return $model->getCodigoControl((float)$model->monto);
    					},
    				],
    				[
    					'attribute' => 'longMonto',
    					'value' => function($model) {
    						$long = self::getDigitoConcatenar((float)$model->monto);
    						return $long;
    					},
    				],
    				[
    					'attribute' => 'fecha',
    					'value' => function($model) {
    						$f = date('d-m-Y', strtotime($model->fecha));
    						return $f;
    					},
    				],
    				[
    					'attribute' => 'codigoControlFecha',
    					'value' => function($model) {
    						$f = date('d-m-Y', strtotime($model->fecha));
    						$f1 = str_replace('-', '', $f);
    						return $model->getCodigoControl($f1);
    					},
    				],
    				[
    					'attribute' => 'longFecha',
    					'value' => function($model) {
    						$f = str_replace('-', '', $model->fecha);
    						return strlen($f);
    					},
    				],
    				[
    					'attribute' => 'CVB',
    					'value' => function($model) {
    						$idAlcadia = Yii::$app->ente->getEnte();
    						$cvbIdAlcaldia = '0'.$idAlcadia;
    						$id = self::findContribuyenteSegunRecibo($model->recibo);
		                    $long = self::getDigitoConcatenar($id);
		                	$cvbContribuyente = $model->getCodigoControl((int)$id);
		                	$cvbContribuyente = $cvbContribuyente . $long;
    						$cvbRecibo = $model->getCodigoControl($model->recibo) . strlen($model->recibo);
    						$m = self::getDigitoConcatenar($model->monto);
    						$cvbMonto = $model->getCodigoControl($model->monto) . $m;
    						$f = date('d-m-Y', strtotime($model->fecha));
    						$f1 = str_replace('-', '', $f);
    						$cvbFecha = $model->getCodigoControl($f1) . strlen($f1);
    						return $cvbIdAlcaldia . '-' . $cvbContribuyente . $cvbRecibo . '-' . $cvbMonto . $cvbFecha;
    					},
    				],
    				// [
    				// 	'attribute' => 'total',
    				// 	'value' => function() {
    				// 		return 0;
    				// 	},
    				// ]
    			], //without header working, because the header will be get label from attribute label.
    			'headers' => [
    				// 'idAlcadia' => 'Id Alcaldia',
    				// 'idContribuyente' => 'Id Conribuyente',
    				// 'codigoControlContribuyente' => 'Cod. Control Id. Cont',
    				// 'longitudIdContribuyente' => 'Long. Id. Cont.',
    				// 'codigoControlRecibo' => 'Cod. Control Recibo',
    				// 'longRecibo' => 'Long. Recibo',

		    		// 'recibo' => 'Header Column 1',
		    		// 'monto' => 'Header Column 2',
		    		// 'fecha' => 'Header Column 3',
		    		// 'total' => 'Header Column 4'
		    	],
			]);
		}



		public function actionExport1()
		{
			$dataProvider = self::getDataProvider();
			//$findModel = self::findDeposito();
			 ExcelView::widget([
            'dataProvider' => $dataProvider,
            'fileName' => 'exports_'.date('Y-m-d H:i:s'),
            //'filterModel' => $searchModel,
            'fullExportType'=> 'xlsx', //can change to html,xls,csv and so on
            'grid_mode' => 'export',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'header' => 'IdAlcaldia',
                    'format' => 'text',
                    'value' => 0,
                ],
                // [
                // 	'attribute' => 'id_contribuyente',
                // 	'header' => 'IdContribuyente',
                // 	'format' => 'text',
                // 	'value' => function() {
                // 		return 0;
                // 	},
                // ],
                'recibo',
                'monto',
                'fecha',
              ],
        ]);
		}




		/***/
		public function findDeposito2()
		{
			//$findModel = Deposito::find()->limit(5);
			$findModel = Deposito::find()->where('monto >:monto',
													[':monto' => 10000000])
										 ->andWhere('estatus =:estatus',
										 			[':estatus' => 1])
										 //->andWhere(['BETWEEN', 'recibo', 663792, 663792])
                                         //->andWhere(['BETWEEN', 'recibo', 500000, 663792])
										 ->orderBy(['monto' => SORT_DESC]);
										 //->limit(5);
										//->all();

			return isset($findModel) ? $findModel : null;
		}



        public function findDeposito()
        {
            //$findModel = Deposito::find()->limit(5);
            $findModel = Deposito::find()->where('monto >:monto',
                                                    [':monto' => 0])
                                         ->andWhere('estatus =:estatus',
                                                    [':estatus' => 1])
                                         ->andWhere(['BETWEEN', 'recibo', 1000000, 1000100])
                                         //->andWhere(['BETWEEN', 'recibo', 517761, 517761])
                                         //->andWhere(['BETWEEN', 'recibo', 500000, 500100])
                                         ->orderBy(['monto' => SORT_DESC]);
                                         //->limit(5);
                                        //->all();

            return isset($findModel) ? $findModel : null;
        }




		/***/
		public function getDataProvider()
		{
			$query = self::findDeposito();

			$dataProvider = New ActiveDataProvider([
							'query' => $query,
				]);
			$query->all();
			return $dataProvider;
		}



		/***/
		public function findContribuyenteSegunRecibo($recibo)
		{
			$tabla = DepositoPlanilla::tableName();
			$findModel = DepositoPlanilla::find()->where($tabla.'.recibo =:recibo',
												 		[':recibo' => $recibo])
												 ->andWhere('estatus =:estatus',
												 		[':estatus' => 1])
												 ->joinWith('pago', true,'INNER JOIN')
												 ->asArray()
												 ->one();

			return isset($findModel['pago']['id_contribuyente']) ? $findModel['pago']['id_contribuyente'] : 0;
		}



		/***/
		public function getDigitoConcatenar($valor)
		{
			$long = null;
			if ( is_float($valor) ) {
				$entero = (string)$valor * 100;

				$long = (int)strlen($entero);

				if ( $long > 9 ) {
					// Digito mas significativo a la derecha
					return (int)substr($long, -1);
				}
			} else {
				$long = (int)strlen($valor);
				if ( $long > 9 ) {
					// Digito mas significativo a la derecha
					return (int)substr($long, -1);
				}
			}
			return $long;
		}


	}
?>