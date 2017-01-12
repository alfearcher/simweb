<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;  
use yii\grid\GridView;
use kartik\icons\Icon;

  
                        

$this->title = 'Modificar Ordenanza de Presupuestos';
?>
 
<?php $form = ActiveForm::begin([
   // 'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-10">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
                </div>
                    <div class="panel-body" >
               
                  
<!-- NRO PRESUPUESTO -->
                           
                               <div class="row" style="margin-left:10px;">
                            
                            
                                   
<!-- FIN NRO PRESUPUESTO -->



                              


<!-- ANO IMPOSITIVO -->
                                 
                                      <div class="col-sm-2" style="margin-left:0px;">
                                        
                                            <?= $form->field($model, 'ano_impositivo')->textInput([
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    //'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => height:32px;width:150px;',''
                                                                                                   'value' => $datos[0]['ano_impositivo'],
                                                                                                  // 'readOnly' => true,
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                             </div>

                                       </div>
                               
<!-- FIN DE ANO IMPOSITIVO -->    
        <?php 
          $prueba =  '-'.$datos[0]['ano_impositivo'].'Y';



         ?>
                              

    <!-- FECHA DESDE -->   

          <div class="row">
             <div class="col-sm-3" style="margin-left:20px;">
                       
                            <?= $form->field($model, 'fecha_desde')->widget(\yii\jui\DatePicker::classname(),[
                                                                                        //'type' => 'date',
                                                                                        'clientOptions' => [
                                                                                        
                                                                                        'minDate' => $prueba,

                                                                                           // 'maxDate' => '+0d', // Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                        'changeYear' => 'true', 
                                                                                         
                                                                                         ],
                                                                                       'language' => 'es-ES',
                                                                                       'dateFormat' => 'dd-MM-yyyy',
                                                                                        'options' => [
                                                                                            //'onClick' => 'alert("calendario")',
                                                                                            'id' => 'fecha-desde',
                                                                                            'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                           
                                                                                        //'value' => date("dd-MM-yyyy", strtotime($datos[0]['fecha_desde'])),
                                                                                           
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                     
                    </div>                 
                         

<!-- FIN DE FECHA DESDE --> 

<!-- FECHA HASTA -->
<div class="col-sm-3" style="margin-left:20px;">
                       
                            <?= $form->field($model, 'fecha_hasta')->widget(\yii\jui\DatePicker::classname(),[
                                                                                        //'type' => 'date',
                                                                                        'clientOptions' => [
                                                                                           // 'maxDate' => '+0d', // Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                        'changeYear' => 'true', 
                                                                                         
                                                                                         ],
                                                                                       'language' => 'es-ES',
                                                                                       'dateFormat' => 'dd-MM-yyyy',
                                                                                        'options' => [
                                                                                            //'onClick' => 'alert("calendario")',
                                                                                            'id' => 'fecha_hasta',
                                                                                            'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                            'style' => 'background-color: white;',
                                                                                            'value' => $datos[0]['fecha_hasta'],
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                     
                    </div>                 
                         
 </div>
<!-- FIN DE FECHA HASTA --> 

<!-- OBSERVACION -->
                           
      <div class="row">
        
          
               <div class="col-sm-7"  style="margin-left: 20px;">
                                     
                                            <div>
                                              <?= $form->field($model, 'observacion')->textArea(['rows' => '6',
                                                                                                'id' => 'observacion',
                                                                                                'value' => $datos[0]['observacion'],


                                              ])  ?>
                                        </div>
                                      </div>

      </div>


<!--FIN DE OBSERVACION-->
            





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
  </div> 


</div> 
    

     

<?php $form->end() ?>

