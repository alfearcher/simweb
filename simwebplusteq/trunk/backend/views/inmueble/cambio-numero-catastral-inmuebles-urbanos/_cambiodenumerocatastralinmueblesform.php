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
$this->title = Yii::t('backend', 'Property Update'). '<p>Id Tax: ' . $model->id_impuesto.'</p>';
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
    'enableAjaxValidation' => false,
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
                            <div class="col-sm-2">
                            <?= $form->field($model, 'id_contribuyente')->textInput(['readOnly'=>true,'style' => 'width:80px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 100px" align="letf">                            
                            <div class="col-sm-2">
                            <?= $form->field($model, 'inactivo')->checkbox(['disabled'=>$disabled,'style' => 'width:50px;'])?> 
                            </div>
                        </td>

                        <td colspan="9" style="max-width: 100px" align="letf">
                            <div class="col-sm-5">  
                            <?= $form->field($model, 'propiedad_horizontal')->checkbox(['id'=> 'propiedadhorizontal', 
                                                                                         'style' => 'width:50px;',
                                                                                         'onclick'=>'bloquea()',
                                                                                         //'onchange' =>'bloquea()',
                                                                                         
                                                                                     ]); 

                                               ?>
                            </div>

                        </td>
                    </tr>
                                                    
<!-- Direccion de Catastro  -->         
                   <tr>
                        <td style="max-width: 85px" align="right"> 
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Edo.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'estado_catastro')->textInput(['style' => 'width:80px;'])->label(false);?> 
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Mnp.') ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'municipio_catastro')->textInput(['style' => 'width:80px;'])->label(false); ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Prq.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'parroquia_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Amb.') ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'ambito_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Sct.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'sector_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Mzn.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'manzana_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>
                   </tr>
<!-- Tipo de Domicilios del catastro -->
                   <tr>
                        <td style="max-width: 85px" align="right">                   
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Plot') ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'parcela_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1" id="subparcela" style="display:none"> 
                            <?= Yii::t('backend', 'Sub-plot') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">                    
                            <div class="col-sm-1" id="subparcelac" style="display:none">
                            <?= $form->field($model, 'subparcela_catastro')->textInput(['style' => 'width:80px;'])->label(false) //<?= $form->field($model, 'capa_subparcela')->textInput(['style' => 'width:80px;display:block;', 'id' => 'ms2', 'disabled'=>'disabled' ])->label(false) , 'id' => 'ms1' display:none;?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">                           
                           <div class="col-sm-1" id="level" style= 'display:none'>
                            <?= Yii::t('backend', 'Level') ?> <!--</legend> -->
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1" id="levelc" style="display:none">
                            <?php
                                $modelParametros = ParametrosNivelesCatastro::find()->asArray()->all();                                         
                                $listaParametros = ArrayHelper::map($modelParametros,'codigo','descripcion'); 
                                //echo'<pre>'; var_dump($listaParametros); echo '</pre>'; die(); ?> 
                            <?= $form->field($model, 'nivela')->dropDownList($listaParametros, [ 
                                                                                                            'id'=> 'parametro', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:80px;',
                                                                                                           /*'onchange' =>
                                                                                                                '$.post( "' . Yii::$app->urlManager
                                                                                                                                       ->createUrl('parroquias/lists') . '&municipio=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                                            $( "select#parroquias" ).html( data );
                                                                                                                                                                                                            });' 
                                                                                                           */ ],['style' => 'width:80px;'])->label(false);
                                                                                                          ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-2" id="levelc2" style="display:none">
                            <?= $form->field($model, 'nivelb')->dropDownList(['prompt' => Yii::t('backend', 'Select'),
                                                                                        '00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09',
                                                                                        '10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19',
                                                                                        '20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29',
                                                                                        '30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39',
                                                                                        '40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49',
                                                                                        '50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59',
                                                                                        '60'=>'60','61'=>'61','62'=>'62','63'=>'63','64'=>'64','65'=>'65','66'=>'66','67'=>'67','68'=>'68','69'=>'69',
                                                                                        '70'=>'70','71'=>'71','72'=>'72','73'=>'73','74'=>'74','75'=>'75','76'=>'76','77'=>'77','78'=>'78','79'=>'79',
                                                                                        '80'=>'80','81'=>'81','82'=>'82','83'=>'83','84'=>'84','85'=>'85','86'=>'86','87'=>'87','88'=>'88','89'=>'89',
                                                                                        '90'=>'90','91'=>'91','92'=>'92','93'=>'93','94'=>'94','95'=>'95','96'=>'96','97'=>'97','98'=>'98','99'=>'99',
                                                                                        ],['style' => 'width:80px;'])->label(false) ?> 

                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1" id="unidad1" style="display:none"> 
                            <?= Yii::t('backend', 'Unit') ?>
                            </div> 
                        </td>

                        <td colspan="4" style="max-width: 100px">
                            <div class="col-sm-1" id="unidad1c" style="display:none">
                            <?= $form->field($model, 'unidad_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                  
                        </td> 
                   </tr> 

                                                                                    
                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?>
                            </div> 
                        </td>

                        <td colspan="4" style="max-width: 100px">
                            <div class="col-sm-4"> 
                            <?= $form->field($model, 'direccion')->textarea(['disabled'=>$disabled,'maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Year home') ?>
                            </div> 
                        </td>

                        <td colspan="7" style="max-width: 100px">
                            <div class="col-sm-1"> 
                            <?= $form->field($model, 'ano_inicio')->textInput(['disabled'=>$disabled,'style' => 'width:100px;'])->label(false) ?> 
                            </div>                                                                          
                        </td>
                   </tr> 
 <!-- Direccio del domicilio -->                   
                                   
                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Phone') ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-2"> 
                            <?= $form->field($model, 'tlf_hab')->textInput(['disabled'=>$disabled,'style' => 'width:100px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Meter') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-lg-2">
                            <?= $form->field($model, 'medidor')->textInput(['disabled'=>$disabled,'style' => 'width:100px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td colspan="8" style="max-width: 100px">
                            <div class="col-lg-2">
                            <?= $form->field($model, 'id_impuesto')->hiddenInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                                      
                        </td>
                   </tr>

                   <tr>
                        <td style="max-width: 85px" align="right">
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Observation') ?>
                            </div> 
                        </td>

                        <td colspan="4" style="max-width: 100px">
                            <div class="col-lg-4">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td colspan="2" style="max-width: 100px">                                               
                            <div class="col-lg-2"> 
                            <?= $form->field($model, 'tipo_ejido')->checkbox(['disabled'=>$disabled,'style' => 'width:50px;']) ?>
                            </div>
                        </td>

                        <td colspan="5" style="max-width: 100px"> 
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


<?= $form->field($model, 'validacion')->hiddenInput(['value' => '3'])->label(false) ?> 
<?//= Html::endForm();?> 

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

  <div class="col-lg-1">
                            <? //= $form->field($model, 'nivel_catastro')->textInput([])->label(false) ?>
                            </div>