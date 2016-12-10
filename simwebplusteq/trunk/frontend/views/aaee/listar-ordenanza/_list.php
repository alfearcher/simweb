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
 *  @file _view.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 01-08-2016
 *
 *  @view _view.php
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

 <div class="list">
	 <div class="row">
		<div class="listar-ordenanza">
			<?= $this->render('/aaee/listar-ordenanza/listar-ordenanza', [
														'caption' => $caption,
														'dataProvider' => $dataProvider,
														'idConfig' => $idConfig,
	    					]);
	    	?>
		</div>
	</div>
</div>