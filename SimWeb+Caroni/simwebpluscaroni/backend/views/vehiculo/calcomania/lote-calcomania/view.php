<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\LoteCalcomaniaForm */
if ($model->inactivo == 1) {
    echo "<script type='text/javascript'>$( window ).load(function() {mostrar();});</script>";
    $labelEstatus = 'Inactivo';
    $color = 'color="red"';
}else{
    echo "<script type='text/javascript'>$( window ).load(function() {ocultar();});</script>";
    $labelEstatus = 'Activo';
    $color = 'color="green"';
}
?>
<script type="text/javascript">
    function mostrar(){
        $('#causacausa').show();
    }
    function ocultar(){
        $('#causa').hide();
    }
</script>
<div class="lote-calcomania-form-view">
    <div class="container" style="width: 700px">
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">
                        <h2><?= Yii::t('backend', 'Lote Nro.') ?> <?= $model["id_lote_calcomania"] ?></h2>
                </div>
                <div class="panel-body" style="margin-left: 160px">

                    <!-- ID LOTE CALCOMANIA -->
                    <div class="row">
                        <div class="col-md-1" style="width: 160px"><b><?= $model->getAttributeLabel('id_lote_calcomania'); ?></b></div>
                        <div class="col-md-1"><?= $model["id_lote_calcomania"] ?></div>
                    </div>
                    <!-- FIN DE ID LOTE CALCOMANIA -->

                    <!-- ANO IMPOSITIVO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('ano_impositivo'); ?></b></div>
                        <div class="col-md-1"><?= $model["ano_impositivo"] ?></div>
                    </div>
                    <!-- FIN DEL ANO IMPOSITIVO -->

                    <!-- RANGO INICIAL -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('rango_inicial'); ?></b></div>
                        <div class="col-md-1"><?= $model["rango_inicial"] ?></div>
                    </div>
                    <!-- FIND DE RANGO INICIAL -->

                    <!-- RANGO FINAL -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('rango_final'); ?></b></div>
                        <div class="col-md-1"><?= $model["rango_final"] ?></div>
                    </div>
                    <!-- FIN DE RANGO FINAL -->

                    <!-- OBSERVACION -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('observacion'); ?></b></div>
                        <div class="col-md-8"><?= $model["observacion"] ?></div>
                    </div>
                    <!-- FIN DE OBSERVACION -->

                    <!-- INACTIVO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= Yii::t("backend", "Estatus"); ?></b></div>
                        <div class="col-md-3"><font <?= $color ?>><?= $labelEstatus ?></font></div>
                    </div>
                    <!-- FIN DE INACTIVO -->

                    <div id="causa">
                        <!-- CAUSA -->
                        <div class="row">
                            <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('causa'); ?></b></div>
                            <div class="col-md-8"><?= $model["causa"] ?></div>
                        </div>
                        <!-- FIN DE CAUSA -->
                    </div>

                </div>
                <div class="panel-footer">
                    <?= Html::a(Yii::t('backend', 'Back'), ['vehiculo/calcomania/lote-calcomania/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>        
    </div>
</div>
