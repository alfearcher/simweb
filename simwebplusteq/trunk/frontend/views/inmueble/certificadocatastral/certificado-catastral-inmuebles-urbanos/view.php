<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Inmuebles */

$this->title = Yii::t('backend', 'View Property Urban');

?>
<div class="inmuebles-view">

    

    <p>
        <?= Html::a(Yii::t('backend', 'Solicitar Certificado catastral'), ['inmueble/certificadocatastral/certificado-catastral-inmuebles-urbanos/view-opcion'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend', 'Descargar del Certificado catastral'), ['inmueble/certificadocatastral/certificado-catastral-inmuebles-urbanos/view-descargar'], ['class' => 'btn btn-success'])?>
        <?= Html::a(Yii::t('backend', 'Volver al Menu Principal'), ['/site/menu-vertical'], ['class' => 'btn btn-danger']) ?>
    </p> 
<div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Datos del Inmueble') ?>  
</div>
    <?= DetailView::widget([
        'model' => $modelInmueble,
        'attributes' => [ 
            'id_impuesto',
            'id_contribuyente',
            'ano_inicio',
            'direccion',
          
        ], 
    ]) ?>
</div>

</div>

