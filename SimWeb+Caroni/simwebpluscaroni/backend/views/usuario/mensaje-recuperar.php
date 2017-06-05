<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;
// 
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
                                                        <?= Yii::t('frontend', 'El Usuario y Contraseña le sera enviado por correo electronico, al email registrado como '.$_SESSION['Contribuyente']['email']) ?>
                                                        </div> 
                                                    
                                                       
                                                    </div>

                                            
                                                    
                                                

                                                
                                                    <div class="row" style="margin-left:20px; margin-top:20px;">
                                                        <div class="col-sm-2">
                                                        <?php 
                                                        echo Html::submitButton(Yii::t('frontend', 'Aceptar'), ['class' => 'btn btn-success', 'name'=>'AcceptBuyer', 'value'=>'Accept']); 
                                                        ?> 
                                                        </div> 
                                                        <div class="col-sm-2">
                                                        <?= Html::a(Yii::t('frontend', 'Volver'), ['/usuario/recuperar-acceso-contribuyente/seleccionar-tipo-contribuyente'], ['class' => 'btn btn-danger']) ?>
                                                        </div> 
                                                    </div> 
                                                 
                                       
                                                   
                  </div>      
              </div>
        </div>
                              

      <?= $form->field($model, 'nivel')->hiddenInput(['value' => 0])->label(false) ?>                       
 
  <?php ActiveForm::end(); ?>                                                   
                