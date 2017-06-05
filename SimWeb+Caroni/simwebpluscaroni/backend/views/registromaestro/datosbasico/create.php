<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Contribuyentes */

//$this->title = Yii::t('backend', 'Create Contribuyentes');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contribuyentes'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contribuyentes-create">
    <?= $this->render('datos-basicos', [
        'model' => $model,        
    ]) ?>

</div>
