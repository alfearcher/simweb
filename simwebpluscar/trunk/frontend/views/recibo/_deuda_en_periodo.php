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
 *  @file recibo-create-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-11-2016
 *
 *  @view recibo-create-form
 *  @brief vista principal del formulario para la creacion de los recibos de pago.
 *
 */

 	use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\Pjax;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>

<div class="deuda-en-periodo">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-deudad-en-periodo',
 			'method' => 'post',
 			//'action'=> $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<!-- <?//=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $findModel['id_contribuyente']])->label(false);?> -->

	<div class="row" style="border-bottom: 1px solid #ccc;padding-left: 0px;">
		<h4><?=Html::encode($caption)?></h4>
	</div>

	<div class="row" class="deuda" style="padding-top: 10px;">
		<?= GridView::widget([
			'id' => 'grid-deuda-por-periodo-impuesto',
			'dataProvider' => $dataProvider,
			//'filterModel' => $model,
			'summary' => '',
			'columns' => [
                [
                	'contentOptions' => [
                    	'style' => 'font-size: 90%;',
                	],
                    'label' => Yii::t('frontend', 'Descripcion'),
                    'value' => function($data) {
                    				$tipo = ( $data['tipo'] == 'periodo=0' ) ? ' (Tasa) ' : '';
									return $data['descripcion'] . $tipo;
								},
                ],
                [
                	'contentOptions' => [
                    	'style' => 'font-size: 90%;text-align:right;',
                	],
                    'class' => 'yii\grid\ActionColumn',
            		'header'=> Yii::t('frontend', 'Deuda'),
            		'template' => '{view}',
            		'buttons' => [
                		'view' => function ($url, $model, $key) {
                				$url =  Url::to(['buscar-deuda']);
                   				return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. $model['deuda'] .'</center></div>',
                    							[
                    								'id' => 'id-deuda-por-periodo',
                    								'value' => json_encode([
                    												'view' => 1,
                    												'i' => $model['impuesto'],
                    												'idC' => $model['id_contribuyente'],
                    												'tipo' => $model['tipo'],
                    											]),
	                        						'name' => 'id',
	                        						'class' => 'btn btn-default',
	                        						'title' => 'deuda '. $model['deuda'],
	                        						//'data-url' => $url,
	                        						'style' => 'text-align:right;',
				                        		]
			                        		);
                				},
                	],
                ],
        	]
		]);?>
	</div>

	<?php ActiveForm::end(); ?>
</div>