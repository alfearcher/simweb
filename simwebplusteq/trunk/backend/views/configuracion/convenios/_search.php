<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\configuracion\convenios\ConfigConveniosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-convenios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_config_convenio') ?>

    <?= $form->field($model, 'impuesto') ?>

    <?= $form->field($model, 'monto_minimo') ?>

    <?= $form->field($model, 'tipo_monto') ?>

    <?= $form->field($model, 'ano_ut') ?>

    <?php // echo $form->field($model, 'solo_deuda_morosa') ?>

    <?php // echo $form->field($model, 'tipo_periodo') ?>

    <?php // echo $form->field($model, 'monto_inicial') ?>

    <?php // echo $form->field($model, 'porcentaje_inicial') ?>

    <?php // echo $form->field($model, 'nro_max_cuotas') ?>

    <?php // echo $form->field($model, 'lapso_tiempo') ?>

    <?php // echo $form->field($model, 'id_tiempo') ?>

    <?php // echo $form->field($model, 'vcto_dif_ano') ?>

    <?php // echo $form->field($model, 'aplicar_interes') ?>

    <?php // echo $form->field($model, 'interes') ?>

    <?php // echo $form->field($model, 'id_impuesto') ?>

    <?php // echo $form->field($model, 'usuario') ?>

    <?php // echo $form->field($model, 'fecha_hora') ?>

    <?php // echo $form->field($model, 'inactivo') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>