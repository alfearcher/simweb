<?php


    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\web\View;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\jui\DatePicker;
    use common\models\vehiculo\clasevehiculo\ClaseVehiculo;
    use common\models\vehiculo\tipovehiculo\TipoVehiculo;
    use common\models\vehiculo\usovehiculo\UsoVehiculo;

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
            'enableAjaxValidation' => true,
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

                
                    <div class="col-sm-2">
                        <?= $form->field($model, 'marca')->textInput(
                                                                [
                                                                'id'=> 'marca',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE MARCA <-->

<!-- MODELO -->

               
                    <div class="col-sm-2">
                        <?= $form->field($model, 'modelo')->textInput(
                                                                [
                                                                'id'=> 'modelo',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN DE MODELO <-->

<!-- AÑO COMPRA -->

               
                    <div class="col-sm-2">
                        <?= $form->field($model, 'ano_compra')->input('date',
                                                                [
                                                                'type' => 'date',
                                                                'id'=> 'ano_compra',
                                                                ]);
                    ?>
                
                    </div>
                    

<!-- FIN DE AÑO COMPRA <-->

<!-- AÑO VEHICULO -->

               
                    <div class="col-sm-2">
                        <?= $form->field($model, 'ano_vehiculo')->textInput(
                                                                [
                                                                'type' => 'date',
                                                                'id'=> 'ano_vehiculo',
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

