<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\LoteCalcomaniaForm */

// $this->title = Yii::t('backend', 'Update {modelClass}: ', [
//     'modelClass' => 'Lote Calcomania Form',
// ]) . ' ' . $model->id_lote_calcomania;
// $this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Lote Calcomania Forms'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->id_lote_calcomania, 'url' => ['view', 'id' => $model->id_lote_calcomania]];
// $this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="lote-calcomania-form-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
