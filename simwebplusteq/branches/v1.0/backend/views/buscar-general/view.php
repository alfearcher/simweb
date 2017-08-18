<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\contribuyente\ContribuyenteBase;

/* @var $this yii\web\View */
/* @var $model app\models\Banco */

//$this->params['breadcrumbs'][] = ['label' => 'Regresar', 'url' => ['index']];
$this->title = Yii::t('backend','Id. Taxpayer') . ': ' . $model->id_contribuyente;
?>

<div class="contribuyente-view">
    <div class="container" style="width: 85%">
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">
                        <h1><?= Html::encode($this->title) ?></h1>
                </div>

                <div class="row" style="width: 98%;padding: 0px;margin:0px;margin-left:10px;margin-top: 10px;">
                    <?= DetailView::widget([
                            'model' => $model,
                            'options' => [
                                'class' => 'table table-striped table-bordered detail-view',
                            ],
                            'attributes' => [
                                [
                                    'label' => Yii::t('backend', 'Id'),
                                    'value' => $model['id_contribuyente'],
                                ],
                                [
                                    'label' => Yii::t('backend', 'Cedula/RIF'),
                                    'value' => ContribuyenteBase::getCedulaRifSegunID($model['id_contribuyente']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Contribuyente'),
                                    'value' => ContribuyenteBase::getContribuyenteDescripcionSegunID($model['id_contribuyente']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Type'),
                                    'value' => ContribuyenteBase::getTipoNaturalezaDescripcion($model['tipo_naturaleza']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Direccion'),
                                    'value' => $model['domicilio_fiscal'],
                                ],
                                [
                                    'label' => Yii::t('backend', 'Telefonos'),
                                    'value' => implode(' / ', ContribuyenteBase::getTelefonosSegunID($model['id_contribuyente'])),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Fax'),
                                    'value' => $model['fax'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Email'),
                                    'value' => $model['email'],
                                ],
                                [
                                    'label' => Yii::t('backend', 'Usuario'),
                                    'value' => isset($model->afiliacion->login) ? $model->afiliacion->login : '',
                                ],
                                [
                                    'label' => Yii::t('backend', 'Licencia'),
                                    'value' => $model['id_sim'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Fecha Inicio'),
                                    'value' => ( $model['fecha_inicio'] !== null ) ? date('d-m-Y', strtotime($model['fecha_inicio'])) : '',
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Sede Principal'),
                                    'value' => ( ContribuyenteBase::getEsUnaSedePrincipal($model['id_contribuyente']) ) ? 'SI' : 'NO',
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Condition'),
                                    'value' => ContribuyenteBase::getActivoInctivoDescripcion($model['inactivo']),
                                ],
                                [
                                    'label' => Yii::t('backend', 'Registro Mercantil'),
                                    'value' => $model['reg_mercantil'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'fecha'),
                                    'value' => date('d-m-Y', strtotime($model['fecha'])),
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Numero de Registro'),
                                    'value' => $model['num_reg'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Tomo'),
                                    'value' => $model['tomo'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Folio'),
                                    'value' => $model['folio'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Capital'),
                                    'value' => Yii::$app->formatter->asDecimal($model['capital'], 2),
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Horario'),
                                    'value' => $model['horario'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Declara'),
                                    'value' => ($model['no_declara']) ? 'NO' : 'SI',
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Agente Retencion'),
                                    'value' => ($model['agente_retencion']) ? 'SI' : 'NO',
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                 [
                                    'label' => Yii::t('backend', 'Fecha Inclusion'),
                                    'value' => ( $model['fecha_inclusion'] !== null ) ? date('d-m-Y', strtotime($model['fecha_inclusion'])) : '',
                                ],
                                [
                                    'label' => Yii::t('backend', 'C.I. Representante'),
                                    'value' => $model['naturaleza_rep'] . '' . $model['cedula_rep'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],
                                [
                                    'label' => Yii::t('backend', 'Representante'),
                                    'value' => $model['representante'],
                                    'visible' => ( $model['tipo_naturaleza'] == 1 ) ? true : false,
                                ],

                            ],
                        ]);
                    ?>
                </div>

                <div class="row" style="width: 100%;padding: 0px;;margin-bottom: 50px;padding-left: 400px;">
                    <div class="col-sm-3" style="text-align: center;">
                       <?= Html::a(Yii::t('backend', 'Quit'), ['menu/vertical'],
                                                              [
                                                                    'class' => 'btn btn-danger',
                                                                    'style' => 'width: 100%;'
                                                              ])
                        ?>
                    </div>
                </div>

            </div>  <!-- Fin de panel panel-primary -->
        </div>  <!-- Fin de container-fluid -->
    </div>  <!-- Fi de container -->
</div>   <!-- Fin de contribuyente-view -->
