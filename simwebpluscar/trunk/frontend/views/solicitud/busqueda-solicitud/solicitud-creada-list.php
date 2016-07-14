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
 *  @file solicitud-creada-list.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-07-2016
 *
 *  @view solicitud-creada-list.php
 *  @brief vista que muestra un listado de solicitudes creadas y pendientes.
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
<div class="solicitud-creada-list">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-solicitud-creada-list',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>
	<?= $form->field($model, 'id_contribuyente')->hiddenInput([
															'id' => 'id-contribuyente',
															'value' => $model->id_contribuyente,
														])->label(false);?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 80%;">
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
	        		<?= MenuController::actionMenuSecundario($opciones); ?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Html::encode($caption) ?></strong></h4>
					</div>

<!-- Inicio lista de funcionarios -->
					<div class="row">
						<div class="lista-solicitud-creada">
							<?= GridView::widget([
								'id' => 'id-lista-solicitud-creada',
								'dataProvider' => $dataProvider,
								//'filterModel' => $model,
								'headerRowOptions' => ['class' => 'success'],
								//'caption' => Yii::t('backend', 'List of Request Taxpayer'),
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
                					// [
                					// 	'label' => Yii::t('backend', 'Id. Taxpayer'),
                					// 	'value' => function($model) {
                					// 		return $model->id_contribuyente;
                					// 	}
                					// ],
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


<!-- Fin de busqueda de todos los funcionarios -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


