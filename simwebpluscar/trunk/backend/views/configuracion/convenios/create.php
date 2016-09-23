<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\configuracion\convenios\ConfigConvenios */

$this->title = Yii::t('backend', 'Create Config Convenios');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Config Convenios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-convenios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>