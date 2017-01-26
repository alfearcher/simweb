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
 *  @file listado-propaganda.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-01-2017
 *
 *  @view listado-propaganda.php
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

 <div class="listado-propaganda">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-propaganda-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode(Yii::t('frontend', 'Listado de Propaganda')) ?></h3>
        </div>

	<!-- <?//= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $model->id_contribuyente])->label(false); ?> -->


<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row" id="listado" style="padding:0px; width: 100%;">
						<?= GridView::widget([
								'id' => 'grid-listado-propaganda',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'rowOptions' => function($model) {
									if ( $model->inactivo == 1 ) {
										return ['class' => 'danger'];
									}
								},
								'filterModel' => $listadoPropaganda,
								'columns' => [
									//['class' => 'yii\grid\SerialColumn'],
					            	[
					            		'attribute' => 'id_impuesto',
					                    'label' => Yii::t('frontend', 'Id'),
					                    'value' => function($data) {
                										return $data->id_impuesto;
        											},
					                ],
					                [
					                	'attribute' => 'nombre_propaganda',
					                	'label' => Yii::t('frontend', 'Nombre'),
					                	'format' => 'raw',
					                    'value' => function($data) {
                										return Html::a($data->nombre_propaganda, '#', [
                																'id' => 'link-view-propaganda',
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-propaganda', 'p' => $data->id_impuesto]),
                																'data-propaganda' => $data->id_impuesto,
                																'data-pjax' => 0,
                											]);
        											},
					                ],
					               	[
					               		'attribute' => 'clase_propaganda',
					                    'label' => Yii::t('frontend', 'Clase'),
					                    'value' => function($data) {
                										return $data->clase->descripcion;
        											},
					                ],
					                [
					                	'attribute' => 'uso_propaganda',
					                    'label' => Yii::t('frontend', 'Uso'),
					                    'value' => function($data) {
                										return $data->uso->descripcion;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Tipo'),
					                    'value' => function($data) {
                										return $data->tipoPropaganda->descripcion;
        											},
					                ],

					        	]
							]);?>
		        	</div>

					<div class="row">
						<div class="form-group">

							<div class="col-sm-3" style="margin-left: 150px;">
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


<?php
$this->registerJs(
    '$(document).on("click", "#link-view-propaganda", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle-propaganda").html(data);
                $("#modal").modal();
            }
        );
    }));

    '
); ?>


<style type="text/css">
	.modal-content	{
			margin-top: 110px;
			margin-left: -180px;
			width: 150%;
	}
</style>

<?php
Modal::begin([
    'id' => 'modal',
    //'header' => '<h4 class="modal-title">Complete</h4>',
    'size' => 'modal-lg',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Cerrar</a>',
]);

//echo "<div class='well'></div>";
Pjax::begin();
echo "<div class='detalle-propaganda'></div>";
Pjax::end();
Modal::end();
?>