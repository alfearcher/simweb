<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;
// agregando titulo
$this->title = 'Recuperacion de contraseña';
// vista para recuperar
?>
 



<?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>




<div class="col-sm-15 ">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" > 
                
                    
 

 


                                                    <div class="row" style="margin-left:20px; margin-top:20px;">
                                                        <div class="col-sm-10"> 
                                                        <?= Yii::t('frontend', 'El Usuario y Contraseña le sera enviado por correo electronico, a su email registrado como contribuyente') ?>
                                                        </div> 
                                                    
                                                       
                                                    </div>

                                            
                                                    
                                                

                                                
                                                    <div class="row" style="margin-left:20px; margin-top:20px;">
                                                        <div class="col-sm-2">
                                                        <?php 
                                                        echo Html::submitButton(Yii::t('frontend', 'Aceptar'), ['class' => 'btn btn-primary', 'name'=>'AcceptBuyer', 'value'=>'Accept']); 
                                                        ?> 
                                                        </div> 
                                                        <div class="col-sm-2">
                                                        <?= Html::a(Yii::t('frontend', 'Volver'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
                                                        </div> 
                                                    </div> 
                                                 
                                       
                                                   
                  </div>      
              </div>
        </div>
                              

      <?= $form->field($model, 'nivel')->hiddenInput(['value' => 0])->label(false) ?>                       

  <?php ActiveForm::end(); ?>                                                   
                