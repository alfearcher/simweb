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
    use frontend\models\usuario\CrearUsuarioNatural;
  

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



$busqueda = CrearUsuarioNatural::find()
                                ->where([
                                    'naturaleza' => $_SESSION['datosNuevos']->naturaleza,
                                    'cedula' => $_SESSION['datosNuevos']->cedula,
                                    //die($_SESSION['datosNuevos']->cedula),
                                    'tipo' => $_SESSION['datosNuevos']->tipo,
                                    'tipo_naturaleza' => 1,
                                    'inactivo' => 0,
                                    ])

                            ->all();

$_SESSION['idComprador'] = $busqueda[0]->id_contribuyente;




$claseVehiculo = ClaseVehiculo::find()
                            ->where([
                            'clase_vehiculo' => $datosVehiculo[0]->clase_vehiculo,
                           // die($datosVehiculo[0]->clase_vehiculo),

                                ])
                            ->all();

$usoVehiculo = UsoVehiculo::find()
                            ->where([
                            'uso_vehiculo' => $datosVehiculo[0]->uso_vehiculo,
                           // die($datosVehiculo[0]->clase_vehiculo),

                                ])
                            ->all();

$tipoVehiculo = TipoVehiculo::find()
                            ->where([
                            'tipo_vehiculo' => $datosVehiculo[0]->tipo_vehiculo,
                           // die($datosVehiculo[0]->clase_vehiculo),

                                ])
                            ->all();
               

?>
               









<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('frontend', 'Vehicle Specifications') ?> </h3>
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
                <?= Yii::t('frontend', 'Buyer Information') ?> 
            </div>
            <div class="panel-body" >

  

<!-- RAZON SOCIAL -->

                <div class="row">
                    <div class="col-sm-3">
                        <?= $form->field($model, 'razon_social')->textInput(
                                                                [
                                                                'value' => $busqueda[0]->razon_social,
                                                                'readonly' => true,
                                                                'id'=> 'razon_social',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE RAZON SOCIAL <-->





<!-- ID  -->

               
                    <div class="col-sm-2">
                        <?= $form->field($model, 'id_contribuyente')->textInput(
                                                                [
                                                                'value' => $busqueda[0]->id_contribuyente,
                                                                'readonly' => true,
                                                                'id'=> 'ID',
                                                                ]);
                    ?>
                
                    </div>
                     </div>

<!-- FIN DE ID <-->
 <div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Vehicle Specifications') ?> 
</div>
                
</div>
<!-- PLACA -->

                <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'placa')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['placa'],
                                                                'readonly' => true,
                                                                'id'=> 'placa',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE PLACA <-->

<!-- MARCA -->

                
                    <div class="col-sm-3" style="margin-left: -20px;">
                        <?= $form->field($model, 'marca')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['marca'],
                                                                'readonly' => true,
                                                                'id'=> 'marca',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE MARCA <-->

<!-- MODELO -->

               
                    <div class="col-sm-3" style="margin-left: -20px;">
                        <?= $form->field($model, 'modelo')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['modelo'],
                                                                'readonly' => true,
                                                                'id'=> 'modelo',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN DE MODELO <-->

<!-- AÑO COMPRA -->

               
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'ano_compra')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['ano_compra'],
                                                                'readonly' => true,
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                               
                                                                
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN DE AÑO COMPRA <-->

<!-- AÑO VEHICULO -->

               
                     <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'ano_vehiculo')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['ano_vehiculo'],
                                                                'readonly' => true,
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                               
                                                                
                                                                ]);
                    ?>
                
                    </div>
                       </div>
<!-- FIN DE AÑO VEHICULO <-->



<!-- CLASES -->

                    <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'clase_vehiculo')->textInput( 
                                                                [

                                                                 'value' => $claseVehiculo[0]->descripcion, 
                                                                 //die($datosVehiculo[0]->clase_vehiculo),
                                                                'readonly' => true,
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
                        <?= $form->field($model, 'tipo_vehiculo')->textInput(
                                                                [

                                                              
                                                               
                                                               
                                                                'value' => $tipoVehiculo[0]->descripcion, 
                                                                
                                                                'readonly' => true,
                                                                'id'=> 'tipo_vehiculo',
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                                ]);
                    ?>
                
                    </div>
                    </div>

<!-- FIN DE TIPOS <-->

<!-- USOS -->

                    <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'uso_vehiculo')->textInput( 
                                                                [
                                                                 'value' => $usoVehiculo[0]->descripcion, 
                                                                'readonly' => true,
                                                                'id'=> 'uso_vehiculo',
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                                ]);
                    ?>
                
                    </div>
                    </div>

<!-- FIN DE USOS <-->

<!-- COLOR -->

                    <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'color')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['color'],
                                                                'readonly' => true,
                                                                'id'=> 'color',
                                                                ]);
                    ?>
                
                    </div>

                    

<!-- COLOR <-->

<!-- NRO. EJES -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'no_ejes')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['no_ejes'],
                                                                'readonly' => true,
                                                                'id'=> 'no_ejes',
                                                                ]);
                    ?>
                
                    </div>

                    

<!-- FIN NRO. EJES <-->

<!-- NRO. PUESTO -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'nro_puestos')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['nro_puestos'],
                                                                'readonly' => true,
                                                                'id'=> 'nro_puestos',
                                                                ]);
                    ?>
                
                    </div>

                    

<!-- FIN NRO. PUESTO <-->

<!-- FECHA INICIO -->

               
                          <div class="col-sm-2" style="margin-left: -20px;">
                        <div class="fecha-inicio">
                             <?= $form->field($model, 'fecha_inicio')->textInput(
                                                                [
                                                                 'value' => $datosVehiculo[0]['fecha_inicio'],
                                                                'readonly' => true,
                                                                'id'=> 'nro_puestos',
                                                                ]);
                    ?>
                        </div>
                    </div>
                     </div>

<!-- FIN DE FECHA INICIO <-->



                   
                 
                   
                    



<!-- PESO -->

                    <div class="row"> 
                    <div class="col-sm-2">
                        <?= $form->field($model, 'peso')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['peso'],
                                                                'readonly' => true,
                                                                'id'=> 'peso',
                                                                ]);
                    ?>
                
                    </div>
                   
                    

<!-- FIN PESO <-->

<!-- CILINDRADA -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'nro_cilindros')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['nro_cilindros'],
                                                                'readonly' => true,
                                                                'id'=> 'cilindrada',
                                                                ]);
                    ?>
                
                    </div>
                    
                    

<!-- FIN DE CILINDRADA <-->

<!-- PRECIO -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'precio_inicial')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['precio_inicial'],
                                                                'readonly' => true,
                                                                'id'=> 'precio_inicial',
                                                                ]);
                    ?>
                
                    </div>
                   

<!-- FIN PRECIO <-->

<!-- CALCOMANIA -->

                   
                    <div class="col-sm-2" style="margin-left: -20px;">
                        <?= $form->field($model, 'nro_calcomania')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['nro_calcomania'],
                                                                'readonly' => true,
                                                                'id'=> 'precio_inicial',
                                                                ]);
                    ?>
                
                    </div>
                   </div>

<!-- FIN DE CALCOMANIA <-->


<!-- CAPACIDAD DE CARGA -->

                    <div class="row">                    
                    <div class="col-sm-2">
                        <?= $form->field($model, 'capacidad')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['capacidad'],
                                                                'readonly' => true,
                                                                'id'=> 'capacidad',
                                                                ]);
                    ?>
                
                    </div>
                    </div>
                 

<!-- FIN CAPACIDAD DE CARGA <-->

<!-- EXCESO CAPACIDAD DE CARGA <-->


                    <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'exceso_cap')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['exceso_cap'],
                                                                'readonly' => true,
                                                                'id'=> 'exceso_cap',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN EXCESO CAPACIDAD DE CARGA <-->

<!-- MEDIDA DE CAPACIDAD -->

                         
                    <div class="col-sm-2" style="margin-bottom:43px;">
                       
                     <?= $form->field($model, 'medida_cap')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['medida_cap'],
                                                                'readonly' => true,
                                                                'id'=> 'exceso_cap',
                                                                ]);
                    ?>
                               
                
                    </div>
                    </div>
                    



<!-- FIN MEDIDA DE CAPACIDAD -->


<!-- SERIAL DE CARROCERIA <-->


                    <div class="row">
                    <div class="col-sm-5">
                        <?= $form->field($model, 'serial_carroceria')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['serial_carroceria'],
                                                                'readonly' => true,
                                                                'id'=> 'serial_carroceria',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN SERIAL DE CARROCERIA <-->



<!-- SERIAL DE MOTOR <-->


                    
                    <div class="col-sm-5" style="margin-left: -20px;">
                        <?= $form->field($model, 'serial_motor')->textInput(
                                                                [
                                                                'value' => $datosVehiculo[0]['serial_motor'],
                                                                'readonly' => true,
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

