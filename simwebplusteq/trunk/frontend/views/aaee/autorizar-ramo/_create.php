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
 *  @date 11-08-2016
 *
 *  @view create.php
 *  @brief vista que canaliza la salida de los formularios.
 *
 */

	use yii\helpers\Html;


	/**
	*@var $this yii\web\View */

	$this->title = Yii::t('frontend', 'Autorizacion de Ramo(s)');

	?>
<div class="autorizar-ramo-form-create">
    <?= $this->render('@frontend/views/aaee/autorizar-ramo/seleccionar-ramo-autorizar-form', [
						        'model' => $model,
						        'datos' => $datos,
						        'subCaption' => $subCaption,
						        'añoCatalogo' => $añoCatalogo,
					  			'añoVenceOrdenanza' => $añoVenceOrdenanza,
						        'dataProvider' => $dataProvider,
						        'searchModel' => $searchModel,
						        'dataProviderSeleccionado' => $dataProviderSeleccionado,
						        'periodo' => $periodo,
						        'fechaDesde' => $fechaDesde,
						        'fechaHasta' => $fechaHasta,
						        'activarBotonCreate' => $activarBotonCreate,
						        'errorRubroSeleccionado' => $errorRubroSeleccionado,
						        'rutaAyuda' => $rutaAyuda,

					    ]) ?>
</div>



