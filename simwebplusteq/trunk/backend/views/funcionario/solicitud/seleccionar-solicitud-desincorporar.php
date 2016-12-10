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
 *  @file seleccionar-solicitud-desincorporar.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-04-2016
 *
 *  @view seleccionar-solicitud-desincorporar.php
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
	use backend\controllers\menu\MenuController;
	use backend\models\funcionario\solicitud\FuncionarioSearch;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>
<div class="seleccionar-solicitud">
	<?php
		$form = ActiveForm::begin([
			'id' => 'id-seleccionar-solicitud-form',
		    'method' => 'post',
		    'action' => $url,
			'enableClientValidation' => true,
			//'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>

	<?=
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
	        						'back' => '/funcionario/solicitud/funcionario-solicitud/index-delete',
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
						<h4><strong><?= $subCaption; ?></strong></h4>
					</div>

<!-- Inicio lista de funcionarios -->
					<div class="row">
						<div class="lista-solictudes">
							<?= GridView::widget([
								'id' => 'id-seleccionar-solicitud',
								'dataProvider' => $dataProvider,
								//'filterModel' => $model,
								'headerRowOptions' => ['class' => 'success'],
								'caption' => Yii::t('backend', 'List of Request'),
								'summary' => '',
								'columns' => [
									[ 'class' => 'yii\grid\SerialColumn',],
                					[
										'label' => Yii::t('backend', 'Request'),
										'value' => function($model) {
											return $model->tipoSolicitud['id_tipo_solicitud'];
										}
									],
									[
										'label' => Yii::t('backend', 'Tax'),
										'value' => function($model) {
											$datos = FuncionarioSearch::getInfoSolicitudImpuesto($model->tipoSolicitud['id_tipo_solicitud']);
											return $datos['impuesto'][$model->tipoSolicitud['impuesto']];
										}
									],
									[
										'label' => Yii::t('backend', 'Description'),
										'value' => function($model) {
											return $model->tipoSolicitud['descripcion'];
										}
									],
									[
										'class' => 'yii\grid\CheckboxColumn',
										'name' => 'chk-id-funcionario-solicitud',
										'multiple' => true,
									],

								],
							]);
						?>
						</div>
					</div>
<!-- Fin de lista de funcionario -->

<!-- Inicio de boton -->
					<div class="col-sm-3">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('backend', 'Remove Request'),
												  [
													'id' => 'btn-remove-request',
													'class' => 'btn btn-success',
													'value' => 1,
													'name' => 'btn-remove-request',
													'style' => 'width: 100%;',
												  ])
							?>
						</div>
					</div>
<!--  -->


				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->


	<?php ActiveForm::end(); ?>
</div>


