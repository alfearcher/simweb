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
 *  @file lista-combo-impuesto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view lista-combo-impuesto vista que muestra una lista-combo de u=impuesto
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
	use yii\widgets\DetailView;
	use yii\bootstrap\Collapse;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="lista-combo">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-view-lista-impuesto',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<?=$form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width:105%;">
						<div class="col-sm-3" style="width:45%;">
							<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width:100%;">
								<div class="col-sm-3" style="width: 100%;">
									<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
								</div>
							</div>

							<div class="row" style="padding-left: 0px;padding-top: 10px;width:100%;">
								<div class="col-sm-3" style="padding: 0px; width: 60%;">
									<div class="lista-impuesto" style="padding: 0px;">
					                        <?= $form->field($model, 'impuesto')->dropDownList($listaImpuesto,[
					                                                                           	'prompt' => Yii::t('backend', 'Select'),
					                                                                            'style' => 'width:100%;'
					                                                                         ])->label(false)
					            			?>
			        				</div>
			        			</div>

			        			<div class="col-sm-2" style="padding: 0px; width: 40%;padding-left: 20px;">
									<?= Html::submitButton(Yii::t('frontend', 'Aceptar'),
																				  	[
																						'id' => 'btn-search-planillas',
																						'class' => 'btn btn-success',
																						'value' => 1,
																						'style' => 'width: 100%;',
																						'name' => 'btn-search-planillas',

																				  	])
									?>
			        			</div>
			        		</div>
			        	</div>

			        	<div class="col-sm-5" style="width: 55%;">
			        		<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding-top: 0px;width:100%;">
								<div class="col-sm-3" style="width: 100%;">
									<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
								</div>
							</div>

							<div class="row" style="padding: 0px;padding-top: 10px;width: 100%;">
								<div class="col-sm-3" style="width: 100%;padding: 0px;">
									<?php echo Collapse::widget([
    'items' => [
        // equivalent to the above
        [
            'label' => 'Collapsible Group Item #1',
            'content' => 'Anim pariatur cliche...',
            // open its content by default
            //'contentOptions' => ['class' => 'in']
        ],
        // another group item
        [
            'label' => 'Collapsible Group Item #1',
            'content' => 'Anim pariatur cliche...',
            'contentOptions' => [],
            'options' => [],
        ],
        // if you want to swap out .panel-body with .list-group, you may use the following
        [
            'label' => 'Collapsible Group Item #1',
            'content' => "kk",


            'contentOptions' => [],
            'options' => [],
            'footer' => 'Footer' // the footer label in list-group
        ],
    ]
]); ?>
								</div>
							</div>
			        	</div>

		        	</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">

							<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
								<div class="form-group">
									<?= Html::a(Yii::t('frontend', Yii::t('frontend', 'Quit')),
																					['quit'],
																				  	[
																						'id' => 'btn-quit',
																						'class' => 'btn btn-danger',
																						'value' => 1,
																						'style' => 'width: 100%;',
																						'name' => 'btn-quit',

																				  	])
									?>
								</div>
							</div>

						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


