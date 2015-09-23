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
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-vertical'],]); ?>


<div class="col-sm-15 ">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                <table class="table table-striped ">
                                             
<!-- Direccion de Catastro  -->         
                                                                                                       
                   <tr>
                        <td colspan="4">

                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?>
                            </div> 

                            <div class="col-lg-4"> 
                            <?= $form->field($model, 'direccion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Year home') ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= $form->field($model, 'ano_inicio')->textInput(['style' => 'width:100px;'])->label(false) ?> 
                            </div> 


                        </td>
                   </tr>
 <!-- Direccio del domicilio -->                   
                   <tr>
                        <td colspan="6">
                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Hse/Building/Ctryhse') ?>
                            </div>  

                            <div class="col-lg-2">
                            <?= $form->field($model, 'casa_edf_qta_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Floor/Level') ?>
                            </div> 

                            <div class="col-lg-2">
                            <?= $form->field($model, 'piso_nivel_no_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Apartment/Num') ?>
                            </div> 

                            <div class="col-lg-2">
                            <?= $form->field($model, 'apto_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 
                        </td>
                  
                   <tr>
                        <td colspan="4">

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Phone') ?>
                            </div> 

                            <div class="col-lg-2"> 
                            <?= $form->field($model, 'tlf_hab')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Meter') ?>
                            </div> 

                            <div class="col-lg-2">
                            <?= $form->field($model, 'medidor')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>    

                            <div class="col-lg-2">
                            <?= $form->field($model, 'id_contribuyente')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                            <div class="col-lg-2">
                            <?= $form->field($model, 'id_impuesto')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                                      
                        </td>
                   </tr>

                   <tr>
                        <td colspan="2" >

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Observation') ?>
                            </div> 

                            <div class="col-lg-4">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div>                                                                          
                        
                            <div class="col-lg-2"> 
                            <?= $form->field($model, 'tipo_ejido')->textInput(['style' => 'width:100px;'])->checkbox() ?>
                            </div>  

                            <div class="col-lg-2">
                            <?= $form->field($model, 'inactivo')->checkbox()?> 
                            </div>      

                            <div class="form-group"> 
<?= Html::beginForm();?>
<?= Html::submitButton(Yii::t('backend', 'Accept'), ['class' => 'btn btn-primary', 'name'=>'Accept', 'value'=>'Accept']) ?>
<?= Html::endForm();?> 
                            </div>
                                                                       
                        </td>
                   </tr>

                    
                </table>
            </div>
        </div>
    </div>

<!-- Campos ocultos -->   
<?= $form->field($model, 'manzana_limite')->hiddenInput(['value' => 130])->label(false) ?> 
<?= $form->field($model, 'id_habitante')->hiddenInput(['value' => 123456])->label(false) ?>
<?= $form->field($model, 'liquidado')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'nivel')->hiddenInput(['value' => 0])->label(false) ?>



<?= $form->field($model, 'validacion')->hiddenInput(['value' => '2'])->label(false) ?> 
<?= Html::endForm();?> 
<?php //ActiveForm::end(); ?> 



</div><!-- inscripcionInmueblesUrbanos -->

