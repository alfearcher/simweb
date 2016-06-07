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
use yii\jui\DatePicker;


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
    
    <?

    $idContribuyente = yii::$app->user->identity->id_contribuyente; 
    //die($idContribuyente);
    
    ?>

    <?= Html::activeHiddenInput( $model, 'planilla', [ 'value' => '0' ] )?>
    <?= Html::activeHiddenInput( $model, 'id_cp', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'id_sim', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'inactivo', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'id_contribuyente', [ 'value' => 0 ] ) ?>
    

    
    <div class="col-sm-11" style="margin-left:5%;">
        <div class="panel panel-primary">
            <div class="panel-heading"><?=  Yii::t( 'backend', $this->title )?></div>
                <div class="panel-body" >
                   
                        
        <div class="row">

        <!--INICIO ANO IMPOSITIVO -->

            <div class="col-sm-3">
                            <?= $form->field($model, 'ano_impositivo')->textInput(                         [ 'id'=> 'ano_impositivo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => date('Y'),
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
            </div>   

        <!--FIN ANO IMPOSITIVO -->


        <!--INICIO DE CLASE PROPAGANDA -->

    
                    <?php   
                    $modelClasesPropaganda = ClasesPropaganda::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();       
                    $listaClasesPropaganda = ArrayHelper::map( $modelClasesPropaganda, 'clase_propaganda', 'descripcion' ); 
                    ?>

            <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'clase_propaganda')->dropDownList($listaClasesPropaganda,
                                                                [
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                                ]);
                    ?>
                
            </div>


        <!--FIN DE CLASE PROPAGANDA -->

        <!--INICIO DE USO DE PROPAGANDA -->

                    <?php   
                    $modelUsosPropaganda = UsosPropaganda::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();  
                    $listaUsosPropaganda = ArrayHelper::map( $modelUsosPropaganda, 'uso_propaganda', 'descripcion' ); 
                    ?>


            <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'uso_propaganda')->dropDownList($listaUsosPropaganda,
                                                            [
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            ]);
                    ?>
                
            </div>

        <!--FIN DE USO DE PROPAGANDA -->

        </div>

        <div class="row">
        
         <!--INICIO DE FECHA INICIAL -->

            <div class="col-sm-4">
                <div class="fecha-nac">
                <?= $form->field($model, 'fecha_inicial')->widget(\yii\jui\DatePicker::classname(),[
                                                                                        //'type' => 'date',
                                                                                        'clientOptions' => [
                                                                                           // 'maxDate' => '+0d', // Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                        'changeYear' => 'true', 
                                                                                         
                                                                                         ],
                                                                                       'language' => 'es-ES',
                                                                                       'dateFormat' => 'dd-MM-yyyy',
                                                                                        'options' => [
                                                                                            //'onClick' => 'alert("calendario")',
                                                                                            'id' => 'fecha_inicial',
                                                                                            'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                            'style' => 'background-color: white;',
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                </div>
            </div>


        <!--FIN DE FECHA INICIAL -->
        
        
        <!--INICIO DE CANTIDAD -->

            <div class="col-sm-3">
                                  
                <?= $form->field( $model, 'cantidad_base' )->label( false )->textInput( [  'onkeyup' => 'puntitos(this,this.value.charAt(this.value.length-1),2)', 
                                                                                                               'inline' => true, 
                                                                                                               'style' => 'width:100%;'
                                                                                                            ] )
                ?>
            </div> 
        
        <!--FIN DE CANTIDAD -->

        <!--INICIO DE CIGARRILLOS -->

                <div>
                    <p><i><small><?= $form->field( $model, 'cigarrillos' )->checkBox( [ 'inline' => true ] )?></small></i></p>
                </div> 

        <!--FIN DE CIGARRILLOS -->

        </div>

        <div class="row">
            
        <!--INICIO DE CANTIDAD TIEMPO -->

            <div class="col-sm-3">
                            <?= $form->field($model, 'cantidad_tiempo')->textInput(                         [ 'id'=> 'cantidad_tiempo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                          
                                                                                                         
                                                                                                                                                                                                                   
                                                                                                            ]); ?>
            </div>  


        <!--FIN DE CANTIDAD TIEMPO -->

        <!--INICIO DE BASE -->
            
            <?php  
            $modelBaseCalculo = BasesCalculos::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                 
            $listaBaseCalculo  = ArrayHelper::map( $modelBaseCalculo, 'base_calculo', 'descripcion' ); 
            ?>

            <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'base_calculo')->dropDownList($listaBaseCalculo,
                                                            [
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            ]);
                    ?>
                
            </div>

        <!--FIN DE BASE -->

        <!--INICIO DE BEBIDAS ALCOHOLICAS -->
               <div>
                    <p><i><small><?= $form->field( $model, 'bebidas_alcoholicas' )->checkBox( [ 'inline' => true ] )?></small></i></p>
                </div>

         <!--FIN DE BEBIDAS ALCOHOLICAS -->
        
        </div>

        <div class="row">

        <!--INICIO DE TIEMPO -->

            <div class="col-sm-3">
                                  
                                    <?php   
                                    $modelTiempo = Tiempo::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();    
                                    $listaTiempo = ArrayHelper::map( $modelTiempo, 'id_tiempo', 'descripcion' ); 
                                    ?> 
                                    
                                    }<?= $form->field( $model, 'tiempo' )->dropDownList( $listaTiempo, [   'id' => 'tiempo', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:100%;',
                                                                                                            'onchange' =>'this.form.submit()'
                                                                                                                         ] ); 
                                    ?>
            </div>
            
             <?php
                                    /**
                                     *  Condicional para verificar que fecha fin se le debe colocar a la propaganda dependiendo si es por: ( horas, dias, semanas, mes, anos ).
                                     */
                                    if($model->tiempo == 1) {
                                        
                                        $f = $model->fecha_inicial;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'hours'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->tiempo == 2 ) {
                                        $f = $model->fecha_inicial;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'days'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->tiempo == 3 ) {
                                        $f = $model->fecha_inicial;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'weeks'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->tiempo == 4 ) {
                                        $f = $model->fecha_inicial;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'months'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }

                                    if( $model->tiempo == 5 ) {
                                        $f = $model->fecha_inicial;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'years'));
                                        $fecha_fin = date_format($fecha, 'Y/m/d');
                                    }
                            ?> 

        <!--FIN DE TIEMPO -->

        <!--INICIO DE IDIOMA -->
        
            <div>
                <p><i><small><?= $form->field( $model, 'idioma' )->checkBox( [ 'inline' => true ] )?></small></i></p>
            </div>
    
        <!--FIN DE IDIOMA -->


        </div>

        <div class="row">  

        <!--INICIO DE FECHA FIN -->

            <div class="col-sm-3">
                            <?= $form->field($model, 'fecha_fin')->textInput(                         [ 'id'=> 'cantidad_tiempo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $fecha_fin,
                                                                                                            'readonly' => true,
                                                                                                         
                                                                                                                                                                                                                   
                                                                                                            ]); ?>
            </div> 


        <!--FIN DE FECHA FIN -->

        <!--INICIO DE ID SIM -->

            <div>
                <?= $form->field( $model, 'id_sim' )->label( false )->textInput( [ 'inline' => true, 'style' => 'width:100%;' ] )?>
            </div>

        <!--FIN DE ID SIM -->
        
        </div>

        <div class="row">

        <!--INICIO DE TIPO --> 

            <div>
                                   
                <?php   
                $modelTiposPropaganda = TiposPropaganda::find()->orderBy( [ 'tipo_propaganda' => SORT_ASC ] )->asArray()->all();          
                $listaTiposPropaganda = ArrayHelper::map( $modelTiposPropaganda, 'tipo_propaganda', 'descripcion' );
                ?>
                
                <?= $form->field( $model, 'tipo_propaganda' )->dropDownList( $listaTiposPropaganda, [  'id' => 'tipo_propaganda',
                                                                                                                                           ''
                                                                                                     ] );
                ?>
            </div>
            
        <!--FIN DE TIPO -->


        </div>

        <div class="row">

        <!--INICIO DE MATERIALES -->
            
            <?php   
            $modelMediosDifusion = MediosDifusion::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();                  
            $listaMediosDifusion = ArrayHelper::map( $modelMediosDifusion, 'medio_difusion', 'descripcion' );
            ?>
            
            <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'materiales')->dropDownList($listaMediosDifusion,
                                                            [
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            ]);
                    ?>
                
            </div>


        <!--FIN DE MATERIALES -->
        </div>




        
            
        
        


        


                                                                                                                                              '     style' => 'width:
                            
                            
                          
                        
                            
                           
                          
                        
                         
                                                
                           
                          
                                                
                        
                         
                               
                           
                     
                                                
                       
             
                    
                   
                                <div>
                                    <p><i><small><?=Yii::t( 'backend', 'Through Construction:' ) ?></small></i></p>
                                    
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