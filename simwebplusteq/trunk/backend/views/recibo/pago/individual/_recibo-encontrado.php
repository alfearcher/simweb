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
 *  @file _recibo-encontrado.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 23-02-2017
 *
 *  @view _recibo-encontrado.php
 *
 *
 *  @property
 *
 *
 *  @method
 *
 *
 *  @inherits
 *
 */

 	use yii\web\Response;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\web\View;


 ?>

 <div class="row" style="width:100%;">

 	<div class="row" style="width:100%;">
		<div class="col-sm-4" style="width: 80%;padding: 0px;">
		 	<?php if ( $htmlMensaje !== null ) { ?>
			 	<div class="well well-sm" style="width:100%;color:red;font-size: 120%;">
			 		<?= $htmlMensaje; ?>
			 	</div>
			<?php } ?>
		</div>

		<div class="row" style="margin-top: 25px;">

			<?php
				if ( $bloquearFormaPago ) {
					$bloquear = true;
				} else {
					$bloquear = false;
				}
			?>
			<div class="col-sm-2" style="margin-left: 20px;width: 20%;">
				<div class="form-group">
					<?= Html::submitButton(Yii::t('backend', 'Formas de Pago'),
													  [
														'id' => 'btn-forma-pago',
														'class' => 'btn btn-primary',
														'value' => 2,
														'style' => 'width: 100%',
														'name' => 'btn-forma-pago',
														'disabled' => $bloquear,
													  ])
					?>
				</div>
			</div>

			<div class="col-sm-2" style="margin-left: 5px;width: 15%;">
				<div class="form-group">
					<?= Html::submitButton(Yii::t('backend', 'Back'),
													  [
														'id' => 'btn-back',
														'class' => 'btn btn-danger',
														'value' => 1,
														'style' => 'width: 100%',
														'name' => 'btn-back',
													  ])
					?>
				</div>
			</div>

			<div class="col-sm-2" style="margin-left: 5px;width: 15%;">
				<div class="form-group">
					<?= Html::submitButton(Yii::t('backend', 'Quit'),
													  [
														'id' => 'btn-quit',
														'class' => 'btn btn-danger',
														'value' => 1,
														'style' => 'width: 100%',
														'name' => 'btn-quit',
													  ])
					?>
				</div>

			</div>


			<div class="col-sm-2" style="margin-left: 5px;width: 25%;">
				<div class="form-group">
					<?= Html::submitButton(Yii::t('backend', 'Imprimir Rafaga del Recibo'),
													  [
														'id' => 'btn-rafaga-printer',
														'class' => 'btn btn-primary',
														'value' => 1,
														'style' => 'width: 100%',
														'name' => 'btn-rafaga-printer',
														'disabled' => $desactivarBotonRafaga,
													  ])
					?>
				</div>
			</div>

		</div>
	</div>

	<div class="row" style="width: 101%;padding: 0px;padding-left: 40px;margin-top: 15px;">
		<?= $this->render('/recibo/pago/individual/datos-recibo',[
												'dataProviderRecibo' => $dataProviderRecibo,
												'dataProviderReciboPlanilla' => $dataProviderReciboPlanilla,
												'totales' => $totales,
					]);
    	?>
	</div>
</div>