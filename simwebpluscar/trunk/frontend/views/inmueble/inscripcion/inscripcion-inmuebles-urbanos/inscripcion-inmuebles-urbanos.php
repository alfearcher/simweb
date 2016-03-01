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
$this->title = Yii::t('backend', 'Property Registration');
?>

<script type="text/javascript">
function bloquea() { 

/*if ($('input:checkbox[name="Inmuebles[propiedad_horizontal]"]:checked').val() == '0'){

    alert('estoy');
}*/
 //alert('estoy'+document.getElementById("propiedadhorizontal").checked);

       
    
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
                    <tr>
                        <td style="max-width: 85px" align="right"> 
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'IdTaxpayer') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-4">
                            <?= $form->field($model, 'id_contribuyente')->textInput(['readonly'=>'readonly','value'=>178131,'style' => 'width:80px;'])->label(false) //Yii::$app->user->identity->id_contribuyente?>
                            </div> 
                        </td>

                    </tr>
                                                    

<!-- Direccion y anio de domicilio --> 
                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?> 
                            </div> 
                        </td>

                        <td colspan="4" style="max-width: 100px" align="letf">
                            <div class="col-lg-4"> 
                            <?= $form->field($model, 'direccion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Year home') ?>
                            </div> 
                        </td>

                        <td colspan="7" style="max-width: 100px" align="letf">
                            <div class="col-lg-1"> 
                            <?= $form->field($model, 'ano_inicio')->textInput(['style' => 'width:80px;'])->label(false)/*->input('date', 
                                                                           [
                                                                              //'value' => date('d-m-Y'),
                                                                              'type' => 'date',
                                                                              'style' => 'width:160px;'
                                                                              //'format' => 'yyyy-mm-dd',
                                                                           ])*/  ?>  
                            </div>                                                                          
                        </td>
                   </tr>
 <!-- Direccion del domicilio -->                   
                   <tr> 
                        <td colspan="2" style="max-width: 85px" align="right">
                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Hse/Building/Ctryhse') ?>
                            </div>  
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-2">
                            <?= $form->field($model, 'casa_edf_qta_dom')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Floor/Level') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-2">
                            <?= $form->field($model, 'piso_nivel_no_dom')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-2"> 
                            <?= Yii::t('backend', 'Apartment/Num') ?>
                            </div> 
                        </td>

                        <td  style="max-width: 100px" align="letf">
                            <div class="col-lg-2">
                            <?= $form->field($model, 'apto_dom')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Meter') ?>
                            </div> 
                        </td>

                        <td colspan="4" style="max-width: 100px" align="letf">
                            <div class="col-lg-2">
                            <?= $form->field($model, 'medidor')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                                                        
                        </td>
                    </tr>

<!-- Observacion y Tipo de ejido de domicilio --> 
                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Observation') ?> 
                            </div> 
                        </td>

                        <td colspan="4" style="max-width: 100px" align="letf">
                            <div class="col-lg-4">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div>    
                        </td>

                        <td colspan="2" style="max-width: 100px" align="letf">                                                                    
                            <div class="col-lg-2">
                            <?= $form->field($model, 'tipo_ejido')->checkbox(['style' => 'width:50px;']) ?>
                            </div> 
                        </td>

                        <td colspan="5" style="max-width: 100px" align="letf">
                            <div class="form-group"> 
                            <?= Html::submitButton(Yii::t('backend', 'Incorporate'), ['class' => 'btn btn-primary',
                                      'data' => [
                                                  'confirm' => Yii::t('app', 'Are you sure you want to Incorporate this item?'),
                                                  'method' => 'post',],]) ?>
                            <?= Html::a(Yii::t('backend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger']) ?>
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
<?= $form->field($model, 'catastro')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'tlf_hab')->hiddenInput(['style' => 'width:80px;','value' =>0])->label(false) ?>



<?= Html::endForm();?>

</div><!-- inscripcionInmueblesUrbanos -->

