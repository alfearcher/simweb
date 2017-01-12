<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



                                

$this->title = 'Registro de Monto Presupuestario';
?>
 



<?php $form = ActiveForm::begin([
   // 'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>


<div class="col-sm-6" style="margin-right:200px;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
                </div>
                    <div class="panel-body" >
               
                  


                           
                         

<!-- NIVEL CONTABLE -->
                                    <div class="row">
                               
<!-- FIN DE NIVEL CONTABLE -->

<!-- DESCRIPCION -->
                                    <div class="col-sm-5" style="padding-right: 0px;padding-left: 40px;">
                                     
                                            
                                               <?= $form->field($model, 'monto')->textInput([
                                                                                            'id' => 'monto',
                                                                                            //'style' => 'height:32px;width:200px;',
                                                                                           
                                                                                          
                                                                                         ]); ?>
                                       
                                    </div>
                                    </div>
                               

                           



            

 <!-- Boton para aplicar la actualizacion -->
                                    <div class="col-sm-4" >
                                        
                                            <?= Html::submitButton(Yii::t('frontend' , 'Registrar'),
                                                                                                      [
                                                                                                        'id' => 'btn-search',
                                                                                                        'class' => 'btn btn-success',
                                                                                                        'name' => 'btn-search',
                                                                                                        'value' => 1,
                                                                                                        'style' => 'height:30px;width:100px;margin-right:0px;',
                                                                                                      ])
                                            ?>
                                        
                                    </div>

                                       <div class="col-sm-2" >
                                        
                                            <?= Html::a('Return',['/menu/vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:50px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                                        
                                    </div>
                                   
<!-- Fin de Boton para aplicar la actualizacion -->

                                



          </div>
       </div>
    </div>

    

     

<?php $form->end() ?>

