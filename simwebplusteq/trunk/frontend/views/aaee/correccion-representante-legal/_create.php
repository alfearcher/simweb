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
 *  @date 08-08-2016
 *
 *  @view create.php
 *  @brief vista que canaliza la salida de los formularios.
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

	$this->title = Yii::t('backend', 'Update of Legal Represent');

	?>
<div class="correccion-representante-legal-form-create">
    <?= $this->render('@frontend/views/aaee/correccion-representante-legal/correccion-rep-legal-form', [
						        'model' => $model,
						        'datos' => $datos,
						        'subCaption' => $subCaption,
						        'dataProvider' => $dataProvider,
						        'listaNaturaleza' => $listaNaturaleza,
						        'rutaAyuda' => $rutaAyuda,

					    ]) ?>
</div>
