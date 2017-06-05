<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\VehiculosForm */

$this->title = Yii::t('backend', 'Create Vehiculos Form');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Vehiculos Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="vehiculos-form-create">

    <?= $this->render('_form', [
        'model' => $model,
        'msg' => $msg,
        'desabilitar' => $desabilitar,
    ]) ?>

</div>
