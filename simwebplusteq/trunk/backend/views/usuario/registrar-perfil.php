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
$this->title = Yii::t('backend', 'Perfil del Usuario'); 
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
 <div class="col-sm-12" style="width:900px">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-3"> 
                            <?= Yii::t('backend', 'Nombre de usuario:') ?>
                            </div> 
                        
                            <div class="col-sm-4">
                                <?php
                                    $modelParametros = Users::find()->where(['activate'=>1])->asArray()->all();                                         
                                    $listaParametros = ArrayHelper::map($modelParametros,'username','username'); 
                                ?> 
                            <?= $form->field($model, 'username')->dropDownList($listaParametros, [ 
                                                                                                            'id'=> 'parametro', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:180px;',
                                                                                                           ])->label(false);
                                                                                                           ?>
                            </div>
                        </div> 

                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-3"> 
                            <?= Yii::t('backend', 'Acceso al Menu:') ?>
                            </div> 
                        
                            <div class="col-sm-4">
                            

                            <?= $form->field($model, 'ruta')-> Checkboxlist($rutas,[
                                            'id' => 'id-lista-menu',
                                            'style' => 'width:380px;',
                                        ])->label(false); ?>
                            </div> 
                        </div> 
                        
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="form-group"> 
                            <?= Html::submitButton(Yii::t('backend', 'Añadir Permiso al Usuario'), ['class' => 'btn btn-success',
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

  