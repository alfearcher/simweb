<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm */

$this->title = $model->id_funcionario_calcomania;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Funcionario Calcomania Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="funcionario-calcomania-form-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id_funcionario_calcomania], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_funcionario_calcomania], [
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
            'id_funcionario_calcomania',
            'id_funcionario',
            'estatus',
            'ci',
        ],
    ]) ?>

</div>
