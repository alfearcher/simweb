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

//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $modeloCodigosContables = CodigosContables::find()->asArray()->all();
    $listaCodigos = ArrayHelper::map($modeloCodigosContables, 'id_codigo', function($modeloCodigosContables)
      { return '"'.$modeloCodigosContables['codigo'].'"  -'.$modeloCodigosContables['descripcion'];});
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    $modelImpuesto = Impuesto::find()->asArray()->all();
    $listaImpuesto = ArrayHelper::map($modelImpuesto, 'impuesto', 'descripcion');


//////////////////////////////////////////////////////////////////////////////////////////////////////////

  
  $rangoFinal = date('Y') + 1;
  $rangoInicial = $rangoFinal - 11;
//die(var_dump($rangoFinal));

        $fecha = new RangoFecha();

        $rangoFecha = $fecha->RangoFechaOrdenanza($rangoInicial, $rangoFinal);
        
        $rangos = ArrayHelper::map($rangoFecha, 'id' , 'campo');

//////////////////////////////////////////////////////////////////////////////////////////////////////////

      $modelGruposSubniveles = GrupoSubnivel::find()->where(['inactivo' => 0])->asArray()->all();
      $grupoSubNiveles = ArrayHelper::map($modelGruposSubniveles, 'grupo_subnivel', 'descripcion');

//////////////////////////////////////////////////////////////////////////////////////////////////////////     




      $modelTiposRangos = TiposRangos::find()->asArray()->all();
      $tiposRangos = ArrayHelper::map($modelTiposRangos, 'tipo_rango', 'descripcion');

//////////////////////////////////////////////////////////////////////////////////////////////////////////                         

$this->title = 'Registrar Tasas';
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
                                        
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($rangos,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                            </div>

<!--FIN DE ANO IMPOSITIVO-->

            
                                        


                              </div>


                              <div class="row">
                                
  
<!-- GRUPO SUBNIVELES-->
      
                                           <div class="col-sm-10" style="margin-left:25px;">
                                        
                                            <?= $form->field($model, 'grupo_subnivel')->dropDownList($grupoSubNiveles,[
                                                                                                    'id' => 'grupo_subnivel',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                            </div>
                            



<!--FIN DE GRUPO SUBNIVELES-->

                                          </div>



                  
<!-- NIVEL CONTABLE -->
                           
                               <div class="row" style="margin-left:10px;">
                            
                                    <div class="col-sm-2" >
                                      
                                            <?= $form->field($model, 'codigo')->textInput([
                                                                                                    'id' => 'codigo',
                                                                                                
                                                                                                   // 'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- FIN DE NIVEL CONTABLE -->

<!-- DESCRIPCION -->
                      
                                   <div class="col-sm-8" style="margin-left:20px;">
                                      
                                            <?= $form->field($model, 'descripcion')->textInput([
                                                                                                    'id' => 'descripcion',
                                                                                                
                                                                                                   // 'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>

<!--FIN DE DESCRIPCION -->

                                    </div>



                                    <div class="row">
                                      
<!-- MONTO -->
                      
                                  <div class="col-sm-2" style="margin-left:25px;">
                                      
                                            <?= $form->field($model, 'monto')->textInput([
                                                                                              'id' => 'monto',
                                                                                                
                                                                                              // 'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                              // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                  </div>

<!--FIN DE MONTO -->  


<!--TIPO RANGO -->                        

                                   <div class="col-sm-4" style="margin-left:25px;">
                                
                                    <?= $form->field($model, 'tipo_rango')->dropDownList($tiposRangos,[
                                                                                            'id' => 'tipo_rango',
                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                           // 'style' => 'height:32px;width:150px;',
                                                                                            
                                                                                            ])
                                    ?>
                                
        
                                    </div>

<!--FIN DE TIPO RANGO --> 

<!--CANTIDAD UT --> 

                                      <div class="col-sm-2" style="margin-left:25px;">
                                      
                                            <?= $form->field($model, 'cantidad_ut')->textInput([
                                                                                              'id' => 'cantidad_ut',
                                                                                                
                                                                                              // 'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                              // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                  </div>

<!--FIN DE CANTIDAD UT -->

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

