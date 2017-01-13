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
 *  @file listado-solicitud-declaracion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-08-2016
 *
 *  @view listado-solicitud-declaracion.php
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

 	//use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\widgets\Pjax;
	use backend\models\aaee\listado\ListadoSolicitudDeclaracion;
	use yii\bootstrap\Modal;
	use backend\controllers\menu\MenuController;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="listado-solicitud-declaracion-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-solicitud-declaracion-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 110%;">
        <div class="panel-heading">
        	<h3><?= Html::encode('Listado de Solicitudes de Declaraciones') ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<?= GridView::widget([
							'id' => 'grid-lista',
							'dataProvider' => $dataProvider,
							'headerRowOptions' => ['class' => 'success'],
							'showFooter' => true,
							'footerRowOptions' => [
								'style' => 'font-weight: bold;text-align:right;'
							],
							//'filterModel' => $listadoModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								//'nro_solicitud',
								[
		                            'label' => 'Nro. Solicitud',
		                            'format' => 'raw',
		                            'value' => function($data) {
		                            	return Html::a($data['nro_solicitud'], '#', [
																	'id' => 'link-view-solicitud',
														            //'class' => 'btn btn-success',
														            'data-toggle' => 'modal',
														            'data-target' => '#modal',
														            'data-url' => Url::to(['view-detalle-solicitud', 'nro' => $data['nro_solicitud']]),
														            'data-solicitud' => $data['nro_solicitud'],
														            'data-pjax' => '0',
														        ]);
		                            	},
		                        ],
								'fecha_hora_creacion',
								'ano_impositivo',
								'descripcion',
								'condicion',
								'id_contribuyente',
								[
				                    'label' => Yii::t('frontend', 'Contribuyente'),
				                    'value' => function($data) {
				                    				$listado = New ListadoSolicitudDeclaracion();
            										return $listado->getContribuyente($data['id_contribuyente']);
    											},
				                ],
								[
			                    	'label' => Yii::t('frontend', 'Suma Declaracion'),
			                    	'contentOptions' => [
			                    		'style' => 'text-align: right',
			                    	],
				                    'value' => function($data) {
				                    		return Yii::$app->formatter->asDecimal($data['suma'],2);
				                    },
				                    'footer' => Yii::$app->formatter->asDecimal($totalDeclarado, 2),
				                ],
								[
				                    'label' => Yii::t('frontend', 'Liquidado'),
				                    'contentOptions' => [
				                    	'style' => 'text-align: right',
				                    ],

				                    'value' => function($data) {
				                    				$tipo = 0;
				                    				if ( $data['tipo_declaracion'] == 1 ) {
				                    					$tipo = 0;
				                    				} elseif ( $data['tipo_declaracion'] == 2 ) {
				                    					$tipo = 1;
				                    				}

				                    				$listado = New ListadoSolicitudDeclaracion();
				                    				$suma = $listado->getMontoLiquidacion($tipo, $data['ano_impositivo'], $data['id_contribuyente']);
            										return Yii::$app->formatter->asDecimal($suma, 2);
    											},
				                ],

				        	]
						]);?>
					</div>

					<div class="col-sm-3" style="margin-left: 50px;">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('backend', 'Back'),
																	  [
																		'id' => 'btn-back-form',
																		'class' => 'btn btn-danger',
																		'value' => 1,
																		'style' => 'width: 100%',
																		'name' => 'btn-back-form',
																	  ])
							?>
						</div>
					</div>
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->
		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->



<?php
$this->registerJs(
    '$(document).on("click", "#link-view-solicitud", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $(".detalle-solicitud").html(data);
                $("#modal").modal();
            }
        );
    }));

    '
); ?>


<style type="text/css">
	.modal-content	{
			margin-top: 150px;
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
echo "<div class='detalle-solicitud'></div>";
Pjax::end();
Modal::end();
?>