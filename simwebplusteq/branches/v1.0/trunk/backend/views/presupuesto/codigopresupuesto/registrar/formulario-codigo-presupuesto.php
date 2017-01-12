<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;
use common\models\presupuesto\nivelespresupuesto\NivelesContables;


$modeloNivelesContables = NivelesContables::find()->asArray()->all();
$listaNiveles = ArrayHelper::map($modeloNivelesContables, 'nivel_contable', 'descripcion');
                                

$this->title = 'Registro de Codigo de Presupuesto';
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
               
                  
<!-- NIVEL CONTABLE -->
                           
                               <div class="row" style="margin-left:10px;">
                            
                                    <div class="col-sm-5" >
                                      
                                            <?= $form->field($model, 'nivel_contable')->dropDownList($listaNiveles,[
                                                                                                    'id' => 'nivel_contable',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    //'style' => //'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                                   
<!-- FIN DE NIVEL CONTABLE -->



                                    </div>


<!-- CODIGO -->
                                    <div class="row">
                                    <div class="col-sm-3" style="padding-right: 0px;padding-left: 40px;">
                                     
                                            <div>
                                               <?= $form->field($model, 'codigo')->textInput([
                                                                                            'id' => 'codigo',
                                                                                           // 'style' => 'height:32px;width:38px;',
                                                                                           
                                                                                            //'maxlength' => 1,
                                                                                         ]) ?>
                                        </div>
                                    </div>
                               
<!-- FIN DE CODIGO -->    


<!-- DESCRIPCION -->
                                    <div class="col-sm-5" style="padding-right: 0px;padding-left: 40px;">
                                     
                                            <div>
                                               <?= $form->field($model, 'descripcion')->textInput([
                                                                                            'id' => 'codigo',
                                                                                           // 'style' => 'height:32px;width:38px;',
                                                                                           
                                                                                            //'maxlength' => 1,
                                                                                         ]) ?>
                                        </div>
                                    </div>
                                </div>
<!-- FIN DE DESCRIPCION -->                       
                         

<!-- FIN DE CODIGO CONTABLE -->

                           



            

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
                                        
                                            <?= Html::a('Return',['/menu-vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:50px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                                        
                                    </div>
                                   
<!-- Fin de Boton para aplicar la actualizacion -->

                                



          </div>
       </div>
    </div>
  </div> 
</div> 
    

     

<?php $form->end() ?>

