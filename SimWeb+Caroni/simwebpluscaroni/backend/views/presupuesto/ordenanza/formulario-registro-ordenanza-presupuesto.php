<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;  



$modeloNivelesContables = NivelesContables::find()->asArray()->all();
$listaNiveles = ArrayHelper::map($modeloNivelesContables, 'nivel_contable', 'descripcion');

       // die(var_dump($datos[0]['codigo']).'hola');                          

$this->title = 'Registrar Ordenanza de Presupuestos';
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
                           
                               <div class="row">
                            
                                    <div class="col-sm-5" >
                                      
                                            <?= $form->field($model, 'nro_presupuesto')->textInput([
                                                                                                    'id' => 'nro_presupuesto',
                                                                                                
                                                                                                    //'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- FIN NRO PRESUPUESTO -->



                              


<!-- ANO IMPOSITIVO -->
                                 
                                    <div class="col-sm-3" >
                                     
                                            <div>
                                               <?= $form->field($model, 'ano_impositivo')->textInput([
                                                                                            'id' => 'ano_impositivo',
                                                                                           // 'style' => 'height:32px;width:38px;',
                                                                                           'value' => date('Y'),
                                                                                            'readOnly' => true,
                                                                                         ]) ?>
                                        </div>
                                    </div>

                                       </div>
                               
<!-- FIN DE ANO IMPOSITIVO -->    

<!-- DESCRIPCION -->

                               


                                            <div class="row">  
                                             
                                    <div class="col-sm-7" style="padding-right: 0px;padding-left: 40px;">
                                     
                                            <div>
                                               <?= $form->field($model, 'descripcion')->textInput([
                                                                                            'id' => 'descripcion',
                                                                                           // 'style' => 'height:32px;width:38px;',
                                                                                          // 'value' => date('Y'),
                                                                                          //  'readOnly' => true,
                                                                                         ]) ?>
                                        </div>
                                      </div>

            
                                            </div>



  <!-- FIN DE DESCRIPCION -->   

    <!-- FECHA DESDE -->   

          <div class="row">
             <div class="col-sm-4">
                        <div class="fecha-nac">
                            <?= $form->field($model, 'fecha_desde')->widget(\yii\jui\DatePicker::classname(),[
                                                                                        //'type' => 'date',
                                                                                        'clientOptions' => [
                                                                                           // 'maxDate' => '+0d', // Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                        'changeYear' => 'true', 
                                                                                         
                                                                                         ],
                                                                                       'language' => 'es-ES',
                                                                                       'dateFormat' => 'dd-MM-yyyy',
                                                                                        'options' => [
                                                                                            //'onClick' => 'alert("calendario")',
                                                                                            'id' => 'fecha-desde',
                                                                                           // 'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                            'style' => 'background-color: white;',
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                        </div>
                    </div>                 
                         

<!-- FIN DE FECHA DESDE --> 

<!-- FECHA HASTA -->
<div class="col-sm-4">
                        <div class="fecha-nac">
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
                                                                                        //    'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                           // 'style' => 'background-color: white; margin-left:-20px; ',
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                        </div>
                    </div>                 
                         

<!-- FIN DE FECHA HASTA --> 

<!-- DESCRIPCION -->
                           
      <div class="row">
        
          
               <div class="col-sm-7" style="padding-right: 0px;padding-left: 40px;">
                                     
                                            <div>
                                               <?= $form->field($model, 'descripcion')->textInput([
                                                                                            'id' => 'descripcion',
                                                                                           // 'style' => 'height:32px;width:38px;',
                                                                                          // 'value' => date('Y'),
                                                                                          //  'readOnly' => true,
                                                                                         ]) ?>
                                        </div>
                                      </div>

      </div>


<!--FIN DE DESCRIPCION -->
            





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
                                        
                                            <?= Html::a('Return',['/usuario/opcion-crear-usuario/seleccionar-tipo-usuario'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:50px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                                        
                                    </div>
                                   
<!-- Fin de Boton para aplicar la actualizacion -->

                                



          </div>
       </div>
    </div>
  </div> 
</div> 
    

     

<?php $form->end() ?>

