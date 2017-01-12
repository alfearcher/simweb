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
 *  @file _operacion-no-valido.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view operacion-no-valido.php
 *  @brief
 *
 */

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;



	$form = ActiveForm::begin([
		'id' => 'id-erroroperacion-form',
		'method' => 'post',
		//'action' => $url,
		'enableClientValidation' => false,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);

?>
<div class="error-mensaje">
	<div class="well well-sm" style="color: red;padding-left: 35px;">
		<h4><b><?=Html::encode('NO ES POSIBLE REALIZAR LA OPERACION. EL VALOR A GURDAR NO ES VALIDO') ?></b></h4>
	</div>
	<div class="row" style="width: 25%;">
		<?= Html::submitButton(Yii::t('frontend', 'Back'),
												  [
													'id' => 'btn-error',
													'class' => 'btn btn-danger',
													'value' => 1,
													'style' => 'width: 100%; margin-left:0px;margin-top:20px;',
													'name' => 'btn-error',

												  ]);
		?>
	</div>
</div>

<?php ActiveForm::end(); ?>
