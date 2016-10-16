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


                       

$this->title = 'Registrar Grupo Subnivel';
?>
 
<?php $form = ActiveForm::begin([
   // 'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-7">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
                </div>
                    <div class="panel-body" >

                    
                           

<!-- DESCRIPCION -->
                           
                               <div class="row" style="margin-left:10px;">
                            
                                    <div class="col-sm-5" >
                                      
                                            <?= $form->field($model, 'descripcion')->textInput([
                                                                                                    'id' => 'descripcion',
                                                                                                
                                                                                                   // 'value' => ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($datos[0]['nivel_contable']),
                                                                                                   // 'readOnly' => true,
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- FIN DE DESCRIPCION -->




                                    </div>



                         
<!-- Boton para aplicar la actualizacion -->
                                    <div class="col-sm-4" >
                                        
                                            <?= Html::submitButton(Yii::t('frontend' , 'Modificar'),
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

