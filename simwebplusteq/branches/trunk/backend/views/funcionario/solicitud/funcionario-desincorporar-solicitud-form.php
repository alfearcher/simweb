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
 *  @file funcionario-desincorporar-solicitud-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-04-2016
 *
 *  @view funcionario-desincorporar-solicitud-form.php
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
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>
<div class="funcionario-desincorporar-solicitud-form">
	<?php
		$form = ActiveForm::begin([
			'id' => 'funcionario-solicitud-form-desincorporar',
		    'method' => 'post',
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => true,
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
						<h4><strong><?= Yii::t('backend', 'Search for Tax')?></strong></h4>
					</div>

<!-- Inicio Impuetos -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel(Yii::t('backend', 'Tax')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row">
								 <?= $form->field($model, 'impuesto')
								          ->dropDownList($listaImpuesto, [
                                                                  'id'=> 'impuesto',
                                                                  'prompt' => Yii::t('backend', 'Select'),
                                                                  'style' => 'width:280px;',
                                                                  'onchange' => '$.post( "' . Yii::$app->urlManager
                                                                                       		           ->createUrl('funcionario/solicitud/funcionario-solicitud/list-solicitud') . '&i=' . '" + $(this).val(), function( data ) {
                                                                                                                 $( "select#tipo-solicitud" ).html( data );
                                                                                                           });'
                                                                            ])->label(false);
                                ?>
							</div>
						</div>
					</div>
<!-- Fin de Impuestos -->

<!-- Inicio Tipo de Solicitud -->
					<div class="row">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $model->getAttributeLabel(Yii::t('backend', 'Request')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="row" class="tipo-solicitud">
								 <?= $form->field($model, 'tipo_solicitud')
								          ->dropDownList([], [
                                                            	'id'=> 'tipo-solicitud',
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
														'id' => 'btn-search-impuesto',
														'class' => 'btn btn-success',
														'value' => 1,
														'name' => 'btn-search-impuesto',
														'style' => 'width: 100%;',
													  ])
								?>
							</div>
						</div>
					</div>
<!-- Fin de Tipo de Solicitud -->

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

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->

	<?php ActiveForm::end(); ?>
</div>


