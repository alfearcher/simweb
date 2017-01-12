<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Inmuebles */

/*$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Property',
]) . ' ' . $model->id_impuesto;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inmuebles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_impuesto, 'url' => ['view', 'id' => $model->id_impuesto]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');*/
$disabled = true;
?>
<div class="inmuebles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_cambioapropietariocompradorinmueblesform', [
        'model' => $model, 'disabled'=>$disabled, 'datos'=>$datos,
    ]) ?>

</div>
