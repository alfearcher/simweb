<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\apuestailicita\ApuestasIlicitaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apuestas-ilicita-form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_impuesto') ?>

    <?= $form->field($model, 'id_contribuyente') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'direccion') ?>

    <?= $form->field($model, 'id_cp') ?>

    <?php // echo $form->field($model, 'id_sim') ?>

    <?php // echo $form->field($model, 'status_apuesta') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
