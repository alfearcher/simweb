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
 *  @file autorizar-ramo-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 15-10-2015
 *
 *  @view autorizar-ramo-form.php
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
	use backend\controllers\utilidad\documento\DocumentoRequisitoController;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="autorizar-ramo-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'autorizar-ramo-form',
 			'method' => 'post',
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
<!--
	<?//= Html::activeHiddenInput($model, 'id_sede_principal', ['id' => 'id-sede-principal', 'name' => 'id-sede-principal', 'value' => $_SESSION['idContribuyente']]) ?>

	<?//= $form->field($model, 'usuario')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false); ?>
	<?//= $form->field($model, 'estatus')->hiddenInput(['value' => 0])->label(false); ?>
-->


<!-- Cuerpo del formulario -->
        <div class="panel-body" style="background-color: #F9F9F9;">
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<div class="panel panel-success" style="width: 103%;margin-left: -15px;">
							<div class="panel-heading">
					        	<span><?= Html::encode(Yii::t('backend', 'Category')) ?></span>
					        </div>
					        <div class="panel-body">
					        	<div class="row">

<!-- Id Contribuyente -->
									<div class="col-sm-2" style="margin-left: 15px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('id_contribuyente')) ?></i></p>
										</div>
										<div class="row">
											<div class="id-contribuyente">
												<?= $form->field($model, 'id_contribuyente')->textInput([
																									'id' => 'id-contribuyente',
																									'name' => 'id-contribuyente',
																									'style' => 'width:100%;',
																									'value' => $datosContribuyente[0]['id_contribuyente'],
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Id Contribuyente -->

<!-- Fecha de Inicio -->
									<div class="col-sm-2" style="margin-left: 5px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('fecha_inicio')) ?></i></p>
										</div>
										<div class="row">
											<div class="fecha-inicio">
												<?= $form->field($model, 'fecha_inicio')->textInput([
																									'id' => 'fecha-inicio',
																									'name' => 'fecha-inicio',
																									'style' => 'width:70%;',
																									'value' => $datosContribuyente[0]['fecha_inicio'],
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Fecha de Inicio -->

<!-- Año Catalogo de Rubro -->
									<div class="col-sm-2" style="margin-left: -45px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', $model->getAttributeLabel('ano_catalogo')) ?></i></p>
										</div>
										<div class="row">
											<div class="ano-catalogo">
												<?= $form->field($model, 'ano_catalogo')->textInput([
																									'id' => 'ano-catalogo',
																									'name' => 'ano-catalogo',
																									'style' => 'width:60%;',
																									'value' => $anoCatalogo,
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Año Catalogo de Rubro -->

<!-- Año de Vencimiento de la Ordenanza, segun el Año Catalogo -->
									<div class="col-sm-2" style="margin-left: 55px;">
										<div class="row" style="width:100%;">
											<p style="margin-top: 0px;margin-bottom: 0px;"><i><?=Yii::t('backend', 'Ordinance Expired') ?></i></p>
										</div>
										<div class="row">
											<div class="ano-vence-ordenanza">
												<?= $form->field($model, 'ano_vence_ordenanza')->textInput([
																									'id' => 'ano-vence-ordenanza',
																									'name' => 'ano-vence-ordenanza',
																									'style' => 'width:70%;',
																									'value' => $anoVenceOrdenanza,
																									'readonly' => true,
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Año de Vencimiento de la Ordenanza, segun el Año Catalogo -->


								</div> 		<!-- Fin de row -->


								<div class="row">
									<div class="col-sm-5" style="width:80%;">
										<div class="list-group">
  											<a href="#" class="list-group-item">
    											<h4 class="list-group-item-heading">Observaciones</h4>
    											<p class="list-group-item-text">La autorización de los ramos comprenderan el lapso de tiempo entre los años <?= Html::encode($anoCatalogo) ?> y <?= Html::encode($anoVenceOrdenanza) ?>. Ambos inclusibles</p>
  											</a>
										</div>
									</div>
								</div>


								<div class="row">
<!-- Campo de busqueda -->
									<div class="col-sm-6" style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;">
										<div class="row">
											<p><i><?= Yii::t('backend', $modelSearch->getAttributeLabel('campo_busqueda')) ?></i></p>
										</div>
										<div class="row">
											<div class="campo-busqueda">
												<?= $form->field($modelSearch, 'campo_busqueda')->textInput([
																									'id' => 'campo-busqueda',
																									'name' => 'campo-busqueda',
																									'style' => 'width:100%;margin-top: -10px;',
																						 			])->label(false) ?>
											</div>
										</div>
									</div>
<!-- Fin de Campo de busqueda -->


<!-- Boton Search Category -->
									<div class="col-sm-2" style="margin-top: 20px;margin-bottom: 0px;">
										<div class="form-group">
											<?= Html::button(Yii::t('backend', 'Search Category'),[
																									'id' => 'btn-search',
																									'class' => 'btn btn-primary',
																									'name' => 'btn-search',
																									'data' => [
																										'pjax' => 0,
																									],
																									'onClick' => 'miListaRubro("' . Url::toRoute('lista-rubros') . '")',
																								])?>
										</div>
									</div>
<!-- Fin de Boton Search Category -->

								</div>	<!-- Fin de row -->

<!-- LISTA DE CATALOGO DE RUBROS -->
								<div class="row">
									<div class="panel panel-success" style="width: 97%; margin-left: 15px;">
								        <div class="panel-heading">
								        	<span><?= Html::encode(Yii::t('backend', 'Category List')) ?></span>
								        </div>
								        <div class="panel-body">
								        	<div class="row">
								        		<div class="col-sm-12">
								        			<?php Pjax::begin(); ?>
													<div id="listaRubro" class="listaRubro">
													</div>
													<?php Pjax::end(); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
<!-- Fin de LISTA DE CATALOGO DE RUBROS -->


<!-- RUBROS AGREGADOS PARA SU APROBACION -->
								<div class="row">
									<div class="panel panel-success" style="width: 97%; margin-left: 15px;">
								        <div class="panel-heading">
								        	<span><?= Html::encode(Yii::t('backend', 'Added Categorys')) ?></span>
								        </div>
								        <div class="panel-body">
								        	<div class="row">
								        		<div class="col-sm-12">
													<div id="lista-rubros-agregados" class="lista-rubros-agregados">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
<!-- Fin de RUBROS AGREGADOS PARA SU APROBACION -->

							</div>
						</div>
					</div>


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
									            //'filterModel' => $searchModel,
									            //'layout'=>"n{pager}\n{items}",

									            //'headerRowOptions' => ['class' => 'success'],
									            // 'rowOptions' => function($data) {
									            //     if ( $data->inactivo == 1 ) {
									            //         return ['class' => 'danger'];
									            //     }
									            // },
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


					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">

<!-- 								<?//= Html::button(Yii::t('backend', 'Authorize Categorys'), [
								// 															'id' => 'btn-create',
								// 															'class' => 'btn btn-success',
								// 															'onClick' => 'create("' . Url::toRoute('create') . '")',
								// ]) ?>

 -->

<!--
								<?//= Html::submitButton(Yii::t('backend', 'Authorize Categorys'),[
								// 																	'id' => 'btn-create',
								// 																	'class' => 'btn btn-success',
								// 																	'name' => 'btn-create',
								// 																	'value' => 'save-form',
								// 																])?>

 -->

 								<?= Html::a(Yii::t('backend', 'Authorize Categorys'), ['create', 'guardar' => true], ['class' => 'btn btn-success']) ?>

 							</div>
						</div>
						<div class="col-sm-2" style="margin-left: 150px;">
							<div class="form-group">
								 <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
							</div>
						</div>
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->
		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


<!--
<script type="text/javascript">
	// function create(url) {
	// 	$.ajax({
	// 			type: "post",
	// 			url: url + '&guardar=true',
	// 			data: $(".autorizar-ramo-form").serialize(),
	// 			success: function(data) {
	// 						//$.pjax.reload({container:'#grid-list-rubro'});
	// 						$("#lista-rubros-prueba").html("<p>" + data + "</p>");

	// 					}
	// 		});
	// 		return false;
	// }
</script>
 -->

<script type="text/javascript">
	function miListaRubro(url) {
		var params1 = $("#campo-busqueda").val();
		var params2 = $("#ano-catalogo").val();;
		var url2 = url + '&anoImpositivo=' + params2.toString() + '&params=' + params1.toString();

		$.ajax({
				type: "get",
				url: url2,
				data: $(".autorizar-ramo-form").serialize(),
				success: function(data) {
							//$.pjax.reload({container:'#grid-list-rubro'});
							$("#listaRubro").html("<p>" + data + "</p>");

						}
			});
			return false;
	}
</script>