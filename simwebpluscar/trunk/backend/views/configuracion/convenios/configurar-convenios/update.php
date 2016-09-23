<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\configuracion\convenios\ConfigConvenios */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Config Convenios',
]) . ' ' . $model->id_config_convenio;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Config Convenios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_config_convenio, 'url' => ['view', 'id' => $model->id_config_convenio]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="config-convenios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>