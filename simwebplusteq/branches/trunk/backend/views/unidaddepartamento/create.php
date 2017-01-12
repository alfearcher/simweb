<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UnidadDepartamento */

$this->title = Yii::t('backend', 'Create Unidad Departamento');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Unidad Departamentos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unidad-departamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
