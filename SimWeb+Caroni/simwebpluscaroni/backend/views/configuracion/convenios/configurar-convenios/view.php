<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\configuracion\convenios\ConfigConvenios */

$this->title = $model->id_config_convenio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Config Convenios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-convenios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id_config_convenio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_config_convenio], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_config_convenio',
            'impuesto',
            'monto_minimo',
            'tipo_monto',
            'ano_ut',
            'solo_deuda_morosa',
            'tipo_periodo',
            'monto_inicial',
            'porcentaje_inicial',
            'nro_max_cuotas',
            'lapso_tiempo',
            'id_tiempo',
            'vcto_dif_ano',
            'aplicar_interes',
            'interes',
            'id_impuesto',
            'usuario',
            'fecha_hora',
            'inactivo',
        ],
    ]) ?>

</div>