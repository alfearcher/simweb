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
 *  @file error.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 13-02-2017
 *
 *  @view error.php
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


 ?>


 <div class="row" style="width:100%;">
	<div class="well well-sm">
  		<?=Html::encode($mensaje)  ?>
	</div>
</div>

<div class="row">
	<div class="col-sm-2" style="margin-left: 20px;">
		<?= Html::a(Yii::t('backend', 'Back'),Url::to($urlBack),
								  [
									'id' => 'btn-back',
									'class' => 'btn btn-primary',
									'value' => 1,
									'style' => 'width: 100%',
									'name' => 'btn-back',
								  ])
		?>
	</div>
</div>