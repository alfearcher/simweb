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


$fecha_fin = '00/00/0000';

$this->title = 'Propaganda';



?>

   <?php
        $form = ActiveForm::begin([
            'action' => ['modificar-propaganda'],
            'id' => 'form-propaganda',
          //  'method' => 'post',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'enableClientScript' => true,
        ]);
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
/**
 * [bloquea description] funcion que bloquea o muestra campos segun la seleccion de un combo

 */

function bloquea() {


   // alert('llego');
// if ($( "#base_Calculo" ).val() == 2) {

//         $("#unidad").hide();

//     } else {

//         $("#ancho").show();
//         $("#alto").show();

//     }

// if ($( "input:checked" ).val()

//         $("#tipo2").hide();

//     } else {

//         $("#tipo2").show();

//     }
//
    if (document.getElementById("base_calculo").value=='') {
    document.getElementById("alto").style.display='none';
    document.getElementById("ancho").style.display='none';
    document.getElementById("unidad").style.display='none';
    document.getElementById("profundidad").style.display='none';

    }

    if (document.getElementById("base_calculo").value==2) {
        document.getElementById("alto").style.display='';
        document.getElementById("ancho").style.display='';
        document.getElementById("unidad").style.display='none';
        document.getElementById("profundidad").style.display='none';

    }

    if (document.getElementById("base_calculo").value==12) {
        document.getElementById("alto").style.display='';
         document.getElementById("ancho").style.display='';
          document.getElementById("profundidad").style.display='';
        document.getElementById("unidad").style.display='none';

    }


     if (document.getElementById("base_calculo").value != 2) {

    if (document.getElementById("base_calculo").value!=12){
    //     alert('diferente 2 y 12');
        document.getElementById("alto").style.display='none';
        document.getElementById("ancho").style.display='none';
        document.getElementById("unidad").style.display='';
        document.getElementById("profundidad").style.display='none';
        }

     }

//
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

<body onload = "bloquea()"/>

<?php $form = ActiveForm::begin([


]);

?>

    <?




    ?>

    <?= Html::activeHiddenInput( $model, 'planilla', [ 'value' => '0' ] )?>
    <?= Html::activeHiddenInput( $model, 'id_cp', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'id_sim', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'inactivo', [ 'value' => '0' ] ) ?>



   <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= $this->title ?>
            </div>
            <div class="panel-body" >



        <div class="row">

        <!--INICIO ANO IMPOSITIVO -->

            <div class="col-sm-2">
                            <?= $form->field($model, 'ano_impositivo')->textInput(              [ 'id'=> 'ano_impositivo',


                                                                                              //  'value' => $datos[0]->ano_impositivo,
                                                                                               // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                'readOnly' =>true,
                                                                                                'style' => 'width:60px;'
                                                                                                            ]); ?>
            </div>

        <!--FIN ANO IMPOSITIVO -->


         <!--INICIO NOMBRE PROPAGANDA -->

            <div class="col-sm-3" style="margin-left: -50px;">
                            <?= $form->field($model, 'nombre_propaganda')->textInput(              [ 'id'=> 'nombre_propaganda',

                                                                                                      //'value' => $datos[0]->nombre_propaganda,

                                                                                               // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                'readOnly' =>false,

                                                                                                            ]); ?>
            </div>

        <!--FIN NOMBRE PROPAGANDA -->



        <!--INICIO DE CLASE PROPAGANDA -->


                       <?php
                    $modelClasesPropaganda = ClasesPropaganda::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();
                    $listaClasesPropaganda = ArrayHelper::map( $modelClasesPropaganda, 'clase_propaganda', 'descripcion' );
                    ?>

            <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'clase_propaganda')->dropDownList($listaClasesPropaganda,
                                                                [
                                                                //'value' => $datos[0]->clase_propaganda,
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


            <div class="col-sm-2">
                        <?= $form->field($model, 'uso_propaganda')->dropDownList($listaUsosPropaganda,
                                                            [
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            ]);
                    ?>

            </div>

        <!--FIN DE USO DE PROPAGANDA -->

        </div>

        <hr>

        <div class="row">



            <!--INICIO DE FECHA INICIAL -->

            <div class="col-sm-2">

                <?= $form->field($model, 'fecha_desde')->widget(\yii\jui\DatePicker::classname(),[
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


        <!--FIN DE FECHA INICIAL -->




        <!--INICIO DE CANTIDAD TIEMPO -->

            <div class="col-sm-3">
                            <?= $form->field($model, 'cantidad_tiempo')->textInput(                         [ 'id'=> 'cantidad_tiempo',





                                                                                                            ]); ?>
            </div>


        <!--FIN DE CANTIDAD TIEMPO -->

        <!--INICIO DE TIEMPO -->

            <div class="col-sm-3">

                                    <?php
                                    $modelTiempo = Tiempo::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();
                                    $listaTiempo = ArrayHelper::map( $modelTiempo, 'id_tiempo', 'descripcion' );
                                    ?>

                                    <?= $form->field( $model, 'id_tiempo' )->dropDownList( $listaTiempo, [   'id' => 'tiempo',
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
                                    if($model->id_tiempo == 1) {
                                       // die('llego a 1');

                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);

                                        date_add($fecha, date_interval_create_from_date_string($t.'hours'));
                                        $fecha_fin = date_format($fecha, 'd-m-Y');
                                    }

                                    if( $model->id_tiempo == 2 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'days'));
                                        $fecha_fin = date_format($fecha, 'd-m-Y');
                                    }

                                    if( $model->id_tiempo == 3 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'weeks'));
                                        $fecha_fin = date_format($fecha, 'd-m-Y');
                                    }

                                    if( $model->id_tiempo == 4 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'months'));
                                        $fecha_fin = date_format($fecha, 'd-m-Y');
                                    }

                                    if( $model->id_tiempo == 5 ) {
                                        $f = $model->fecha_desde;
                                        $t = $model->cantidad_tiempo;
                                        $fecha = date_create($f);
                                        date_add($fecha, date_interval_create_from_date_string($t.'years'));
                                        $fecha_fin = date_format($fecha, 'd-m-Y');
                                    }
                            ?>

        <!--FIN DE TIEMPO -->

        <!--INICIO DE FECHA FIN -->

            <div class="col-sm-2">
                            <?= $form->field($model, 'fecha_fin')->textInput([ 'id'=> 'fecha_fin',


                                                                                'value' => $fecha_fin,
                                                                                'readonly' => true,


                                                                                ]); ?>
            </div>


        <!--FIN DE FECHA FIN -->

        </div>

        <hr>


        <!--INICIO DE CANTIDAD -->

        <div class="row">

            <div class="col-sm-3">

                <?= $form->field( $model, 'cantidad_base' )->textInput( [  'onkeyup' => 'puntitos(this,this.value.charAt(this.value.length-1),2)',
                                                                                                               'inline' => true,
                                                                                                               'style' => 'width:100%;'
                                                                                                            ] )
                ?>
            </div>

        <!--FIN DE CANTIDAD -->

        <!--INICIO DE BASE -->

            <?php
            $modelBaseCalculo = BasesCalculos::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();
            $listaBaseCalculo  = ArrayHelper::map( $modelBaseCalculo, 'base_calculo', 'descripcion' );
            ?>

            <div class="col-sm-2" >
                        <?= $form->field($model, 'base_calculo')->dropDownList($listaBaseCalculo,
                                                            [
                                                            'id' => 'base_calculo',
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            'onchange' => 'bloquea()',
                                                            ]);
                    ?>

            </div>

        <!--FIN DE BASE -->


        <!--INICIO DE UNIDAD -->


            <div class="col-sm-1" id="unidad">

                <?= $form->field( $model, 'unidad' )->textInput( [
                                                                                                               'inline' => true,
                                                                                                               'style' => 'width:100%;',

                                                                                                            ] )
                ?>
            </div>

        <!--FIN DE UNIDAD -->


        <!--INICIO DE ALTO -->


        <div class="col-sm-1" id="alto">

                <?= $form->field( $model, 'alto' )->textInput( [
                                                                                                               'inline' => true,
                                                                                                               'style' => 'width:100%;',

                                                                                                            ] )
                ?>
        </div>

        <!--FIN DE ALTO -->

        <!--INICIO DE ANCHO -->


        <div class="col-sm-1" id="ancho">

                <?= $form->field( $model, 'ancho' )->textInput( [
                                                                                                               'inline' => true,
                                                                                                               'style' => 'width:100%;',

                                                                                                            ] )
                ?>
        </div>

        <!--FIN DE ANCHO -->


        <!--INICIO DE PROFUNDIDAD -->


        <div class="col-sm-1" id="profundidad">

                <?= $form->field( $model, 'profundidad' )->textInput( [
                                                                                                               'inline' => true,
                                                                                                               'style' => 'width:100%;',

                                                                                                            ] )
                ?>
        </div>

        <!--FIN DE PROFUNDIDAD -->






        </div>

        <hr>


        <div class="row">

        <!--INICIO DE CIGARRILLOS -->

                <div class="col-sm-3" style="margin-left: 40px; margin-top: 30px;">
                    <p><i><small><?= $form->field( $model, 'cigarros' )->checkBox( [ 'inline' => true ] )?></p>
                </div>

        <!--FIN DE CIGARRILLOS -->

        <!--INICIO DE BEBIDAS ALCOHOLICAS -->
               <div class="col-sm-3" style="margin-left: 40px; margin-top: 30px;">
                   <?= $form->field( $model, 'bebidas_alcoholicas' )->checkBox( [ 'inline' => true ] )?>
                </div>

         <!--FIN DE BEBIDAS ALCOHOLICAS -->



        <!--INICIO DE IDIOMA -->
            <div class="col-sm-3" style="margin-left: 40px; margin-top: 30px;">

              <?= $form->field( $model, 'idioma' )->checkBox( [ 'inline' => true ] )?>

            </div>
        <!--FIN DE IDIOMA -->


        </div>


        <div class="row">





        </div>

        <hr>

        <div class="row">

        <!--INICIO DE TIPO -->

            <div class="col-sm-5">

                <?php
                $modelTiposPropaganda = TiposPropaganda::find()->orderBy( [ 'tipo_propaganda' => SORT_ASC ] )->asArray()->all();
                $listaTiposPropaganda = ArrayHelper::map( $modelTiposPropaganda, 'tipo_propaganda', 'descripcion' );
                ?>



                <?= $form->field( $model, 'tipo_propaganda' )->dropDownList( $listaTiposPropaganda, ['id' => 'tipo_propaganda',
                                                                                                    'prompt' => 'select',                                     ''
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

            <div class="col-sm-2">
                        <?= $form->field($model, 'medio_difusion')->dropDownList($listaMediosDifusion,
                                                            [
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            ]);
                    ?>

            </div>


        <!--FIN DE MATERIALES -->

        <!--INICIO DE MEDIO DE TRANSPORTE -->

            <?php
            $modelMediosTransporte = MediosTransporte::find()->orderBy( [ 'descripcion' => SORT_ASC ] )->asArray()->all();
            $listaMediosTransporte = ArrayHelper::map( $modelMediosTransporte, 'medio_transporte', 'descripcion' );
            ?>

            <div class="col-sm-2">
                        <?= $form->field($model, 'medio_transporte')->dropDownList($listaMediosTransporte,
                                                            [
                                                            'prompt' => yii::t('frontend', 'Select'),
                                                            ]);
                    ?>

            </div>

        <!--FIN DE MEDIO DE TRANSPORTE -->


        </div>


        <div class="row">

        <!--INICIO DE DIRECCION -->

            <div class="col-sm-5">
                            <?= $form->field($model, 'direccion')->textArea(
                                  [ 'id'=> 'direccion',

                                'style' => 'width:280px;',



                                                                                                            ]); ?>
            </div>

        <!--INICIO DE OBSERVACION -->

            <div class="col-sm-3">
                        <?= $form->field($model, 'observacion')->textArea([ 'id'=> 'observacion',

                                                                            'style' => 'width:280px; margin-left: -40px;',



                                                                            ]); ?>
            </div>

        <!--FIN DE OBSERVACION -->

           <!--INICIO DE ID SIM -->

            <div class="col-sm-1">
                <?= $form->field( $model, 'id_sim' )->textInput( [ 'inline' => false, 'style' => 'width:100%;' ] )?>
            </div>

        <!--FIN DE ID SIM -->




        </div>


        <div class="row">

          <div class="col-sm-4" >

                  <?= Html::submitButton(Yii::t('frontend' , 'Modificar'),
                                                                            [
                                                                              'id' => 'btn-search',
                                                                              'class' => 'btn btn-success',
                                                                              'name' => 'btn-search',
                                                                              'value' => 1,
                                                                              'style' => 'height:30px;width:100px;margin-right:0px;',
                                                                            ])
                  ?>

          </div>


            <div class="col-sm-3" >

            <?= Html::a('Return',['/menu/vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:140px;margin-left:-100px;' ]) //Retornar a seleccionar tipo usuario ?>

            </div>

        </div>





            </div>
        </div>
    </div>

        <?php ActiveForm::end(); ?>
