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
$this->title = Yii::t('backend', 'Change of property values'). '<p>Id Taxpayer: ' . $modelContribuyente->id_contribuyente.'</p>';
 

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
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" > 
                
                    
 
<div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Datos del Inmueble') ?>  
</div>
                                            <table class="table table-striped ">
                    <tr>
                        <td style="max-width: 85px" align="right"> 
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'IdTaxpayer') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-4">
                            <?= $form->field($model, 'id_contribuyente')->textInput(['readonly'=>'readonly','value'=>$_SESSION['idContribuyente'],'style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Id Sim') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px">
                            <div class="col-sm-4">
                            <?= $form->field($model, 'id_sim')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-2">
                            <?= $form->field($model, 'inactivo')->checkbox(array(['style' => 'width:80px;']))?> 
                            </div> 
                        </td>

                        <td colspan="7" style="max-width: 100px" align="letf">
                            <div class="col-sm-6"> 
                            <?= $form->field($model, 'propiedad_horizontal')->checkbox(['id'=> 'propiedadhorizontal',
                                                                                         'style' => 'width:50px;', 
                                                                                         'onclick'=>'bloquea()',
                                                                                         
                                                                                          ]); ?> 
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

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'estado_catastro')->textInput(['style' => 'width:80px;'])/*->dropDownList($listaEstados, [
                                                                                                            'id'=> 'estados', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:80px;',
                                                                                                            'onchange' =>
                                                                                                                '$.post( "' . Yii::$app->urlManager
                                                                                                                                       ->createUrl('municipios/lists') . '&estado=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                                                $( "select#municipios" ).html( data );
                                                                                                                                                                                                            });' 
                                                                                                            ])*/->label(false); 
                                    
                             ?> 
                            </div>
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Mnp.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'municipio_catastro')->textInput(['style' => 'width:80px;'])/*->dropDownList($listaMunicipios, [
                                                                                                            'id'=> 'municipios', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:80px;',
                                                                                                           'onchange' =>
                                                                                                                '$.post( "' . Yii::$app->urlManager
                                                                                                                                       ->createUrl('parroquias/lists') . '&municipio=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                                            $( "select#parroquias" ).html( data );
                                                                                                                                                                                                            });' 
                                                                                                            ])*/->label(false); 
                                    
                             ?> 
                            </div>
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Prq.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'parroquia_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Amb.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1">
                            <?= $form->field($model, 'ambito_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Sct.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1">
                            <?= $form->field($model, 'sector_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Mzn.') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1">
                            <?= $form->field($model, 'manzana_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div> 
                        </td>
                   </tr>
<!-- Tipo de Domicilios del catastro --> 
                   <tr>
                        <td style="max-width: 85px" align="right">                 
                            <div class="col-lg-1"> 
                            <?= Yii::t('backend', 'Plot') ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1" >
                            <?= $form->field($model, 'parcela_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1" id="subparcela" style="display:none"> 
                            <?= Yii::t('backend', 'Sub-plot') ?>
                            </div>  
                        </td>

                        <td style="max-width: 100px" align="letf">                    
                            <div class="col-lg-1" id="subparcelac" style="display:none">
                            <?= $form->field($model, 'subparcela_catastro')->textInput(['style' => 'width:80px;'])->label(false) //<?= $form->field($model, 'capa_subparcela')->textInput(['style' => 'width:80px;display:block;', 'id' => 'ms2', 'disabled'=>'disabled' ])->label(false) , 'id' => 'ms1' display:none;?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">                           
                           <div class="col-lg-1" id="level" style= 'display:none'>
                            <?= Yii::t('backend', 'Level') ?> <!--</legend> -->
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1" id="levelc" style="display:none">
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
                                                                                                           */ ])->label(false);
                                                                                                          ?>
                            </div> 
                        </td>

                        <td style="max-width: 100px" align="letf">
                            <div class="col-lg-1" id="levelc2" style="display:none">
                            <?= $form->field($model, 'nivelb')->dropDownList([
                                                                                        'prompt' => Yii::t('backend', 'Select'),
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

                        <td style="max-width: 100px" align="letf">                         
                            <div class="col-lg-1" id="unidad1" style="display:none"> 
                            <?= Yii::t('backend', 'Unit') ?>
                            </div>
                        </td>

                        <td colspan="4" style="max-width: 100px" align="letf">
                            <div class="col-lg-1" id="unidad1c" style="display:none">
                            <?= $form->field($model, 'unidad_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
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
                                                
</div>
 

<div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Valores de la propiedad') ?>  
</div>
                                                    <div class="row" style="margin-left:20px; margin-top:20px;">
                                                        <div class="col-sm-2"> 
                                                        <?= Yii::t('backend', 'Meters Construction') ?>
                                                        </div> 
                                                    
                                                        <div class="col-sm-2"> 
                                                        <?= $form->field($model, 'metros_construccion')->textInput(['maxlength' => true,'style' => 'width:100px;'])->label(false) ?>
                                                        </div> 

                                                        <div class="col-sm-2"> 
                                                        <?= Yii::t('backend', 'Value of Construction') ?>
                                                        </div> 

                                                        <div class="col-sm-2"> 
                                                        <?= $form->field($model, 'valor_construccion')->textInput(['maxlength' => true,'style' => 'width:100px;'])->label(false) ?>
                                                        </div> 
                                                    </div>

                                            
                                                    <div class="row" style="margin-left:20px; margin-top:20px;">
                                                        <div class="col-sm-2"> 
                                                        <?= Yii::t('backend', 'Meters of Land') ?>
                                                        </div> 
                                                    
                                                        <div class="col-sm-2"> 
                                                        <?= $form->field($model, 'metros_terreno')->textInput(['maxlength' => true,'style' => 'width:100px;'])->label(false) ?> 
                                                        </div>

                                                        <div class="col-sm-2"> 
                                                        <?= Yii::t('backend', 'Value of Land') ?>
                                                        </div> 

                                                        <div class="col-sm-2"> 
                                                        <?= $form->field($model, 'valor_terreno')->textInput(['maxlength' => true,'style' => 'width:100px;'])->label(false) ?>
                                                        </div> 
                                                    </div>         
                                                
</div>
                                                
                                                    <div class="row" style="margin-left:20px; margin-top:20px;">
                                                        <div class="col-sm-2">
                                                        <?php 
                                                        echo Html::submitButton(Yii::t('backend', 'Accept'), ['class' => 'btn btn-primary', 'name'=>'AcceptBuyer', 'value'=>'Accept']); 
                                                        ?> 
                                                        </div> 
                                                        <div class="col-sm-2">
                                                        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
                                                        </div> 
                                                    </div> 
                                                 
                                       
                                                   
                  </div>      
              </div>
        </div>
                              

                             

                            
                                        
                                  

                                                     
 
                                                       
                                                       
<!-- Campos ocultos -->  

<?= $form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false) ?>



<?//= Html::endForm();?> 
  <?php ActiveForm::end(); ?>                                                   
                   

                

   


       

            </div>
        </div>
    </div>



<?php //$form->end(); ?>
 
</div><!-- inscripcionInmueblesUrbanos 
['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9']
-->                     
