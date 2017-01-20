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
 *  @file _view-create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 17-01-2017
 *
 *  @view _view-create.php
 *  @brief vista para mostrar el formulario de inscripcion de propagandanda.
 *
 */

	use yii\helpers\Html;
	use common\mensaje\MensajeController;


	/**
	*@var $this yii\web\View */

	?>

<div class="view">
	<div class="row">
		<?= MensajeController::actionMensaje($codigo); ?>
	</div>
	<div class="row">
		<div class="view-create-formulario">
		    <?= $this->render('@frontend/views/propaganda/inscripcion-propaganda/view-solicitud-creada', [
														'caption' => $caption,
														'model' => $model,

							    ]) ?>
		</div>
	</div>
</div>