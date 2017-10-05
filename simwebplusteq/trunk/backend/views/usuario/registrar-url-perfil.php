<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\grid\GridView;

use common\models\Users;
use common\models\User;
use backend\models\usuario\RutaAccesoMenu;
/* @var $this yii\web\View */
/* @var $model backend\models\InscripcionInmueblesUrbanosForm */
/* @var $form ActiveForm */
$this->title = Yii::t('backend', 'Perfil del Usuario (Creacion de Ruta)'); 
?>


<body onload = "bloquea()"/>
<div class="inscripcionInmueblesUrbanos">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>


<div class="container" style="width:1280px">
 <div class="col-sm-15" style="width:900px">
        <div class="panel panel-primary" style="width: 100%;">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                
                        
                    <div class="row" class="informacion-contribuyente" id="informacion-contribuyente">
                        <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 25px;">
                            <h4><strong><?=Html::encode(Yii::t('frontend', 'Nombre del Url Perfil'))?></strong></h4>
                        </div>
                        
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-4">
                                
                            <?= $form->field($model, 'menu')->textinput( [ 
                                                                                                            'id'=> 'parametro', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:580px;',
                                                                                                           ])->label(false);
                                                                                                           ?>
                            </div>
                        </div>
                        </div> 
                    

                        
                    <div class="row" class="informacion-contribuyente2" id="informacion-contribuyente2">
                        <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 25px;">
                            <h4><strong><?=Html::encode(Yii::t('frontend', 'Ruta Acceso al menu'))?></strong></h4>
                        </div>
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-4">
                            

                            <?= $form->field($model, 'ruta')->textinput( [ 
                                                                                                            'id'=> 'parametro', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:580px;',
                                                                                                           ])->label(false);?>
                            </div> 
                        </div>
                        </div>
                    </div> 
                        
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="form-group"> 
                            <?= Html::submitButton(Yii::t('backend', 'Añadir Ruta Url'), ['class' => 'btn btn-success',
                                      'data' => [
                                                  'confirm' => Yii::t('app', 'Estas seguro de guardar la siguiente informacion?'),
                                                  'method' => 'post',],]) ?>
                            <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
                            </div>  
                        </div>                                                                       
                        
                   
            </div>
        </div>
    </div>
</div>
<!-- Campos ocultos -->   
<?= $form->field($model, 'manzana_limite')->hiddenInput(['value' => 130])->label(false) ?> 
<?= $form->field($model, 'id_habitante')->hiddenInput(['value' => 123456])->label(false) ?>
<?= $form->field($model, 'liquidado')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'nivel')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'catastro')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'tlf_hab')->hiddenInput(['style' => 'width:80px;','value' =>0])->label(false) ?>


<?php ActiveForm::end(); ?> 
<?//= Html::endForm();?>

</div><!-- inscripcionInmueblesUrbanos -->

  