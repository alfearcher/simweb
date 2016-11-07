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
$this->title = Yii::t('backend', 'Property Update');
 ?>

<script language="javascript"> 



function activar(val) {
      
      var tit1=tit2=tit3=tit4=tit5='none';
        
        if (val=='1'){tit1='block';}else{tit2='block';}
      
    //alert('val'+val);
    document.getElementById("ms1").style.display=tit1;
    document.getElementById("ms2").style.display=tit2;    
} 


function bloquea() { 

/*if ($('input:checkbox[name="Inmuebles[propiedad_horizontal]"]:checked').val() == '0'){

    alert('estoy');
}*/
    if (document.getElementById("propiedadhorizontal").checked==1) { 
        document.getElementById("subparcela").style.display=''; 
        document.getElementById("subparcelac").style.display=''; 
        document.getElementById("level").style.display=''; 
        document.getElementById("levelc").style.display=''; 
        document.getElementById("levelc2").style.display=''; 
        document.getElementById("unidad1").style.display=''; 
        document.getElementById("unidad1c").style.display=''; 
        //readOnly = false
    } 

    if (document.getElementById("propiedadhorizontal").checked==0) { 
        document.getElementById("subparcela").style.display='none'; 
        document.getElementById("subparcelac").style.display='none';
        document.getElementById("level").style.display='none'; 
        document.getElementById("levelc").style.display='none';
        document.getElementById("levelc2").style.display='none';
        document.getElementById("unidad1").style.display='none'; 
        document.getElementById("unidad1c").style.display='none';
        
    }
} 

</script> 
<body onload = "bloquea()"/>
<div class="inscripcionInmueblesUrbanos">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-vertical'],]); ?>




<div class="container" style="width:1280px">
 <div class="col-sm-12" style="width:1230px">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                <table class="table table-striped ">
                    

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
                                                    
<!-- Direccion de Catastro  -->         
                   <div class="row">
                        
                            <div class="col-sm-1">
                            <?= Yii::t('backend', 'Edo.') ?>
                            </div> 
                        
                            <div class="col-sm-1">
                            <?= $form->field($model, 'estado_catastro')->textInput(['style' => 'width:80px;'])->label(false);?> 
                            </div>
                        
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Mnp.') ?>
                            </div>
                        

                        
                            <div class="col-sm-1">
                            <?= $form->field($model, 'municipio_catastro')->textInput(['style' => 'width:80px;'])->label(false); ?>
                            </div>
                        

                        
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Prq.') ?>
                            </div> 
                        
                            <div class="col-sm-1">
                            <?= $form->field($model, 'parroquia_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Amb.') ?>
                            </div>
                        
                            <div class="col-sm-1">
                            <?= $form->field($model, 'ambito_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Sct.') ?>
                            </div> 
                        
                            <div class="col-sm-1">
                            <?= $form->field($model, 'sector_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                       
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Mzn.') ?>
                            </div> 
                        
                            <div class="col-sm-1">
                            <?= $form->field($model, 'manzana_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 

                    </div>

 <!-- Direccio del domicilio -->                   
                                   
                  
                        
                   <div class="row">
                        
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Observation') ?>
                            </div> 
                        
                            <div class="col-lg-4">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 

                    </div>  
                    <div class="row">

                             
                                <?= Html::beginForm();?>
                                <div class="col-sm-1">
                                <?= Html::submitButton(Yii::t('frontend', 'Accept'), ['class' => 'btn btn-success',
                                      'data' => [
                                                  'confirm' => Yii::t('app', 'Are you sure you want to Update this item?'),
                                                  'method' => 'post',], 'name'=>'Accept', 'value'=>'Accept']) ?>
                                </div>
                                <div class="col-sm-1">
                                <?= Html::a(Yii::t('frontend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger']) ?>
                                </div>
                                <?= Html::endForm();?> 
                                
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


<?= $form->field($model, 'validacion')->hiddenInput(['value' => '3'])->label(false) ?> 
<? //= Html::endForm();?> 
<?php ActiveForm::end(); ?> 




</div>