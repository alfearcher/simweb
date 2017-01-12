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

////////////////////////////Creacion de la lista con años impositivos/////////////////////////////
    $busquedaAno = Calcomania::find()
                            ->select('ano_impositivo')
                            ->distinct()
                            ->asArray()
                            ->all();

    
    $listaAnoImpositivo = ArrayHelper::map($busquedaAno, 'ano_impositivo' , 'ano_impositivo' );


////////////////////////////////////////////////////////////////////////////////////////////////


$busquedaFuncionario =Funcionario::find()
                               ->where([
          
                          'estatus' => 0,
                          ])
        ->joinWith('funcionarioCalcomania')
                       ->all();
        //die(var_dump($busquedaFuncionario));

        
        


$listaFuncionarios = ArrayHelper::map($busquedaFuncionario, 'id_funcionario', function($busquedaFuncionario){return ''.$busquedaFuncionario['nombres'].'    '.$busquedaFuncionario['apellidos'].'   '.$busquedaFuncionario['ci'];}); 
?>







<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('frontend', 'Multiple Search') ?> </h3>
</div>

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



    <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Officer Search') ?> 
            </div>
            <div class="panel-body" >

  

            <!--FORMULARIO PARA BUSQUEDA DE FUNCIONARIO Y AÑO IMPOSITIVO-->

            <div class="row">  
                <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($listaAnoImpositivo,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                </div>

            </div>

            <div class="row">  
            <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'id_funcionario')->dropDownList($listaFuncionarios,[
                                                                                                    'id' => 'id_funcionario',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                                    </div>

            


            <div class="col-sm-5" style="margin-left:0px;">
                        <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-funcionario-ano', 'name' => 'btn-funcionario-ano','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                    </div>

                   
            </div>

            <!--FIN DE FORMULARIO PARA BUSQUEDA DE FUNCIONARIO Y AÑO IMPOSITIVO-->

        <hr>

            <!--INICIO DE FORMULARIO PARA BUSQUEDA DE AÑO IMPOSITIVO Y CALCOMANIA ESPECIFICA-->


        

            
            <div class="row">  
            <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'ano_impositivo2')->dropDownList($listaAnoImpositivo,[
                                                                                                    'id' => 'ano_impositivo2',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                                    </div>
                                    </div>

            <div class="row">           
            <div class="col-sm-2">
                        <?= $form->field($model, 'nro_calcomania')->textInput(
                                                                [
                                                               
                                                               
                                                                'id'=> 'nro_calcomania',
                                                                ]);
                    ?>
                
            </div>
         

            
            <div class="col-sm-5" style="margin-left:230px;">
                        <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-funcionario-calcomania', 'name' => 'btn-funcionario-calcomania' ,'class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
            </div>

            </div>  

             <!--FIN DE FORMULARIO PARA BUSQUEDA DE FUNCIONARIO Y AÑO IMPOSITIVO-->


            <hr>

           <!--INICIO DE FORMULARIO PARA BUSQUEDA DE RANGO DE CALCOMANIA Y AÑO IMPOSITIVO-->


        

            
            <div class="row">  
            <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'ano_impositivo3')->dropDownList($listaAnoImpositivo,[
                                                                                                    'id' => 'ano_impositivo3',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                                    </div>
                                    </div>

            <div class="row">           
            <div class="col-sm-2">
                        <?= $form->field($model, 'rango_inicial')->textInput(
                                                                [
                                                               
                                                               
                                                                'id'=> 'rango_inicial',
                                                                ]);
                    ?>
                
            </div>

             <div class="col-sm-2">
                        <?= $form->field($model, 'rango_final')->textInput(
                                                                [
                                                               
                                                               
                                                                'id'=> 'rango_final',
                                                                ]);
                    ?>
                
            </div>
         

            
            <div class="col-sm-5" style="margin-left:80px;">
                        <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-rango-calcomania', 'name' => 'btn-rango-calcomania' ,'class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
            </div>

            </div>  

             <!--FIN DE FORMULARIO PARA BUSQUEDA DE RANGO DE CALCOMANIA Y AÑO IMPOSITIVO-->


            </div>

             
    

    



            </div>
        </div>
    </div>



  
<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO REGISTRO VEHICULO -->

