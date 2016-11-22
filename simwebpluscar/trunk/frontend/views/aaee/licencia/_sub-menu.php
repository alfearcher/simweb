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
 *  @file _sub-menu.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2016
 *
 *  @view _sub-menu.php
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

 ?>

<div class="sub-menu-tipo-form" style="width: 25%;">
	<?= $this->render('/aaee/licencia/sub-menu-tipo', [
									'urlNueva' => $urlNueva,
									'urlRenovacion' => $urlRenovacion,
									'urlSalida' => $urlSalida,
									'caption' => $caption,
					]);
	    	?>
</div>
