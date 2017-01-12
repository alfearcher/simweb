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
<body onload = "bloquea()"/>


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
                                                    <td>
                                                        <div class="col-lg-50">
                                                            <?= Yii::t('backend', 'Choose to Integrate Urban Property') ?>
                                                         </div> 

                                                    </td>

                                                </tr>
                                                
                                                <tr>
                        
                        
                                                    <td colspan="2"> 

                                                        <div class="col-lg-4">
                                                            <?= Yii::t('backend', 'Select the First Urban Property') ?>
                                                        </div>
                                                        <div class="col-lg-5" align='left'> 
                                                            
                                                            <?php 
                                                            $modelParametros = InmueblesUrbanosForm::find()->where(['id_contribuyente'=>$modelContribuyente->id_contribuyente])->asArray()->all();                                         
                                                            $listaParametros = ArrayHelper::map($modelParametros,'id_impuesto','direccion');  
                                                            ?>

                                                            <?= $form->field($model, 'direccion')->dropDownList($listaParametros, [ 
                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                    'style' => 'width:100px;',
                                                                                                                    'onchange' => 'bloquea()'
                                                                                                                    ])->label(false) ?> 
                                                        </div>
                                                    
                                                    </td>
                                                </tr>

                                                <tr>
                        
                        
                                                    <td colspan="2"> 

                                                        <div class="col-lg-4">
                                                            <?= Yii::t('backend', 'Select the Second Urban Property') ?>
                                                        </div>
                                                        <div class="col-lg-5" align='left'> 
                                                            
                                                            <?php 
                                                            $modelParametros = InmueblesUrbanosForm::find()->where(['id_contribuyente'=>$modelContribuyente->id_contribuyente])->asArray()->all();                                         
                                                            $listaParametros = ArrayHelper::map($modelParametros,'id_impuesto','direccion');  
                                                            ?>

                                                            <?= $form->field($model, 'direccion2')->dropDownList($listaParametros, [ 
                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                    'style' => 'width:100px;',
                                                                                                                    'onchange' => 'bloquea()'
                                                                                                                    ])->label(false) ?> 
                                                        </div>
                                                    
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                
                                                    <td colspan="2">
                                                
                                                        <div class="form-group"> 
<?= Html::beginForm();?> 
<?= Html::submitButton(Yii::t('backend', 'Accept'), ['class' => 'btn btn-primary', 'name'=>'AcceptSeller', 'value'=>'AcceptSeller']) ?>
<?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
<?= Html::endForm();?> 


                                                        </div>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div> 

                            
                                                      
 

<!-- Campos ocultos -->  
<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $modelContribuyente->id_contribuyente])->label(false) ?>
<?= $form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false) ?>

<?= $form->field($model, 'validacion')->hiddenInput(['value' => 4])->label(false) ?>

<?= Html::endForm();?> 
                                                  
                    </div>
            </div>
     </div> 
                       

            


<?php //$form->end(); ?>
 
</div><!-- inscripcionInmueblesUrbanos 
['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9']
-->                     
