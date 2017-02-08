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
 *  @file listado-contribuyente-sin-licencia-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-02-2017
 *
 *  @view listado-contribuyente-sin-licencia-form.php
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
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="listado-contribuyente-asignado">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-contribuyente-asignado',
 			'method' => 'post',
 			// 'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>


<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;margin-top: 20px;">
						<h4><strong><?=Html::encode($subCaption)?></strong></h4>
					</div>

		        	<div class="row" style="padding:0px;padding-right:-20px;width:100%;">
						<?= GridView::widget([
								'id' => 'grid-listado-contribuyente-asignado',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                	'attribute' => 'id_contribuyente',
					                	'label' => Yii::t('backend', 'Id. Contribuyente'),
					                	'format' => 'raw',
					                    'value' => function($data) {
					                    				return $data['id_contribuyente'];
        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('backend', 'Rif'),
					                    'value' => function($data) {
                										return $data['rif'];
        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('backend', 'Suc'),
					                    'value' => function($data) {
                										return $data['sucursal'];
        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('backend', 'Contribuyente'),
					                    'value' => function($data) {
                										return $data['contribuyente'];
        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('backend', 'Licencia Asignada'),
					                    'value' => function($data) {
                										return $data['licencia'];
        											},
					                ],

					        	]
							]);?>
		        	</div>

					<div class="row" style="margin-top: 20px;padding: 0px;">
						<div class="form-group">
							<div class="col-sm-3" style="width: 30%;margin-left: 40px;">
								 <?= Html::submitButton(Yii::t('backend', 'Asignar a Otros'),[
																		'id' => 'btn-asignar-otro',
																		'class' => 'btn btn-primary',
																		'name' => 'btn-asignar-otro',
																		'value' => 3,
																		'style' => 'width: 100%;'
									])?>
							</div>

							<div class="col-sm-3" style="width: 20%;margin-left:50px;">
								 <?= Html::submitButton(Yii::t('backend', 'Quit'),[
																		'id' => 'btn-quit',
																		'class' => 'btn btn-danger',
																		'name' => 'btn-quit',
																		'value' => 1,
																		'style' => 'width: 100%;'
									])?>
							</div>
						</div>
					</div>
				</div>	<!-- Fin de col-sm-12 -->
			</div> <!-- Fin de container-fluid -->
		</div>	<!-- Fin de panel-body -->
	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->
