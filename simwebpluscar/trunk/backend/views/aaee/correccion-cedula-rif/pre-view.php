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
	use yii\widgets\DetailView;

	/* @var $this yii\web\View */
	/* @var $model app\models\Banco */

    //session_start();
?>

<?php

    if ( $preView == true ) {
        $this->title = Yii::t('backend', 'Pre-View Correccion de DNI');
    } else {
        $this->title = Yii::t('backend', 'Correccion de DNI No. ' . $model->id_correccion);
    }
    unset($_SESSION['id_correccion']);
?>


<div class="col-sm-10">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= $this->title ?>
        </div>
        <div class="panel-body" >
            <table class="table table-striped">
            </table>
        </div>
    </div>
</div>



<div class="correcion-dni-pre-view">
    <div class="row">
        <div class="col-sm-8" style="width: 75%;">
            <h3><?= Html::encode($this->title) ?></h3>
<?php if ( $model->tipo_naturaleza_new == 0 ) {?>
    
<?php } elseif ( $model->tipo_naturaleza_new == 1 ) { ?>
    
<?php }?>
        </div>
        <div class="col-sm-4" style="width:20%; top: 55px;">
        	<?php if ( $preView == true ) { ?>
        	    <div class="row">
                    <div class="col-sm-3">
        	           <?= Html::a(Yii::t('backend', 'Confirm Update'), ['create', 'guardar' => true], ['class' => 'btn btn-primary']) ?>
                    </div>
                    <div class="col-sm-3" style="margin-left: 80px;">
        	           <?= Html::a(Yii::t('backend', 'Back to Form'), ['index'], ['class' => 'btn btn-danger']) ?>
                    </div>
        	    </div>
        	<?php } else { ?>
        		<div class="row">
                    <div class="col-sm-3">
                	   <?= Html::a(Yii::t('backend', 'Quit'), ['quit'], ['class' => 'btn btn-danger']) ?>
                    </div>
        	    </div>
        	<?php } ?>
        </div>
    </div>
</div>