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
	use yii\data\ArrayDataProvider;



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




		/***/
		public function findDeposito()
		{
			$findModel = Deposito::find()->where('monto >:monto',
													[':monto' => 0])
										 ->andWhere('estatus =:estatus',
										 			[':estatus' => 1]);
										 ->andWhere('recibo >:recibo',
										 			[':recibo' => 55000])
										 ->limit(5)
										 ->all();

			return isset($findModel) ? $findModel : null;
		}



		/***/
		public function getDataProvider()
		{
			$query = self::findDeposito();

			$dataprovider = New ActiveDataProvider([
							'query' => $query,
				]);

			return $dataProvider;
		}




	}
?>