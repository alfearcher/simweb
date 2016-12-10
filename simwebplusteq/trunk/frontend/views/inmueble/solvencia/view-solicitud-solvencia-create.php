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
 *  @file pre-view-create-solvencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view pre-view-create-solvencia.php
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

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


<div class="view-solicitud-solvencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-view-solicitud-solvencia-form',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="width:100%;">
						<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 5px;padding-top: 0px;">
							<h4><?=Html::encode(Yii::t('frontend', $subCaption))?></h4>
						</div>
						<div class="row" id="id-solicitud-inmueble" style="padding: 0px;">
							<?= GridView::widget([
								'id' => 'id-grid-rubro-inmueble',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => [
									'class' => 'success',
								],
								'tableOptions' => [
                    				'class' => 'table table-hover table-bordered',
              					],
								'summary' => '',
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Nro. Solicitud'),
				                        'value' => function($data) {
				                                   		return $data->nro_solicitud;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Tipo Solicitud'),
				                        'value' => function($data) {
				                                   		return $data->getDescripcionTipoSolicitud($data->nro_solicitud);
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Id. Objeto'),
				                        'value' => function($data) {
				                                   		return $data->id_impuesto;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Direccion'),
				                        'value' => function($data) {
				                                   		return $data->inmueble->direccion;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Catastro'),
				                        'value' => function($data) {
				                                   		return $data->inmueble->catastro;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;width:15%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Ult. pago al monemto de crear la solicitud'),
				                        'value' => function($data) {
				                                   		return $data->ultimo_pago;
				            			           },
				                    ],
				                    [
				                        'contentOptions' => [
				                              'style' => 'font-size: 90%;',
				                        ],
				                        'label' => Yii::t('frontend', 'Condicion'),
				                        'value' => function($data) {
				                                   		return $data->estatusSolicitud->descripcion;
				            			           },
				                    ],

				          //            [
				          //               'contentOptions' => [
				          //                     'style' => 'font-size: 90%;',
				          //               ],
				          //               'format' => 'raw',
				          //               'label' => Yii::t('frontend', 'bloqueado'),
				          //               'value' => function($data) {
				          //               				$m = '';
				          //               				if ( count($data['condicion']) > 0 ) {
														// 	foreach ( $data['condicion'] as $key => $value ) {
														// 		if ( trim($m) == '' ) {
														// 			$m = Html::tag('li', $value) . '<br>';
														// 		} else {
														// 			$m = $m . Html::tag('li', $value) . '<br>';
														// 		}
														// 	}
														// }
														// return $m;
				          //   			           },
				          //           ],


					        	]
							]);?>
						</div>
					</div>

					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

					<div class="row" style="width: 100%;padding: 0px;margin-top: 20px;">

						<div class="col-sm-3" style="width: 25%;padding: 0px; padding-left: 25px;margin-left:30px;">
							<div class="form-group">
								<?= Html::a(Yii::t('frontend', 'Quit'),
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
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


