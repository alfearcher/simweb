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

		<div class="col-sm-2" style="width: 15%;float: right;">
			<div class="btn-group">
				<button type="button" class="btn btn-danger btn-lg dropdown-toggle" data-toggle="dropdown">
					Opciones <span class="caret"></span>
				</button>

				<ul class="dropdown-menu" role="menu" style="background-color: white;">
					<?php if ( $bloquearFormaPago ) {
								$class = 'disabled';
							} else {
								$class = 'forma-pago';
							}
					?>

					<li class=<?=$class ?>><a href=<?=$urlFormaPagos; ?>>Formas de Pago</a></li>
					<li><a href=<?=Url::to(['quit']) ?>>Salida</a></li>
				</ul>
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