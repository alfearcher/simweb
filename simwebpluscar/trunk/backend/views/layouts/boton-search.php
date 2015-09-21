<?php
	use kartik\icons\Icon;
  	//use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;

  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);
 ?>
 <?php
    //if (!Yii::$app->user->isGuest) {
    $menuItems1[] = ['label' => '<div class="undo" id="undo" style="padding-left: 0px; padding-right: 40px;"><p>' . Icon::show('fa fa-undo',['class' => $typeLong], $typeIcon) . Yii::t('backend', 'undo') . '</p></div>', 'url' => ['buscargeneral/buscar-general/eliminar-session']];
        $menuItems1[] = ['label' =>  '<div class="search" id="lupa" style="padding-left: 0px; padding-right: 80px;"><p>' . Icon::show('fa fa-search',['class' => $typeLong], $typeIcon) . Yii::t('backend', 'search') . '</p></div>', 'url' => ['buscargeneral/buscar-general/index']];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems1,
            'encodeLabels' => false,
        ]);

    //}
 ?>