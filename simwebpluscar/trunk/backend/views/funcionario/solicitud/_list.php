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
 *  @file _list.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view _list.php
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
	use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
		'id' => 'asignar-solicitud',
	    'method' => 'post',
	    //'action' => Yii::$app->urlManager
         //                    ->createUrl('funcionario/solicitud/funcionario-solicitud/prueba'),
		//'enableClientValidation' => true,
		//'enableAjaxValidation' => true,
		//'enableClientScript' => true,
	]);?>
<div class="lista-funcionario">
	<?=	$this->render('/funcionario/solicitud/lista-funcionario-vigente', [
																'model' => $model,
																'dataProvider' => $dataProvider,
																'caption' => $caption,
																'subCaption' => $subCaption,
				]);
	?>
</div>
<div class="lista-impuesto">
	<?= $this->render('/funcionario/solicitud/combo-impuesto', [
													'modelImpuesto' => $modelImpuesto,
													'listaImpuesto' => $listaImpuesto,
		]);
	?>
</div>

<div class="boton-enviar" style="padding-top: 25px;">
	<div class="col-sm-3">
		<div class="form-group">
			<?= Html::submitButton(Yii::t('backend', 'Send Request'),
								  [
									'id' => 'btn-send-request',
									'class' => 'btn btn-success',
									'value' => 1,
									'name' => 'btn-send-request',
									'style' => 'width: 100%;',
									'onClick' => '$.post( "' . Yii::$app->urlManager
                     ->createUrl('funcionario/solicitud/funcionario-solicitud/prueba') . '&id=1",
                     function( data ) {
                           $( "#lista-impuesto-solicitud" ).html( data );
		             });'
								  ])
			?>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
