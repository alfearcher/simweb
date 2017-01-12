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
 *  @file correccion-domicilio-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2015
 *
 *  @view correccion-domicilio-form
 *  @brief vista principal del cambio o correccion del domicilio fiscal
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
	//use yii\widgets\Pjax;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use common\models\contribuyente\ContribuyenteBase;

?>

<div class="correccion-domicilio-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'correccion-domicilio-fiscal-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);

 		$descripcionContribuyente = ContribuyenteBase::getContribuyenteDescripcion($datosContribuyente[0]['tipo_naturaleza'],
																				   $datosContribuyente[0]['razon_social'],
																				   $datosContribuyente[0]['apellidos'],
																				   $datosContribuyente[0]['nombres']);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
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
					        	<span><?= Html::encode(Yii::t('backend', 'Information of Main Taxpayer')) ?></span>
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


<?php
	if ( $datosContribuyente[0]['tipo_naturaleza'] == 1) {
?>
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
<?php } ?>
<!-- Fin de Rif o Cedula del Contribuyente -->


<!-- Descripcion del Contribuyente-->
									<div class="col-sm-5" style="margin-left: 7px;margin-top: 0px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Taxpayer') ?></i></p>
										</div>
										<div class="row">
											<div class="razon-social">
												<?= $form->field($model, 'razon_social')->textInput([
																								'id' => 'razon-social',
																								'style' => 'width:114%;',
																								'value' => $descripcionContribuyente,
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Descripcion del Contribuyente -->

					        	</div>  <!-- Fin de row inicial-->


<!-- Domicilio Fiscal Actual -->
					        	<div class="row">
									<div class="col-sm-5" style="margin-left: 15px;margin-top: 0px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?= $model->getAttributeLabel('domicilio_fiscal_v') ?></i></p>
										</div>
										<div class="row">
											<div class="domicilio_fiscal_v">
												<?= $form->field($model, 'domicilio_fiscal_v')->textArea([
																								'id' => 'domicilio-fiscal',
																								'style' => 'width:140%;',
																								'value' => $datosContribuyente[0]['domicilio_fiscal'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Domicilio Fiscal Actual -->
					        	</div> <!-- Fin de row Domicilo Fiscal -->

					        </div>
					    </div>
					</div>

<!-- DOMICILIO FISCAL NUEVO -->
					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode($model->getAttributeLabel('domicilio_fiscal_new')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
<!-- Domicilio Fiscal Nueva -->
									<div class="col-sm-5" style="margin-left: 15px;margin-top: 0px">
										<div class="row">
											<div class="domicilio-fiscal-new">
												<?= $form->field($model, 'domicilio_fiscal_new')->textArea([
																								'id' => 'domicilio-fiscal-new',
																								'style' => 'width:140%;',
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Domicilio Fiscal Nueva -->
<!-- FIN DE DOMICILIO FISCAL NUEVO -->

<?php
	if ( $datosContribuyente[0]['tipo_naturaleza'] == 1) {
?>
					<div class="row">
<!-- LISTA DE DOCUMENTOS Y REQUISITOS -->
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
					        <div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Documents and Requirements Consigned')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row">
					        		<div class="col-sm-8">
										<div class="documento-requisito-consignado">
									        <?= GridView::widget([
									        	'id' => 'grid-list',
									            'dataProvider' => DocumentoRequisitoController::actionGetDataProviderSegunImpuesto(1),
									            'columns' => [
									                	['class' => 'yii\grid\SerialColumn'],
									                	[
										                    'label' => 'ID.',
										                    'value' => 'id_documento',
										                ],
										                [
										                    'label' => 'Descripcion',
										                    'value' => 'descripcion',
										                ],
										                ['class' => 'yii\grid\CheckboxColumn'],
									            ]
											]);?>
										</div>
									</div>
								</div>
							</div>   	<!-- Fin de panel-body documento -->
						</div>  		<!-- Fin de panel panel-success documento -->
<!-- FINAL DE DOCUMENTOS Y REQUISITOS -->
					</div>
<?php }?>

					<div class="row" style="margin-top: 15px;">
<!-- Boton para aplicar la actualizacion -->
						<div class="col-sm-2">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Execute Update of Tax Address')),
																						  [
																							'id' => 'btn-update',
																							'class' => 'btn btn-success',
																							'value' => 1,
																							'name' => 'btn-update',
																						  ])
								?>
							</div>
						</div>
<!-- Fin de Boton para aplicar la actualizacion -->

						<div class="col-sm-1"></div>

<!-- Boton para salir de la actualizacion -->
						<div class="col-sm-2" style="margin-left: 50px;">
							<div class="form-group">
								<?= Html::a(Yii::t('backend', 'Quit'), ['quit'], ['class' => 'btn btn-danger']) ?>
							</div>
						</div>
<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>