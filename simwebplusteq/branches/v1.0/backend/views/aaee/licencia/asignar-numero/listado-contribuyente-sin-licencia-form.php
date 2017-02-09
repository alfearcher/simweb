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

 <div class="listado-contribuyente-sin-licencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-contribuyente-sin-licencia',
 			'method' => 'post',
 			'action' => $url,
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
        			<div class="row">
        				<div class="list-group">
        					<strong><h3 class="list-group-item-heading">Indicaciones:</h3></strong>
        					<p class="list-group-item-text">
        						<?= Html::tag('li', Yii::t('backend', 'El listado corresponde a los Contribuyentes JURIDICOS ACTIVOS, con rubros cargados para el año actual y que no tienen asignado un numero de licencia.')); ?>
        						<?= Html::tag('li', Yii::t('backend', 'La filas en color rojo indican que el registro esta bloqueado por no cumplir
        						con una de las políticas establecidas para su asignación. Se puede leer los motivos de su condición en la columna Observación')); ?>
        						<?= Html::tag('li', Yii::t('backend', 'Se pueden seleccionar una o todas las filas (que no esten bloqueadas) del listado
        						para su asignación.')); ?>

        					</p>

        				</div>
        			</div>

					<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;margin-top: 20px;">
						<h4><strong><?=Html::encode($subCaption)?></strong></h4>
					</div>

		        	<div class="row" style="padding:0px;padding-right:-20px;width:100%;">
						<?= GridView::widget([
								'id' => 'grid-listado-contribuyente-sin-licencia',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'rowOptions' => function($data) {
									if ( $data['bloquear'] == 1 ) {
										return ['class' => 'danger'];
									}
								},
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkIdContribuyente',
				                        'multiple' => true,
				                        'checkboxOptions' => function ($model, $key, $index, $column) {
				                        	if ( $model['bloquear'] == 1 ) {
				                				return [
				                					'disabled' => 'disabled',
				                				];
				                			}
				                        }
				                    ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                	'attribute' => 'id_contribuyente',
					                	'label' => Yii::t('backend', 'Id. Contribuyente'),
					                	'format' => 'raw',
					                    'value' => function($data) {
					                    				return $data['id_contribuyente'];
                										// return Html::a($data['nro_solicitud'], '#', [
                										// 						'id' => 'link-view-solicitud',
                										// 						'data-toggle' => 'modal',
                										// 						'data-target' => '#modal',
                										// 						'data-url' => Url::to(['view-solicitud', 'nro' => $data['nro_solicitud']]),
                										// 						'data-solicitud' => $data['nro_solicitud'],
                										// 						'data-pjax' => 0,
                										// 	]);
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
					                    'label' => Yii::t('backend', 'Licencia'),
					                    'value' => function($data) {
                										return $data['licencia'];
        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Observacion'),
					                    'format' => 'raw',
					                    'value' => function($data, $nota) {
					                    				$nota = '';
					                    				foreach ( $data['observacion'] as $obs ) {
					                    					$nota .= Html::tag('li', $obs);
					                    				}
                										return $nota;
        											},
					                ],

					        	]
							]);?>
		        	</div>

					<div class="row" style="margin-top: 20px;padding: 0px;">
						<div class="form-group">
							<div class="col-sm-3" style="width: 30%;margin-left: 40px;">
								 <?= Html::submitButton(Yii::t('backend', 'Asignar Numero Licencia'),[
																		'id' => 'btn-asignar-numero-licencia',
																		'class' => 'btn btn-success',
																		'name' => 'btn-asignar-numero-licencia',
																		'value' => 2,
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
