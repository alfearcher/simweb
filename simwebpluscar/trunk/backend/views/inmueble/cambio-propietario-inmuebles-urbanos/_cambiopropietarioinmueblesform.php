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
$this->title = Yii::t('backend', 'Change of Ownership of Property Urban'). '<p>Id Taxpayer: ' . $modelContribuyente->id_contribuyente.'</p>';
 

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
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>




<div class="col-sm-15 ">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                <table class="table table-striped ">
                    

                    <tr>
                        <td colspan="2"> 

                             <?= DetailView::widget([
                                                        'model' => $modelContribuyente,
                                                        'attributes' => [ 
                                                        'id_contribuyente',
                                                        'cedula',
                                                        'nombres',
                                                        'apellidos',
                                                        'domicilio_fiscal',
                                                        'email',
                                                         ],
                                                         
                              ]) ?> 

                        </td>
                        
                    </tr>

               
                    
                    <tr colspan="2">
                        <td> 

                           <div class="col-sm-10 ">
                                <div class="panel panel-primary ancho-alto ">
                                       <div class="panel-heading">
                                       <?= Yii::t('backend', 'Request Tax') ?>
                                       </div> 
                                             <div class="panel-body" id="panelvendedor" style="display:">
                                                                             
                            

                                                       <?php $modelParametros = InmueblesUrbanosForm::find()->where(['id_contribuyente'=>$modelContribuyente->id_contribuyente])->asArray()->all();                                         
                                                             $listaParametros = ArrayHelper::map($modelParametros,'id_impuesto','direccion'); 
                                                             //echo'<pre>'; var_dump($modelParametros); echo '</pre>'; die(); 
                                                          
                                                       ?> 
                                                
                              
                                                        <div class="col-lg-50" id="mostrarinmueble" style="display:none">
                                                            <?= Yii::t('backend', 'Select Urban Property') ?>
                                                            <?= $form->field($model, 'id_impuesto')->dropDownList($listaParametros, [ 'id'=> 'id_impuesto', 
                                                                                                                                        'prompt' => Yii::t('backend', 'Select'),
                                                                                                                                        'style' => 'width:280px;',                                                                                                            
                                                                                                                                        ])->label(false); ?>
                                                        </div> 

                                                   
                                                        <div class="col-lg-50" id="mostrarinmueble" style="display:">
                                                        <?php 

                                                             

                                                             if(count($listaParametros) > 0){
                                                                 $listaOperaciones = array('1'=>Yii::t('backend', 'Change of Owner (Seller)'),
                                                                                           '2' => Yii::t('backend', 'Change of Owner (Buyer)'),);

                                                                echo $form->field($model, 'operacion')->dropDownList($listaOperaciones, [
                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                    'style' => 'width:100px;',
                                                                                                                    'onchange' => 'bloquea()'
                                                                                                                    ])->label(false);
                                                                                                  

                                                             } else { 
                                                                $listaOperaciones = array('1'=>Yii::t('backend', 'Change of Owner (Seller)'),);

                                                                echo $form->field($model, 'operacion')->dropDownList($listaOperaciones, [ 
                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                    'style' => 'width:100px;',
                                                                                                                    'onchange' => 'bloquea()'
                                                                                                                    ])->label(false);
                                                                
                                                             }
                                                        ?>
                                                        </div>
                                        </div>
                                    </div>
                            </div>  

                        </td>
                        <td>

                            <div class="col-sm-10 " id="seller" style="display:none">
                                    <div class="panel panel-primary ancho-alto ">
                                        <div class="panel-heading">
                                            <?= Yii::t('backend', 'Change of Owner (Seller)') ?>
                                        </div> 
                                        <div class="panel-body" id="panelvendedor" style="display:">
                                             
                                           <table class="table table-striped ">
                                                
                                                <tr>
                                                    <td colspan="2"> 

                                                        <div class="col-lg-4">
                                                            <?= Yii::t('backend', 'Select Urban Property') ?>
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
                                                    <td>
                                                        <div class="col-lg-50">
                                                            <?= Yii::t('backend', 'Search Details Buyer') ?>
                                                         </div> 

                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="4"> 

                                                        <div class="col-lg-2">
                                                        <?= Yii::t('backend', 'Year over:') ?>
                                                        </div> 


                                                        <div class="col-lg-2" align='left'> 
                                                        <?= $form->field($model, 'ano_traspaso1')->textInput()->label(false) ?> 
                                                        </div> 

                                                        <div class="col-lg-2">
                                                        <?= Yii::t('backend', 'type nature:') ?>
                                                        </div> 
                                                    
                                                        <div class="col-lg-4" align='left'> 
                                                        <?= $form->field($model, 'tipo_naturaleza1')->radioList([2 => Yii::t('backend', 'Legal'), 1 => Yii::t('backend', 'Persons')], ['itemOptions' => ['class' =>'radio-inline','id'=>'tipo_naturaleza1', 'onclick'=>'bloquea()' ]] )->label(false) ?>
                                                        </div> 
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"> 
                                                        <div class="col-lg-2">
                                                        <?= Yii::t('backend', 'Cedula/Rif') ?>
                                                        </div> 
                                                        <div class="col-lg-2" align='left'> 
                                                        <?= $form->field($model, 'naturalezaBuscar1')->dropDownList(['V'=>'V','E'=>'E','P'=>'P','J'=>'J'])->label(false) ?> 
                                                        </div>
                                                        <div class="col-lg-2" align='left'> 
                                                        <?= $form->field($model, 'cedulaBuscar1')->textInput()->label(false) ?> 
                                                        </div>
                                                        <div class="col-lg-2" align='left' id='tipo' style='display:none'> 
                                                        <?= $form->field($model, 'tipoBuscar1')->textInput()->label(false) ?> 
                                                        </div> 
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
                                    </div>
                            </div> 

                            <div class="col-sm-10 " id="buyer" style="display:none">
                                    <div class="panel panel-primary ancho-alto ">
                                        <div class="panel-heading">
                                            <?= Yii::t('backend', 'Change of Owner (Buyer)') ?>
                                        </div> 
                                        <div class="panel-body" id="panelcomprador">
                                             
                                           <table class="table table-striped ">
                                                <tr>
                                                    <td>
                                                        <div class="col-lg-50">
                                                            <?= Yii::t('backend', 'Search Details Seller') ?>
                                                         </div> 

                                                    </td>

                                                </tr> 

                                                <tr>
                                                    <td colspan="4"> 


                                                        <div class="col-lg-2">
                                                        <?= Yii::t('backend', 'Year over:') ?>
                                                        </div> 


                                                        <div class="col-lg-2" align='left'> 
                                                        <?= $form->field($model, 'ano_traspaso')->textInput()->label(false) ?> 
                                                        </div> 

                                                        <div class="col-lg-2">
                                                        <?= Yii::t('backend', 'type nature:') ?>
                                                        </div> 
                                                    
                                                        <div class="col-lg-4" align='left'> 
                                                        <?= $form->field($model, 'tipo_naturaleza')->radioList([2 => Yii::t('backend', 'Legal'), 1 => Yii::t('backend', 'Persons')], ['itemOptions' => ['class' =>'radio-inline','id'=>'tipo_naturaleza', 'onclick'=>'bloquea()' ]] )->label(false) ?>
                                                        </div> 
                                                    </td>
                                                </tr> 

                                                <tr> 
                                                    <td colspan="5">

                                                        <div class="col-lg-2">
                                                        <?= Yii::t('backend', 'Cedula/Rif') ?>
                                                        </div> 
                                                        <div class="col-lg-2" align='left'> 
                                                        <?= $form->field($model, 'naturalezaBuscar')->dropDownList(['prompt' => Yii::t('backend', 'Select'),'V'=>'V','E'=>'E','P'=>'P','J'=>'J'],['onchange'=>'naturaleza(this.value)'])->label(false) ?> 
                                                        </div>
                                                <div class="col-lg-2" align='left'> 
                                                        <?= $form->field($model, 'cedulaBuscar')->textInput()->label(false) ?> 
                                                        </div>
                                                        <div class="col-lg-2" align='left' id='tipo2' style='display:none'> 
                                                        <?= $form->field($model, 'tipoBuscar')->textInput()->label(false) ?> 
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="col-lg-2" align='left'> 

                                                         <?php   if(count($datosVContribuyente) >0){
                                                                      $listaParametros = ArrayHelper::map($datosVContribuyente,'id_contribuyente','nombres');
                                                                      echo   $form->field($model, 'datosVendedor')->dropDownList($listaParametros,['prompt' => Yii::t('backend', 'Select'),
                                                                                                                                                   'id'=>'datosVendedor',
                                                                                                                                                   //'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl( 'inmuebles-urbanos-form/lists' ) . '&id=' . '" + $(this).val(), function( data ) {$( "select#inmuebleVendedor" ).html( data );});' 
                                                                                                                                                   ])->label(false);
                                  
                                                                 } else {         
                                                                      echo   $form->field($model, 'datosVendedor')->hiddenInput(['value'=>$datosVContribuyente])->label(false); 
                                                                 }

                                 
                                                         ?>  

                                                       </div> 
                                                       <div class="col-lg-2" align='left'> 

                                                         <?php   if(count($datosVInmueble) >0){
                                                                      $listaParametrosi = ArrayHelper::map($datosVInmueble,'id_impuesto','direccion');
                                                                      echo   $form->field($model, 'inmuebleVendedor')->dropDownList($listaParametrosi,['prompt' => Yii::t('backend', 'Select'),
                                                                                                                                                       'id'=>'inmuebleVendedor'])->label(false);
                                  
                                                                 } else {         
                                                                      echo   $form->field($model, 'inmuebleVendedor')->hiddenInput(['value'=>$datosVContribuyente])->label(false); 
                                                                 }

                                 
                                                         ?>  

                                                       </div> 
 
                                                        <div class="form-group">
                                                        <?php if(count($datosVInmueble) >0){
                                                                    echo Html::submitButton(Yii::t('backend', 'Accept'), ['class' => 'btn btn-primary', 'name'=>'AcceptBuyer', 'value'=>'Accept']); 
                                                                    echo Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']); 
                                                              } else {         
                                                                    echo Html::submitButton(Yii::t('backend', 'Next'), ['class' => 'btn btn-primary', 'name'=>'NextBuyer', 'value'=>'Next']);
                                                                    echo Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']);
                                                                 }?>
                                                        </div>
<!-- Campos ocultos -->  
<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $modelContribuyente->id_contribuyente])->label(false) ?>
<?= $form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false) ?>

<?= $form->field($model, 'validacion')->hiddenInput(['value' => 4])->label(false) ?>
<?php ActiveForm::end(); ?> 
<?// = Html::endForm();?> 
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                            </div> 
                        </td>
                    </tr>

                </table>

   


       

            </div>
        </div>
    </div>



<?php //$form->end(); ?>
 
</div><!-- inscripcionInmueblesUrbanos 
['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9']
-->                     
