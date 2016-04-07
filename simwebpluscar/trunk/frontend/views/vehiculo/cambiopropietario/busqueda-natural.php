<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;



$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');


$this->title = 'Busqueda Persona Natural';

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





                            <div class="row" style="width:100%;">
                                <p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('frontend', 'DNI') ?></small></i></p>
                            </div>
<!-- COMBO NATURALEZA -->
                             <div class="col-sm-9">
                               <div class="row" style="width:100%; padding-left: 0px;padding-right: 0px;">
                                 <div class="container-fluid" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;">
                                    <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'naturaleza')->dropDownList($listaNaturaleza,[
                                                                                                    'id' => 'naturaleza',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',

                                                                                                    ])->label(false)
                                            ?>
                                        </div>
                                    </div>
<!-- FIN DE COMBO NATURALEZA -->

<!-- CEDULA -->
                                    <div class="col-sm-4" style="padding-left: 27px;">


                                               <div class="cedula">
                                                 <?= $form->field($model, 'cedula')->textInput([
                                                                                            'id' => 'cedula',
                                                                                            'style' => 'height:32px;width:122px;',

                                                                                            //'maxlength' => $maxLength,
                                                                                          ])->label(false) ?>
                                                </div>
                                    </div>
<!-- FIN DE CEDULA -->


                                </div>
<!-- FIN DE TIPO -->






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

