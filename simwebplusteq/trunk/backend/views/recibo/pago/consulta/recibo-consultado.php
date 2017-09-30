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
 *  @file recibo-consultado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 30-07-2017
 *
 *  @view recibo-consultado.php
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
	use yii\widgets\ActiveForm;
	use yii\web\View;
 ?>

<?php
    $form = ActiveForm::begin([
        'id' => 'id-recibo-consultado',
        'method' => 'post',
        //'action' => '#',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'enableClientScript' => false,
    ]);
?>

<div class="row" style="width:100%;margin:0px;padding:0px;margin-left:15px;">
    <div class="row" style="width: 100%;border-bottom: 1px solid #ccc;background-color:#F1F1F1;">
        <h4><strong><?=Html::encode(Yii::t('backend', 'Recibo de pago Nro. ') . $recibo)?></strong></h4>
    </div>

    <div class="row" style="width: 100%;margin:0px;padding:0px;margin-left:10px;">
        <?=$htmlDatoRecibo;?>
    </div>

    <div class="row" style="width: 100%;margin:0px;padding:0px;margin-left:10px;">
        <?=$htmlDepositoDetalle;?>
    </div>

    <div class="row" style="width: 100%;margin:0px;padding:0px;margin-left:10px;">
        <?=$htmlRegistroTxtRecibo;?>
    </div>
</div>

<?php ActiveForm::end(); ?>

