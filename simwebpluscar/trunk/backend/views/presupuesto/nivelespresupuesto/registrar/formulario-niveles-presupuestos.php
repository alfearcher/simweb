<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



                                

$this->title = 'Carga de Niveles Contables';
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
                                    <div class="row">
                                    <div class="col-sm-2" style="margin-left: 27px;">

                                          
                                               <div class="cedula">
                                                 <?= $form->field($model, 'nivel_contable')->textInput([
                                                                                            'id' => 'nivel_contable',
                                                                                          //  'style' => 'height:32px;width:122px;',
                                                                                           
                                                                                            //'maxlength' => $maxLength,
                                                                                          ]);?>
                                                </div>
                                    </div>
                                     </div>
<!-- FIN DE NIVEL CONTABLE -->

<!-- DESCRIPCION -->
                                    <div class="row">
                                    <div class="col-sm-8" style="padding-right: 0px;padding-left: 40px;">
                                     
                                            <div class="tipo">
                                               <?= $form->field($model, 'descripcion')->textInput([
                                                                                            'id' => 'descripcion',
                                                                                          //  'style' => 'height:32px;width:200px;',
                                                                                           
                                                                                           
                                                                                         ]); ?>
                                        </div>
                                    </div>
                                    </div>
                                   
                               
<!-- FIN DE DESCRIPCION -->



<!-- INGRESO PROPIO -->
                    <div class="row"> 
                    <div class="col-sm-2" style="margin-bottom:30px; margin-left:30px;">
                       
                    <?= $form->field($model, 'ingreso_propio')->radioList([
                                                                                                        '0' => Yii::t('frontend', 'Si'),

                                                                                                        '1' => Yii::t('frontend', 'No'),
                                                                                                      ],
                                                                                                      [
                                                                                                        
                                                                                                      ]
                                                                                                      ) ?>
                               
                
                    </div>
                    </div>
<!-- FIN DE INGRESO PROPIO -->
                           



            

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

