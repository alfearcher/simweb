<?php

/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 *
 *  > This library is free software; you can redistribute it and/or modify it under
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion)
 *  > any later version.
 *  >
 *  > This library is distributed in the hope that it will be usefull,
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence
 *  > for more details.
 *  >
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**
 *  @file pre-view.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 11-11-2015
 *
 *  @view pre-view.php
 *  @brief vista  previa del formulario, mostrando lo que el usuario desea guardar para que el mismo confirmme.
 *
 */

	use yii\helpers\Html;
	use common\models\contribuyente\ContribuyenteBase;
    use yii\grid\GridView;

	/* @var $this yii\web\View */
	/* @var $model app\models\Banco */

    //session_start();
?>

<?php

    if ( $preView == true ) {
        $this->title = Yii::t('backend', 'Pre-View Update of DNI');
    } else {
        $this->title = Yii::t('backend', 'Update of DNI');
    }
    $nombreForm = $model->formName();
?>


<div class="col-sm-10">
    <div class="panel panel-primary" style="width: 110%;">
        <div class="panel-heading">
            <h2><?= $this->title ?></h2>
        </div>
        <div class="panel-body" >
<?php if ($preView == true ) {?>
            <table class="table table-striped">
                <ul class="list-group">

                    <li class="list-group-item .panel panel-primary">
                        <div class="row">
                            <div class="col-sm-4" style="margin-left: 20px;">
                                <p><i><h4><?= Yii::t('backend', 'DNI Updated')  ?>: </h4></i></p>
                            </div>
                            <div class="col-sm-6">
                                <?php if ( $postData[$nombreForm]['tipo_naturaleza_v'] == 0 ) { ?>
                                    <h4><?= $postData[$nombreForm]['naturaleza_new'] . '-' . $postData[$nombreForm]['cedula_new'] ?></h4>
                                <?php } elseif ( $postData[$nombreForm]['tipo_naturaleza_v'] == 1 ) { ?>
                                    <h4><?= $postData[$nombreForm]['naturaleza_new'] . '-' . $postData[$nombreForm]['cedula_new'] . '-' . $postData[$nombreForm]['tipo_new'] ?></h4>
                                <?php } ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </table>

            <div class="row">
                <div class="panel panel-success" style="width: 97%;margin-left: 15px;">
                    <div class="panel-heading">
                        <span><?= Html::encode(Yii::t('backend', 'Taxpayers Afected')) ?></span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="contribuyente-asociado">
                                    <?= GridView::widget([
                                        'id' => 'grid-contribuyente-afectados',
                                        'dataProvider' => $dataProvider,
                                        'columns' => [

                                            [
                                                'label' => Yii::t('backend', 'ID.'),
                                                'value' => 'id_contribuyente',
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Current DNI'),
                                                'value' => function($data) {
                                                                return ContribuyenteBase::getCedulaRifDescripcion($data->tipo_naturaleza, $data->naturaleza, $data->cedula, $data->tipo);
                                                            },
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Taxpayers'),
                                                'value' => function($data) {
                                                                return ContribuyenteBase::getContribuyenteDescripcionSegunID($data->id_contribuyente);
                                                            },
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'License No.'),
                                                'value' => function($data) {
                                                                return $data->id_sim;
                                                            },
                                            ],
                                        ]
                                    ]);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  <!-- Fin de row del grid -->

<?php } else {?>
<!-- Si llego aqui es porque se guardo de forma satisfatoria -->

            <div class="row">
                <div class="panel panel-success" style="width: 97%;margin-left: 15px;">
                    <div class="panel-heading">
                        <span><?= Html::encode(Yii::t('backend', 'Taxpayers Afected')) ?></span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="contribuyente-asociado">
                                    <?= GridView::widget([
                                        'id' => 'grid-contribuyente-update',
                                        'dataProvider' => $dataProvider,
                                        'columns' => [

                                            [
                                                'label' => Yii::t('backend', $model->getAttributeLabel('id_correccion')),
                                                'value' => function($data) {
                                                                return $data->id_correccion;
                                                            },
                                            ],
                                            [
                                                'label' => Yii::t('backend', $model->getAttributeLabel('id_contribuyente')),
                                                'value' => function($data) {
                                                                return $data->id_contribuyente;
                                                            },
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'Antique DNI'),
                                                'value' => function($data) {
                                                                return ContribuyenteBase::getCedulaRifDescripcion($data->tipo_naturaleza_v,
                                                                                                                  $data->naturaleza_v,
                                                                                                                  $data->ccedula_v,
                                                                                                                  $data->tipo_v);
                                                            },
                                            ],
                                            [
                                                'label' => Yii::t('backend', Yii::t('backend', 'Current DNI')),
                                                'value' => function($data) {
                                                                return ContribuyenteBase::getCedulaRifDescripcion($data->tipo_naturaleza_new,
                                                                                                                  $data->naturaleza_new,
                                                                                                                  $data->ccedula_new,
                                                                                                                  $data->tipo_new);
                                                            },
                                            ],
                                            [
                                                'label' => Yii::t('backend', 'License No.'),
                                                'value' => function($data) {
                                                             return ContribuyenteBase::getLicenciaSegunID($data->id_contribuyente);
                                                         },
                                            ],
                                        ]
                                    ]);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  <!-- Fin de row del grid -->
<?php }?>
            <div class="row">
                <div class="col-sm-3" style="width:100%; top: 5px; margin-left: 80px;">
                    <?php if ( $preView == true ) { ?>
                        <div class="col-sm-3">
                           <?= Html::a(Yii::t('backend', 'Confirm Update'), ['create', 'guardar' => true], ['class' => 'btn btn-primary']) ?>
                        </div>
                        <div class="col-sm-3" style="margin-left: 90px;">
                           <?= Html::a(Yii::t('backend', 'Back to Form'), ['index'], ['class' => 'btn btn-danger']) ?>
                        </div>

                    <?php } else { ?>
                            <div class="col-sm-3" style="margin-left: 10px;">
                               <?= Html::a(Yii::t('backend', 'Other Update'), ['index'], ['class' => 'btn btn-default']) ?>
                            </div>
                            <div class="col-sm-3">
                               <?= Html::a(Yii::t('backend', 'Quit'), ['quit'], ['class' => 'btn btn-danger']) ?>
                            </div>

                    <?php } ?>
                </div>
            </div> <!-- fin de row -->
        </div>
    </div>
</div>