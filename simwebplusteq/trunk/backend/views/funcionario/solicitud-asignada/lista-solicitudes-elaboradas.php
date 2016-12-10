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
 *  @file lista-funcionario-vigente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view lista-funcionario-vigente.php
 *  @brief vista del formualario que se utilizara para capturar los datos a guardar.
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

 	//session_start();		// Iniciando session

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use backend\controllers\menu\MenuController;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>
<div class="lista-funcionario-por-solicitud">
	<?php
		$form = ActiveForm::begin([
			'id' => 'lista-funcionario-por-solicitud-form',
		    'method' => 'post',
		    'action' => $url,
			'enableClientValidation' => true,
			//'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>

	<?=
		// Variable que me indica el tipo de listado generado
		// 1 => listado por departamento y unidades
		// 2 => listado de todos (all).
		$form->field($model, 'listado')->hiddenInput(['value' => $listado])->label(false);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 115%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
        			<style type="text/css">
					.col-sm-3 > ul > li > a:hover {
						background-color: #F5F5F5;
					}
    			</style>
	        		<?= MenuController::actionMenuSecundario([
	        						'back' => '/funcionario/solicitud/solicitud-asignada/index',
	        						'quit' => '/funcionario/solicitud/solicitud-asignada/quit',
	        			])
	        		?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Search Official: ') . $subCaption; ?></strong></h4>
					</div>

<!-- Inicio lista de funcionarios -->
					<div class="row">
						<div class="lista-funcionario">
							<?= GridView::widget([
								'id' => 'id-lista-solicitud-elaborada',
								'dataProvider' => $dataProvider,
								//'filterModel' => $model,
								'headerRowOptions' => ['class' => 'success'],
								'caption' => Yii::t('backend', 'List of Request Taxpayer'),
								//'summary' => '{begin} - {end} {count} {totalCount} {page} {pageCount}',
								'summary' => Yii::t('backend', 'Total Register') . ': ' . ' {totalCount}' . ' - ' . Yii::t('backend', 'page') . ': ' . '{page}' . ' ' . Yii::t('backend', 'of') . ' ' . '{pageCount}',
								'columns' => [
                					[
                						'label' => Yii::t('backend', 'Request No.'),
                						'value' => function($model, $key, $index, $colum) {
                							return $model->nro_solicitud;
                						}
                					],
									[
                						'label' => Yii::t('backend', 'Date/Hour'),
                						'value' => function($model) {
                							return $model->fecha_hora_creacion;
                						}
                					],
                					[
                						'label' => Yii::t('backend', 'Request'),
                						'value' => function($model) {
                							return $model->tipoSolicitud->descripcion;
                						}
                					],
                					[
                						'label' => Yii::t('backend', 'Tax'),
                						'value' => function($model) {
                							return $model->impuestos->descripcion;
                						}
                					],
                					[
                						'label' => Yii::t('backend', 'Condition'),
                						'value' => function($model) {
                							return $model->estatusSolicitud->descripcion;
                						}
                					],
                					// [
                					// 	'label' => Yii::t('backend', 'User'),
                					// 	'value' => function($model) {
                					// 		return $model->usuario;
                					// 	}
                					// ],
                					[
                						'label' => Yii::t('backend', 'Id. Taxpayer'),
                						'value' => function($model) {
                							return $model->id_contribuyente;
                						}
                					],
                					[
                                    	'class' => 'yii\grid\ActionColumn',
                                    	'header'=> Yii::t('backend','OK'),
                                    	'template' => '{view}',
                                    	'buttons' => [
                                        	'view' => function ($url, $model, $key) {
                                            	return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',
                                            							 ['class' => 'fa-1x'],
                                            							 Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('backend', $key),
                                                                            'style' => 'margin: 0 auto; display: block;',
                                                                        ]);
                                        			},
                                    	],
                                	],

								],
							]);
						?>
						</div>
					</div>
<!-- Fin de lista de funcionario -->


					<!-- Inicio de boton -->
					<!-- <div class="col-sm-3">
						<div class="form-group">
							<?//= Html::submitButton(Yii::t('backend', 'Remove Request'),
												  //[
													//'id' => 'btn-remove-request',
													//'class' => 'btn btn-success',
													//'value' => 1,
													//'name' => 'btn-remove-request',
												//	'style' => 'width: 100%;',
												  //])
							?>
						</div>
					</div> -->
<!--  -->




				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->


	<?php ActiveForm::end(); ?>
</div>


