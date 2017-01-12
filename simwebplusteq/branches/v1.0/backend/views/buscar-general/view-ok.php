<?php
	use yii\helpers\Html;
	//use yii\web\View;
	/* @var $this yii\web\View */

	if ( $mostrarMenuPrincipal == 1 ) {
		return Yii::$app->getResponse()->redirect(array('/menu/vertical'));
	}
?>



