<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\LoteCalcomaniaForm */
/* @var $form yii\widgets\ActiveForm */

if (!empty($model->id_lote_calcomania)) {
    $display = '';
    $readonly = false;
    $valorDate = $model->ano_impositivo;
    $accion = '';
    $btnAccion = Yii::t("backend", "Update");
    $btnClass = 'btn btn-primary';
    echo "<script type='text/javascript'>$( window ).load(function() {ocultar();});</script>";
}else{
    $display = 'display:none';
    $readonly = true;
    $valorDate = date('Y');
    $accion = '0';
    $btnAccion = Yii::t("backend", "Create");
    $btnClass = 'btn btn-success';
}

if ($model->inactivo == 1) {
    echo "<script type='text/javascript'>$( window ).load(function() {mostrar();});</script>";
}

?>

<style type="text/css">
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

/* webkit solution */
::-webkit-input-placeholder { text-align:right; }
/* mozilla solution */
input:-moz-placeholder { text-align:right; }
</style>

<script type="text/javascript">
    function ocultar(){
        $('#causa').hide();
    }

    function mostrar(){
        $('#causa').show();
    }
    
    function estado(val){
        valor = $("#lotecalcomaniaform-inactivo").is(':checked') ? 1 : 0;
        if (valor == 0) {
            $('#causa').hide();
        }if (valor == 1){
            $('#causa').show();
        };
    }
</script>

<div class="lote-calcomania-form-form">

    <?php $form = ActiveForm::begin(); ?>
        <div class="container">            
            <div class="panel panel-primary" style="width:550px;margin-left:285px">
                <div class="panel-heading">
                <h1><?= Yii::t('backend', ''.$btnAccion.' Lote Calcomania Form') ?></h1>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-1" style="width:150px;">
                                <b><?= $model->getAttributeLabel('ano_impositivo'); ?>:</b>
                            </div>
                            <div class="col-md-8">
                                <?= $form->field($model, 'ano_impositivo')->label(false)->textInput(['style' => 'max-width:60px;','value' => $valorDate, 'readonly' => $readonly]) ?>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-3" style="width:150px;">
                                <b><?= $model->getAttributeLabel('rango_inicial'); ?></b>
                            </div>

                            <div class="col-md-3" style="width:150px;">
                                <b><?= $model->getAttributeLabel('rango_final'); ?></b>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3" style="width:150px;">
                                <?= $form->field($model, 'rango_inicial')->label(false)->textInput(['maxlength' => 7]) ?>
                            </div>

                            <div class="col-md-3" style="width:150px;">
                                <?= $form->field($model, 'rango_final')->label(false)->textInput(['maxlength' => 7]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
                            </div>
                        </div>

                        <div style="<?= $display ?>">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><?= $form->field($model, 'inactivo')->checkbox(['onclick' => 'estado(this.value)']) ?></legend>
                                <div class="control-group">
                                    <div class="controls bootstrap-timepicker">
                                        <!-- CAUSA -->
                                        <div class="row" id="causa">
                                            <div class="col-md-2">
                                                <b><?= $model->getAttributeLabel('causa'); ?></b>
                                            </div>
                                            <div class="col-md-12">
                                                <?= $form->field($model, 'causa')->label(false)->textarea(['rows' => 6]) ?>
                                            </div>
                                        </div>
                                        <!-- FIN DE CAUSA -->
                                    </div>
                                </div>
                            </fieldset>                                                        
                        </div>
                        <?= $form->field($model, 'accion')->hiddenInput(['value' => $accion])->label(false) ?>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-10">
                                </div>
                                <div class="col-md-1">
                                    <?= Html::submitButton( $btnAccion, ['class' => $btnClass]) ?>
                                </div>
                            </div>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
