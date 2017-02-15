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
	use backend\models\solicitud\especial\aaee\licencia\BusquedaSolicitudLCertificadoForm;

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
        	<h3><?= Html::encode(Yii::t('frontend', 'Confirme Aprobación de Listado de Solicitudes de Certificados Catastrales')) ?></h3>
        </div>


<!-- Cuerpo del formulario -->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12">
		        	<div class="row" style="padding:0px;padding-right:-20px;width:103%;">
						<?= GridView::widget([
								'id' => 'grid-listado-licencia-seleccionada',
								'dataProvider' => $dataProvider,
								'headerRowOptions' => ['class' => 'success'],
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],
									[
				                        'class' => 'yii\grid\CheckboxColumn',
				                        'multiple' => false,
				                        'name' => 'chkNroSolicitud',
				                        'checkboxOptions' => function ($model, $key, $index, $column) {

			                				return [
			                					'onClick' => 'javascript: return false;',
					                            'checked' => true,
			                				];

				                        }
				                    ],
					                [
					                	'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					                	'attribute' => 'nro_solicitud',
					                	'label' => Yii::t('backend', 'Nro Solicitud'),
					                	//'format' => 'raw',
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
					                	'attribute' => 'id_contribuyente',
					                    'label' => Yii::t('frontend', 'ID.'),
					                    'value' => function($data) {
                										return $data['id_contribuyente'];
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
					                    'label' => Yii::t('frontend', 'Nro. Certificado'),
					                    'value' => function($data) {
                										return $data['licencia'];
        											},
					                ],
					              //   [
					              //   	'contentOptions' => [
				               //          	'style' => 'font-size: 90%;',
				               //          ],
					              //       'label' => Yii::t('frontend', 'Observacion'),
					              //      'format' => 'raw',
					              //       'value' => function($data, $nota) {
					              //       				$nota = '';
					              //       				foreach ( $data['observacion'] as $obs ) {
					              //       					$nota .= Html::tag('li', $obs);
					              //       				}
                			// 							return $nota;
        											// },
					              //   ],
					               	[
					               		'contentOptions' => [
				                        	'style' => 'font-size: 90%;',
				                        ],
					               		'attribute' => 'planilla',
					                    'label' => Yii::t('frontend', 'Planilla'),
					                    'value' => function($data) {
                										return $data['planilla'];
        											},
					              	],

					        	]
							]);?>
		        	</div>

					<div class="row">
						<div class="form-group">
							<div class="col-sm-3" style="width: 35%;margin-left: 40px;">
								 <?= Html::submitButton(Yii::t('backend', 'Confirmar Aprobar Solicitud(es) Seleccionada(s)'),[
																		'id' => 'btn-confirmar-aprobar',
																		'class' => 'btn btn-success',
																		'name' => 'btn-confirmar-aprobar',
																		'value' => 9,
																		'style' => 'width: 100%;'
									])?>
							</div>

							<div class="col-sm-3" style="width: 20%;margin-left: 70px;">
								 <?= Html::submitButton(Yii::t('backend', 'Back'),[
																		'id' => 'btn-back',
																		'class' => 'btn btn-danger',
																		'name' => 'btn-back',
																		'value' => 3,
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




