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
 *  @date 20-11-2015
 *
 *  @view pre-view.php
 *  @brief vista  previa del formulario, mostrando lo que el usuario desea guardar para que el mismo confirmme.
 *
 */

 	use yii\helpers\Html;
	use common\models\contribuyente\ContribuyenteBase;
	// use yii\grid\GridView;
	//use backend\controllers\mensaje\MensajeController;
?>

<?php
	if ( $postData['btn-update'] != 2 ) {
		echo 'La operacion no es valida';
		exit;
	}

    if ( $preView == true ) {
        $this->title = Yii::t('backend', 'Pre-View Update of Tax Address');
    } else {
        $this->title = Yii::t('backend', 'Update of Tax Address No. ' . $model->id_correccion);
    }
?>


<div class="col-sm-10">
    <div class="panel panel-primary">
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
		            			<p><i><h4><?= $model->getAttributeLabel('id_contribuyente') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-4">
		    					<h4><?= $datosContribuyente[0]['id_contribuyente'] ?></h4>
		    				</div>
		            	</div>
					</li>

					<li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'DNI:') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-4">
		    					<h4>
		    						<?php
		    							if ( $datosContribuyente[0]['tipo_naturaleza'] == 0 ) {
		    								echo $datosContribuyente[0]['naturaleza'] . "-" . $datosContribuyente[0]['cedula'];
		    							} elseif ( $datosContribuyente[0]['tipo_naturaleza'] == 1 ) {
		    								echo $datosContribuyente[0]['naturaleza'] . "-" . $datosContribuyente[0]['cedula'] . "-" . $datosContribuyente[0]['tipo'];
		    							}
		    						?>
		    					  </h4>
		    				</div>
		            	</div>
					</li>

					<li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'Taxpayer:') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-7">
		    					<h4>
		    						<?php
		    							if ( $datosContribuyente[0]['tipo_naturaleza'] == 0 ) {
		    								echo $datosContribuyente[0]['apellidos'] . " " . $datosContribuyente[0]['nombres'];
		    							} elseif ( $datosContribuyente[0]['tipo_naturaleza'] == 1 ) {
		    								echo $datosContribuyente[0]['razon_social'];
		    							}
		    						?>
		    					</h4>
		    				</div>
		            	</div>
		            </li>

					<li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'Current Tax Address:') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-7">
		    					<h4><?= $datosContribuyente[0]['domicilio_fiscal'] ?></h4>
		    				</div>
		            	</div>
		            </li>

		            <li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'New Tax Address:') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-7">
		    					<h4><?= $model->domicilio_fiscal_new ?></h4>
		    				</div>
		            	</div>
		            </li>
				</ul>
            </table>

<?php } else {?>
<!-- Si llego aqui es porque se guardo de forma satisfatoria -->
			<table class="table table-striped">
				<ul class="list-group">
					<li class="list-group-item .panel panel-primary">
		            	<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= $model->getAttributeLabel('id_correccion') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-4">
		    					<h4><?= $model->id_correccion ?></h4>
		    				</div>
		            	</div>
					</li>

					<li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= $model->getAttributeLabel('id_contribuyente') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-4">
		    					<h4><?= $model->id_contribuyente ?></h4>
		    				</div>
		            	</div>
					</li>


					<li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'DNI') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-4">
		    					<h4>
		    						<?php
		    							if ( $datosContribuyente[0]['tipo_naturaleza'] == 0 ) {
		    								echo $datosContribuyente[0]['naturaleza'] . "-" . $datosContribuyente[0]['cedula'];
		    							} elseif ( $datosContribuyente[0]['tipo_naturaleza'] == 1 ) {
		    								echo $datosContribuyente[0]['naturaleza'] . "-" . $datosContribuyente[0]['cedula'] . "-" . $datosContribuyente[0]['tipo'];
		    							}
		    						?>
		    					</h4>
		    				</div>
		            	</div>
		            </li>

					<li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'Taxpayer') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-7">
		    					<h4>
		    						<?php
		    							if ( $datosContribuyente[0]['tipo_naturaleza'] == 0 ) {
		    								echo $datosContribuyente[0]['apellidos'] . " " . $datosContribuyente[0]['nombres'];
		    							} elseif ( $datosContribuyente[0]['tipo_naturaleza'] == 1 ) {
		    								echo $datosContribuyente[0]['razon_social'];
		    							}
		    						?>
		    					</h4>
		    				</div>
		            	</div>
		            </li>

		            <li class="list-group-item .panel panel-primary">
						<div class="row">
		            		<div class="col-sm-4" style="margin-left: 20px;">
		            			<p><i><h4><?= Yii::t('backend', 'Tax Address Updated: ') ?>:</h4></i></p>
		            		</div>
		    				<div class="col-sm-7">
		    					<h4><?= $model->domicilio_fiscal_new ?></h4>
		    				</div>
		            	</div>
		            </li>
				</ul>
            </table>
<?php }?>
			<div class="row">
                <div class="col-sm-3" style="width:100%; top: 5px; margin-left: 180px;">
                	<?php if ( $preView == true ) { ?>
                        <div class="col-sm-3">
            	           <?= Html::a(Yii::t('backend', 'Confirm Update'), ['create', 'guardar' => true], ['class' => 'btn btn-primary']) ?>
                        </div>
                        <div class="col-sm-3" style="margin-left: 90px;">
            	           <?= Html::a(Yii::t('backend', 'Back to Form'), ['index'], ['class' => 'btn btn-danger']) ?>
                        </div>

                	<?php } else { ?>
                			<div class="col-sm-3" style="margin-left: 10px;">
                        	   <?= Html::a(Yii::t('backend', 'Go to Form'), ['index'], ['class' => 'btn btn-default']) ?>
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