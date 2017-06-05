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
 *  @file _pre-referencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 23-02-2017
 *
 *  @view _pre-referencia.php
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


 ?>


<div class="row" style="width:100%;">
	<?=$this->render('/recibo/pago/individual/pre-referencia-form',[
										'model' => $model,
										'caption' => $caption,
										'subCaption' => $subCaption,
										'datosBanco' => $datosBanco,
										'url' => $url,
										'datosRecibo' => $_SESSION['datosRecibo'],
										'dataProviders' => $dataProviders,
										'htmlSerialForm' => $htmlSerialForm,
										'htmlSerialAgregado' => $htmlSerialAgregado,
										'totalPlanilla' => $totalPlanilla,
										'cantidadDeposito' => $cantidadDeposito,
			]);?>
</div>