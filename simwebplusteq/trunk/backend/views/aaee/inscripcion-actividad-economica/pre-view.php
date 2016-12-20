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
 *  @date 20-09-2015
 *
 *  @view pre-view.php
 *  @brief vista  previa del formulario, mostrando lo que el usuario desea guardar para que el mismo confirmme.
 *
 */

	use yii\helpers\Html;
	use yii\widgets\DetailView;

	/* @var $this yii\web\View */
	/* @var $model app\models\Banco */
	if ( $preView == true ) {
		$this->title = Yii::t('backend', 'Pre-View Registration of Economic Activity');
	} else {
		$this->title = Yii::t('backend', 'Registration of Economic Activity No. ' . $model->id_inscripcion);
	}
?>
<div class="inscripcion-act-econ-pre-view">
    <div class="row">
        <div class="col-sm-8" style="width: 75%;">
            <h3><?= Html::encode($this->title) ?></h3>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_inscripcion',
                    'id_contribuyente',
                    'nro_solicitud',
                    'num_reg',
                    'reg_mercantil',
                    'fecha',
                    'tomo',
                    'folio',
                    'capital',
                    'num_empleados',
                    'naturaleza_rep',
                    'cedula_rep',
                    'representante'
                ],
            ]) ?>
        </div>
        <div class="col-sm-4" style="width:20%; top: 55px;">
        	<?php if ( $preView == true ) { ?>
        	    <div class="row">
                    <div class="col-sm-3">
        	           <?= Html::a(Yii::t('backend', 'Confirm Create'), ['create', 'guardar' => true], ['class' => 'btn btn-primary']) ?>
                    </div>
                    <div class="col-sm-3" style="margin-left: 80px;">
        	           <?= Html::a(Yii::t('backend', 'Back to Form'), ['index'], ['class' => 'btn btn-danger']) ?>
                    </div>
        	    </div>
        	<?php } else { ?>
        		<div class="row">
                    <div class="col-sm-3">
                	   <?= Html::a(Yii::t('backend', 'Quit'), ['menu/vertical'], ['class' => 'btn btn-danger']) ?>
                    </div>
        	    </div>
        	<?php } ?>
        </div>
    </div>
</div>
