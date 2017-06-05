<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

use backend\models\inmueble\ParametrosNivelesCatastro;
use backend\models\inmueble\Estados;
use backend\models\inmueble\Municipios;
/* @var $this yii\web\View */
/* @var $model backend\models\InscripcionInmueblesUrbanosForm */
/* @var $form ActiveForm */
$this->title = Yii::t('backend', 'Property Update'). '<p>Id Tax: ' . $model->id_impuesto;
?>
<div class="inscripcionInmueblesUrbanos">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>

<div class="container" style="width:1280px">
 <div class="col-sm-10" style="width:1230px">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                <table class="table table-striped "cellpadding="1px" cellspacing="1px">
                                             
<!-- Direccion de Catastro  -->         
                                                                                                       
                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?>
                            </div> 
                        </td>

                        <td colspan="2" style="max-width: 100px">
                            <div class="col-sm-3"> 
                            <?= $form->field($model, 'direccion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="right">
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Year home') ?>
                            </div> 
                        </td>

                        <td colspan="2" style="max-width: 100px">
                            <div class="col-sm-2"> 
                            <?= $form->field($model, 'ano_inicio')->textInput(['style' => 'width:100px;', 'readOnly'=>true])->label(false) ?> 
                            </div>
                        </td>
                   </tr>
 <!-- Direccio del domicilio -->                   
                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Hse/Building/Ctryhse') ?>
                            </div>
                        </td>

                        <td  style="max-width: 100px" align="right">  
                            <div class="col-sm-2">
                            <?= $form->field($model, 'casa_edf_qta_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 50px" align="right"> 
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Floor/Level') ?>
                            </div> 
                        </td>

                        <td style="max-width: 50px" align="right">
                            <div class="col-sm-2">
                            <?= $form->field($model, 'piso_nivel_no_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 50px" align="right">
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Apartment/Num') ?>
                            </div>
                        </td>

                        <td style="max-width: 50px" align="right">
                            <div class="col-sm-2">
                            <?= $form->field($model, 'apto_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 
                        </td>
                  
                   <tr>
                        

                        <td style="max-width: 200px" align="right">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Meter') ?>
                            </div>
                        </td>

                        <td style="max-width: 200px">
                            <div class="col-sm-2">
                            <?= $form->field($model, 'medidor')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 200px">
                            <div class="col-sm-2">
                            <?= $form->field($model, 'id_contribuyente')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 200px"> 
                            <div class="col-sm-2">
                            <?= $form->field($model, 'id_impuesto')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                                      
                        </td>
                   </tr>

                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Observation') ?>
                            </div>
                        </td>

                        <td colspan="2"style="max-width: 200px">
                            <div class="col-sm-3">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">                                                   
                            <div class="col-sm-2"> 
                            <?= $form->field($model, 'tipo_ejido')->textInput(['style' => 'width:100px;'])->checkbox() ?>
                            </div> 
                        </td>

                        

                        <td style="max-width: 100px">      

                            <div class="form-group"> 
<?= Html::beginForm();?>
<?= Html::submitButton(Yii::t('backend', 'Accept'), ['class' => 'btn btn-primary', 'name'=>'Accept', 'value'=>'Accept']) ?>
<?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
<?= Html::endForm();?> 
                            </div>
                                                                       
                        </td>
                   </tr>

                    
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Campos ocultos -->   
<?= $form->field($model, 'manzana_limite')->hiddenInput(['value' => 130])->label(false) ?> 
<?= $form->field($model, 'id_habitante')->hiddenInput(['value' => 123456])->label(false) ?>
<?= $form->field($model, 'liquidado')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'nivel')->hiddenInput(['value' => 0])->label(false) ?>



<?= $form->field($model, 'validacion')->hiddenInput(['value' => '2'])->label(false) ?> 
<? //= Html::endForm();?> 
<?php ActiveForm::end(); ?> 



</div><!-- inscripcionInmueblesUrbanos -->

