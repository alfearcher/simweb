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
 *  @file resumen-cuenta-recaudadora.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 07-04-2017
 *
 *  @view resumen-cuenta-recaudadora
 *  @brief vista principal
 *
 */

 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\Pjax;
	use yii\bootstrap\Modal;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;

?>


<div class="row" style="width: 100%;padding: 0px;margin: 0px;margin-top: 10px;margin-left: -10px;">
	<div class="row" style="width:100%;border-bottom: 1px solid;">
		<h4><strong><?=Html::encode(Yii::t('backend', 'Cuenta Recaudadora'))?></strong></h4>
	</div>

<!-- LISTA DE BANCOS CON CUENTAS RECAUDADORAS-->
	<div class="row" style="width:100%;padding:0px;margin:0px;margin:0px;margin-top: 10px;">
		<div class="col-sm-2" style="width: 20%;padding:0px;">
			<p><strong><?=Html::encode(Yii::t('backend', 'Banco:'))?></strong></p>
		</div>
		<div class="col-sm-4" style="width:60%;padding:0px;margin-left:0px;">
			<?=Html::textInput('banco',
						       $datosBanco['nombre'],
						       [
						       		'id' => 'banco',
						       		'class' => 'form-control',
						       		'style' => 'width:100%;
						       					background-color:white;
						       					font-size:120%;
						       					font-weight:bold;',
						       		'readOnly' => true,
						       ])
			?>
		</div>
	</div>
<!-- FIN LISTA DE BANCOS CON CUENTAS RECAUDADORAS -->

<!-- LISTA DE CUENTA RECAUDADORAS -->
	<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 5px;">
		<div class="col-sm-2" style="width: 20%;padding:0px;">
			<p><strong><?=Html::encode(Yii::t('backend', 'Cuenta Recaudadora:'))?></strong></p>
		</div>
		<div class="col-sm-3" style="width:60%;padding:0px;margin-left:0px;">
			<?=Html::textInput('cuenta_recaudadora',
						       $datosBanco['cuenta_recaudadora'],
						       [
						       		'id' => 'id-cuenta-recaudadora',
						       		'class' => 'form-control',
						       		'style' => 'width:100%;
						       					background-color:white;
						       					font-size:120%;
						       					font-weight:bold;',
						       		'readOnly' => true,
						       ])
			?>
		</div>
	</div>
<!-- FIN DE LISTA DE CUENTA RECAUDADORAS -->

<!-- TIPO DE CUENTA RECAUDADORA -->
	<div class="row" style="width:100%;padding:0px;margin:0px;margin-top: 5px;">
		<div class="col-sm-2" style="width: 20%;padding:0px;">
			<p><strong><?=Html::encode(Yii::t('backend', 'Tipo:'))?></strong></p>
		</div>
		<div class="col-sm-4" style="width:60%;padding:0px;margin-left:0px;">
			<?=Html::textInput('tipo_cuenta',
						       $datosBanco['tipo_cuenta'],
						       [
						       		'id' => 'id-tipo-cuenta',
						       		'class' => 'form-control',
						       		'style' => 'width:100%;
						       					background-color:white;
						       					font-size:120%;
						       					font-weight:bold;',
						       		'readOnly' => true,
						       ])
			?>
		</div>
	</div>
<!-- FIN DE TIPO DE CUENTA RECAUDADORA -->
</div>
