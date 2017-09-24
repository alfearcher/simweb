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
 *  @file licencia-no-emitida-reporte.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 22-09-2017
 *
 *  @view licencia-no-emitida-reporte.php
 *  @brief vista.
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
	use yii\grid\GridView;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\bootstrap\Modal;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>
<div class="licencia--no-emitida-reporte">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-licencia-no-emitida-reporte',
			//'action' => $url,
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => false,
			'enableClientScript' => false,
		]);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 100%;padding:0px;margin:0px;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?=Html::encode($caption)?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12" style="width:100%;padding: 0px;margin: 0px;">

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
	        						'export-excel' => '/reporte/aaee/licencia/licencia-no-emitida/exportar-excel',
	        						//'export-pdf' => '/funcionario/solicitud/solicitud-asignada/quit',
	        					])
	        				?>
	        			</div>
					</div>

					<div class="row" style="width:100%;padding: 0px;margin: 0px;">
						<?= GridView::widget([
							'id' => 'id-grid-licencia-emitida-reporte',
							'dataProvider' => $dataProvider,
							'headerRowOptions' => ['class' => 'success'],
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
					            [
					                'label' => Yii::t('backend', 'ID.'),
					                'contentOptions' => [
					                	'style' => 'font-size:90%;',
					                ],
					                'format' => 'raw',
					                'value' => function($model) {
													return Html::a($model->id_contribuyente, '#', [
            																'id' => 'link-id-contribuyente',
            																'data-toggle' => 'modal',
            																'data-target' => '#modal',
            																'data-url' => Url::to(['view-contribuyente-modal',
            																							'id' => $model->id_contribuyente]),
            																'data-pjax' => 0,
            													]);
												},
					            ],
					            [
					                'label' => Yii::t('backend', 'Rif'),
					                'contentOptions' => [
					                	'style' => 'text-align:center;font-size:90%;',
					                ],
					                'format' => 'raw',
					                'value' => function($model) {
													return $model->contribuyente->naturaleza . '-' . $model->contribuyente->cedula . '-' . $model->contribuyente->tipo;
												},
					            ],
					            [
					                'label' => Yii::t('backend', 'Razón Social'),
					                'contentOptions' => [
					                	'style' => 'font-size:90%;',
					                ],
					                'format' => 'raw',
					                'value' => function($model) {
													return $model->contribuyente->razon_social;
												},
					            ],
					            [
					                'label' => Yii::t('backend', 'Domicilio'),
					                'contentOptions' => [
					                	'style' => 'font-size:90%;',
					                ],
					                'format' => 'raw',
					                'value' => function($model) {
													return $model->contribuyente->domicilio_fiscal;
												},
					            ],
					            [
					                'label' => Yii::t('backend', 'Telefono(s)'),
					                'contentOptions' => [
					                	'style' => 'font-size:90%;',
					                ],
					                'format' => 'raw',
					                'value' => function($model) {
													return $model->contribuyente->tlf_ofic . ' / ' . $model->contribuyente->tlf_ofic_otro . ' / ' . $model->contribuyente->tlf_celular;
												},
					            ],
					            [
					                'label' => Yii::t('backend', 'email'),
					                'contentOptions' => [
					                	'style' => 'font-size:90%;',
					                ],
					                'format' => 'raw',
					                'value' => function($model) {
													return $model->contribuyente->email;
												},
					            ],
					            [
				                	'contentOptions' => [
			                        	'style' => 'font-size: 90%;padding-left:15px;',
			                        ],
				                    'label' => Yii::t('backend', 'Causa de No Emisión'),
				                    'format' => 'raw',
				                    'value' => function($model) {
				                    				$nota = '';
				                    				$fuente = json_decode($model->observacion, true);
				                    				if ( count($fuente) > 0 ) {
				                    					foreach ( $fuente as $key => $obs ) {
				                    						$nota .= Html::tag('li', $obs);
				                    					}
				                    				}
            										return $nota;
    											},
				                ],
					    	]
						]);?>
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin: 0px;">

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

						<div class="col-sm-3" style="width: 25%;">
							<?= Html::submitButton(Yii::t('backend', 'Quit'),
																	  [
																		'id' => 'btn-quit',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'name' => 'btn-quit',
																		'style' => 'width: 100%;',
																	  ])
							?>
						</div>
					</div>
<!-- Fin de Rango Fecha -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


<?php
$this->registerJs(
    '$(document).on("click", "#link-id-contribuyente", (function() {
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