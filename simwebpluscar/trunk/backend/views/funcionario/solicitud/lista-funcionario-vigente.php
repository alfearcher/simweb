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
			'enableClientValidation' => true,
			'enableAjaxValidation' => true,
			'enableClientScript' => true,
		]);
	?>


	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4">
        			<h4><?= Html::encode($caption) ?></h4>
        		</div>
        		<div class="col-sm-3" style="width: 30%; float:right; padding-right: 50px;">
        			<style type="text/css">
					.col-sm-3 > ul > li > a:hover {
						background-color: #F5F5F5;
					}
    			</style>
	        		<?= MenuController::actionMenuSecundario([
	        						'back' => '/funcionario/solicitud/funcionario-solicitud/index',
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
						<h4><strong><?= Yii::t('backend', 'Search Official')?></strong></h4>
					</div>

					<div class="row">
						<div class="lista-funcionario">
							<?= GridView::widget([
										'id' => 'id-lista-funcionario-vigente',
										'dataProvider' => $dataProvider,
										'filterModel' => $model,
										'headerRowOptions' => ['class' => 'success'],
										'summary' => '',
										'columns' => [
											[
                        						'class' => 'yii\grid\CheckboxColumn',
                        						'name' => 'chk-proceso-generado',
                        						'multiple' => true,
                        					],
											 //['class' => 'yii\grid\SerialColumn'],
											'ci',
											'apellidos',
											'nombres'
										]
								])
							?>

						</div>
					</div>

				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>			<!-- Fin de panel panel-default -->


	<?php ActiveForm::end(); ?>
</div>


