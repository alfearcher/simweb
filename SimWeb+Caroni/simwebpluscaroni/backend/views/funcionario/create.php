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
 *  @date 07-07-2015
 * 
 *  @view create.php
 *  @brief vista que canaliza la salida de los formularios, segun sus requerimientos
 * 
 *  
 *  @property
 *
 *  
 *  @method
 *  
 *  
 *  @inherits
 *  
 */

use yii\helpers\Html;


	/** 
	*@var $this yii\web\View */
	/* @var $model backend\models\Funcionarios2Form */

	$this->title = Yii::t('backend', 'Create Funcionario');

	?>
	<div class="funcionario-form-create">

	    <?= $this->render('_form', [
	        'model' => $model, 'msg' => $msg
	    ]) ?>

	</div>
