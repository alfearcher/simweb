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
 *  @file funcionario-solicitud-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view funcionario-solicitud-form.php
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

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use backend\models\Departamento;
	use backend\models\UnidadDepartamento;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>
<div class="funcionario-solicitud-form">
	<?php
		$form = ActiveForm::begin([
			'id' => 'funcionario-solicitud-form-create',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => false,
		]);
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
						<h4><strong><?= Yii::t('backend', 'Search for Departament')?></strong></h4>
					</div>

<!-- Inicio Departamento -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel(Yii::t('backend', 'Departament')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								 <?= $form->field($model, 'id_departamento')
								          ->dropDownList($listaDepartamento, [
                                                                            'id'=> 'departamentos',
                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                            'style' => 'width:280px;',
                                                                            'onchange' =>
                                                                              '$.post( "' . Yii::$app->urlManager
                                                                                                     ->createUrl('utilidad/unidaddepartamento/unidad-departamento/lists') . '&id=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                $( "select#unidad" ).html( data );
                                                                                                                                                                            });'
                                                                            ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Departamento -->

<!-- Inicio Unidad -->
					<div class="row">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel(Yii::t('backend', 'Section')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="unidad">
								 <?= $form->field($model, 'id_unidad')
								          ->dropDownList([], [
                                                            	'id'=> 'unidad',
                                                            	'prompt' => Yii::t('backend', 'Select'),
                                                            	'style' => 'width:280px;',
                                                            ])->label(false);
                                ?>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search'),
													  [
														'id' => 'btn-search',
														'class' => 'btn btn-success',
														'value' => 1,
														'name' => 'btn-search',
														'style' => 'width: 100%;',
														// 'onClick' => 'buscarFuncionario("' . Url::toRoute('buscar-funcionario') . '")',
													  ])
								?>
							</div>
						</div>
					</div>
<!-- Fin de Unidad -->

<!-- Inicio busqueda por parametros -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Search for DNI, Last Name or Name')?></strong></h4>
					</div>

					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel(Yii::t('backend', 'Input')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="search-global">
								<?= $form->field($model, 'searchGlobal')->textInput([
																					'id' => 'searchGlobal',
																					'style' => 'width: 75%;',
																	  			  ])->label(false) ?>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search'),
																		  [
																			'id' => 'btn-search-parameters',
																			'class' => 'btn btn-primary',
																			'value' => 2,
																			'name' => 'btn-search-parameters',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>
					</div>
<!-- Fin de busqueda por parametro -->

<!-- Inicia de busqueda de todos los funcionarios -->
					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<h4><strong><?= Yii::t('backend', 'Search All')?></strong></h4>
					</div>
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-3">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Search All'),
																		  [
																			'id' => 'btn-search-all',
																			'class' => 'btn btn-default',
																			'value' => 3,
																			'name' => 'btn-search-all',
																			'style' => 'width: 100%;',
																		  ])
								?>
							</div>
						</div>
					</div>

<!-- Fin de busqueda de todos los funcionarios -->

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


