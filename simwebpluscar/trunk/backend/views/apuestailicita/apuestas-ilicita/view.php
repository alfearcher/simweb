<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\apuestailicita\ApuestasIlicitaForm */

$this->title = $model->id_impuesto;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Apuestas Ilicita Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apuestas-ilicita-form-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id_impuesto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_impuesto], [
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
            'id_impuesto',
            'id_contribuyente',
            'descripcion',
            'direccion',
            'id_cp',
            'id_sim',
            'status_apuesta',
        ],
    ]) ?>

</div>
