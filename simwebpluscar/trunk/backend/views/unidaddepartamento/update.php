<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UnidadDepartamento */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Unidad Departamento',
]) . ' ' . $model->id_unidad;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Unidad Departamentos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_unidad, 'url' => ['view', 'id' => $model->id_unidad]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="unidad-departamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
