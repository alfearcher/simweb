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
 *  @file licencia-emitida-consulta-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 09-06-2017
 *
 *  @view licencia-emitida-consulta-form.php
 *  @brief vista del formualario que se utilizara para consultar las licencias emitidas.
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

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\grid\GridView;
	use backend\controllers\menu\MenuController;

?>
<div class="busqueda-licencia-no-emitida">
	<?php
		$form = ActiveForm::begin([
			'id' => 'busqueda-licencia-no-emitida-form',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 80%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding:0px;margin-left: 25px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

<!-- Inicio Tipo de Licencia -->
					<div class="row" style="width: 100%;padding-top: 10px;">
						<div class="col-sm-2" style="width: 20%;margin-top: 10px;">
							<p><strong><?= $model->getAttributeLabel('tipo_licencia') ?></strong></p>
						</div>
						<div class="col-sm-2" style="width: 25%;">
							 <?= $form->field($model, 'tipo_licencia')
							          ->dropDownList($listaTipoLicencia, [
                                                           	'id'=> 'id-tipo-licencia',
                                                           	'prompt' => Yii::t('backend', 'Select'),
                                                           	'style' => 'width:260px;',
                                                           ])->label(false);
                            ?>
						</div>
					</div>
<!-- Fin de Tipo de Licencia -->

<!-- Contribuyente -->
					<div class="row" style="width: 100%;">
						<div class="col-sm-2" style="width: 20%;margin-top: 10px;">
							<p><strong><?= $model->getAttributeLabel('id_contribuyente') ?></strong></p>
						</div>
						<div class="col-sm-2" style="width: 25%;">
							<?= $form->field($model, 'id_contribuyente')->textInput([
																			'id' => 'id-contribuyente',
																			'style' => 'width: 100%;'
																		])->label(false) ?>
						</div>
					</div>
					<div class="row" style="width: 100%;margin:0px;padding:0px;margin-left:165px;">
						<?= Html::activeCheckbox($model, 'todos_contribuyentes', [
																			'label' => $model->getAttributeLabel('todos_contribuyentes'),
																			'labelOptions' => [
																				'style' => 'width:100%;',
																			],
																		]);
						?>
					</div>
<!-- Fin de Contribuyente -->

<!-- Contribuyente -->
					<div class="row" style="width: 100%;border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Indique la(s) causa(s) de la NO EMISION')?></strong></h4>
					</div>

					<div class="row" style="width:100%;">
						<?= GridView::widget([
								'id' => 'id-grid-causa-no-emision-licencia',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'columns' => [
									//['class' => 'yii\grid\SerialColumn'],
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkCausa',
				                        'multiple' => true,
				                        'checkboxOptions' => function ($model, $key, $index, $column) {
				                        }
				                    ],
						            [
						                'label' => Yii::t('backend', 'Id'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;text-align:center;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
						                				return $model->id_causa;
													},
						            ],
						            [
						                'label' => Yii::t('backend', 'Descripcion'),
						                'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
						                'format' => 'raw',
						                'value' => function($model) {
						                				return $model->descripcion;
													},
						            ],
						    	]
							]);
						?>
						<?php if (trim($mensajeCausa) !== '' ) {?>
							<div class="row" style="padding:0px;margin:0px;color:red;font-weight:bold;">
								<?=Html::encode($mensajeCausa);?>
							</div>
						<?php } ?>
					</div>


					<div class="row" style="width:100%;padding-top: 20px;">
						<div class="col-sm-3" style="width:25%;float: right;">
							<?= Html::submitButton(Yii::t('backend', 'Search'),
																	  [
																		'id' => 'btn-search-request',
																		'class' => 'btn btn-primary',
																		'value' => 1,
																		'name' => 'btn-search-request',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>
					</div>

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


