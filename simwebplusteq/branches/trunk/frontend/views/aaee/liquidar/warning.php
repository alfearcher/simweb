<?php
	use yii\helpers\Html;


?>

<div class="lista-error-mensaje">
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
    ?>

    <div class="row" style="color: red;">
    	<h4><strong><?=$view?></strong></h4>
    </div>
</div>