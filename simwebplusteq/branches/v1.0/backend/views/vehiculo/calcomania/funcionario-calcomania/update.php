<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Funcionario Calcomania Form',
]) . ' ' . $model->id_funcionario_calcomania;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Funcionario Calcomania Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_funcionario_calcomania, 'url' => ['view', 'id' => $model->id_funcionario_calcomania]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="funcionario-calcomania-form-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
