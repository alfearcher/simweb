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
 *  @file prueba-cvb.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-09-2016
 *
 *  @view prueba-cvb.php
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *  @inherits
 *
 */

 	use yii\web\Response;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;
	use yii\grid\GridView;
	use arturoliveira\ExcelView;
	use backend\controllers\cvb\PruebaModuloOnceController;
?>

<div class="row">
	<div class="prueba-cvb">
		<div class="row">
			<div class="row">
				<div class="col-sm-3">
					<h3><?= Html::encode('Prueba de CVB') ?></h3>
				</div>
				<h4><div class="sm-5"><?=Html::a('Exportar a Excel', Url::toRoute(['export']))?></div></h4>
			</div>
				<?= GridView::widget([
					'id' => 'prueba-cvb',
					'dataProvider' => $dataProvider,
					//'filterModel' => $model,
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						[
		                    'label' => Yii::t('backend', 'Id Alcaldia'),
		                    'value' => function($data) {
    										return '0'.Yii::$app->ente->getEnte();
										},
		                ],
						[
		                    'label' => Yii::t('backend', 'Id Contr'),
		                    'value' => function($model) {
    										return PruebaModuloOnceController::findContribuyenteSegunRecibo($model->recibo);
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'd.c.id Cont'),
		                    'value' => function($model) {
		                    				$id = PruebaModuloOnceController::findContribuyenteSegunRecibo($model->recibo);
    										return $model->getCodigoControl((int)$id);
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'lon. id. Cont'),
		                    'value' => function($model) {
		                    				$id = PruebaModuloOnceController::findContribuyenteSegunRecibo($model->recibo);
		                    				$long = PruebaModuloOnceController::getDigitoConcatenar($id);
    										return $long;
										},
		                ],
						[
		                    'label' => Yii::t('backend', 'recibo'),
		                    'value' => function($model) {
    										return $model->recibo;
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'd.c.recibo'),
		                    'value' => function($model) {
    										return $model->getCodigoControl((int)$model->recibo);
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'lon. recibo'),
		                    'value' => function($model) {
    										return strlen($model->recibo);
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'monto a pagar'),
		                    'value' => function($model) {
    										return $model->monto;
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'd.c.monto'),
		                    'value' => function($model) {
    										return $model->getCodigoControl((float)$model->monto);
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'lon. monto'),
		                    'value' => function($model) {
		                    				$long = PruebaModuloOnceController::getDigitoConcatenar($model->monto);
		                    				//$long = str_replace(".", "", (string)$model->monto);
    										return $long;
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'fecha'),
		                    'value' => function($model) {
    										return $fecha = date('d-m-Y', strtotime($model->fecha));
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'd.c.fecha'),
		                    'value' => function($model) {
		                    				$fecha = date('d-m-Y', strtotime($model->fecha));
		                    				$fecha1 = str_replace('-', '', $fecha);
    										return $model->getCodigoControl($fecha1);
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'lon.fecha'),
		                    'value' => function($model) {
		                    				$fecha = date('d-m-Y', strtotime($model->fecha));
		                    				$fecha1 = str_replace('-', '', $fecha);
    										return strlen($fecha1);
										},
		                ],
		                [
		                	'label' => 'CVB',
		                	'value' => function($model) {
		                		$idAlcaldia = '0'.Yii::$app->ente->getEnte();
		                		$id = PruebaModuloOnceController::findContribuyenteSegunRecibo($model->recibo);
		                    	$long = PruebaModuloOnceController::getDigitoConcatenar($id);
		                		$cbvID = $model->getCodigoControl((int)$id);
		                		$cbvID = $cbvID . $long;
		                		$cvbRecibo = $model->getCodigoControl((int)$model->recibo);
		                		$cvbRecibo = $cvbRecibo . strlen($model->recibo);
		                		$cvbMonto = $model->getCodigoControl($model->monto);
		                		$long = PruebaModuloOnceController::getDigitoConcatenar($model->monto);
		                		$cvbMonto = $cvbMonto . $long;
		                		$f = date('d-m-Y', strtotime($model->fecha));
		                    	$f1 = str_replace('-', '', $f);
		                		$cvbFecha = $model->getCodigoControl($f1) . strlen($f1);

		                		return $idAlcaldia . '-' . $cbvID . $cvbRecibo . '-' . $cvbMonto . $cvbFecha;
		                	},
		                ],
		        	],
				]);
			?>
		</div>
	</div>
</div>
