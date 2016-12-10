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
 *  @file correccion-cedula-rif-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 31-10-2015
 *
 *  @view correccion-cedula-rif-form.php
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
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\Pjax;
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;
	use common\models\contribuyente\ContribuyenteBase;
	use backend\models\registromaestro\TipoNaturaleza;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);


	//$userProvider->pagination->pageParam = 'user-page';
	//$userProvider->sort->sortParam = 'user-sort';

	$dataProvider->pagination->pageParam = 'contribuyente-page';
	$dataProvider->sort->sortParam = 'contribuyente-sort';

 ?>


<div class="correccion-cedula-rif-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'correccion-cedula-rif-form',
 			'method' => 'post',
 			//'action' => ['index'],
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

        <?= $form->field($model, 'tipo_naturaleza_new')->hiddenInput(['value' => $datosContribuyente[0]['tipo_naturaleza']])->label(false); ?>
        <?= $form->field($model, 'tipo_naturaleza_v')->hiddenInput(['value' => $datosContribuyente[0]['tipo_naturaleza']])->label(false); ?>

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
											<div class="naturaleza-v">
												<?= $form->field($model, 'naturaleza_v')->textInput([
																									'id' => 'naturaleza_v',
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
											<div class="cedula-v">
												<?= $form->field($model, 'cedula_v')->textInput([
																								'id' => 'cedula_v',
																								'style' => 'width:100%;',
																								'value' => $datosContribuyente[0]['cedula'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>


<?php
	$longitudMax = 8;
	if ( $datosContribuyente[0]['tipo_naturaleza'] == 1) {
		$longitudMax = 9;
?>
									<div class="col-sm-1" style="margin-left: 3px;margin-top: 20px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', '') ?></i></p>
										</div>
										<div class="row">
											<div class="tipo-v">
												<?= $form->field($model, 'tipo_v')->textInput([
																								'id' => 'tipo_v',
																								'style' => 'width:50%;',
																								'value' => $datosContribuyente[0]['tipo'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<?php } ?>
<!-- Fin de Rif o Cedula del Contribuyente -->

<!-- Razon Social -->
									<div class="col-sm-6" style="margin-left: -20px;margin-top: 0px">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Company Name') ?></i></p>
										</div>
										<div class="row">
											<div class="razon-social-v">
												<?= $form->field($model, 'razon_social_v')->textInput([
																								'id' => 'razon-social-v',
																								'style' => 'width:100%;',
																								'value' => $datosContribuyente[0]['razon_social'],
																								'readonly' => true,
																						 	])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Razon Social -->


								</div>
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
									                    'value' => function($data) {
	                            										return $data->id_contribuyente;
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'DNI'),
									                    'value' => function($data) {
	                            										return ContribuyenteBase::getCedulaRifDescripcion($data->tipo_naturaleza, $data->naturaleza, $data->cedula, $data->tipo);
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'Taxpayer'),
									                    'value' => function($data) {
	                            										return ContribuyenteBase::getContribuyenteDescripcion($data->tipo_naturaleza, $data->razon_social, $data->apellidos, $data->nombres);
	                    											},
									                ],
									                [
									                    'label' => Yii::t('backend', 'License No.'),
									                    'value' => function($data) {
	                            										return $data->id_sim;
	                    											},
									                ],
									                [
									                	'class' => 'yii\grid\CheckboxColumn',
									                	'name' => 'myche',
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


<!-- CEDULA O RIF NUEVO -->
					<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'New DNI')) ?></span>
					        </div>
	        				<div class="panel-body">
	        					<div class="row">
<!-- Combo Naturaleza Nuevo -->
								<?php if ( $datosContribuyente[0]['tipo_naturaleza'] == 0 ) {
									$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
								} elseif ( $datosContribuyente[0]['tipo_naturaleza'] == 1 ) {
									$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 1 and 4')->all();
								}
								$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');
								?>

	        						<div class="col-sm-3" style="width: 20%;">
										<div class="naturaleza-new">
					                		<?= $form->field($model, 'naturaleza_new')->dropDownList($listaNaturaleza,[
	                																	 			'id' => 'naturaleza_new',
	                                                                     				 			'prompt' => Yii::t('backend', 'Select'),
	                                                                    							])->label(false)
					    					?>
										</div>
	        						</div>
<!-- Fin de Combo Naturaleza Nuevo -->

<!-- Cedula o Rif Nuevo -->
									<div class="col-sm-3" style="width: 15%; margin-left: -25px;">
										<div class="cedula-new">
											<?= $form->field($model, 'cedula_new')->textInput([
																							'id' => 'cedula_new',
																							'maxlength' => $longitudMax,
																		  				  ])->label(false) ?>
										</div>
									</div>
<!-- Fin de Cedula o Rif Nuevo -->

<?php if ( $datosContribuyente[0]['tipo_naturaleza'] == 1) {?>
<!-- Tipo Nuevo ultimo digito del rif, contribuyentes juridico -->
									<div class="col-sm-1" style="width: 7%; margin-left: -25px;">
										<div class="tipo-new">
											<?= $form->field($model, 'tipo_new')->textInput([
																						'id' => 'tipo_new',
																						'maxlength' => 1,
																			  			])->label(false) ?>
										</div>
									</div>
<!-- Fin de Tipo Nuevo -->
<?php } ?>

	        					</div>
	        				</div>
	        			</div>
					</div>
<!-- FIN DE CEDULA O RIF NUEVO -->
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
									        	//'name' => 'grid-list', da error
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

<!-- Boton para aplicar la actualizacion -->
					<div class="col-sm-2">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('backend', Yii::t('backend', 'Execute Update of DNI')),
																					  [
																						'id' => 'btn-update',
																						'class' => 'btn btn-success',
																						'name' => 'btn-update',
																						'value' => 1,
																						'data-confirm' => Yii::t('yii', 'Are you sure you want to change this password?')
																					  ])
							?>
						</div>
					</div>
<!-- Fin de Boton para aplicar la actualizacion -->

					<div class="col-sm-1"></div>

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
</div>

<?php ActiveForm::end(); ?>


<?php
	$this->registerJs(
		'$("#grid-contribuyente-asociado").on("click", function() {
			var keys = $("#grid-contribuyente-asociado").yiiGridView("getSelectedRows");
			alert(keys);
		});
		//var keys = $("#grid-contribuyente-asociado").yiiGridView("getSelectedRows");
		//alert(keys);'
		);
?>