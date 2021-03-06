<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VehiculosForm */

$this->title = Yii::t('backend', 'View Vehiculos') .': '. $model->id_vehiculo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Vehiculos Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiculos-form-view">
    <div class="container" style="width: 700px">
        <div class="container-fluid">
            <div class="panel panel-primary">
                <div class="panel-heading">
                        <h2><?= Html::encode($this->title) ?></h2>
                </div>
                <div class="panel-body" style="margin-left: 160px">

                    <!-- ID VEHICULO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('id_vehiculo'); ?></b></div>
                        <div class="col-md-1"><?= $model["id_vehiculo"] ?></div>
                    </div>
                    <!-- FIN DE ID VEHICULO -->

                    <!-- ID CONTRIBUYENTE -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('id_contribuyente'); ?></b></div>
                        <div class="col-md-1"><?= $model["id_contribuyente"] ?></div>
                    </div>
                    <!-- FIN DEL ID CONTRIBUYENTE -->

                    <!-- PLACA -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('placa'); ?></b></div>
                        <div class="col-md-1"><?= $model["placa"] ?></div>
                    </div>
                    <!-- FIND DE PLACA -->

                    <!-- MARCA -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('marca'); ?></b></div>
                        <div class="col-md-1"><?= $model["marca"] ?></div>
                    </div>
                    <!-- FIN DE MARCA -->

                    <!-- MODELO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('modelo'); ?></b></div>
                        <div class="col-md-1"><?= $model["modelo"] ?></div>
                    </div>
                    <!-- FIN DE MODELO -->

                    <!-- COLOR -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('color'); ?></b></div>
                        <div class="col-md-1" style="width: 150px"><?= $model["color"] ?></div>
                    </div>
                    <!-- FIN DE COLOR -->

                    <!-- PRECIO INICIAL -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('precio_inicial'); ?></b></div>
                        <div class="col-md-1">
                            <?php
                                $precio_inicial = number_format($model["precio_inicial"], 2, ',', '.');
                                echo $precio_inicial;
                            ?>
                        </div>
                    </div>
                    <!-- FIN DE PRECIO INICIAL -->

                    <!-- FECHA DE INICIO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px;"><b><?= $model->getAttributeLabel('fecha_inicio'); ?></b></div>
                        <div class="col-md-1" style="width: 150px;">
                            <?php 
                                $date = new DateTime($model["fecha_inicio"]);
                                echo $date->format('d-m-Y');
                            ?>
                        </div>
                    </div>
                    <!-- FIN DE FECHA DE INICIO -->

                    <!-- ANO DE COMPRA -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('ano_compra'); ?></b></div>
                        <div class="col-md-1"><?= $model["ano_compra"] ?></div>
                    </div>
                    <!-- FIN DE ANO DE COMPRA -->

                    <!-- ANO VEHICULO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('ano_vehiculo'); ?></b></div>
                        <div class="col-md-1"><?= $model["ano_vehiculo"] ?></div>
                    </div>
                    <!-- FIN ANO VEHICULO -->

                    <!-- NUMERO DE EJES -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('no_ejes'); ?></b></div>
                        <div class="col-md-1"><?= $model["no_ejes"] ?></div>
                    </div>
                    <!-- FIN NUMERO DE EJES -->

                    <!-- NUMERO DE PUESTOS -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('nro_puestos'); ?></b></div>
                        <div class="col-md-1"><?= $model["nro_puestos"] ?></div>
                    </div>
                    <!-- FIN NUMEROS DE PUESTOS -->

                    <!-- PESO -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('peso'); ?></b></div>
                        <div class="col-md-1">
                            <?php
                                $peso = number_format($model["peso"], 2, ',', '.');
                                echo $peso;
                            ?>
                        </div>
                    </div>
                    <!-- FIN DE PESO -->

                    <!-- NUMERO DE CALCOMANIA -->
                    <div class="row">
                        <div class="col-md-1" style="width: 150px"><b><?= $model->getAttributeLabel('nro_calcomania'); ?></b></div>
                        <div class="col-md-1"><?= $model["nro_calcomania"] ?></div>
                    </div>
                    <!-- FIN NUMERO DE CALCOMANIA -->

                </div>
                <div class="panel-footer">
                    <?= Html::a(Yii::t('backend', 'Back'), ['vehiculo/vehiculos/busqueda'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id_vehiculo], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>        
    </div>
</div>
