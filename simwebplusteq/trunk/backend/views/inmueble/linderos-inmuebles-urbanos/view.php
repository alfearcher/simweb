<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Inmuebles */

$this->title = Yii::t('backend', 'View Property Urban');

?>
<div class="inmuebles-view">

    

    <p>
        <?= Html::a(Yii::t('backend', 'Linderos Inmueble'), ['inmueble/linderos-inmuebles-urbanos/linderos-inmuebles'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
    </p> 

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [ 
             'id_historico_avaluo', 
             'id_impuesto', 
             'fecha', 
             'inactivo'
        ], 
    ]) ?>

</div>


<!-- <? /*= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_impuesto], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ], 
        ]) */ ?> -->