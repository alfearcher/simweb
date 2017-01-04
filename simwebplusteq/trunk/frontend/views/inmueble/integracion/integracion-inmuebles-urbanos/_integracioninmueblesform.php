<?php
session_start();
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Dropdown;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\widgets\DetailView;

use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\BaseHtml;
use yii\web\UrlManager;
use yii\base\Component;
use yii\base\Object;
use yii\helpers\Url;


use backend\models\inmueble\ParametrosNivelesCatastro;

use backend\models\inmueble\InmueblesUrbanosForm;
use backend\models\inmueble\CambioPropietarioInmueblesForm;
use backend\models\inmueble\Estados;
use backend\models\inmueble\Municipios;
use backend\models\ContribuyentesForm;
/* @var $this yii\web\View */
/* @var $model backend\models\InscripcionInmueblesUrbanosForm */
/* @var $form ActiveForm */
$this->title = Yii::t('backend', 'Integration of Property Urban'). '<p>Id Taxpayer: ' . $modelContribuyente->id_contribuyente.'</p>';
 

 ?>

<script type="text/javascript">
function bloquea1() { 

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
<script type="text/javascript">
function cambioPropietario() { 
        document.getElementById("seller").style.display='none'; 
        document.getElementById("buyer").style.display='';
        //document.getElementById("tipo").style.display='';
        //document.getElementById("contribuyentesform-cedulabuscar").style.display='';
               
    } 
function bloquea() { 
if ($( "input:checked" ).val() == 1) { 
  
        $("#tipo").hide();
        
    } else { 

        $("#tipo").show();
               
    } 

if ($( "input:checked" ).val() == 1) { 
  
        $("#tipo2").hide();
        
    } else { 

        $("#tipo2").show();
               
    } 

    if (document.getElementById("inmueblesurbanosform-operacion").value==1) { 
        document.getElementById("seller").style.display=''; 
        document.getElementById("buyer").style.display='none'; 
        
    } 

    if (document.getElementById("inmueblesurbanosform-operacion").value==2) { 
        document.getElementById("buyer").style.display='';
        document.getElementById("seller").style.display='none';
               
    }
} 

function tipo() { 
//alert('aqui llegue');$( "input:checked" ).val()

    if ($( "input:checked" ).val() == 1) { 
  
        $("#tipo").hide();
        $("#tipo2").hide();
        //document.getElementById("tipo").style.display=''; 
        //document.getElementById("contribuyentesform-cedulabuscar").style.display='none'; 
        
    } else { 
        // alert('valor 0');
        $("#tipo").show();
        $("#tipo").show();
        //document.getElementById("tipo").style.display='';
        //document.getElementById("contribuyentesform-cedulabuscar").style.display='';
               
    } 
}
function tipo2() { 
//alert('aqui llegue');$( "input:checked" ).val()

    if ($( "input:checked" ).val() == 1) { 
        //alert('valor 1');
        $("#tipo").hide();
        //document.getElementById("tipo").style.display=''; 
        //document.getElementById("contribuyentesform-cedulabuscar").style.display='none'; 
        
    } else { 
        // alert('valor 0');
        $("#tipo").show();
        //document.getElementById("tipo").style.display='';
        //document.getElementById("contribuyentesform-cedulabuscar").style.display='';
               
    } 
} 


function naturaleza( val ){

var tu = val;
//alert('Hola'+tu);
  var variablejs = document.getElementById("inmueblesurbanosform-naturalezabuscar").value;
  //alert('alert'+tu);
  var variablejs2 = $('#inmueblesurbanosform-naturalezabuscar').val();
 //alert(tu); 

 //var miVariable = "Esto es una variable en JS";
document.cookie ='variablephp='+tu;
Session["variablephp"] = tu;

//objp.Session["variablephp"] = ''+tu;
//$("p").html("<b>Single:</b> " + variablejs2);

}



</script> 
<body onload = "bloquea1()"/>


<?php 
//$_SESSION['variablephp'] = "<script>document.write(tu)</script>";
$variablephp=$_COOKIE['variablephp'];
//$variablephp=$_SESSION['variablephp'];

//$variablephp = $variablejs2;
//$variablephp= $_GET['naturalezaBuscar'];
//$variablephp= '<script language="javascript" type="text/javascript">document.write(tu);</script>';

//$_SESSION['variablephp'] = "<script>document.write(variablejs2)</script>";
//$variablephp = $_SESSION['variablephp'];
//$variablephp = "<script>document.write(tu)</script>"
?>
<div class="inscripcionInmueblesUrbanos">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>




<div class="col-sm-15 ">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                
                    
<div class="panel panel-primary">
<div class="panel-heading">
<?= Yii::t('backend', 'Choose to Integrate Urban Property') ?>
</div> 

                                                    
                                                
                                                <div class="row" style="margin-left:20px; margin-top:40px;">
                                                    

                                                        <div class="col-sm-4">
                                                            <?= Yii::t('backend', 'Select the First Urban Property') ?>
                                                        </div>
                                                        <div class="col-sm-5" align='left'> 
                                                            
                                                            <?php 

                                                            $modelParametros = InmueblesUrbanosForm::find()->where(['id_contribuyente'=> $_SESSION['idContribuyente']])->asArray()->all();                                         
                                                            $listaParametros = ArrayHelper::map($modelParametros,'id_impuesto','direccion');  
                                                            ?>

                                                            <?= $form->field($model, 'direccion1')->dropDownList($listaParametros, [ 
                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                    'style' => 'width:200px;',
                                                                                                                    'onchange' => 'bloquea()'
                                                                                                                    ])->label(false) ?> 
                                                        </div>
                                                </div>  
                                                <div class="row" style="margin-left:20px; margin-top:40px;"> 

                                                        <div class="col-sm-4">
                                                            <?= Yii::t('backend', 'Select the Second Urban Property') ?>
                                                        </div>
                                                        <div class="col-sm-5" align='left'> 
                                                            
                                                            <?php 
                                                            $modelParametros = InmueblesUrbanosForm::find()->where(['id_contribuyente'=> $_SESSION['idContribuyente']])->asArray()->all();                                         
                                                            $listaParametros = ArrayHelper::map($modelParametros,'id_impuesto','direccion');  
                                                            ?>

                                                            <?= $form->field($model, 'direccion2')->dropDownList($listaParametros, [ 
                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                    'style' => 'width:200px;',
                                                                                                                    'onchange' => 'bloquea()'
                                                                                                                    ])->label(false) ?> 
                                                        </div>
                                                 </div>   
 </div>                                                   
<div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Property Specifications') ?> 
</div>
                        <div class="row" style="margin-left:20px; margin-top:40px;">
                                                   
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Street Addres') ?>
                            </div> 
                        

                        
                            <div class="col-sm-3"> 
                            <?= $form->field($model, 'direccion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 
                        
                            
                            
                        
                   </div>
 <!-- Direccio del domicilio -->                    
                   <div class="row" style="margin-left:20px;">
                        
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
                        
                  
                   <div class="row" style="margin-left:20px;">
                        
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
                            <?= Yii::t('backend', 'Year') ?>
                            </div> 
                            <div class="col-sm-2"> 
                            <?= $form->field($model, 'ano_inicio')->textInput(['style' => 'width:80px;'])->label(false)/*->input('date', 
                                                                           [
                                                                              //'value' => date('d-m-Y'),
                                                                              'type' => 'date',
                                                                              'style' => 'width:160px;'
                                                                              //'format' => 'yyyy-mm-dd',
                                                                           ])*/  ?>  
                            </div> 
                        
                                                 
                        
                   </div>

                   <div class="row" style="margin-left:20px;">
                        
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
                        
                                                 

                    </div> 
</div>    
                                                
                                                <div class="row" style="margin-left:20px;">
                                                        <div class="form-group"> 
<? //= Html::beginForm();?> 
<div class="col-sm-2">
<?= Html::submitButton(Yii::t('frontend', 'Aceptar'), ['class' => 'btn btn-success',
                                      'data' => [
                                                  'confirm' => Yii::t('app', 'Are you sure you want to Incorporate this item?'),
                                                  'method' => 'post',],]) ?> 
</div>
<div class="col-sm-2">
<?= Html::a(Yii::t('backend', 'Back'), ['/menu/menu-vertical'], ['class' => 'btn btn-danger']) ?>
</div>
<? //= Html::endForm();?> 


                                                        </div>
                                                    </div> 
                                        </div> 

                            
                                                      
 

<!-- Campos ocultos -->  
<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $modelContribuyente->id_contribuyente])->label(false) ?>
<?= $form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false) ?>

<?= $form->field($model, 'validacion')->hiddenInput(['value' => 4])->label(false) ?>

<?//= Html::endForm();?> 
<?php ActiveForm::end(); ?>                                                   
                    </div>
            </div>
     </div> 
                       

            


<?php //$form->end(); ?>
 
</div><!-- inscripcionInmueblesUrbanos 
['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9']
-->                     
