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

 $rangoFinal = date('Y') + 1;
  $rangoInicial = $rangoFinal - 2;
//die(var_dump($rangoFinal));

        $fecha = new RangoFecha();

        $rangoFecha = $fecha->RangoFechaOrdenanza($rangoInicial, $rangoFinal);
        
        $rangos = ArrayHelper::map($rangoFecha, 'id' , 'campo');
            

$this->title = 'Seleccione el nuevo Año Impositivo';
?>
 
<?php $form = ActiveForm::begin([
   // 'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-5">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
                </div>
                    <div class="panel-body" >




                             <div class="row">  
                                      



<!-- ANO IMPOSITIVO-->


                                            <div class="col-sm-4" style="margin-left:25px;">
                                        
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($rangos,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                   // 'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        
                
                                            </div>

<!--FIN DE ANO IMPOSITIVO-->

            
                                        


                              </div>


                             

                                   



                    
                         



                           



            

 <!-- Boton para aplicar la actualizacion -->
                                    <div class="col-sm-4" >
                                        
                                            <?= Html::submitButton(Yii::t('backend' , 'Seleccionar'),
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

