<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\UnidadDepartamento */

$this->title = $model->id_unidad;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Unidad Departamentos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unidad-departamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id_unidad], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_unidad], [
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
            'id_unidad',
            'id_departamento',
            'descripcion',
            'inactivo',
        ],
    ]) ?>

</div>
