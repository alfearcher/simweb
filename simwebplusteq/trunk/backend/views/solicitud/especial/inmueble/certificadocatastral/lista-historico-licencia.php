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
 *  @file lista-historico-licencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-01-2017
 *
 *  @view lista-historico-licencia.php
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

 <div class="listado-historico-licencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-historico-licencia',
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
        	<h3><?= Html::encode(Yii::t('frontend', 'Registros Guardados')) ?></h3>
        </div>


<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row" style="padding:0px;padding-right:-20px;width:103%;">
						<?= GridView::widget([
								'id' => 'grid-listado-historico-licencia',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                	'label' => Yii::t('backend', 'Id Historico'),
					                    'value' => function($data) {
                										return $data['id_historico_certificados_catastrales'];
        											},
					                ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                	'label' => Yii::t('backend', 'Nro Solicitud'),
					                    'value' => function($data) {
                										return $data['nro_solicitud'];
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
					                    'label' => Yii::t('frontend', 'Tipo de Certificado'),
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
					                    'value' => function($data) {
                										return $data['id_contribuyente'];
        											},
					                ],
					              //   [
					              //   	'contentOptions' => [
				               //          	'style' => 'font-size: 90%;',
				               //          ],
					              //       'label' => Yii::t('frontend', 'Contribuyente'),
					              //       'value' => function($data) {
                			// 							return $data['contribuyente']['razon_social'];
        											// },
					              //   ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Nro. Certificado'),
					                    'value' => function($data) {
                										return $data['certificado_catastral'];
        											},
					                ],

					               	[
					               		'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                    'label' => Yii::t('frontend', 'Serial Control'),
					                    'value' => function($data) {
                										return $data['serial_control'];
        											},
					              	],

					        	]
							]);?>
		        	</div>

					<div class="row">
						<div class="form-group">

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




