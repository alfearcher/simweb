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
 *  @file _view.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-10-2016
 *
 *  @view _view.php
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

 	use yii\web\Response;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;
	use common\mensaje\MensajeController;

 ?>

 <div class="view">
 	<div class="row">
		<?= MensajeController::actionMensaje($codigo); ?>
	 </div>
	 <div class="row" style="width: 100%;">
		<div class="well well-sm" style="color: blue;padding-left: 25px;padding-right: 25px;">
			<h4><?=Html::encode('Dentro de las 24 horas ingrese al sistema en Liquidación/Consulta, para imprimir las planillas de liquidación y pagar en el Banco.')?></h4>
		</div>
	 </div>
	 <div class="row" style="width: 100%;">
		<div class="well well-sm" style="color: blue;padding-left: 25px;padding-right: 25px;">
			<strong><h4><?=Html::encode('RECUERDE QUE DEBE REALIZAR SU DECLARACION ESTIMADA 2017.')?></h4></strong>
		</div>
	 </div>
	 <div class="row">
		<div class="solicitud-creada">
			<?= $this->render('@frontend/views/aaee/declaracion/definitiva/view-solicitud-create', [
	    															'model' => $model,
	    															'modelSearch' => $modelSearch,
	    															'opciones' => $opciones,
	    															'dataProvider' => $dataProvider,
	    															'historico' => $historico,
	    					]);
	    	?>
		</div>
	</div>
</div>