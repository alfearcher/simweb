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
 *  @date 17-05-2017
 *
 *  @view create.php
 *  @brief vista que que renderiza
 *
 */

	use yii\helpers\Html;


?>
<div class="row">
	<div class="error-mensaje">
		<?php if( count($errorMensaje) > 0 ) {?>
			<div class="well well-sm" style="color: red;padding-left: 35px;">
				<h4><?=Html::encode(Yii::t('frontend', 'IMMPOSIBLE CONTINUAR')); ?></h4>
			</div>
			<div class="well well-sm" style="color: red;padding-left: 35px;">
				<p><strong>
					<?= $this->render('warnings',[
									'mensajes' => $errorMensaje,
						]);
					?>
				</p></strong>
			</div>
		<?php } else {?>
			<div class="row">
				<?= $this->render('@frontend/views/aaee/desincorporar-actividad-economica/desincorporar-actividad-economica-form', [
									        		'model' => $model,
									        		'findModel' => $findModel,
									        		'caption' => $caption,
									        		'rutaAyuda' => $rutaAyuda,
								    ])?>
			</div>
		<?php } ?>
	</div>
</div>


