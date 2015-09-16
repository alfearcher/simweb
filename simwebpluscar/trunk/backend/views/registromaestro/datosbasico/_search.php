<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\DatosBasicoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contribuyentes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_contribuyente') ?>

    <?= $form->field($model, 'ente') ?>

    <?= $form->field($model, 'naturaleza') ?>

    <?= $form->field($model, 'cedula') ?>

    <?= $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'tipo_naturaleza') ?>

    <?php // echo $form->field($model, 'id_rif') ?>

    <?php // echo $form->field($model, 'id_cp') ?>

    <?php // echo $form->field($model, 'nombres') ?>

    <?php // echo $form->field($model, 'apellidos') ?>

    <?php // echo $form->field($model, 'razon_social') ?>

    <?php // echo $form->field($model, 'representante') ?>

    <?php // echo $form->field($model, 'nit') ?>

    <?php // echo $form->field($model, 'fecha_nac') ?>

    <?php // echo $form->field($model, 'sexo') ?>

    <?php // echo $form->field($model, 'casa_edf_qta_dom') ?>

    <?php // echo $form->field($model, 'piso_nivel_no_dom') ?>

    <?php // echo $form->field($model, 'apto_dom') ?>

    <?php // echo $form->field($model, 'domicilio_fiscal') ?>

    <?php // echo $form->field($model, 'catastro') ?>

    <?php // echo $form->field($model, 'tlf_hab') ?>

    <?php // echo $form->field($model, 'tlf_hab_otro') ?>

    <?php // echo $form->field($model, 'tlf_ofic') ?>

    <?php // echo $form->field($model, 'tlf_ofic_otro') ?>

    <?php // echo $form->field($model, 'tlf_celular') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'inactivo') ?>

    <?php // echo $form->field($model, 'cuenta') ?>

    <?php // echo $form->field($model, 'reg_mercantil') ?>

    <?php // echo $form->field($model, 'num_reg') ?>

    <?php // echo $form->field($model, 'tomo') ?>

    <?php // echo $form->field($model, 'folio') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'capital') ?>

    <?php // echo $form->field($model, 'horario') ?>

    <?php // echo $form->field($model, 'extension_horario') ?>

    <?php // echo $form->field($model, 'num_empleados') ?>

    <?php // echo $form->field($model, 'tipo_contribuyente') ?>

    <?php // echo $form->field($model, 'licencia') ?>

    <?php // echo $form->field($model, 'agente_retencion') ?>

    <?php // echo $form->field($model, 'id_sim') ?>

    <?php // echo $form->field($model, 'manzana_limite') ?>

    <?php // echo $form->field($model, 'lote_1') ?>

    <?php // echo $form->field($model, 'lote_2') ?>

    <?php // echo $form->field($model, 'nivel') ?>

    <?php // echo $form->field($model, 'lote_3') ?>

    <?php // echo $form->field($model, 'fecha_inclusion') ?>

    <?php // echo $form->field($model, 'fecha_inicio') ?>

    <?php // echo $form->field($model, 'foraneo') ?>

    <?php // echo $form->field($model, 'no_declara') ?>

    <?php // echo $form->field($model, 'econ_informal') ?>

    <?php // echo $form->field($model, 'grupo_contribuyente') ?>

    <?php // echo $form->field($model, 'fe_inic_agente_reten') ?>

    <?php // echo $form->field($model, 'no_sujeto') ?>

    <?php // echo $form->field($model, 'ruc') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
