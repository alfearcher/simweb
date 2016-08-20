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
 *  @file listar-ordenanza.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19-08-2016
 *
 *  @view listar-ordenanza
 *  @brief vista principal.
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
	//use yii\widgets\Pjax;
	//use yii\widgets\DetailView;

?>

<div class="listar-ordenanza-form">
 	<?php
 		$form = ActiveForm::begin([
 			'id' => 'id-listar-ordenanza-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

<?=Html::hiddenInput('id_contribuyente', $_SESSION['idContribuyente']);?>
<?=Html::hiddenInput('id_config_solicitud', $idConfig);?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 50%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 105%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($caption) ?></span>
					        </div>
					        <div class="panel-body">
<!-- DATOS DEL CONTRIBUYENTE PRINCIPAL -->
					        	<div class="row" style="padding-left: 15px; width: 100%;">
									<?= GridView::widget([
    										'id' => 'id-grid-ordenanza',
        									'dataProvider' => $dataProvider,
        									'columns' => [
        										[
        											'class' => 'yii\grid\SerialColumn',
        										],
								            	[
								                    'label' => Yii::t('frontend', 'Start'),
								                    'value' => function($data) {
                            										return $data['desde'];
                    											},
								                ],
								                [
								                    'label' => Yii::t('frontend', 'End'),
								                    'value' => function($data) {
                            										return $data['hasta'];
                    											},
								                ],
								                [
			                                    	'class' => 'yii\grid\ActionColumn',
			                                    	'header'=> Yii::t('frontend','Click: Select'),
			                                    	/*'visibledButtons' => function ($model, $key, $index) {
    																( $index == 1 ) ? true : false;
    															}*/
			                                    	'template' => '{view}',
			                                    	'buttons' => [
			                                        	'view' => function ($url, $model, $key) {
			                                            	return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',
			                                            							 ['class' => 'fa-1x'],
			                                            							 Icon::FA) .'</center></div>',
			                                                                        [
			                                                                            'value' => $key,
			                                                                            'name' => 'anoOrdenanza',
			                                                                            'title' => Yii::t('backend', $key),
			                                                                            'style' => 'margin: 0 auto; display: block;',
			                                                                        ]);
			                                        			},
			                                    	],
			                                	],
								        	]
										]);
									?>
								</div>
					        </div>
					    </div>
					</div>

<!-- Boton para salir -->
						<div class="col-sm-3" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Quit')),
																						  [
																							'id' => 'btn-quit',
																							'class' => 'btn btn-danger',
																							'value' => 1,
																							'style' => 'width: 100%',
																							'name' => 'btn-quit',
																						  ]);
								?>
							</div>
						</div>
<!-- Fin de Boton para salir -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>