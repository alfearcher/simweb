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
 *  @file resultado-procesar-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 04-02-2017
 *
 *  @view resultado-procesar-solicitud.php
 *  @brief vista que canaliza la salida del resultado de procesar la solicitud.
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
	use common\mensaje\MensajeController;


?>
<div class="row" style="width: 100%;">
	<div class="listar">
		<div class="row" style="width: 100%;>
			<?php if ( $codigo > 0 ) { ?>
				<?= MensajeController::actionMensaje($codigo);  ?>
			<?php } ?>
		</div>

		<div class="row" style="width: 100%;">
			<?=Html::a('Ir al Listado de Solicitudes',Url::to(['buscar-solicitudes-contribuyente']),[
							'class' => 'btn btn-primary',
							'style' => 'width:25%;'
					])
			?>
		</div>
	</div>
</div>