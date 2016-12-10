<?php


    use yii\helpers\Html;
    //use yii\widgets\ActiveForm;
    use yii\web\View;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\jui\DatePicker;
    use common\models\vehiculo\clasevehiculo\ClaseVehiculo;
    use common\models\vehiculo\tipovehiculo\TipoVehiculo;
    use common\models\vehiculo\usovehiculo\UsoVehiculo;
    use common\fecha\RangoFecha;
    use kartik\form\ActiveForm;

//arrayhelper que carga el combo clase vehiculo
$claseVehiculo = ClaseVehiculo::find()->all();
$listaClaseVehiculo = ArrayHelper::map($claseVehiculo, 'clase_vehiculo' , 'descripcion' );
////////////////////////////////////////////
///
///arrayhelper que carga el combo tipo vehiculo
$tipoVehiculo = TipoVehiculo::find()->all();
$listaTipoVehiculo = ArrayHelper::map($tipoVehiculo, 'tipo_vehiculo' , 'descripcion' );
////////////////////////////////////////////
///
///arrayhelper que carga el uso de vehiculo
$usoVehiculo = UsoVehiculo::find()->all();
$listaUsoVehiculo = ArrayHelper::map($usoVehiculo, 'uso_vehiculo' , 'descripcion' );
////////////////////////////////////////////
///

// $fecha_actual = date('Y');

 
// foreach  (range(1960, $fecha_actual) as $fecha[]) { 

//   }
// //die(var_dump($fecha));


 $fecha = new RangoFecha(); // instancia de la clase que contiene el metodo para crear combos de rango de fecha dinamicos.

 $listaFecha = $fecha->RangoFecha(1960);

 //die(var_dump($listaFecha));
 
$listaAño = ArrayHelper::map($listaFecha, 'id' , 'campo');


$listaFecha2 = $fecha->RangoFecha(1920);

$listaAño2  = ArrayHelper::map($listaFecha2, 'id' , 'campo');



    




?>







<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('frontend', 'Vehicle Registration') ?> </h3>
</div>

<div><br></div>





<!-- FORMULARIO PARA VEHICULO -->

<?php $form = ActiveForm::begin([
            'id' => 'form-vehiculo-inline',
            'method' => 'post',
            //'action' => ['/usuario/crear-usuario-natural/natural'],
             'enableClientValidation' => true,
             'enableAjaxValidation' => false,
             'enableClientScript' => true,

        ]);

?>

    <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Vehicle Registration') ?> 
            </div>
            <div class="panel-body" >

<!-- PLACA -->

                <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'placa')->textInput(
                                                                [
                                                                'id'=> 'placa',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE PLACA <-->

<!-- MARCA -->

                
                    <div class="col-sm-3" style="margin-left: -20px;">
                        <?= $form->field($model, 'marca')->textInput(
                                                                [
                                                                'id'=> 'marca',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE MARCA <-->

<!-- MODELO -->

               
                    <div class="col-sm-3" style="margin-left: -20px;">
                        <?= $form->field($model, 'modelo')->textInput(
                                                                [
                                                                'id'=> 'modelo',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN DE MODELO <-->

<!-- AÑO COMPRA -->

               
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'ano_compra')->dropDownList($listaAño,
                                                                [
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                               
                                                                
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN DE AÑO COMPRA <-->

<!-- AÑO VEHICULO -->

               
                     <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'ano_vehiculo')->dropDownList($listaAño2,
                                                                [
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                               
                                                                
                                                                ]);
                    ?>
                
                    </div>
                       </div>
<!-- FIN DE AÑO VEHICULO <-->



<!-- CLASES -->

                    <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'clase_vehiculo')->dropDownList($listaClaseVehiculo,
                                                                [
                                                                'id'=> 'clase_vehiculo',
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                                ]);
                    ?>
                
                    </div>
                      </div>


<!-- FIN DE CLASES <-->

<!-- TIPOS -->

                    <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'tipo_vehiculo')->dropDownList($listaTipoVehiculo,
                                                                [
                                                                'id'=> 'tipo_vehiculo',
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                                ]);
                    ?>
                
                    </div>
                    </div>

<!-- FIN DE TIPOS <-->

<!-- TIPOS -->

                    <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'uso_vehiculo')->dropDownList($listaUsoVehiculo,
                                                                [
                                                                'id'=> 'uso_vehiculo',
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                                ]);
                    ?>
                
                    </div>
                    </div>

<!-- FIN DE TIPOS <-->

<!-- COLOR -->

                    <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'color')->input(
                                                                [
                                                                
                                                                'id'=> 'color',
                                                                ]);
                    ?>
                
                    </div>

                    

<!-- COLOR <-->

<!-- NRO. EJES -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'no_ejes')->input(
                                                                [
                                                                
                                                                'id'=> 'no_ejes',
                                                                ]);
                    ?>
                
                    </div>

                    

<!-- FIN NRO. EJES <-->

<!-- NRO. PUESTO -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'nro_puestos')->input(
                                                                [
                                                                
                                                                'id'=> 'nro_puestos',
                                                                ]);
                    ?>
                
                    </div>

                    

<!-- FIN NRO. PUESTO <-->

<!-- FECHA INICIO -->

               
                          <div class="col-sm-2">
                        <div class="fecha-nac">
                            <?= $form->field($model, 'fecha_inicio')->widget(\yii\jui\DatePicker::classname(),[
                                                                                        //'type' => 'date',
                                                                                        'clientOptions' => [
                                                                                            'maxDate' => '+0d',// Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                            'changeYear' => 'true', 
                                                                                         ],
                                                                                       'language' => 'es-ES',
                                                                                       'dateFormat' => 'dd-MM-yyyy',
                                                                                        'options' => [
                                                                                            //'onClick' => 'alert("calendario")',
                                                                                            'id' => 'fecha-nac',
                                                                                            'class' => 'form-control',
                                                                                           'readonly' => true,

                                                                                            //'type' => 'date',
                                                                                            'style' => 'background-color: white;',
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                        </div>
                    </div>
                     </div>

<!-- FIN DE FECHA INICIO <-->



                   
                 
                   
                    



<!-- PESO -->

                    <div class="row"> 
                    <div class="col-sm-2">
                        <?= $form->field($model, 'peso')->input(
                                                                [
                                                                
                                                                'id'=> 'peso',
                                                                ]);
                    ?>
                
                    </div>
                   
                    

<!-- FIN PESO <-->

<!-- CILINDRADA -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'nro_cilindros')->input(
                                                                [
                                                                
                                                                'id'=> 'cilindrada',
                                                                ]);
                    ?>
                
                    </div>
                    
                    

<!-- FIN DE CILINDRADA <-->

<!-- PRECIO -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'precio_inicial')->input(
                                                                [
                                                                
                                                                'id'=> 'precio_inicial',
                                                                ]);
                    ?>
                
                    </div>
                    </div>

<!-- FIN PRECIO <-->


<!-- CAPACIDAD DE CARGA -->

                    <div class="row">                    
                    <div class="col-sm-2">
                        <?= $form->field($model, 'capacidad')->input(
                                                                [
                                                                
                                                                'id'=> 'capacidad',
                                                                ]);
                    ?>
                
                    </div>
                    </div>
                 

<!-- FIN CAPACIDAD DE CARGA <-->

<!-- EXCESO CAPACIDAD DE CARGA <-->


                    <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'exceso_cap')->input(
                                                                [
                                                                
                                                                'id'=> 'exceso_cap',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN EXCESO CAPACIDAD DE CARGA <-->

<!-- MEDIDA DE CAPACIDAD -->

                         
                    <div class="col-sm-2" style="margin-bottom:43px;">
                       
                    <?= $form->field($model, 'medida_cap')->label(false)->radioList([
                                                                                                        'Kgs' => Yii::t('frontend', 'Kgs.'),

                                                                                                        'Ton' => Yii::t('frontend', 'Tns.'),
                                                                                                      ],
                                                                                                      [
                                                                                                        
                                                                                                      ]
                                                                                                      ) ?>
                               
                
                    </div>
                    </div>
                    



<!-- FIN MEDIDA DE CAPACIDAD -->


<!-- SERIAL DE CARROCERIA <-->


                    <div class="row">
                    <div class="col-sm-5">
                        <?= $form->field($model, 'serial_carroceria')->input(
                                                                [
                                                                'maxlenght' => 17,
                                                                'id'=> 'serial_carroceria',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN SERIAL DE CARROCERIA <-->



<!-- SERIAL DE MOTOR <-->


                    
                    <div class="col-sm-5" style="margin-left: -20px;">
                        <?= $form->field($model, 'serial_motor')->input(
                                                                [
                                                                'maxlenght' => 17,
                                                                'id'=> 'serial_motor',
                                                                ]);
                    ?>
                
                    </div>
                    </div>

                    

<!-- FIN SERIAL DE MOTOR <-->

                


                 


              


                

             

               
               <div class="row">
                    <div class="col-sm-4">
                        <?= Html::submitButton(Yii::t('frontend', 'Create') , ['class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                    </div>

                    <div class="col-sm-4">
                        <?= Html::a('Return',['/site/menu-vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:-55px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                    </div>
                </div>





            </div>
        </div>
    </div>



  
<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO REGISTRO VEHICULO -->

