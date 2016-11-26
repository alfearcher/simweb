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
 *  @file mensaje-error.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 20-11-2016
 *
 *  @view mensaje-error.php
 *  @brief vista intermedia para renderizar los mensaje que indica porque no se puede realizar la solicitud
 *
 */

	use yii\helpers\Html;

	/**
	*@var $this yii\web\View */

?>
<div class="error-mensaje">
	<div class="row">
		<div class="well well-sm" style="color: red;padding-left: 35px;">
			<strong>
				<h3><?=Html::encode(Yii::t('frontend', 'IMPOSIBLE CONTINUAR'))?></h3>
			</strong>
		</div>
	</div>
	<div class="row">
		<?php if( is_array($mensajes) ) {?>
			<div class="well well-sm" style="color: red;padding-left: 35px;">
				<p><strong>
					<?= $this->render('warnings',[
									'mensajes' => $mensajes,
						]);
					?>
				</p></strong>
			</div>
		<?php } ?>
	</div>
</div>





