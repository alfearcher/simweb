<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use yii\widgets\ActiveField;
use yii\helpers\ArrayHelper;
use backend\models\Tiempo;
use backend\models\BasesCalculos;
use backend\models\UsosPropaganda;
use backend\models\MediosDifusion;
use backend\models\TiposPropaganda;
use backend\models\ClasesPropaganda;
use backend\models\MediosTransporte;

$fecha_fin = '00/00/0000';

if( $_GET['r'] == 'propaganda/propaganda/update' ) {
       
            $verificar = 1;
            $ano = $model->ano_impositivo;
            $fecha_guardado = $model->fecha_guardado;
} else {
            $verificar = 0;
            $ano = date('Y');
            $fecha_guardado = date('Y-m-d');
}
?>

<script type = "text/javascript">

function mostrar_contenido() {
   
    $('#id_tipo_propaganda').val($('#tipo_propaganda option:selected').text());
    /*$("#id_tipo_propaganda").val($("#tipo_propaganda").val() + $("#tipo_propaganda option:selected").text());*/
}

function recargar_pagina() {
     
    var tit1 = tit2 = 'none';
    
    tit1 = 'block';
    tit2 = 'block';
    
    document.getElementById( 'ms1' ).style.display = tit1;
    document.getElementById( 'ms8' ).style.display = tit2;
    
    $('#id_tipo_propaganda').val($('#tipo_propaganda option:selected').text());
}

function activar_campos( val ) {
    
    var tit1 = tit2 = tit3 = tit4 = tit5 = tit6 = tit7 = tit8 ='none';
    
    if ( val == '1' ) { tit1='block'; }
    if ( val == '1' ) { tit7='block'; }
    if ( val == '2' ) { tit1='block'; }
    if ( val == '2' ) { tit8='block'; }
    if ( val == '3' ) { tit1='block'; }
    if ( val == '3' ) { tit8='block'; }
    if ( val == '4' ) { tit6='block'; }
    if ( val == '4' ) { tit8='block'; }
    if ( val == '5' ) { tit1='block'; }
    if ( val == '5' ) { tit7='block'; }
    if ( val == '6' ) { tit3='block'; }
    if ( val == '6' ) { tit8='block'; }
    if ( val == '7' ) { tit1='block'; }
    if ( val == '7' ) { tit8='block'; }
    if ( val == '8' ) { tit5='block'; }
    if ( val == '8' ) { tit8='block'; }
    if ( val == '9' ) { tit4='block'; }
    if ( val == '9' ) { tit8='block'; } 
    if ( val == '10') { tit2='block'; }
    if ( val == '10') { tit8='block'; }
    if ( val == '11') { tit1='block'; }
    if ( val == '11') { tit8='block'; }
	
    document.getElementById( 'ms1' ).style.display = tit1;
    document.getElementById( 'ms2' ).style.display = tit2;	
    document.getElementById( 'ms3' ).style.display = tit3;	
    document.getElementById( 'ms4' ).style.display = tit4;	
    document.getElementById( 'ms5' ).style.display = tit5;	
    document.getElementById( 'ms6' ).style.display = tit6;	
    document.getElementById( 'ms7' ).style.display = tit7;	
    document.getElementById( 'ms8' ).style.display = tit8;
}
    
/*****************************************************************************
Código para colocar los indicadores de miles  y decimales mientras se escribe
Script creado por Tunait!    
http://javascript.tunait.com
tunait@yahoo.com  27/Julio/03
Adaptación y Optimización del Script por Ing. Hansel J. Colmenarez G.
http://inghanselcolmenarez.com.ve
hanselcolmenarez@hotmail.com 10/Agosto/2015
******************************************************************************/
function puntitos( donde, caracter, campo ) {
        
    var decimales = true
    dec = campo
    pat = /[\*,\+,\(,\),\?,\\,\$,\[,\],\^]/
    valor = donde.value
    largo = valor.length
    crtr = true
    
    if( isNaN( caracter ) || pat.test( caracter ) == true ) {
        
        if ( pat.test(caracter) == true ) {
            
                caracter = '\\' + caracter
        }
                    carcter = new RegExp( caracter, 'g' )
                    valor = valor.replace( carcter, '' )
                    donde.value = valor
                    crtr = false
        } else {
                    var nums = new Array()
                    
                    cont = 0
                    for( m = 0; m < largo; m++ ) {
                
                        if( valor.charAt( m ) == ',' || valor.charAt( m ) == '' || valor.charAt( m ) == '.' ) {

                                    continue;
                        } else  {
                                    nums[cont] = valor.charAt( m )
                                    cont++
                        }
                    }
        }

        if( decimales == true ) {
            
                    ctdd = eval( 1 + dec );
                    nmrs = 1
        } else {
                    ctdd = 1 
                    nmrs = 3
        }
        
        var cad1 = '', cad2 = '', cad3 = '', tres=0
        if( largo > nmrs && crtr == true ) {
            
            for ( k = nums.length-ctdd;k>=0;k-- ) {
                cad1 = nums[k]
                cad2 = cad1 + cad2
                tres++
                
                if( ( tres%3 ) == 0 ) {
                    
                    if( k != 0 ) {
                        cad2 = '.' + cad2
                    }
                }
            }
                
            for ( dd = dec; dd > 0; dd-- ) {
                
                cad3 += nums[nums.length-dd] 
            }
            if( decimales == true ) {
                
                cad2 += ',' + cad3
            }
                donde.value = cad2
        }
            donde.focus()
} 
</script>

<body onload="recargar_pagina()"/>
<div class="propagandas-create">
    <?php   $form = ActiveForm::begin( [  'id' => 'form-propaganda-inline',
                                          'type' => ActiveForm::TYPE_HORIZONTAL,
                                          'formConfig' => [ 'showErrors' => true, 
                                          'deviceSize' => ActiveForm::SIZE_SMALL, 
                                          'labelSpan' => 2,
                                          'showLabels' => true ]
                                     ]  );
    ?>
    
    <?= Html::activeHiddenInput( $model, 'fecha_guardado', [ 'value' => $fecha_guardado ] )?>
    <?= Html::activeHiddenInput( $model, 'planilla', [ 'value' => '0' ] )?>
    <?= Html::activeHiddenInput( $model, 'id_cp', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'id_sim', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'inactivo', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'id_contribuyente', [ 'value' => $_SESSION['idContribuyente'] ] ) ?>
    

    
    <div class="col-sm-11" style="margin-left:5%;">
        <div class="panel panel-primary">
            <div class="panel-heading"><?=  Yii::t( 'backend', $this->title )?></div>
                <div class="panel-body" >
                    <table class="table table-striped">
                        
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Tax Year:' )?></small></i></p>
                                    <?= $form->field( $model, 'ano_impo' )->label( false )->textInput( [ 'value' => $ano, 'inline' => true, 'style' => 'width:100%;', 'readonly' => 'readonly' ] )?>
                               </div>    
                            </td>
                            
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Class:' )?></small></i></p>
                                    <?php   $modelClasesPropaganda = ClasesPropaganda::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                                         
                                            $listaClasesPropaganda = ArrayHelper::map( $modelClasesPropaganda, 'clase_propaganda', 'descripcion' ); 
                                    ?>
                                    
                                    <?= $form->field( $model, 'clase_propaganda' )->label( false )->dropDownList( $listaClasesPropaganda, [   'id'=> 'clasespropaganda', 
                                                                                                                                              'prompt' => Yii::t('backend', 'Select'),
                                                                                                                                              'style' => 'width:100%;'
                                                                                                                                          ] )
                                    ?>
                                </div>    
                            </td>
                            
                             <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Use:' )?></small></i></p>
                                    <?php   $modelUsosPropaganda = UsosPropaganda::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                                         
                                            $listaUsosPropaganda = ArrayHelper::map( $modelUsosPropaganda, 'uso_propaganda', 'descripcion' ); 
                                    ?>

                                    <?= $form->field( $model, 'uso_propaganda' )->label( false )->dropDownList( $listaUsosPropaganda, [   'id'=> 'usospropaganda', 
                                                                                                                                          'prompt' => Yii::t('backend', 'Select'),
                                                                                                                                           'style' => 'width:100%;'
                                                                                                                                      ] )
                                    ?>
                                </div>    
                            </td>
                        </tr>
                                            
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Duration:' )?></small></i></p>
                                </div>   
                            </td>
                            
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Dimensions and Quantities:' )?></small></i></p>
                                </div>   
                            </td>
                           
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Other Parameters:' ) ?></small></i></p>
                                </div>   
                            </td>
                        </tr>
                                            
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Start Date:' )?></small></i></p>
                                    <?= $form->field( $model, 'fecha_desde' )->label( false )->input( 'date', [   'type' => 'date',
                                                                                                                  'style' => 'width:100%;'
                                                                                                              ] )
                                    ?>
                                </div> 
                            </td>
                            
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Number:' )?></small></i></p>
                                    <?= $form->field( $model, 'cantidad_base' )->label( false )->textInput( [  'onkeyup' => 'puntitos(this,this.value.charAt(this.value.length-1),2)', 
                                                                                                               'inline' => true, 
                                                                                                               'style' => 'width:100%;'
                                                                                                            ] )
                                    ?>
                                </div> 
                            </td>
                                                
                            <td>
                                <div>
                                    <p><i><small><?= $form->field( $model, 'cigarros' )->checkBox( [ 'inline' => true ] )?></small></i></p>
                                </div> 
                            </td>    
                        </tr>
                                             
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Quantity:' )?></small></i></p>
                                    <?= $form->field( $model, 'cantidad_tiempo' )->label( false )->textInput( [   'style' => 'width:100%;text-transform:uppercase;',
                                                                                                                  'inline' => true
                                                                                                              ] )
                                    ?>	
                                </div>
                            </td>
                                                
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Base:' )?></small></i></p>
                                     <?php  $modelBaseCalculo = BasesCalculos::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                                         
                                            $listaBaseCalculo  = ArrayHelper::map( $modelBaseCalculo, 'base_calculo', 'descripcion' ); 
                                    ?>
                                    <?= $form->field( $model, 'base_calculo' )->label( false )->dropDownList( $listaBaseCalculo, [    'id' => 'base_calculo', 
                                                                                                                                      'prompt' => Yii::t('backend', 'Select'),
                                                                                                                                      'style' => 'width:100%;',
                                                                                                                                      'onchange' =>'activar_campos(this.value)'
                                                                                                                                  ] ); 
                                    ?>    
                                </div>
                            </td>
                                                
                            <td>
                                <div>
                                    <p><i><small><?= $form->field( $model, 'bebidas_alcoholicas' )->checkBox( [ 'inline' => true ] )?></small></i></p>
                                </div>
                            </td>   
                        </tr>
                        
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Lapse:' )?></small></i></p>
                                    <?php   $modelTiempo = Tiempo::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                                         
                                            $listaTiempo = ArrayHelper::map( $modelTiempo, 'id_tiempo', 'descripcion' ); 
                                    ?> 
                                    <?= $form->field( $model, 'id_tiempo' )->label( false )->dropDownList( $listaTiempo, [   'id' => 'tiempo', 
                                                                                                                             'prompt' => Yii::t('backend', 'Select'),
                                                                                                                             'style' => 'width:100%;',
                                                                                                                             'onchange' =>'this.form.submit()'
                                                                                                                         ] ); 
                                    ?>
                                </div>
                            </td>                   

                            <?php
                                    /**
                                     *  Condicional para verificar que fecha fin se le debe colocar a la propaganda dependiendo si es por: ( horas, dias, semanas, mes, anos ).
                                     */
                                    if($model->id_tiempo == 1) {
                                        
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'hours'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->id_tiempo == 2 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'days'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->id_tiempo == 3 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'weeks'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->id_tiempo == 4 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'months'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->id_tiempo == 5 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'years'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }
                            ?> 
                                    
                            <td>
                                <div id="ms1" style="display:none"><p><i><small><?= Yii::t( 'backend', 'Units:', [ 'style'=>'font-weight:normal' ] )?></small></i></p></div>
                                <div id="ms2" style="display:none"><p><i><small><?= Yii::t( 'backend', 'Number Hour:', [ 'style'=>'font-weight:normal' ] )?></small></i></p></div>
                                <div id="ms3" style="display:none"><p><i><small><?= Yii::t( 'backend', 'Many Months:', [ 'style'=>'font-weight:normal' ] )?></small></i></p></div>
                                <div id="ms4" style="display:none"><p><i><small><?= Yii::t( 'backend', 'Number of Minutes:', [ 'style'=>'font-weight:normal' ] )?></small></i></p></div>
                                <div id="ms5" style="display:none"><p><i><small><?= Yii::t( 'backend', 'After Years:', [ 'style'=>'font-weight:normal' ] )?></small></i></p></div>
                                <div id="ms6" style="display:none"><p><i><small><?= Yii::t( 'backend', 'Number of Days Elapsed:', [ 'style'=>'font-weight:normal' ] )?></small></i></p></div>
                                <div id="ms7" style="display:none"><?= $form->field( $model, 'cantidad_propagandas' )->label( false )->textInput( [ 'value'=> 0, 'inline' => true, 'style' => 'width:100%;text-transform:uppercase;','readonly'=> 'readonly' ] )?></div>
                                <div id="ms8" style="display:none"><?= $form->field( $model, 'cantidad_propagandas' )->label( false )->textInput( [ 'inline' => true, 'style' => 'width:100%;text-transform:uppercase;' ] )?></div>
                            </td>
                                                
                            <td>
                                <div>
                                    <p><i><small><?= $form->field( $model, 'idioma' )->checkBox( [ 'inline' => true ] )?></small></i></p>
                                </div>
                            </td>  
                        </tr>
                                            
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Date End:' ) ?></small></i></p>
                                    <?= $form->field( $model, 'fecha_fin' )->label( false )->textInput( [ 'value' => $fecha_fin, 'inline' => true, 'style' => 'width:100%;text-transform:uppercase;', 'readonly' => 'readonly' ] )?>
                               </div>
                            </td> 
                                                
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', '&nbsp;' ) ?></small></i></p>
                                    <a href="index.php?r=propaganda/propaganda/search" target="_blank"><?=   Html::Button( Yii::t( 'backend', 'Advertisement Catalog' ), [ 'style' => 'width:81%;', 'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary' ] )?> </a>
                                </div>
                            </td> 
                                                
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Id Sim.:' ) ?></small></i></p>
                                    <?= $form->field( $model, 'id_sim' )->label( false )->textInput( [ 'inline' => true, 'style' => 'width:100%;' ] )?>
                                </div>
                            </td> 
                        </tr>
                    </table>  
                    
                    <table class="table table-striped">                   
                        <tr>
                            <td colspan="2">
                                <div>
                                   <p><i><small><?=Yii::t( 'backend', 'Kind:' ) ?></small></i></p>
                                    <?php   $modelTiposPropaganda = TiposPropaganda::find()->orderBy( [ 'tipo_propaganda' => SORT_ASC ] )->asArray()->all();                                        
                                            $listaTiposPropaganda = ArrayHelper::map( $modelTiposPropaganda, 'tipo_propaganda', 'descripcion' );
                                    ?>
                                    <?= $form->field( $model, 'tipo_propaganda' )->label( false )->dropDownList( $listaTiposPropaganda, [  'id' => 'tipo_propaganda',
                                                                                                                                           'prompt' => Yii::t('backend','Select'),
                                                                                                                                           'style' => 'width:115%;',
                                                                                                                                           'onchange' =>'mostrar_contenido()'
                                                                                                                                        ] );
                                    ?>
                                    <?= $form->field( $model, 'id_tipo_propaganda' )->label( false )->textArea( [ 'style' => 'width:115%;', 'maxlength' => true,'id' => 'id_tipo_propaganda', 'readonly' => 'readonly' ] )?>
                                </div>
                            </td>
                        </tr>
                                   
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?=Yii::t( 'backend', 'Through Construction:' ) ?></small></i></p>
                                    <?php   $modelMediosDifusion = MediosDifusion::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                                        
                                            $listaMediosDifusion = ArrayHelper::map( $modelMediosDifusion, 'medio_difusion', 'descripcion' );
                                    ?>
                                    <?= $form->field( $model, 'medio_difusion' )->label( false )->dropDownList( $listaMediosDifusion, [   'id' => 'medio_difusion',
                                                                                                                                          'prompt' => Yii::t('backend','Select'),
                                                                                                                                          'style' => 'width:107%;'
                                                                                                                                      ] )
                                    ?> 
                                </div>
                            </td>
                                
                           <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Transport Means:' ) ?></small></i></p>
                                    <?php   $modelMediosTransporte = MediosTransporte::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                                        
                                            $listaMediosTransporte = ArrayHelper::map( $modelMediosTransporte, 'medio_transporte', 'descripcion' );
                                    ?>
                                    <?= $form->field( $model, 'medio_transporte' )->label( false )->dropDownList( $listaMediosTransporte, [  'id' => 'medio_trasnporte',
                                                                                                                                             'prompt' => Yii::t('backend','Select'),
                                                                                                                                              'style' => 'width:108%;'
                                                                                                                                          ] );
                                    ?> 
                                </div>
                            </td>
                        </tr>
                           
                        <tr>
                            <td>
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'Address:' ) ?></small></i></p>
                                    <?= $form->field( $model, 'direccion' )->label( false )->textArea( [ 'maxlength' => true, 'style' => 'width:107%;text-transform:uppercase;' ] )?> 
                                </div>
                            </td>
                               
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Observation:' ) ?></small></i></p>
                                        <?= $form->field( $model, 'observacion')->label( false )->textArea( [ 'maxlength' => true,'style' => 'width:108%;text-transform:uppercase;' ] )?> 
                                    </div>
                                </td>
                        </tr>
                    </table>     
                    
                    <table class="table table-striped">                
                        <tr>
                            <td width="78%">
                                <div>
                                    <p><i><small><?= Yii::t( 'backend', 'State&nbsp;/&nbsp;Town&nbsp;/&nbsp;Parish&nbsp;/&nbsp;Population center:' )?></small></i></p>
                                    <?= $form->field( $model, 'est_mun_parr_cp' )->label( false )->textInput( [ 'maxlength' => true, 'style' => 'width:120%;' ] )?> 
                                </div>
                            </td>
                                
                            <td>
                                <div class="col-md-5">
                                    <p><i><small><?= Yii::t( 'backend', 'Location:' ) ?></small></i></p>
                                    <?= $form->field( $model, 'id_cp' )->label( false )->textInput( [ 'maxlength' => true,'style' => 'width:150%;' ] )?> 
                                </div> 
                                    
                                <div class="col-md-3"> 
                                    <p><i><small><?=Yii::t('backend', '&nbsp;') ?></small></i></p>
                                    <?= Html::Button( Yii::t( 'backend', 'UPJ' ), [ 'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary' ] )?>
                                </div> 
                            </td>
                        </tr>
                              
                        <tr>
                            <td colspan="2">
                                <?= Html::submitButton( $model->isNewRecord ? 'Create' : 'Update', [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name'=> 'btn', ' value'=> 'crud' ] ) ?>
                                
								<?php if( $_GET['r'] == 'propaganda/propaganda/update' ) { ?>
								
																								<?= Html::a(Yii::t('backend', 'Quit'), ['propaganda/propaganda/index'], ['class' => 'btn btn-danger']) ?>
            					<?php } else { ?>
																								<?= Html::a(Yii::t('backend', 'Quit'), ['menu/vertical'], ['class' => 'btn btn-danger']) ?>
								<?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>
        </div>
    </div>
                    
        <?php ActiveForm::end(); ?>
</div>    