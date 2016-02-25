<?php
session_start();
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InmueblesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inmuebles-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_impuesto') ?>

    <?= $form->field($model, 'id_contribuyente')->textInput(['value'=>$_SESSION['idContribuyente']]) ?>

    <? //= $form->field($model, 'ano_inicio') ?>

    <?= $form->field($model, 'direccion') ?>

    <? //= $form->field($model, 'liquidado') ?>

    <?php // echo $form->field($model, 'manzana_limite') ?>

    <?php // echo $form->field($model, 'lote_1') ?>

    <?php // echo $form->field($model, 'lote_2') ?>

    <?php // echo $form->field($model, 'nivel') ?>

    <?php // echo $form->field($model, 'lote_3') ?>

    <?php // echo $form->field($model, 'av_calle_esq_dom') ?>

    <?php // echo $form->field($model, 'casa_edf_qta_dom') ?>

    <?php // echo $form->field($model, 'piso_nivel_no_dom') ?>

    <?php // echo $form->field($model, 'apto_dom') ?>

    <?php  echo $form->field($model, 'tlf_hab') ?>

    <?php // echo $form->field($model, 'medidor') ?>

    <?php // echo $form->field($model, 'id_sim') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'inactivo') ?>

    <?php // echo $form->field($model, 'catastro') ?>

    <?php  echo $form->field($model, 'id_habitante') ?>

    <?php // echo $form->field($model, 'tipo_ejido') ?>

    <?php // echo $form->field($model, 'propiedad_horizontal') ?>

    <?php // echo $form->field($model, 'estado_catastro') ?>

    <?php // echo $form->field($model, 'municipio_catastro') ?>

    <?php // echo $form->field($model, 'parroquia_catastro') ?>

    <?php // echo $form->field($model, 'ambito_catastro') ?>

    <?php // echo $form->field($model, 'sector_catastro') ?>

    <?php // echo $form->field($model, 'manzana_catastro') ?>

    <?php // echo $form->field($model, 'parcela_catastro') ?>

    <?php // echo $form->field($model, 'subparcela_catastro') ?>

    <?php // echo $form->field($model, 'nivel_catastro') ?>

    <?php // echo $form->field($model, 'unidad_catastro') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
