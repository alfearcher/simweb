<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm */

// $this->title = Yii::t('backend', 'Create Funcionario Calcomania Form');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Funcionario Calcomania Forms'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="funcionario-calcomania-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'result' => $result,
    ]) ?>

</div>
