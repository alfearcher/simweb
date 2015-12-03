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
 *  @file correccion-capital-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-11-2015
 *
 *  @view correccion-capital-form
 *  @brief vista principal del cambio o correccion del capital
 *
 */


 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\MaskedInput;
	use yii\widgets\Pjax;
	//use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use common\models\contribuyente\ContribuyenteBase;
	use yii\i18n\Formatter;
?>


<div class="correccion-capital-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'correccion-capital-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 98%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Infomation of Main Tarpayer')) ?></span>
					        </div>
					        <div class="panel-body">
<!-- DATOS DEL CONTRIBUYENTE PRINCIPAL -->
					        	<div class="row">
<!-- Id Contribuyente -->
									<div class="col-sm-2" style="margin-left: 15px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('id_contribuyente')) ?></i></p>
										</div>
										<div class="row">
											<div class="id-contribuyente">
												<?= $form->field($model, 'id_contribuyente')->textInput([
																									'id' => 'id_contribuyente',
																									'style' => 'width:100%;',
																									'value' => $datosContribuyente[0]['id_contribuyente'],
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Id Contribuyente -->

<!-- Rif o Cedula del Contribuyente -->
									<div class="col-sm-1" style="margin-left: 20px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'DNI') ?></i></p>
										</div>
										<div class="row">
											<div class="naturaleza">
												<?= $form->field($model, 'naturaleza')->textInput([
																									'id' => 'naturaleza',
																									'style' => 'width:50%;',
																									'value' => $datosContribuyente[0]['naturaleza'],
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>


									<div class="col-sm-2" style="margin-left: -37px;margin-top: 20px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', '') ?></i></p>
										</div>
										<div class="row">
											<div class="cedula">
												<?= $form->field($model, 'cedula')->textInput([
																								'id' => 'cedula',
																								'style' => 'width:100%;',
																								'value' => $datosContribuyente[0]['cedula'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>

									<div class="col-sm-1" style="margin-left: 3px;margin-top: 20px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', '') ?></i></p>
										</div>
										<div class="row">
											<div class="tipo">
												<?= $form->field($model, 'tipo')->textInput([
																								'id' => 'tipo',
																								'style' => 'width:50%;',
																								'value' => $datosContribuyente[0]['tipo'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Rif o Cedula del Contribuyente -->

<!-- Razon Social Vieja-->
									<div class="col-sm-6" style="margin-left: -20px;margin-top: 0px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Company Name') ?></i></p>
										</div>
										<div class="row">
											<div class="razon-social">
												<?= $form->field($model, 'razon_social')->textInput([
																								'id' => 'razon-social',
																								'style' => 'width:100%;',
																								'value' => $datosContribuyente[0]['razon_social'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Razon Social Vieja-->
								</div>	<!-- fin de Row -->


								<div class="row">
<!-- Capital Viejo -->
									<div class="col-sm-6" style="margin-left: 15px;margin-top: 0px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?= $model->getAttributeLabel('capital_v') ?></i></p>
										</div>
										<div class="row">
											<div class="capital-v">
												<?= $form->field($model, 'capital_v')->textInput([
																								'id' => 'capital-v',
																								'style' => 'width:40%;',
																								'value' => Yii::$app->formatter->asDecimal($datosContribuyente[0]['capital']),
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Capital Viejo -->
								</div>   <!-- Fin de row capital -->
					        </div>
					    </div>
					</div>

<!-- Contribuyentes asociados al rif o cedula -->
					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Related Taxpayers')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
	        						<div class="col-sm-12">
		        						<div class="contribuyente-asociado">
	    									<?= GridView::widget([
	    										'id' => 'grid-contribuyente-asociado',
	        									'dataProvider' => $dataProvider,
	        									//'filterModel' => $model,
	        									//'layout'=>"\n{pager}\n{summary}\n{items}",
	        									'columns' => [
	        										//['class' => 'yii\grid\SerialColumn'],

									            	[
									                    'label' => Yii::t('backend', 'ID.'),
									                    'value' => 'id_contribuyente',
									                ],
									                [
									                    'label' => Yii::t('backend', 'DNI'),
									                    'value' => function($data) {
	                            										return ContribuyenteBase::getCedulaRifDescripcion($data->tipo_naturaleza, $data->naturaleza, $data->cedula, $data->tipo);
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', Yii::t('backend', 'Company Name')),
									                    'value' => function($data) {
	                            										return $data->razon_social;
	                    											},
									                ],
									                [
									                    'label' => $model->getAttributeLabel('capital_v'),
									                    'format'=>['decimal',2],
									                    'value' => function($data) {
	                            										return $data->capital;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'License No.'),
									                    'value' => function($data) {
	                            										return $data->id_sim;
	                    											},
									                ],
									                ['class' => 'yii\grid\CheckboxColumn',
														                 'options' => [
														                 		'checked' => 'checked',
														                 ],
									                ],
									        	]
											]);?>
										</div>
		        					</div>
		        				</div>
		        				<div class="row" style="color: red; margin-left: 15px;">
		        					<p><?= Html::encode($msjErrorLista) ?></p>
		        				</div>
	        				</div>
	        			</div>
					</div>
<!-- Fin de Contribuyentes asociados a rif o cedula -->


<!-- CAPITAL NUEVO -->
					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'New Capital')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">

<!-- Capital Nuevo -->
									<div class="col-sm-6" style="margin-left: 15px;margin-top: 0px">
										<div class="row">
											<div class="capital-new">
												<?= $form->field($model, 'capital_new')->textInput([
																								'id' => 'capital-new',
																								'style' => 'width:40%;',
																								//'name' => 'ca',																								//'onChange' => 'fo()',
																								//'value' => Yii::$app->formatter->asDecimal(0),
																								//'mask' => '999.999.999.999,99',
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Capital Nuevo -->


<!-- Boton para aplicar la actualizacion -->
									<div class="col-sm-2">
										<div class="form-group">
											<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Execute Update of Capital')),
																									  [
																										'id' => 'btn-update',
																										'class' => 'btn btn-success',
																										'name' => 'btn-update',
																										'value' => 1,
																									  ])
											?>
										</div>
									</div>
<!-- Fin de Boton para aplicar la actualizacion -->

									<div class="col-sm-1" style="width: 15%;"></div>

<!-- Boton para salir de la actualizacion -->
									<div class="col-sm-2">
										<div class="form-group">
											<?= Html::a(Yii::t('backend', 'Quit'), ['quit'], ['class' => 'btn btn-danger']) ?>
										</div>
									</div>
<!-- Fin de Boton para salir de la actualizacion -->


	        					</div>
	        				</div>
	        			</div>
					</div>
<!-- FIN DE CAPITAL NUEVO -->


				</div>
			</div>
		</div>
	</div>
</div>

 <?php ActiveForm::end(); ?>


 <?php $this->registerJs(
 	"$(function() {
 		//$('#ca').mask('000.000.000.000.000,00', {reverse: true});
 		//$('.capital-new').mask('999.999.999.999,99');
 	});"
 );?>