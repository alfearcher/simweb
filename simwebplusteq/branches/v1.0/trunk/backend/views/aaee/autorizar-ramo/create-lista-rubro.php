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
 *  @file create-lista-rubro.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19-10-2015
 *
 *  @view create-lista-rubro.php
 *  @brief vista que redirecciona a la vista que permite renderizar una lista de rubros.
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

	//$this->title = Yii::t('backend', 'List of Category');

	?>
	<div class="crear-lista-rubro">

	    <?= $this->render('lista-rubro', [
	    	'model' => $model,
	        'dataProvider' => $dataProvider,
	    ]) ?>

	</div>