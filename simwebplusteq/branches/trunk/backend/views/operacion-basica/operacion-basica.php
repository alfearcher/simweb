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
 *  @file seleccionar-tipo-naturaleza.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com - jperez820@hotmail.com
 *
 *  @date 22-02-2016
 *
 */

 	use yii\helpers\Html;
 	use kartik\icons\Icon;
 	use yii\bootstrap\Modal;
 	use yii\bootstrap\Alert;
 	use yii\jui\Dialog;
 	use yii\widgets\ActiveForm;
 	use kartik\sidenav\SideNav;

	// map to view file
	Icon::map($this, Icon::FA);

?>

<div class="seleccion-operacion-basica">
	<?php
		$form = ActiveForm::begin([
 			'id' => 'seleccion-operacion-basica-form',
 			'method' => 'post',
 			//'action' => [$urlNatural],
 			//'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			'enableClientScript' => true,
 		]);
	 ?>


<?= SideNav::widget([
		'type' => SideNav::TYPE_SUCCESS,
		'encodeLabels' => false,
		'headingOptions' => [
			'method' => 'post',
		],
		'heading' => Icon::show('fa fa-cube',['class' => 'fa-3x'], Icon::FA) . ' ' . $this->title,
		//'heading' => 'Options',
		'items' => [
			['label' => Icon::show('fa fa-file-text',['class' => 'fa-2x'], Icon::FA) . '&nbsp;' . Yii::t('backend','Create'),
								 'options' => [
								 		'class' => 'item-principal',
								 		'data' => [
								 			'operacion' => 1,
								 		],
								 ],
								 'url' => [$urlCreate],
								 'visible' => $urlCreate == '#' ? false : true,
			],
			['label' => Icon::show('fa fa-floppy-o',['class' => 'fa-2x'], Icon::FA) . '&nbsp;' . Yii::t('backend','Update'),
								 'options' => [
								 		'class' => 'item-principal',
								 		'data' => [
								 			'operacion' => 2,
								 		],
								 ],
								 'url' => [$urlUpdate],
								 'visible' => $urlUpdate == '#' ? false : true,
			],
			['label' => Icon::show('fa fa-list',['class' => 'fa-2x'], Icon::FA) . '&nbsp;' . Yii::t('backend','List'),
								 'options' => [
								 		'class' => 'item-principal',
								 		'data' => [
								 			'operacion' => 3,
								 		],
								 ],
								 'url' => [$urlList],
								 'visible' => $urlList == '#' ? false : true,
			]
		],
	])
?>
	 <?php ActiveForm::end(); ?>
</div>

