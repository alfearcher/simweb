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
?>

<div class="row">
	<div class="prueba-cvb">
		<div class="row">
			<h3><?= Html::encode('Prueba de CVB') ?></h3>
				<?= GridView::widget([
					'id' => 'prueba-cvb',
					'dataProvider' => $dataProvider,
					//'filterModel' => $model,
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						[
		                    'label' => Yii::t('backend', 'Id de la Alcaldia'),
		                    'value' => Yii::$app->ente->getEnte(),
		                ],
						[
		                    'label' => Yii::t('backend', 'Id Contribuyente'),
		                    'value' => function($data) {
    										return 0;
										},
		                ],
						[
		                    'label' => Yii::t('backend', 'recibo'),
		                    'value' => function($data) {
    										return $data->recibo;
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'Monto a pagar'),
		                    'value' => function($data) {
    										return $data->monto;
										},
		                ],
		                [
		                    'label' => Yii::t('backend', 'Fecha'),
		                    'value' => function($data) {
    										return $data->fecha;
										},
		                ],

		        	]
				]);
			?>
		</div>
	</div>
</div>