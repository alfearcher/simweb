<?php 
	use yii\helpers\Html;
	use yii\bootstrap\Alert;


	if ( $tipoError == 0 ) {
		$alert = 'alert-info';
	} else if ( $tipoError == 1 ) {
		$alert = 'alert-danger';
	}

 ?>

<?= Alert::widget([
			'options' => [
					'class' => $alert,
			],
			'body' => $msg,
	])  
?>
<?= $url ?>
<!-- Capa contenedora de la imagen gif loading -->
<div class="container" style="padding-top: 1em;">
<?= Html::img('@web/images/loading.gif', ['width' => '100px'], ['heigth' => '100px']);?>
</div>
<!-- Fin de Capa contenedora de la imagen gif loading-->