<?php
	use yii\helpers\Html;
	use yii\bootstrap\Alert;
	use yii\bootstrap\Modal;


	// if ( $tipoError == 0 ) {
	// 	$alert = 'alert-info';
	// } else if ( $tipoError == 1 ) {
	// 	$alert = 'alert-danger';
	// }



	// Modal::begin([
 //    	'header' => '<h2>Hello world</h2>',
 //    	//'toggleButton' => ['label' => 'click me'],
 //    	'toggleButton' => ['function() {
 //    						<script> alert("hola"); </script>
 //    					}'],
	// ]);

	// echo 'Say hello...';

	// Modal::end();
 ?>

<div class="row" style="alignment: center;">
<div class="panel panel-warning" style="width: 60%;">
	<div class="panel-heading">
    	<h3 class="panel-title"><strong><?= Yii::t('frontend', 'NOTE')?></strong></h3>
  	</div>
  	<div class="panel-body">
  		<class="message"><h3><p><?= Html::encode($cuerpoMensaje) ?></p></h3>
  	</div>
</div>
</div>

