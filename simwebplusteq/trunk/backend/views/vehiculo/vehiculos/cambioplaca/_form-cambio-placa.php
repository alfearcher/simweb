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
 *  @file _form-cambio-placa.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 20/08/2015
 * 
 *  @brief Vista donde se encuentra un input para cambiar el codigo de placa
 *  @property
 *
 *  
 *  @method
 *    
 *  @inherits
 *  
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>

<!-- VARIABLE QUE MANEJA EL MENSAJE DE ERROR -->
<?= $msg ?>
<div class="vehiculos-form-form">
    <?php $form = ActiveForm::begin([
                                'id' => 'form-vehiculos-form-inline',
                                'method' => 'post',
                                'enableClientValidation' => true,
                                'enableAjaxValidation' => true,
                                'enableClientScript' => true,
                            ]);    
                        ?> 
        <div style="margin-left:600px;margin-top:150px;" class="container">
            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <?= Yii::t('backend', 'New License Plate') ?>
                    </div>
                    <!-- PLACA -->
                    <div class="panel-body">
                        <?= $form->field($model, 'placa')->textInput(['maxlength' => 12]) ?>
                        <?= Html::activeHiddenInput($model, 'id_vehiculo') ?>
                    </div>
                    <!-- FIN PLACA -->
                    <div class="modal-footer">  
                        <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-primary']) ?>
                     </div>
                </div>
            </div>
        </div>                    
    <?php ActiveForm::end(); ?>
</div>