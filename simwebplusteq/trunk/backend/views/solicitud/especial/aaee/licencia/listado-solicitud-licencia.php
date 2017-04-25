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
 *  @file listado-solicitud-licencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-01-2017
 *
 *  @view listado-solicitud-licencia.php
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
	use backend\models\solicitud\especial\aaee\licencia\BusquedaSolicitudLicenciaForm;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

 <div class="listado-solicitud-licencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-solicitud-licencia',
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
        	<h3><?= Html::encode(Yii::t('frontend', 'Listado de Solicitudes de Licencias')) ?></h3>
        </div>


<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">
        			<div class="row">
        				<div class="list-group">
        					<strong><h3 class="list-group-item-heading"><?=Yii::t('backend', 'Indicaciones');?></h3></strong>
        					<p class="list-group-item-text">
        						<?= Html::tag('li', Yii::t('backend', 'El listado corresponde a las solicitudes de EMISION DE LICENCIAS (Nuevas y/o Renovadas).')); ?>
        						<?= Html::tag('li', Yii::t('backend', 'La filas en color rojo indican que la solicitud esta bloqueada por no cumplir
        						con una de las políticas establecidas para su aprobación. Se puede leer los motivos de su condición en la columna Observación')); ?>
        						<?= Html::tag('li', Yii::t('backend', 'Se pueden seleccionar una o todas las filas (que no esten bloqueadas) del listado
        						para su aprobación.')); ?>
        						<?= Html::tag('li', Yii::t('backend', 'Una vez aprobada la solicitud el Contribuyente podrá descargar su licencia a través
        						de la opción <strong>Solicitudes/Actividades Económicas/Licencia/Descargar Licencia.</strong>')); ?>

        					</p>

        				</div>
        			</div>

		        	<div class="row" style="padding:0px;padding-right:-20px;width:103%;">
						<?= GridView::widget([
								'id' => 'grid-listado-licencia',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'rowOptions' => function($data) {
									if ( $data['bloquear'] == 1 ) {
										return ['class' => 'danger'];
									}
								},
								'columns' => [
									//['class' => 'yii\grid\SerialColumn'],

									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'name' => 'chkNroSolicitud',
				                        'multiple' => $soloLectura ? false : true,
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
					                	'attribute' => 'nro_solicitud',
					                	'label' => Yii::t('backend', 'Nro Solicitud'),
					                	'format' => 'raw',
					                    'value' => function($data) {
					                    				return $data['nro_solicitud'];
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
					                    'label' => Yii::t('frontend', 'Feha y Hora'),
					                    'value' => function($data) {
                										return $data['fecha_hora'];
        											},
					                ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Tipo/Licencia'),
					                    'value' => function($data) {
                										return $data['tipo'];
        											},
					                ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Año'),
					                    'value' => function($data) {
                										return $data['ano_impositivo'];
        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'ID.'),
					                    'format' => 'raw',
					                    'value' => function($data) {
                										//return $data['id_contribuyente'];
                										return Html::a($data['id_contribuyente'], '#', [
                																'id' => 'link-id-contribuyente',
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-pre-licencia-modal',
                																					    'nro' => $data['nro_solicitud'],
                																						'id' => $data['id_contribuyente'],
                																				 		'a' => $data['ano_impositivo'], 'p' => 1]),
                																'data-ano-impositivo' => $data['ano_impositivo'],
                																'data-periodo' => 1,
                																'data-pjax' => 0,
                													]);

        											},
					                ],

					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Contribuyente'),
					                    'value' => function($data) {
                										return $data['contribuyente'];
        											},
					                ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Nro. Licencia'),
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
					               	[
					               		'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Planilla'),
					                    'format' => 'raw',
					                    'value' => function($data) {

                									return Html::a($data['planilla'], '#', [
                																'id' => 'link-view-planilla',
                																'data-toggle' => 'modal',
                																'data-target' => '#modal',
                																'data-url' => Url::to(['view-planilla', 'p' => $data['planilla']]),
                																'data-planilla' => $data['planilla'],
                																'data-pjax' => 0,
                													]);

        											},
					              	],

					        	]
							]);?>
		        	</div>

					<?php
						$disabled = false;
						if ( $soloLectura ) {
							$disabled = true;
						}
					 ?>
					<div class="row">
						<div class="form-group">
							<div class="col-sm-3" style="width: 30%;margin-left: 40px;">
								 <?= Html::submitButton(Yii::t('backend', 'Aprobar Solicitud(es) Seleccionada(s)'),[
																		'id' => 'btn-aprobar',
																		'class' => 'btn btn-success',
																		'name' => 'btn-aprobar',
																		'value' => 2,
																		'style' => 'width: 100%;',
																		'disabled' => $disabled,

									])?>
							</div>

							<div class="col-sm-3" style="width: 20%;margin-left: 70px;">
								 <?= Html::submitButton(Yii::t('backend', 'Back'),[
																		'id' => 'btn-back',
																		'class' => 'btn btn-danger',
																		'name' => 'btn-back',
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


<?php
$this->registerJs(
    '$(document).on("click", "#link-view-planilla", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle").html(data);
                $("#modal").modal();
            }
        );
    }));

	$(document).on("click", "#link-id-contribuyente", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle").html(data);
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
echo "<div class='detalle'></div>";
Pjax::end();
Modal::end();
?>