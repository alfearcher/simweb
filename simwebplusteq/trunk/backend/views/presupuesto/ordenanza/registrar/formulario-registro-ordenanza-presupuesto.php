<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;  
use yii\grid\GridView;
use kartik\icons\Icon;
use common\fecha\RangoFecha;
  
  $rangoFinal = date('Y') + 1;
  $rangoInicial = $rangoFinal - 7;
//die(var_dump($rangoFinal));

        $fecha = new RangoFecha();

        $rangoFecha = $fecha->RangoFechaOrdenanza($rangoInicial, $rangoFinal);
        
        $rangos = ArrayHelper::map($rangoFecha, 'id' , 'campo');
     



      // foreach (range($rangoInicial , $rangoFinal) as $rangos[]){


      //     $rangos[$rangos];


      // }

      // foreach ($rangos as $rango){

      //     $rangoFecha[] = [ 'id' => $rango , 'campo' => $rango];

      // }


      // $rangos  = ArrayHelper::map($rangoFecha, 'id' , 'campo');

      
      

  

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
                           
                               <div class="row" style="margin-left:10px;">
                            
                                    <div class="col-sm-2" >
                                      
                                            <?= $form->field($model, 'nro_presupuesto')->textInput([
                                                                                                    'id' => 'nro_presupuesto',
                                                                                                
                                                                                                    //'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- FIN NRO PRESUPUESTO -->



                              


<!-- ANO IMPOSITIVO -->
                                 
                                      <div class="col-sm-2" style="margin-left:25px;">
                                        
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($rangos,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                             </div>

                                       </div>
                               
<!-- FIN DE ANO IMPOSITIVO -->    



    <!-- FECHA DESDE -->   

          <div class="row">
             <div class="col-sm-3" style="margin-left:20px;">
                       
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
                                                                                            'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                           
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
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                     
                    </div>                 
                         

<!-- FIN DE FECHA HASTA --> 

<!-- OBSERVACION -->
                           
      <div class="row">
        
          
               <div class="col-sm-7"  style="margin-left: 35px;">
                                     
                                            <div>
                                              <?= $form->field($model, 'observacion')->textArea(['rows' => '6']) ?>
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

    <div class="row" style="width: 70%; margin-left:40px;">
    <div class="info-solicitud">
        <div class="row">
            <h3><?= Html::encode('Ordenanzas de Presupuesto') ?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                       // die(var_dump($dataProvider)),
                      'columns'  => [
                
                 [

                    'label' =>" Nro Presupuesto",

                   
                    'value'=> function($data){ 

                        return $data->nro_presupuesto;
                    }

                ],
    
         

                [

                    'label' =>"Año Impositivo",

                   
                    'value'=> function($data){ 

                        return $data->ano_impositivo;
                    }

                ],

                     [

                    'label' =>"Fecha Desde",

                   
                    'value'=> function($data){ 

                        return date("d-m-Y", strtotime($data->fecha_desde));
                    }

                ],

                 [

                    'label' =>"Fecha Hasta",

                   
                    'value'=> function($data){ 
                    
                        return  date("d-m-Y", strtotime($data->fecha_hasta));
                    }

                ],

                   [

                    'label' =>"Descripcion",

                   
                    'value'=> function($data){ 

                        return $data->descripcion;
                    }

                ],

                [

                    'label' =>"Observacion",

                   
                    'value'=> function($data){ 

                        return $data->observacion;
                    }

                ],


                

                                
                
        ],
    ]); ?>


        </div>
    </div>
</div>
</div> 
    

     

<?php $form->end() ?>

