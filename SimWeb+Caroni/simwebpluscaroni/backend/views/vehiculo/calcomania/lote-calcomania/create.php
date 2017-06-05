<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\LoteCalcomaniaForm */

// $this->title = Yii::t('backend', 'Create Lote Calcomania Form');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Lote Calcomania Forms'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="lote-calcomania-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
