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
 *  @date 09-05-2016
 *
 *  @view create.php
 *  @brief vista que que renderiza un formulario de carga de datos para la inscripcion
 *  del usuario como contribuyente de actividades economicas.
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

	$this->title = Yii::t('frontend', 'Register of Economic Activity');

	?>
	<div class="inscripcion-act-econ-form-create">
	    <?= $this->render('@frontend/views/aaee/inscripcion-actividad-economica/inscripcion-act-econ-form', [
	        							'model' => $model,
	        							'bloquear' => $bloquear,
	        							'url' => $url,
	        							'rutaAyuda' => $rutaAyuda,
	    ]) ?>

	</div>
