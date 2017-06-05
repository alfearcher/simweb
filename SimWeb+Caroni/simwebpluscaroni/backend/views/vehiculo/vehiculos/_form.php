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
 *  @file _form.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 23/07/2015
 * 
 *  @class Vehiculos
 *  @brief Vista de registro de Vehiculos
*   @property
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
use backend\models\vehiculo\UsosVehiculos;
use backend\models\vehiculo\TiposVehiculos;
use backend\models\vehiculo\ClasesVehiculos;

/* @var $this yii\web\View */
/* @var $model backend\models\VehiculosForm */
/* @var $form yii\widgets\ActiveForm */

?>
<style type="text/css">
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

/* webkit solution */
::-webkit-input-placeholder { text-align:right; }
/* mozilla solution */
input:-moz-placeholder { text-align:right; }
</style>

<script type="text/javascript">
    /*****************************************************************************
    Código para colocar los indicadores de miles  y decimales mientras se escribe
    Script creado por Tunait!    
    http://javascript.tunait.com
    tunait@yahoo.com  27/Julio/03
    Adaptación y Optimización del Script por Ing. Hansel J. Colmenarez G.
    http://inghanselcolmenarez.com.ve
    hanselcolmenarez@hotmail.com 10/Agosto/2015
    ******************************************************************************/
    function puntitos(donde,caracter,campo)
    {
        var decimales = true
        dec = campo
        pat = /[\*,\+,\(,\),\?,\\,\$,\[,\],\^]/
        valor = donde.value
        largo = valor.length
        crtr = true
        if(isNaN(caracter) || pat.test(caracter) == true)
            {
            if (pat.test(caracter)==true) 
                {caracter = "\\" + caracter}
            carcter = new RegExp(caracter,"g")
            valor = valor.replace(carcter,"")
            donde.value = valor
            crtr = false
            }
        else
            {
            var nums = new Array()
            cont = 0
            for(m=0;m<largo;m++)
                {
                if(valor.charAt(m) == "," || valor.charAt(m) == " " || valor.charAt(m) == ".")
                    {continue;}
                else{
                    nums[cont] = valor.charAt(m)
                    cont++
                    }
                
                }
            }

        if(decimales == true) {
            ctdd = eval(1 + dec);
            nmrs = 1
            }
        else {
            ctdd = 1; nmrs = 3
            }
        var cad1="",cad2="",cad3="",tres=0
        if(largo > nmrs && crtr == true)
            {
            for (k=nums.length-ctdd;k>=0;k--){
                cad1 = nums[k]
                cad2 = cad1 + cad2
                tres++
                if((tres%3) == 0){
                    if(k!=0){
                        cad2 = "." + cad2
                        }
                    }
                }
                
            for (dd = dec; dd > 0; dd--)    
            {cad3 += nums[nums.length-dd] }
            if(decimales == true)
            {cad2 += "," + cad3}
             donde.value = cad2
            }
        donde.focus()
    }   
</script>

<!-- VARIABLE QUE MANEJA EL MENSAJE DE ERROR -->
<?= $msg ?>

<!-- VARIABLE OCULTAS -->
<?= Html::activeHiddenInput($model, 'id_contribuyente', ['value' => '319306']) ?>
<?= Html::activeHiddenInput($model, 'nro_calcomania', ['value' => '319306']) ?>

<div class="vehiculos-form-form">
    <?php $form = ActiveForm::begin([
            'id' => 'form-vehiculos-form-inline',
            'method' => 'post',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,
        ]);    
    ?> 
    <div class="container" style="width:1580px">
        <div class="col-sm-10" style="width:1530px">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?= Html::encode($this->title) ?>
                </div>
                <div class="panel-body">
                    <table class="table table-striped" cellpadding="1px" cellspacing="1px" >
<!-- PLACA, MARCA, MODELO, AÑO DE COMPRAY AÑO DEL VEHICULO -->
                        <tr>
                            <td style="max-width: 150px" align="right">
                              <b><?= $model->getAttributeLabel('placa'); ?></b>
                            </td>
                            <td>
                                <?= $form->field($model, 'placa')->label(false)->textInput(['readonly' => $desabilitar,'maxlength' => 12, 'style' => 'width:250px;']) ?>
                            </td>
                            <td align="right">
                              <b><?= $model->getAttributeLabel('marca'); ?></b>
                            </td>
                            <td >
                                <?= $form->field($model, 'marca')->label(false)->textInput(['maxlength' => 15,'style' => 'width:150px;']) ?>
                            </td>
                            <td align="right">
                              <b><?= $model->getAttributeLabel('modelo'); ?></b>
                            </td>
                            <td>
                                <?= $form->field($model, 'modelo')->label(false)->textInput(['maxlength' => 15,'style' => 'width:150px;']) ?>
                            </td>
                            <td width="420px" align="center">
                              <b><?= $model->getAttributeLabel('ano_compra'); ?></b>
                            </td>
                            <td width="25px">
                                <?= $form->field($model, 'ano_compra')->label(false)->textInput(['maxlength' => 4, 'style' => 'width:80px;']) ?>
                            </td>
                            <td width="410px" align="center">
                              <b><?= $model->getAttributeLabel('ano_vehiculo'); ?></b>
                            </td>
                            <td width="160px">
                                <?= $form->field($model, 'ano_vehiculo')->label(false)->textInput(['maxlength' => 4,'style' => 'width:80px;']) ?>
                            </td>                            
                        </tr>
<!-- FIN DE PLACA, MARCA, MODELO, AÑO DE COMPRAY AÑO DEL VEHICULO -->


<!-- TIPO DE VEHICULO Y CLASE DE VEHICULO -->
                        <tr>
                            <td width="190px" align="right">
                                <b><?= $model->getAttributeLabel('clase_vehiculo'); ?></b>
                            </td>
                            <td colspan="3">
                                <?= $form->field($model, 'clase_vehiculo')->dropDownList(
                                                                    ArrayHelper::map(ClasesVehiculos::find()->all(), 'clase_vehiculo', 'descripcion'),
                                                                    [
                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                    'id'=> 'clase_vehiculo',
                                                                    'style' => 'width:500px;'
                                                                    ])->label(false);
                                ?>                                
                            </td>
                            <td width="250px" align="right">
                              <b><?= $model->getAttributeLabel('tipo_vehiculo'); ?></b>
                            </td>
                            <td colspan="2">
                                <?= $form->field($model, 'tipo_vehiculo')->dropDownList(
                                                                    ArrayHelper::map(TiposVehiculos::find()->all(), 'tipo_vehiculo', 'descripcion'),
                                                                    [
                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                    'id'=> 'tipo_vehiculo',
                                                                    ])->label(false);
                                ?>
                            </td>
                            <td width="190px" align="right">
                              <b><?= $model->getAttributeLabel('uso_vehiculo'); ?></b>
                            </td>                            
                            <td colspan="2">
                                <?= $form->field($model, 'uso_vehiculo')->dropDownList(
                                                                    ArrayHelper::map(UsosVehiculos::find()->all(), 'uso_vehiculo', 'descripcion'),
                                                                    [
                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                    'id'=> 'uso_vehiculo',
                                                                    ])->label(false);
                                ?>
                            </td>                        
                        </tr>
<!-- FIN DE TIPO DE VEHICULO Y CLASE DE VEHICULO -->

<!-- COLOR, NUMERO DE EJES, NUMERO DE PUESTOS, PESO Y PRECIO INICIAL -->
                        <tr>
                            <td width="200px" align="right">
                              <b><?= $model->getAttributeLabel('color'); ?></b>
                            </td>
                            <td>
                                <?= $form->field($model, 'color')->label(false)->textInput(['maxlength' => true,'style' => 'width:200px;']) ?>
                            </td>
                            <td align="right">
                              <b><?= $model->getAttributeLabel('no_ejes'); ?></b>
                            </td>
                            <td style="width:10px;">
                                <?= $form->field($model, 'no_ejes')->label(false)->textInput(['maxlength' => true]) ?>
                            </td> 
                            <td align="right">
                              <b><?= $model->getAttributeLabel('nro_puestos'); ?></b>
                            </td>
                            <td>
                                <?= $form->field($model, 'nro_puestos')->label(false)->textInput(['maxlength' => true]) ?>
                            </td>
                            <td align="right" width="15px">
                              <b><?= $model->getAttributeLabel('peso'); ?></b>
                            </td>
                            <td width="350px">
                                <?= $form->field($model, 'peso')->label(false)->textInput(['onkeyup' => 'puntitos(this,this.value.charAt(this.value.length-1),2)','placeholder' => Yii::t('backend', 'Weight in kgs')]) ?>
                            </td>
                            <td align="right">
                              <b><?= $model->getAttributeLabel('precio_inicial'); ?></b>
                            </td>
                            <td width="200px">
                                <?= $form->field($model, 'precio_inicial')->label(false)->textInput(['style' => 'width:160px;','onkeyup' => 'puntitos(this,this.value.charAt(this.value.length-1),2)','title' => Yii::t('backend', 'Recuerde Registrar los Montos en Bolivares Fuertes (Bs. F.)')]) ?>
                            </td>
                        </tr>
<!-- FIN DE COLOR, NUMERO DE EJES, NUMERO DE PUESTOS, PESO Y PRECIO INICIAL -->             

<!-- MEDIDA DE CAPACIDAD, CAPACIDAD, EXCESO, SERIAL DEL MOTOR Y SERIAL DE LA CARROCERIA -->
                        <tr>
                            <td colspan="4">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Capacity</legend>
                                    <div class="control-group">
                                        <div class="controls bootstrap-timepicker">
                                            <table class="table table-hover table-condensed">
                                                <tr>
                                                    <td>
                                                        <?= $form->field($model, 'medida_cap')->label(false)->radioList(array('Ton'=>'Tons', 'Kgs'=>'Kgs')); ?>
                                                    </td>
                                                    <td align="right">
                                                      <b><?= $model->getAttributeLabel('capacidad'); ?></b>
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'capacidad')->label(false)->textInput() ?>
                                                    </td>                        
                                                    <td width="130px" align="right">
                                                      <b><?= $model->getAttributeLabel('exceso_cap'); ?></b>
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'exceso_cap')->label(false)->textInput(['maxlength' => true]) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="2px" colspan="6"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>                                
                            </td>
                            <td colspan="6">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Serials</legend>
                                    <div class="control-group">
                                        <div class="controls bootstrap-timepicker">
                                            <table class="table table-hover table-condensed">
                                                <tr>
                                                    <td width="100px" align="right">
                                                      <b><?= $model->getAttributeLabel('serial_motor'); ?></b>
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'serial_motor')->label(false)->textInput(['maxlength' => true]) ?>
                                                    </td>
                                                    <td width="100px" align="right">
                                                      <b><?= $model->getAttributeLabel('serial_carroceria'); ?></b>
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'serial_carroceria')->label(false)->textInput(['maxlength' => true]) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="25px" colspan="4">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>                                
                            </td>                           
                        </tr>                        
<!-- FIN DE MEDIDA DE CAPACIDAD, CAPACIDAD, EXCESO, SERIAL DEL MOTOR Y SERIAL DE LA CARROCERIA -->

<!-- ID DEL VEHICULO, ESTATUS, NUMERO DE CALCOMANIA, FECHA DE INICIO, BOTON DE CREAR Y BORRAR -->
                        <tr>
                            <td rowspan="3" colspan="6">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">...</legend>
                                    <div class="control-group">
                                        <div class="controls bootstrap-timepicker">
                                            <table class="table table-hover table-condensed">
                                                <tr>
                                                    <td align="right">
                                                      <b><?= $model->getAttributeLabel('id_vehiculo'); ?></b>
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'id_vehiculo')->label(false)->textInput(['disabled' => 'disabled','maxlength' => true]) ?>
                                                    </td>
                                                    <td align="right">
                                                      <b><?= $model->getAttributeLabel('nro_calcomania'); ?></b>
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'nro_calcomania')->label(false)->textInput(['disabled' => 'disabled','maxlength' => true]) ?>
                                                    </td>                                                    
                                                    <td align="right"> 
                                                        <b><?= $model->getAttributeLabel('fecha_inicio'); ?></b>                             
                                                    </td>
                                                    <td>
                                                        <?= $form->field($model, 'fecha_inicio')->input('date', 
                                                                                                  [
                                                                                                    'id' => 'fecha-inicio',
                                                                                                    'type' => 'date',
                                                                                                    'style' => 'width:160px;height:32px;'
                                                                                                  ])->label(false); 
                                                        ?>
                                                    </td> 
                                                    <td colspan="3">
                                                        <?= $form->field($model, 'status_vehiculo')->label(false)->checkbox(['disabled' => 'disabled']) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td> 
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </fieldset>
                            </td>
                            <td style="vertical-align:middle;" align="center" colspan="2">
                              <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </td>
                            <td style="vertical-align:middle;" align="center" colspan="2">
                              <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-success']) ?>
                            </td>
                        </tr>
<!-- FIN DEL ID DEL VEHICULO, ESTATUS, NUMERO DE CALCOMANIA, FECHA DE INICIO ,BOTON DE CREAR Y BORRAR -->
                    </table>
                </div>
            </div>
        </div>        
    </div>
    <?php 
    /*
    *****************************************************
    *   Segmento para setear variables ocultas          *    
    *****************************************************
    */
    ?>
    <?= Html::activeHiddenInput($model, 'id_contribuyente', ['value' => 280069]) ?>
    <?php 
    /*
    *****************************************************
    *                       Fin del                     *
    *       Segmento para setear variables ocultas      *
    *****************************************************
    */
    ?>
<?php ActiveForm::end(); ?>
</div>
