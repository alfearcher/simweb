<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\configuracion\convenios\ConfigConveniosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Config Convenios');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-convenios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Config Convenios'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_config_convenio',
            'impuesto',
            'monto_minimo',
            'tipo_monto',
            'ano_ut',
            // 'solo_deuda_morosa',
            // 'tipo_periodo',
            // 'monto_inicial',
            // 'porcentaje_inicial',
            // 'nro_max_cuotas',
            // 'lapso_tiempo',
            // 'id_tiempo',
            // 'vcto_dif_ano',
            // 'aplicar_interes',
            // 'interes',
            // 'id_impuesto',
            // 'usuario',
            // 'fecha_hora',
            // 'inactivo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>