<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Contribuyentes */

$this->title = $model->id_contribuyente;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Contribuyentes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contribuyentes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id_contribuyente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_contribuyente], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_contribuyente',
            'ente',
            'naturaleza',
            'cedula',
            'tipo',
            'tipo_naturaleza',
            'id_rif',
            'id_cp',
            'nombres',
            'apellidos',
            'razon_social',
            'representante',
            'nit',
            'fecha_nac',
            'sexo',
            'casa_edf_qta_dom',
            'piso_nivel_no_dom',
            'apto_dom',
            'domicilio_fiscal',
            'catastro',
            'tlf_hab',
            'tlf_hab_otro',
            'tlf_ofic',
            'tlf_ofic_otro',
            'tlf_celular',
            'fax',
            'email:email',
            'inactivo',
            'cuenta',
            'reg_mercantil',
            'num_reg',
            'tomo',
            'folio',
            'fecha',
            'capital',
            'horario',
            'extension_horario',
            'num_empleados',
            'tipo_contribuyente',
            'licencia',
            'agente_retencion',
            'id_sim',
            'manzana_limite',
            'lote_1',
            'lote_2',
            'nivel',
            'lote_3',
            'fecha_inclusion',
            'fecha_inicio',
            'foraneo',
            'no_declara',
            'econ_informal',
            'grupo_contribuyente',
            'fe_inic_agente_reten',
            'no_sujeto',
            'ruc',
        ],
    ]) ?>

</div>
