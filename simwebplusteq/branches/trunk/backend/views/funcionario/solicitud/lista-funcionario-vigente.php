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
 *  @file lista-funcionario-vigente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view lista-funcionario-vigente.php
 *  @brief vista del formualario que se utilizara para capturar los datos a guardar.
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

 	//session_start();		// Iniciando session

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>
<div class="lista-funcionario-vigente">
	<?php
		$form = ActiveForm::begin([
			'id' => 'lista-funcionario-vigente-form',
		    'method' => 'post',
		    'action' => Url::toRoute(['funcionario/solicitud/funcionario-solicitud/verificar-envio']),
			'enableClientValidation' => false,
			'enableAjaxValidation' => false,
			'enableClientScript' => true,
		]);
	?>

	<?=
		// Variable que me indica el tipo de listado generado
		// 1 => listado por departamento y unidades
		// 2 => listado de todos (all).
		$form->field($model, 'listado')->hiddenInput(['value' => $listado])->label(false);
	?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
        			<style type="text/css">
					.col-sm-3 > ul > li > a:hover {
						background-color: #F5F5F5;
					}
    			</style>
	        		<?= MenuController::actionMenuSecundario([
	        						'back' => '/funcionario/solicitud/funcionario-solicitud/index-create',
	        						'quit' => '/funcionario/solicitud/funcionario-solicitud/quit',
	        			])
	        		?>
	        	</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Search Official: ') . $subCaption; ?></strong></h4>
					</div>

<!-- Inicio lista de funcionarios -->
					<div class="row">
						<? if ( $_SESSION['errListaFuncionario'] != '' ) { ?>
							<div class="well well-sm" style="color: red;">
								<?= $_SESSION['errListaFuncionario']; ?>
							</div>
						<?}?>
					</div>
					<div class="row">
						<div class="lista-funcionario">
							<?= GridView::widget([
										'id' => 'id-lista-funcionario-vigente',
										'dataProvider' => $dataProvider,
										//'filterModel' => $model,
										'headerRowOptions' => ['class' => 'success'],
										'caption' => Yii::t('backend', 'List of Official Public'),
										'summary' => '',
										'columns' => [
											[
                        						'class' => 'yii\grid\CheckboxColumn',
                        						'name' => 'chk-funcionario',
                        						'multiple' => true,
                        					],
                        					[
                        						'label' => Yii::t('backend', 'DNI'),
                        						'value' => function($model) {
                        							return $model->ci;
                        						}
                        					],
											[
                        						'label' => Yii::t('backend', 'Last Name'),
                        						'value' => function($model) {
                        							return $model->apellidos;
                        						}
                        					],
                        					[
                        						'label' => Yii::t('backend', 'First Name'),
                        						'value' => function($model) {
                        							return $model->nombres;
                        						}
                        					],
                        					[
                        						'label' => Yii::t('backend', 'Departamento'),
                        						'value' => function($model) {
                        							return $model->departamento->descripcion;
                        						}
                        					],
                        					[
                        						'label' => Yii::t('backend', 'Unidad'),
                        						'value' => function($model) {
                        							return $model->unidad->descripcion;
                        						}
                        					],
										]
								])
							?>
						</div>
					</div>
<!-- Fin de lista de funcionario -->




<!-- Inicio Impuesto -->
					<div class="row" style="border-top: 0.5px solid #ccc; border-bottom: 0.5px solid #ccc; padding-top: 45px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $modelImpuesto->getAttributeLabel(Yii::t('backend', 'impuesto')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="row">
								 <?= $form->field($modelImpuesto, 'impuesto')
								          ->dropDownList($listaImpuesto, [
				                                                            'id'=> 'impuesto',
				                                                            'prompt' => Yii::t('backend', 'Select'),
				                                                            'style' => 'width:280px;',
				                                                            'onchange' =>
				                                                              '$.post( "' . Yii::$app->urlManager
                     ->createUrl('funcionario/solicitud/funcionario-solicitud/lista-impuesto-solicitud') . '&id=' . '" + $(this).val(),
                     function( data ) {
                           $( "#lista-impuesto-solicitud" ).html( data );
		             });return false;'
				                                                            ])->label(false);
				                ?>
							</div>
						</div>
					</div>
<!-- Fin de Impuesto -->

<!-- Lista de Solicitudes -->
					<div class="row">
						<? if ( $_SESSION['errListaSolicitud'] != '' ) { ?>
							<div class="well well-sm" style="color: red;">
								<?= $_SESSION['errListaSolicitud']; ?>
							</div>
						<?}?>
					</div>


					<div class="row" style="border-bottom: 0.5px solid #ccc;">
					<?php Pjax::begin()?>
						<div class="lista-impuesto-solicitud" id="lista-impuesto-solicitud">
						</div>
					<?php Pjax::end()?>
					</div>

<!-- Fin de lista de Solicitudes -->


<!-- Inicio Boton Enviar -->
					<div class="row">
						<div class="boton-enviar" style="padding-top: 25px;">
							<div class="col-sm-3">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('backend', 'Save'),
														  [
															'id' => 'btn-send-request',
															'class' => 'btn btn-success',
															'value' => 1,
															'name' => 'btn-send-request',
															'style' => 'width: 100%;',
															'data-confirm' => Yii::t('backend', 'Confirm Save?.'),

														  ])
									?>
								</div>
							</div>
						</div>
					</div>
<!-- Fin de Boton Enviar -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->


	<?php ActiveForm::end(); ?>
</div>


