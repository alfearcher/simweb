<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;  
use backend\models\impuesto\Impuesto;
use common\fecha\RangoFecha;
use common\models\tasas\GrupoSubnivel;
use common\models\tasas\TiposRangos;
use backend\models\tasa\Tasa;
//die('llegue a la vbista');
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $modeloCodigosContables = CodigosContables::find()->asArray()->all();
    $listaCodigos = ArrayHelper::map($modeloCodigosContables, 'id_codigo', function($modeloCodigosContables)
      { return '"'.$modeloCodigosContables['codigo'].'"  -'.$modeloCodigosContables['descripcion'];});
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    $modelImpuesto = Impuesto::find()->asArray()->all();
    $listaImpuesto = ArrayHelper::map($modelImpuesto, 'impuesto', 'descripcion');


//////////////////////////////////////////////////////////////////////////////////////////////////////////

  
 

       $modelAnoImpositivo = Tasa::find()->distinct()->asArray()->orderBy('ano_impositivo')->all();
        
        $anosImpositivos = ArrayHelper::map($modelAnoImpositivo, 'ano_impositivo' , 'ano_impositivo');

    //die(var_dump($modelAnoImpositivos));
         // die(var_dump($anosImpositivos));



      $modelCodigosEspecificos = Tasa::find()->distinct()->asArray()->all();
      $listaCodigosEspecificos = ArrayHelper::map($modelCodigosEspecificos, 'codigo', 'codigo');

//////////////////////////////////////////////////////////////////////////////////////////////////////////                         

$this->title = 'Busqueda de Tasas';
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


<!-- CODIGO CONTABLE-->

                             <div class="row">  
                                            <div class="col-sm-8" style="margin-left:25px;">
                                        
                                            <?= $form->field($model, 'id_codigo')->dropDownList($listaCodigos,[
                                                                                                    'id' => 'id_codigo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                            </div>

            
                                            </div>



<!-- FIN DE CODIGO CONTABLE-->


<!-- IMPUESTO-->

                             <div class="row">  
                                            <div class="col-sm-4" style="margin-left:25px;">
                                        
                                            <?= $form->field($model, 'impuesto')->dropDownList($listaImpuesto,[
                                                                                                    'id' => 'impuesto',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                             </div>

<!-- FIN DE IMPUESTO-->

<!-- ANO IMPOSITIVO-->


                                            <div class="col-sm-2" style="margin-left:25px;">
                                        
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($anosImpositivos,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                            </div>

<!--FIN DE ANO IMPOSITIVO-->

            
                                        


                              </div>


                            



                  
<!-- CODIGO ESPECIFICO -->
                           
                               <div class="row" style="margin-left:10px;">
                            
                                    <div class="col-sm-2" >
                                      
                                            <?= $form->field($model, 'codigo')->dropDownList($listaCodigosEspecificos,[
                                                                                                    'id' => 'codigo',
                                                                                                   'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- CODIGO ESPECIFICO -->



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

