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
    use backend\models\tasa\Tasa;
    use backend\models\impuesto\Impuesto;

    /////////////////////////////////////////////////////////////////////

    $modelImpuesto = Impuesto::find()->asArray()->all();
    $listaImpuesto = ArrayHelper::map($modelImpuesto, 'impuesto', 'descripcion');

    ////////////////////////////////////////////////////////////////////

    $modelAnoImpositivo = Tasa::find()->distinct()->asArray()->orderBy('ano_impositivo')->all();
        
    $anosImpositivos = ArrayHelper::map($modelAnoImpositivo, 'ano_impositivo' , 'ano_impositivo');

    ////////////////////////////////////////////////////////////////////////


      $modelCodigosEspecificos = Tasa::find()->distinct()->asArray()->all();
      $listaCodigosEspecificos = ArrayHelper::map($modelCodigosEspecificos, 'codigo', 'codigo');

      ////////////////////////////////////////////////////////////////////

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
                                        
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($anosImpositivos,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>

            
            </div>

            <div class="row">
                <div class="col-sm-3">
                        <?= Html::submitButton(Yii::t('frontend', 'Buscar') , ['id' => 'btn-busqueda-nivel', 'name' => 'btn-busqueda-ano','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                </div>
            </div>
            



            <hr>

 

            <!--FIN DE FORMULARIO PARA BUSQUEDA DE FUNCIONARIO Y AÑO IMPOSITIVO-->

       
        <div class="row">  
                <div class="col-sm-7" style="padding-right: 12px;">
                                        
                                            <?= $form->field($model, 'ano_impositivo2')->dropDownList($anosImpositivos,[
                                                                                                    'id' => 'ano_impositivo1',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>

        </div>

                <div class="row">

                      <div class="col-sm-7" style="padding-right: 12px;">
                                        
                                            <?= $form->field($model, 'impuesto')->dropDownList($listaImpuesto,[
                                                                                                    'id' => 'impuesto',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>

            
            </div>

            
            <div class="row"> 
                <div class="col-sm-3" style="margin-left:0px;">
                    <?= Html::submitButton(Yii::t('frontend', 'Buscar') , ['id' => 'btn-busqueda-codigo', 'name' => 'btn-busqueda-ano-impuesto','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                </div>  
            </div>


            <hr>



        <div class="row">  
                <div class="col-sm-7" style="padding-right: 12px;">
                                        
                                            <?= $form->field($model, 'ano_impositivo3')->dropDownList($anosImpositivos,[
                                                                                                    'id' => 'ano_impositivo2',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>

        </div>

                <div class="row">

                      <div class="col-sm-7" style="padding-right: 12px;">
                                        
                                            <?= $form->field($model, 'impuesto2')->dropDownList($listaImpuesto,[
                                                                                                    'id' => 'impuesto2',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>

            
            </div>
            
            <div class="row">


                     <div class="col-sm-7" style="padding-right: 12px;">
                                        
                                            <?= $form->field($model, 'codigo')->dropDownList($listaCodigosEspecificos,[
                                                                                                    'id' => 'codigo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                </div>
                


            </div>

               <div class="row"> 
                <div class="col-sm-3" style="margin-left:0px;">
                    <?= Html::submitButton(Yii::t('frontend', 'Buscar') , ['id' => 'btn-busqueda-codigo', 'name' => 'btn-busqueda-ano-impuesto-codigo','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                </div>  
            </div>



       
    

    



            </div>
        </div>
    </div>



  
<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO REGISTRO VEHICULO -->

