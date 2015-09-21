<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Banco */

$this->params['breadcrumbs'][] = ['label' => 'Regresar', 'url' => ['index']];
$this->title = 'ID. Contribuyente: ' . $model->id_contribuyente;
?>
<div class="contribuyente-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Aceptar', ['update', 'id' => $model->id_contribuyente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Regresar', ['delete', 'id' => $model->id_contribuyente], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_contribuyente',

        ],
    ]) ?>

</div>