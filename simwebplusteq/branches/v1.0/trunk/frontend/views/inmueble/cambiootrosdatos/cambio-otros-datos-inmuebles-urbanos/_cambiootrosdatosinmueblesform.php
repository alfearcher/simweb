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
$this->title = Yii::t('frontend', 'Property Update');
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
                    <div class="row">

                        <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Id Tax') ?>
                        </div> 

                        <div class="col-sm-2">
                           <?= $form->field($datos, 'id_impuesto')->textInput(
                                                                   [
                                                                   'readOnly'=>true,
                                                                   'id'=> 'id_impuesto',
                                                                   ])->label(false);
                            ?>
                    
                        </div>

                        <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?>
                        </div> 

                        <div class="col-sm-4">
                            <?= $form->field($datos, 'direccion')->textInput(
                                                                    [
                                                                    'readOnly'=>true,
                                                                    'id'=> 'direccion',
                                                                    ])->label(false);
                            ?>
                    
                        </div>   
                    </div>  


                   <div class="row">
                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?>
                            </div> 
                        

                        
                            <div class="col-sm-3"> 
                            <?= $form->field($model, 'direccion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        
                            <div class="col-sm-1">
                            </div>
                        
                            
                        
                   </div>
 <!-- Direccio del domicilio -->                    
                   <div class="row">
                        
                            <div class="col-sm-2" > 
                            <?= Yii::t('backend', 'Hse/Building/Ctryhse') ?>
                            </div>
                        

                          
                            <div class="col-sm-2">
                            <?= $form->field($model, 'casa_edf_qta_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div> 
                        

                         
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Floor/Level') ?>
                            </div> 
                        

                        
                            <div class="col-sm-2">
                            <?= $form->field($model, 'piso_nivel_no_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>
                        

                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Apartment/Num') ?>
                            </div>
                        

                        
                            <div class="col-sm-2">
                            <?= $form->field($model, 'apto_dom')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>
                    </div> 
                        
                  
                   <div class="row">
                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Phone') ?>
                            </div> 
                        

                        
                            <div class="col-sm-2"> 
                            <?= $form->field($model, 'tlf_hab')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>
                        

                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Meter') ?>
                            </div>
                        

                        
                            <div class="col-sm-2">
                            <?= $form->field($model, 'medidor')->textInput(['style' => 'width:100px;'])->label(false) ?>
                            </div>
                        

                        
                            <div class="col-sm-2">
                            <?= $form->field($model, 'id_contribuyente')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        

                         
                            <div class="col-sm-2">
                            <?= $form->field($model, 'id_impuesto')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                                      
                        
                   </div>

                   <div class="row">
                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Observation') ?>
                            </div>
                        

                        
                            <div class="col-sm-3">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 

                            <div class="col-sm-1">
                            </div> 
                        
                            <div class="col-sm-2"> 
                            <?= $form->field($model, 'tipo_ejido')->textInput(['style' => 'width:100px;'])->checkbox() ?>
                            </div> 
                        

                        
                            <div class="col-sm-2">
                            <?= $form->field($model, 'inactivo')->checkbox()?> 
                            </div> 

                                                 

                    </div>     
                    <div class="row">

                             
                                <? //= Html::beginForm();?>
                                <div class="col-sm-1">
                                <?= Html::submitButton(Yii::t('frontend', 'Accept'), ['class' => 'btn btn-success',
                                      'data' => [
                                                  'confirm' => Yii::t('app', 'Are you sure you want to Update this item?'),
                                                  'method' => 'post',], 'name'=>'Accept', 'value'=>'Accept']) ?>
                                </div>
                                <div class="col-sm-1">
                                <?= Html::a(Yii::t('frontend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger']) ?>
                                </div>
                                <? //= Html::endForm();?> 
                                
                    </div>
                                                                       
                        
                   

                    
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
<?//= Html::endForm();?> 
<?php ActiveForm::end(); ?> 



</div><!-- inscripcionInmueblesUrbanos -->

