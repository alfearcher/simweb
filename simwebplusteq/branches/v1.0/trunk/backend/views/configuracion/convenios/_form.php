<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\configuracion\convenios\ConfigConvenios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-convenios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_config_convenio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'impuesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'monto_minimo')->textInput() ?>

    <?= $form->field($model, 'tipo_monto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ano_ut')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'solo_deuda_morosa')->textInput() ?>

    <?= $form->field($model, 'tipo_periodo')->textInput() ?>

    <?= $form->field($model, 'monto_inicial')->textInput() ?>

    <?= $form->field($model, 'porcentaje_inicial')->textInput() ?>

    <?= $form->field($model, 'nro_max_cuotas')->textInput() ?>

    <?= $form->field($model, 'lapso_tiempo')->textInput() ?>

    <?= $form->field($model, 'id_tiempo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vcto_dif_ano')->textInput() ?>

    <?= $form->field($model, 'aplicar_interes')->textInput() ?>

    <?= $form->field($model, 'interes')->textInput() ?>

    <?= $form->field($model, 'id_impuesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_hora')->textInput() ?>

    <?= $form->field($model, 'inactivo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>