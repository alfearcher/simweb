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
 *  @date 05-10-2015
 *
 *  @view pre-view.php
 *  @brief vista  previa del formulario de inscripcion de sucursales, mostrando lo que el usuario desea guardar para que el mismo confirmme.
 *
 */

	use yii\helpers\Html;
	use yii\widgets\DetailView;

	/* @var $this yii\web\View */
	/* @var $model app\models\Banco */
?>

<?php

    if ( $preView == true ) {
        $this->title = Yii::t('backend', 'Pre-View Registration of Sucursal');
    } else {
        $this->title = Yii::t('backend', 'Registration of Surucsal No. ' . $model->id_inscripcion_sucursal);
    }
   unset($_SESSION['idInscripcion']);
?>

<div class="inscripcion-sucursal-pre-view">
    <div class="contribuyente-view">
        <div class="container" style="width: 90%">
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                                <h1><?= Html::encode($this->title) ?></h1>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <!-- Aqui van los datos -->
                                <?php
                                        foreach ($model as $key => $value) {
                                            echo '<div class="panel-body" style="margin-left: 80px; width: 140%;">
                                                    <div class="row">
                                                        <div class="col-sm-3" style="width: 25%;"><b>' . $key . ': ' . '</b>
                                                        </div>
                                                        <div class="col-sm-3" style="width: 5%; margin-left: 120px;"></div>
                                                        <div class="col-sm-8">' . $model[$key] . '</div>
                                                    </div>
                                                </div>';
                                        }

                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3" style="width:50%; top: -15px; margin-left: 480px;">
                            	<?php if ( $preView == true ) { ?>
                                    <div class="col-sm-3">
                        	           <?= Html::a(Yii::t('backend', 'Confirm Create'), ['create', 'guardar' => true], ['class' => 'btn btn-primary']) ?>
                                    </div>
                                    <div class="col-sm-3" style="margin-left: 90px;">
                        	           <?= Html::a(Yii::t('backend', 'Back to Form'), ['index'], ['class' => 'btn btn-danger']) ?>
                                    </div>

                            	<?php } else { ?>
                                        <?php unset($_SESSION['datosContribuyente'],$_SESSION['postData']); ?>
                                        <div class="col-sm-3">
                                    	   <?= Html::a(Yii::t('backend', 'Quit'), ['quit'], ['class' => 'btn btn-danger']) ?>
                                        </div>

                            	<?php } ?>
                            </div>
                        </div> <!-- fin de row -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>