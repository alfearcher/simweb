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
	use backend\controllers\menu\MenuController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="propaganda-existente">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-propaganda-existente',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?=Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">
        			<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<div class="col-sm-2" style="width: 25%;padding: 0px;margin: 0px;margin-top: 10px;">
							<h4><strong><?=Html::encode($subCaption)?></strong></h4>
						</div>
						<div class="col-sm-3" style="width: 30%;float:right;padding: 0px;margin: 0px;">
	    					<style type="text/css">
								.col-sm-3 > ul > li > a:hover {
									background-color: #ECF1EF;
								}
							</style>
	        				<?= MenuController::actionMenuSecundario([
	        						'export-excel' => '/reporte/propaganda/general/propaganda-general/exportar-excel',
	        						//'export-pdf' => '/funcionario/solicitud/solicitud-asignada/quit',
	        					])
	        				?>
	        			</div>
					</div>
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
								//'filterModel' => $model,
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
					            	[
					            		'attribute' => 'id_impuesto',
					                    'label' => Yii::t('frontend', 'Id. Propaganda'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return $data->id_impuesto;
        											},
					                ],
					                [
					                	'attribute' => 'nombre_propaganda',
					                	'label' => Yii::t('frontend', 'Nombre'),
					                	'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
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
					               		'attribute' => 'fecha_hora',
					                    'label' => Yii::t('frontend', 'Fecha'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return date('d-m-Y', strtotime($data->fecha_hora));
        											},
					                ],
					               	[
					               		'attribute' => 'clase_propaganda',
					                    'label' => Yii::t('frontend', 'Clase'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return $data->clase->descripcion;
        											},
					                ],
					                [
					                	'attribute' => 'uso_propaganda',
					                    'label' => Yii::t('frontend', 'Uso'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return $data->uso->descripcion;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Tipo'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return $data->tipoPropaganda->descripcion;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Condición'),
					                    'contentOptions' => [
						                	'style' => 'text-align:center;font-size:90%;',
						                ],
					                    'value' => function($data) {
                										if ( $data->inactivo == 1 ) {
                											return 'INACTIVO';
                										} else {
                											return 'ACTIVO';
                										}
        											},
					                ],
					                [
					                	'attribute' => 'id_contribuyente',
					                    'label' => Yii::t('frontend', 'ID. Cont.'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return $data->id_contribuyente;
        											},
					                ],
					                 [
					                    'label' => Yii::t('frontend', 'Contribuyente'),
					                    'contentOptions' => [
						                	'style' => 'font-size:90%;',
						                ],
					                    'value' => function($data) {
                										return $data->contribuyenteName;
        											},
					                ],
					                [
					                    'label' => Yii::t('frontend', 'Condición'),
					                    'contentOptions' => [
						                	'style' => 'text-align:center;font-size:90%;',
						                ],
					                    'value' => function($data) {
                										if ( $data->contribuyente->inactivo == 1 ) {
                											return 'INACTIVO';
                										} else {
                											return 'ACTIVO';
                										}
        											},
					                ],
					        	]
							]);?>
		        	</div>

					<div class="row">
						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Back'),
																	  [
																		'id' => 'btn-back',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'name' => 'btn-back',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>
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