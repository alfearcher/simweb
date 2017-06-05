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
 *  @file _view-ramo.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view _view-ramo.php
 *  @brief vista.
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

	$mensajes = json_decode($errorMensaje, true);
?>


<div class="error-mensaje">
	<?php if( is_array($mensajes) ) {?>
		<div class="well well-sm" style="color: red;padding-left: 35px;">
			<p><strong>
				<?= $this->render('warnings',[
								'mensajes' => $mensajes,
					]);
				?>
			</p></strong>
		</div>
	<?php } elseif ( trim($errorMensaje) !== '' ) { ?>
			<div class="well well-sm" style="color: red;padding-left: 35px;">
				<h4><b><?=Html::encode($errorMensaje) ?></b></h4>
			</div>
	<?php } ?>
</div>

<div class="desincorporar-ramo-seleccion-form">
	<?= $this->render('@frontend/views/aaee/desincorpora-ramo/view-ramo-registrado', [
						        'model' => $model,
								'findModel' => $findModel,
								'dataProviderRubro' => $dataProviderRubro,
								'caption' => $caption,
								'opciones' =>$opciones,
								'errorChk' => $errorChk,
								'totalItem' => $totalItem,
								'chkSeleccion' => $chkSeleccion,
								'rubroSeleccionado' => $rubroSeleccionado,
					    ]) ?>
</div>



