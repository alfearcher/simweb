<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Contribuyentes */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Contribuyentes',
]) . ' ' . $model->id_contribuyente;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contribuyentes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_contribuyente, 'url' => ['view', 'id' => $model->id_contribuyente]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="contribuyentes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
