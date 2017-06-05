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
 *  @file _view-listado-sin-licencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 08-01-2017
 *
 *  @view _view-listado-sin-licencia.php
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

 <div class="view">
 	<?php if (trim($mensajes) !== '' ) { ?>
		 <div class="well well-sm" style="color: red;padding-left: 35px;">
			<h4><b><?=Html::encode($mensajes) ?></b></h4>
		</div>
	<?php } ?>
	 <div class="row">
		<div class="sin-licencia-creada">
			<?= $this->render('/aaee/licencia/asignar-numero/listado-contribuyente-sin-licencia-form', [
															'model' => $model,
															'url' => $url,
															'caption' => $caption,
															'subCaption' => $subCaption,
															'dataProvider' => $dataProvider,
	    					]);
	    	?>
		</div>
	</div>
</div>