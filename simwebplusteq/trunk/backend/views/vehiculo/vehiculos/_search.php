<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VehiculosSearch */
/* @var $form yii\widgets\ActiveForm */


if ($visible == 'no') {
    $none = 'display:none';
    $noneP = 'display:';
    $titulo = array(
                    '0' => '', 
                    '1' => '', 
                    '2' => 'placa', 
                    '3' => '', 
                    '4' => '',
                );
    $accion = 'cambio-placa-result';
}else{
    $none = 'display:';
    $noneP = 'display:';
    $titulo = array(
                    '0' => 'id_vehiculo', 
                    '1' => 'id_contribuyente', 
                    '2' => 'placa', 
                    '3' => 'marca', 
                    '4' => 'modelo',
                );
    $accion = 'index';
}
?>

<div class="vehiculos-form-search">

    <?php $form = ActiveForm::begin([
        'action' => [$accion],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_vehiculo')->label(ucfirst($titulo[0]))->textInput(['style'=> $none]) ?>

    <?= $form->field($model, 'id_contribuyente')->label(ucfirst($titulo[1]))->textInput(['style'=> $none]) ?>

    <?= $form->field($model, 'placa')->label(ucfirst($titulo[2]))->textInput(['style'=> $noneP]) ?>

    <?= $form->field($model, 'marca')->label(ucfirst($titulo[3]))->textInput(['style'=> $none]) ?>

    <?= $form->field($model, 'modelo')->label(ucfirst($titulo[4]))->textInput(['style'=> $none]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
