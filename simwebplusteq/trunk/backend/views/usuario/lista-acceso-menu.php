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
 *  @file lista-acceso-menu.php
 *
 *  @author Alvaro Jose Fernandez Archer
 *
 *  @date 21-04-2016
 *
 *  @view lista-acceso-menu.php
 *  @brief vista del formualario
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
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\widgets\Pjax;
	use backend\controllers\menu\MenuController;

?>

<div class="lista-acceso-menu">
	<?= GridView::widget([
			'id' => 'id-lista-acceso-menu',
			'dataProvider' => $dataProvider,
			//'filterModel' => $model,
			'caption' => $caption,  //Yii::t('backend', 'List of Request'),
			'headerRowOptions' => ['class' => 'info'],
			
			'summary' => '',
			'columns' => [

				'id_ruta_acceso_menu',
                'ruta',
                'menu',
                
				
				
			]
		]);
	?>
</div>

