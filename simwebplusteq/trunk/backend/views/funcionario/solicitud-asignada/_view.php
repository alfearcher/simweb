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
 *  @file view_solicitud_seleccionada.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view view_solicitud_seleccionada.php
 *  @brief vista del formualario que se utilizara para mostrar los datos principales
 *  de la solicitud seleccionada y los datos basicos del contribuyente.
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

 	//session_start();		// Iniciando session

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;


?>
<div class="row">
	<?= $this->render('/funcionario/solicitud-asignada/view-solicitud-seleccionada', [
																'model' => $model,
																'caption' => $caption,
																'subCaption' => $subCaption,
																'listado' => $listado,
																'url' => $url,
																'contribuyente' => $contribuyente,
																'viewDetalle' => $viewDetalle,
																'dataProvider' => $dataProvider,
																'dataProviderPlanilla' => $dataProviderPlanilla,
																'exigirDocumento' => $exigirDocumento,
																'errorChk' => $errorChk,
																'planillaNoSolvente' => $planillaNoSolvente,
				]);
	?>
</div>
