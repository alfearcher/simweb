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






?>







<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('frontend', 'Oficcer Information to Enable') ?> </h3>
</div>

<div><br></div>





<!-- FORMULARIO PARA DATOS DEL FUNCIONARIO -->

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
                <?= Yii::t('frontend', 'Vehicle Specifications') ?> 
            </div>
            <div class="panel-body" >

<!-- NOMBRE -->

                <div class="row">
                    <div class="col-sm-2">
                        <?= $form->field($model, 'nombre')->textInput(
                                                                [
                                                                'value' => $datosFuncionario[0]['nombres'],
                                                                'readonly' => true,
                                                                'id'=> 'nombre',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE NOMBRE <-->

<!-- APELLIDO -->

                
                    <div class="col-sm-3" style="margin-left: -20px;">
                        <?= $form->field($model, 'apellido')->textInput(
                                                                [
                                                                 'value' => $datosFuncionario[0]['apellidos'],
                                                                'readonly' => true,
                                                                'id'=> 'apellido',
                                                                ]);
                    ?>
                
                    </div>
                

<!-- FIN DE MARCA <-->

<!-- MARCA -->

                
                    <div class="col-sm-3" style="margin-left: -20px;">
                        <?= $form->field($model, 'cedula')->textInput(
                                                                [
                                                                 'value' => $datosFuncionario[0]['ci'],
                                                                'readonly' => true,
                                                                'id'=> 'cedula',
                                                                ]);
                    ?>
                
                    </div>
                    </div>
                

<!-- FIN DE MARCA <-->



                <div class="row">
                    <div class="col-sm-4">
                        <?= Html::submitButton(Yii::t('frontend', 'Enable') , ['class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
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

