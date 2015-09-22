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
                    <tr>
                        <td colspan="5"> 

                            <div class="col-lg-1"> 

                            <?= Yii::t('backend', 'IdTaxpayer') ?>
                            </div> 

                            <div class="col-lg-2">
                            <?= $form->field($model, 'id_contribuyente')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Cadastre') ?>
                            </div> 

                            <div class="col-lg-2">  
                            <?= $form->field($model, 'catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Id Sim') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'id_sim')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-2">
                            <?= $form->field($model, 'inactivo')->checkbox()?> 
                            </div> 

                            <div class="col-lg-3"> 
                            <?= $form->field($model, 'propiedad_horizontal')->checkbox(['id'=> 'propiedadhorizontal', 
                                                                                         'disabled'=>$disabled,
                                                                                         'onchange' =>
                                                                                         '$.post( "' . Yii::$app->urlManager->createUrl('unidaddepartamento/lists') . '&id=' . '" + $(this).val(), function( data ) {
                                                                                          $( "select#unidades" ).html( data ); });' 
                                                                                     ]); ?>
                            </div> 

                        </td>
                    </tr>
                                                    
<!-- Direccion de Catastro  -->         
                   <tr>
                        <td  colspan="6">

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Edo.') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'estado_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false);?> 
                            </div>

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Mnp.') ?>
                            </div>  

                            <div class="col-lg-1">
                            <?= $form->field($model, 'municipio_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false); ?>
                            </div>

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Prq.') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'parroquia_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Amb.') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'ambito_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Sct.') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'sector_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Mzn.') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'manzana_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 


                        </td>
                   </tr>

                   <tr>
                        <td>
                           
     <!-- Tipo de Domicilios del catastro --> 

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Plot') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'parcela_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div>

                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Sub-plot') ?>
                            </div>  
                    
                            <div class="col-lg-1">
                            <?= $form->field($model, 'subparcela_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 
                           
                            <div class="col-lg-1">
                            <?= Yii::t('backend', 'Level') ?> 
                            </div> 
                            


                            <div class="col-lg-1">
                            <?= $form->field($model, 'nivel_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div> 
                            
                           
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Unit') ?>
                            </div> 

                            <div class="col-lg-1">
                            <?= $form->field($model, 'unidad_catastro')->textInput(['disabled'=>$disabled,'style' => 'width:80px;'])->label(false) ?>
                            </div>                                          
                        </td> 
                   </tr>

                                                                                    
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

                            <div class="form-group"> 
                            <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-success','data' => [
                                                          'confirm' => Yii::t('app', 'Are you sure you want to Update this item?'),
                                                          'method' => 'post',
            ],]) ?>
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
<?php ActiveForm::end(); ?> 



</div><!-- inscripcionInmueblesUrbanos -->

<!-- 
<tr>
                        <td><div class="col-lg-5">
                            
                            </div>                                                                          
                        </td>
                   </tr>
                   <tr>
                        <td><div class="col-lg-5">
                            <? // = $form->field($model, 'lote_1') ?>
                            </div>                                                                          
                        </td>
                   </tr>
                   <tr>
                        <td><div class="col-lg-5">
                            <? // = $form->field($model, 'lote_2') ?>
                            </div>                                                                          
                        </td>
                   </tr>
                   <tr>
                        <td><div class="col-lg-5">
                        <? // = $form->field($model, 'lote_3') ?>
                        </div>                                                                          
                        </td>
                   </tr>  <? // = $form->field($model, 'estado_catastro')->label(false) ?>
                   <? // = $form->field($model, 'municipio_catastro')->label(false) ?>


select E.estado,M.municipio,P.parroquia,A.ambito,S.codigo_ambito,A.descripcion,S.sector,MZ.manzana from estados As E " & _
             "inner join municipios as M on E.estado=M.estado " & _
             "inner join parroquias as P on M.estado=P.estado and M.municipio=P.municipio " & _
             "inner join sectores as S on P.estado=S.estado and P.municipio=S.municipio and P.parroquia=S.parroquia " & _
             "inner join ambitos as A on S.ambito=A.ambito " & _
             "inner join urbanizaciones as U on S.id_cp=U.id_cp " & _
             "inner join manzanas as MZ on U.id_cp=MZ.id_cp and U.urbanizacion=MZ.urbanizacion " & _
             "inner join manzana_limites as ML on MZ.id_manzana=ML.id_manzana " & _
             "where ML.manzana_limite=" & Str(nIdManzanaLimite) 

<div class="form-group">
        <? //= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<script>
function cambio(val) {
   
  // alert(''.val);
     $("#ver").val($("#tipo_propaganda").val());
       
    }
</script>

 'onchange' =>    'cambio()'