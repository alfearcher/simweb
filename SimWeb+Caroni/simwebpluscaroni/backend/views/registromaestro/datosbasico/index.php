<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DatosBasicoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Contribuyentes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contribuyentes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Contribuyentes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_contribuyente',
            'ente',
            'naturaleza',
            'cedula',
            'tipo',
            // 'tipo_naturaleza',
            // 'id_rif',
            // 'id_cp',
            // 'nombres',
            // 'apellidos',
            // 'razon_social',
            // 'representante',
            // 'nit',
            // 'fecha_nac',
            // 'sexo',
            // 'casa_edf_qta_dom',
            // 'piso_nivel_no_dom',
            // 'apto_dom',
            // 'domicilio_fiscal',
            // 'catastro',
            // 'tlf_hab',
            // 'tlf_hab_otro',
            // 'tlf_ofic',
            // 'tlf_ofic_otro',
            // 'tlf_celular',
            // 'fax',
            // 'email:email',
            // 'inactivo',
            // 'cuenta',
            // 'reg_mercantil',
            // 'num_reg',
            // 'tomo',
            // 'folio',
            // 'fecha',
            // 'capital',
            // 'horario',
            // 'extension_horario',
            // 'num_empleados',
            // 'tipo_contribuyente',
            // 'licencia',
            // 'agente_retencion',
            // 'id_sim',
            // 'manzana_limite',
            // 'lote_1',
            // 'lote_2',
            // 'nivel',
            // 'lote_3',
            // 'fecha_inclusion',
            // 'fecha_inicio',
            // 'foraneo',
            // 'no_declara',
            // 'econ_informal',
            // 'grupo_contribuyente',
            // 'fe_inic_agente_reten',
            // 'no_sujeto',
            // 'ruc',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
