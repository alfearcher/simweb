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
 *  @file _view-detalle.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view _view-detalle, vista que renderiza a la vista principal.
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

 <div class="view">
	 <div class="row">
		<div class="view-detalle-liquidacion-aaee">
			<?= $this->render('@frontend/views/aaee/liquidar/detalle-liquidacion-aaee', [
															'dataProvider' => $dataProvider,
															'caption' => $caption,
															'subCaption' => $subCaption,
															'fechaInicio' => $fechaInicio,
															'ultimoLapsoLiquidado' => $ultimoLapsoLiquidado,

	    					]);
	    	?>
		</div>
	</div>
</div>