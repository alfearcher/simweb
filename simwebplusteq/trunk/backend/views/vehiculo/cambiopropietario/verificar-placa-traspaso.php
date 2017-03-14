<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\fecha\RangoFecha;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$fecha = new RangoFecha(); // instancia de la clase que contiene el metodo para crear combos de rango de fecha dinamicos.

$listaFecha = $fecha->RangoFecha(1960);

$listaAño = ArrayHelper::map($listaFecha, 'id' , 'campo');


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$this->title = 'Busqueda de Placa';

?>




<?php $form = ActiveForm::begin([
   'method' => 'post',
    'id' => 'formulario',

    
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'enableClientScript' => true,

    'options' => ['class' => 'form-horizontal'],

]);
?>

<div class="col-sm-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
                </div>
                    <div class="panel-body" >





                      
<!-- PLACA -->
                             <div class="col-sm-9">
                               <div class="row" style="width:100%; padding-left: 0px;padding-right: 0px;">
                                 <div class="container-fluid" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;">
                                    <div class="col-sm-5" style="padding-right: 12px;">
                                          <div class="placa">
                                                 <?= $form->field($model, 'placa')->textInput([
                                                                                            'id' => 'placa',
                                                                                            'style' => 'height:32px;width:122px;',

                                                                                            //'maxlength' => $maxLength,
                                                                                          ]) ?>
                                                </div>
                                    </div>
<!-- FIN DE PLACA -->

<!-- AÑO TRASPASO -->
                                    <div class="col-sm-4">


                                               <div class="ano traspaso">
                                                 <?= $form->field($model, 'ano_traspaso')->dropDownList($listaAño,
                                                                [
                                                                'prompt' => yii::t('frontend', 'Select'),
                                                               
                                                                
                                                                ]);
                    ?>
                                                </div>
                                    </div>
<!-- FIN DE AÑO TRASPASO -->


                                </div>







 <!-- Boton para aplicar la actualizacion -->
                                    <div class="col-sm-4" >

                                            <?= Html::submitButton(Yii::t('frontend' , 'Search'),
                                                                                                      [
                                                                                                        'id' => 'btn-search',
                                                                                                        'class' => 'btn btn-success',
                                                                                                        'name' => 'btn-search',
                                                                                                        'value' => 1,
                                                                                                        'style' => 'height:30px;width:100px;margin-left:-15px;',
                                                                                                      ])
                                            ?>

                                    </div>

                                    <div class="col-sm-3" >

                                            <?= Html::a('Return',['site/menu-vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:30px;' ]) //boton para volver al menu de seleccion tipo usuario ?>

                                    </div>

<!-- Fin de Boton para aplicar la actualizacion -->





          </div>
       </div>
    </div>
  </div>
</div>




<?php $form->end() ?>

