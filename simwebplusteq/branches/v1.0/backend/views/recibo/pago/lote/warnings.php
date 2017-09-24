<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\web\View;

?>

<?php

	$form = ActiveForm::begin([
		'id' => 'id-lista-archivo',
		'method' => 'post',
		//'action' => ['mostrar-archivo-txt'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => false,
	]);
 ?>

<div class="lista-error-mensaje">
	<div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding:0px;padding-top: 0px;">
		<h4><strong><?=Html::encode(Yii::t('backend', 'Archivo: ') . $archivo)?></strong></h4>
	</div>
	<?php
		$view = '';

		foreach ( $mensajes as $mensaje ) {
			if ( is_array($mensaje) ) {
				foreach ( $mensaje as $key => $value ) {
					if ( is_array($value) ) {
						foreach ( $value as $i => $m ) {
							$view = $view . Html::tag('li', $m);
						}
					} else {
						$view = $view . Html::tag('li', $value);
					}
				}
			} else {
				$view = $view . Html::tag('li', $mensaje);
			}
		}

		echo $view;
    ?>
    <div class="row" style="margin-top: 25px;">
		<div class="col-sm-2" style="margin-left: 20px;">
			<div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Back'),
													  [
														'id' => 'btn-back',
														'class' => 'btn btn-danger',
														'value' => 1,
														'style' => 'width: 100%',
														'name' => 'btn-back',
													  ])
				?>
			</div>
		</div>

		<div class="col-sm-2" style="margin-left: 20px;">
			<div class="form-group">
				<?= Html::submitButton(Yii::t('backend', 'Quit'),
													  [
														'id' => 'btn-quit',
														'class' => 'btn btn-danger',
														'value' => 1,
														'style' => 'width: 100%',
														'name' => 'btn-quit',
													  ])
				?>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>