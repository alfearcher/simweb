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
 *  @file _operacion.php
 *
 *  @author Jose Rafael Perez Teran
 *  @email jperez320@gmail.com - jperez820@hotmail.com
 *
 *  @date 22-02-2016
 *
 */

 	use yii\helpers\Html;

 	$this->title = Yii::t('backend', 'Basic Operation. ' . $caption );
?>
<div class="row">
	<div class="operacion" style="width: 40%; padding-top: 25px;">
		<?= $this->render('@backend/views/operacion-basica/operacion-basica', [
														'urlCreate' => $urlCreate,
														'urlUpdate' => $urlUpdate,
														'urlList' => $urlList,
					])
		?>
	</div>
</div>

