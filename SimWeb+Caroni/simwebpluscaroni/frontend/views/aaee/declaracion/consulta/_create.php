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
 *  @file _create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view create.php
 *  @brief vista intermedia para renderizar el formulario principal de la solicitud para
 *  la declaracion estimada.
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

<div class="seleccionar-lapso-form-create">
	<?= $this->render('@common/views/aaee/seleccionar-lapso/seleccionar-lapso-form', [
								        			'model' => $model,
								        			'findModel' => $findModel,
								        			'listaAño' => $listaAño,
								        			'caption' => $caption,
								        			'subCaption' => $subCaption,
								        			'url' =>$url,
													'rutaLista' => $rutaLista,
													'searchSustitutiva' => $searchSustitutiva,
													'btnBack' => 1,
													//'errorMensaje' => $errorMensaje,
					    ]) ?>
</div>

