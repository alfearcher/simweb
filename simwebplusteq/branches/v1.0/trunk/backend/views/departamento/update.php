<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Departamento */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Departamento',
]) . ' ' . $model->id_departamento;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Departamentos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_departamento, 'url' => ['view', 'id' => $model->id_departamento]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="departamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
