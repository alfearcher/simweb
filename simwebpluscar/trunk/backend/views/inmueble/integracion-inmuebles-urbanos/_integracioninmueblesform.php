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
                            <div class="col-sm-4"> 
                            <?= $form->field($model, 'propiedad_horizontal')->checkbox(['id'=> 'propiedadhorizontal',
                                                                                         'style' => 'width:50px;', 
                                                                                         'onclick'=>'bloquea1()',
                                                                                         
                                                                                          ]); ?> 
                            </div>

                            <div class="col-sm-1"> 
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
                        <div class="row" style="margin-left:20px">
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Edo.') ?>
                            </div> 
                        
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
                        
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Mnp.') ?>
                            </div> 
                        
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
<!-- Tipo de Domicilios del catastro --> 
                   <div class="row" style="margin-left:20px">
                                         
                            <div class="col-sm-1"> 
                            <?= Yii::t('backend', 'Plot') ?>
                            </div> 
                        
                            <div class="col-sm-1" >
                            <?= $form->field($model, 'parcela_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>
                       
                            <div class="col-sm-1" id="subparcela" style="display:none"> 
                            <?= Yii::t('backend', 'Sub-plot') ?>
                            </div>  
                                           
                            <div class="col-sm-1" id="subparcelac" style="display:none">
                            <?= $form->field($model, 'subparcela_catastro')->textInput(['style' => 'width:80px;'])->label(false) //<?= $form->field($model, 'capa_subparcela')->textInput(['style' => 'width:80px;display:block;', 'id' => 'ms2', 'disabled'=>'disabled' ])->label(false) , 'id' => 'ms1' display:none;?>
                            </div> 
                                                   
                           <div class="col-sm-1" id="level" style= 'display:none'>
                            <?= Yii::t('backend', 'Level') ?> <!--</legend> -->
                            </div> 
                        
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
                                                                                                           */ ])->label(false);
                                                                                                          ?>
                            </div> 
                        
                            <div class="col-sm-1" id="levelc2" style="display:none">
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
                                              
                            <div class="col-sm-1" id="unidad1" style="display:none"> 
                            <?= Yii::t('backend', 'Unit') ?>
                            </div>
                        
                            <div class="col-sm-1" id="unidad1c" style="display:none">
                            <?= $form->field($model, 'unidad_catastro')->textInput(['style' => 'width:80px;'])->label(false) ?>
                            </div>                                          
                   </div>    
                   <div class="row" style="margin-left:20px">
                        
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
<?= Html::beginForm();?> 
<div class="col-sm-2">
<?= Html::submitButton(Yii::t('backend', 'Accept'), ['class' => 'btn btn-primary', 'name'=>'AcceptSeller', 'value'=>'AcceptSeller']) ?>
</div>
<div class="col-sm-2">
<?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
</div>
<?= Html::endForm();?> 


                                                        </div>
                                                    </div> 
                                        </div> 

                            
                                                      
 

<!-- Campos ocultos -->  
<?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $modelContribuyente->id_contribuyente])->label(false) ?>
<?= $form->field($model, 'id_impuesto')->hiddenInput(['value' => $model->id_impuesto])->label(false) ?>

<?= $form->field($model, 'validacion')->hiddenInput(['value' => 4])->label(false) ?>

<?//= Html::endForm();?> 
                                                  
                    </div>
            </div>
     </div> 
                       

            

<?php ActiveForm::end(); ?> 
<?php //$form->end(); ?>
 
</div><!-- inscripcionInmueblesUrbanos 
['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9']
-->                     
