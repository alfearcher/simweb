<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;  


       // die(var_dump($datos[0]['codigo']).'hola');                          

$this->title = 'Modificacion de Monto de Presupuesto';
?>
 



<?php $form = ActiveForm::begin([
   // 'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
                </div>
                    <div class="panel-body" >
               
                  
<!-- NIVEL CONTABLE -->
                           
                               <div class="row" style="margin-left:10px;">
                            
                                    <div class="col-sm-5" >
                                      
                                            <?= $form->field($model, 'monto')->textInput([
                                                                                                    'id' => 'monto',
                                                                                                
                                                                                                  //  'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- FIN DE NIVEL CONTABLE -->



                                    </div>





                           



            

 <!-- Boton para aplicar la actualizacion -->
                                    <div class="col-sm-4" >
                                        
                                            <?= Html::submitButton(Yii::t('frontend' , 'Search'),
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
  </div> 
</div> 
    

     

<?php $form->end() ?>

