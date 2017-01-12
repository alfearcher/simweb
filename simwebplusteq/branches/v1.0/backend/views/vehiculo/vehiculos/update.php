<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VehiculosForm */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Vehiculos Form',
]) . ' ' . $model->id_vehiculo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Vehiculos Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_vehiculo, 'url' => ['view', 'id' => $model->id_vehiculo]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="vehiculos-form-update">

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
        'desabilitar' => $desabilitar,
    ]) ?>

</div>
