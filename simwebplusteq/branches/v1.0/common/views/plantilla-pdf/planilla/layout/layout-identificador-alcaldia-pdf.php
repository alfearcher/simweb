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
 *  @file layout-identificador-alcaldia-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-10-2016
 *
 *  @view layout-identificador-alcaldia-pdf.php
 *
 *
 * layout que genera el logo de la AlcaLdia y los datos de identificacion de la misma
 * como Estado, Municipio, Direccion de Rentas y Rif de la Alcandia
 *
 *
 *  @inherits
 *
 */

 	//use yii\web\Response;
 	use kartik\icons\Icon;
	use yii\helpers\Html;
	use yii\helpers\Url;
	//use yii\widgets\ActiveForm;
	use yii\web\View;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

 ?>

<div class="row">
	<div class="col-sm-3" id="logo">
		<?=Html::img('@common/public/imagen/customize/logo.jpg');
		?>
	</div>
</div>




