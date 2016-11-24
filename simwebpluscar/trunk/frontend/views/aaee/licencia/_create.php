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
 *  @date 21-11-2016
 *
 *  @view create.php
 *  @brief vista intermedia para renderizar el formulario principal de la solicitud para
 *  la declaracion estimada.
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

?>

<div class="create-licencia">
	<?= $this->render('/aaee/licencia/create-licencia-form', [
								        			'model' => $model,
		  											'dataProvider' => $dataProvider,
		  											'tipo' => $tipo,
		  											'ano_impositivo' => $ano_impositivo,
		  											'periodo' => 1,
		  											'caption' => $caption,
					    ]) ?>
</div>


