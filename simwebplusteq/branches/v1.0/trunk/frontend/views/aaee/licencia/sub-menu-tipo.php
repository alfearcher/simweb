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
 *  @file sub-menu-tipo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2016
 *
 *  @view sub-menu-tipo.php
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

 	use yii\web\Response;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;
	use common\mensaje\MensajeController;
	use kartik\sidenav\SideNav;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>

<div class="seleccion-tipo-licencia" style="width: 35%;margin: auto;">
	<?php
		$form = ActiveForm::begin([
 			'id' => 'id-seleccion-tipo-licencia-form',
 			'method' => 'post',
 			//'action' => $url,
 			//'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
	 ?>


<?= SideNav::widget([
		'type' => SideNav::TYPE_DEFAULT,
		'encodeLabels' => false,
		'headingOptions' => [
			'method' => 'post',
		],
		'heading' => Icon::show('fa fa-cube',['class' => 'fa-3x'], Icon::FA) . ' ' . $caption,
		//'heading' => 'Options',
		'items' => [
			['label' => Icon::show('fa fa-file-text',['class' => 'fa-2x'], Icon::FA) . '&nbsp;' . Yii::t('backend','Nueva ( Inicio de Actividad ' . date('Y'). ' )'),
								 'options' => [
								 		'class' => 'item-principal',
								 		'data' => [
								 			'tipo' => 'NUEVA',
								 		],

								 ],
								 'url' => [$urlNueva],
								 'visible' => $urlNueva == '#' ? false : true,
			],
			['label' => Icon::show('fa fa-floppy-o',['class' => 'fa-2x'], Icon::FA) . '&nbsp;' . Yii::t('backend','Renovacón'),
								 'options' => [
								 		'class' => 'item-principal',
								 		'data' => [
								 			'tipo' => 'RENOVACION',
								 		],
								 ],
								 'url' => [$urlRenovacion],
								 'visible' => $urlRenovacion == '#' ? false : true,
			],
			['label' => Icon::show('fa fa-list',['class' => 'fa-2x'], Icon::FA) . '&nbsp;' . Yii::t('backend','Salida'),
								 'options' => [
								 		'class' => 'item-principal',
								 		'data' => [
								 			'operacion' => 3,
								 		],
								 ],
								 'url' => [$urlSalida],
								 'visible' => $urlSalida == '#' ? false : true,
			]
		],
	])
?>
	 <?php ActiveForm::end(); ?>
</div>
