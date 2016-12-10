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
    use common\models\calcomania\calcomaniamodelo\Calcomania;
    use backend\models\funcionario\Funcionario;
    use common\models\presupuesto\nivelespresupuesto\NivelesContables;


$modeloNivelesContables = NivelesContables::find()->asArray()->all();
$listaNiveles = ArrayHelper::map($modeloNivelesContables, 'nivel_contable', 'descripcion');

?>








<div><br></div>





<!-- FORMULARIO PARA VEHICULO -->

<?php $form = ActiveForm::begin([
            'id' => 'form-busqueda-inline',
            'method' => 'post',
            //'action' => ['/usuario/crear-usuario-natural/natural'],
             'enableClientValidation' => false,
             'enableAjaxValidation' => false,
             'enableClientScript' => true,

        ]);

?>



    <div class="col-sm-11">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Busqueda Multiple') ?> 
            </div>
            <div class="panel-body" >

  

         

            <div class="row">  
                <div class="col-sm-7" style="padding-right: 12px;">
                                        
                                            <?= $form->field($model, 'nivel_contable')->dropDownList($listaNiveles,[
                                                                                                    'id' => 'nivel_contable',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>

            
            </div>

            <div class="row">
                <div class="col-sm-3">
                        <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-busqueda-nivel', 'name' => 'btn-busqueda-nivel','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                </div>
            </div>
            



            <hr>

 

            <!--FIN DE FORMULARIO PARA BUSQUEDA DE FUNCIONARIO Y AÑO IMPOSITIVO-->

       
            <div class="row">           
                <div class="col-sm-5">
                        <?= $form->field($model, 'codigo')->textInput(
                                                                [
                                                               
                                                               
                                                                'id'=> 'codigo',
                                                                ]);
                    ?>
                
                </div>
            </div>
            
            <div class="row"> 
                <div class="col-sm-3" style="margin-left:0px;">
                    <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-busqueda-codigo', 'name' => 'btn-busqueda-codigo','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                </div>  
            </div>


        

       
    

    



            </div>
        </div>
    </div>



  
<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO REGISTRO VEHICULO -->

