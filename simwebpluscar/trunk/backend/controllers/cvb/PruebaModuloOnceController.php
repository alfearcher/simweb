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
    			//'format' => 'Excel2003XML',
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
    					'value' => function() {
    						return 0;
    					},
    				],
    				[
    					'attribute' => 'codigoControlContribuyente',
    					'value' => function() {
    						return 0;
    					},
    				],
    				[
    					'attribute' => 'longitudIdContribuyente',
    					'value' => function() {
    						return 0;
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
    						return $model->getCodigoControl($model->monto);
    					},
    				],
    				[
    					'attribute' => 'longMonto',
    					'value' => function($model) {
    						$m = str_replace('.', '', $model->monto);
    						return strlen($m);
    					},
    				],
    				'fecha',
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
    						$cvbContribuyente = '00';
    						$cvbRecibo = $model->getCodigoControl($model->recibo) . strlen($model->recibo);
    						$m = str_replace('.', '', $model->monto);
    						$cvbMonto = $model->getCodigoControl($model->monto) . strlen($m);
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
		public function findDeposito()
		{
			//$findModel = Deposito::find()->limit(5);
			$findModel = Deposito::find()->where('monto >:monto',
													[':monto' => 0])
										 ->andWhere('estatus =:estatus',
										 			[':estatus' => 1])
										 ->andWhere(['BETWEEN', 'recibo', 50000, 50015])
										 ->orderBy(['monto' => SORT_DESC]);
										 //->limit(5);
										//->all();

			return isset($findModel) ? $findModel : null;
		}



		/***/
		public function getDataProvider()
		{
			$query = self::findDeposito();
// die(var_dump($query->all()));
			$dataProvider = New ActiveDataProvider([
							'query' => $query,
				]);
			$query->all();
			return $dataProvider;
		}




	}
?>