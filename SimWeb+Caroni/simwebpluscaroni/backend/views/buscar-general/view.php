<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\contribuyente\ContribuyenteBase;

/* @var $this yii\web\View */
/* @var $model app\models\Banco */

//$this->params['breadcrumbs'][] = ['label' => 'Regresar', 'url' => ['index']];
$this->title = Yii::t('backend','Id. Taxpayer') . ': ' . $model->id_contribuyente;
?>

<div class="contribuyente-view">
    <div class="container" style="width: 80%">
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">
                        <h1><?= Html::encode($this->title) ?></h1>
                </div>

                <div class="row">
<!-- ID CONTRIBUYENTE -->
                <div class="panel-body" style="margin-left: 160px">
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('id_contribuyente') . ':'; ?></b></div>
                        <div class="col-md-1"><?= $model["id_contribuyente"] ?></div>
                    </div>
                </div>

<!-- CEDULA Y/O RIF -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-3" style="width: 150px"><b><?= Html::encode(Yii::t('backend', 'DNI/Rif') . ':'); ?></b></div>
                        <div class="col-md-3"><?= ContribuyenteBase::getCedulaRifDescripcion($model['tipo_naturaleza'],
                                                                                             $model['naturaleza'],
                                                                                             $model['cedula'],
                                                                                             $model['tipo']) ?></div>
                    </div>
                </div>

<!-- CONTRIBUYENTE -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-4" style="width: 150px"><b><?= Html::encode(Yii::t('backend', 'Taxpayer') . ':'); ?></b></div>
                        <div class="col-md-8"><?= ContribuyenteBase::getContribuyenteDescripcion($model['tipo_naturaleza'],
                                                                                                 $model['razon_social'],
                                                                                                 $model['apellidos'],
                                                                                                 $model['nombres']) ?></div>
                    </div>
                </div>


<!-- DOMICILIO FISCAL -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-4" style="width: 150px"><b><?= Html::encode(Yii::t('backend', 'Address') . ':'); ?></b></div>
                        <div class="col-md-8"><?= $model['domicilio_fiscal'] ?></div>
                    </div>
                </div>


<!-- NATURALEZA -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= Yii::t('backend', 'Type') . ':'; ?></b></div>
                        <div class="col-md-1"><?= ContribuyenteBase::getTipoNaturalezaDescripcion($model['tipo_naturaleza']) ?></div>
                    </div>
                </div>

<!-- CORREO -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= Yii::t('backend', 'Correo') . ':'; ?></b></div>
                        <div class="col-md-1"><?= $model->email; ?></div>
                    </div>
                </div>

<!-- CONDICION -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= Yii::t('backend', 'Condicion') . ':'; ?></b></div>
                        <div class="col-md-1"><?= ( $model->inactivo == 0 ) ? 'ACTIVO' : 'INACTIVO'; ?></div>
                    </div>
                </div>


<!-- USUARIO -->
                <div class="panel-body" style="margin-left: 160px; padding-top: 0px;">
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= Yii::t('backend', 'Usuario') . ':'; ?></b></div>
                        <div class="col-md-1"><?=$model->afiliacion->login; ?></div>
                    </div>
                </div>


                </div>

                <div class="row" style="width: 100%;padding: 0px;;margin-bottom: 50px;padding-left: 400px;">
                    <div class="col-sm-3" style="text-align: center;">
                       <?= Html::a(Yii::t('backend', 'Quit'), ['menu/vertical'],
                                                              [
                                                                    'class' => 'btn btn-danger',
                                                                    'style' => 'width: 100%;'
                                                              ])
                        ?>
                    </div>
                </div>




            </div>  <!-- Fin de panel panel-primary -->
        </div>  <!-- Fin de container-fluid -->
    </div>  <!-- Fi de container -->
</div>   <!-- Fin de contribuyente-view -->







<!--
    <?/*= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_contribuyente',

        ],
    ]) */?>
-->