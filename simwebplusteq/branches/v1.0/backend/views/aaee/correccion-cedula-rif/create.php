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
 *  @file create.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 31-10-2015
 *
 *  @view create.php
 *  @brief vista que canaliza la salida de los formularios, segun sus requerimientos, create del formulario correccion-cedula-rif-form.php
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

	$this->title = Yii::t('backend', 'Update of DNI');

?>
	<div class="correccion-cedula-rif-form-create">

	    <?= $this->render('correccion-cedula-rif-form', [
	        'model' => $model,
	        'datosContribuyente' => $datosContribuyente,
	        'dataProvider' => $dataProvider,
	        'msjErrorLista' => $msjErrorLista,
	    ]) ?>

	</div>
