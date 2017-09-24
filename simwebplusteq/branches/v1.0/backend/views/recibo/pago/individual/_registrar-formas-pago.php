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
 *  @file _registar-formas-pago.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 25-02-2017
 *
 *  @view _registar-formas-pago.php
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
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\Pjax;
	use yii\bootstrap\Modal;
	//use common\models\contribuyente\ContribuyenteBase;
	use yii\widgets\DetailView;
	use yii\widgets\MaskedInput;


 ?>

<?php
	$form = ActiveForm::begin([
		'id' => 'id-registrar-forma-pago-form',
		'method' => 'post',
		'action' => ['registrar-formas-pago'],
		'enableClientValidation' => true,
		'enableAjaxValidation' => false,
		'enableClientScript' => true,
	]);
 ?>

	<!-- <?//=Html::hiddenInput('operacion', $operacion, ['id' => 'id-operacion'])?> -->


	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption . '. ' . $captionRecibo) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body">
        	<div class="container-fluid">
        		<div class="col-sm-12" >

					<div class="row" style="margin:0px;padding:0px;">
						<div class="row" style="padding: 0px;padding-left: 5px;">
							<?php if ( $htmlResumenReciboFormaPago !== null ) { ?>
								<div class="resumen-forma-pago"><?=$htmlResumenReciboFormaPago;?></div>
							<?php } ?>
						</div>

<!-- FORMULARIO DE FORMA DE PAGO -->
						<div class="row" style="padding: 0px;padding-left: 5px;width: 75%;">
							<?php if ( $htmlFormaPago !== null ) { ?>
								<div class="forma-pago"><?=$htmlFormaPago;?></div>
							<?php } ?>
						</div>
<!-- FINAL DEL FORMULARIO DE FORMA DE PAGO -->

<!-- FORMA DE PAGO CONTABILIZADA-->
						<div class="row" style="padding: 0px;padding-left: 5px;width: 100%;">
							<?php if ( $htmlFormaPagoContabilizada !== null ) { ?>
								<div class="forma-pago-contabilizada"><?=$htmlFormaPagoContabilizada;?></div>
							<?php } ?>
						</div>
<!-- FINAL DE FORMA DE PAGO CONYTABILIZADA -->


<!-- FORMA PARA REGISTRAR LA PRE-REFERENCIAS -->
						<div class="row" style="padding: 0px;padding-left: 5px;width: 100%;">
							<?php if ( $htmlFormaPagoContabilizada !== null ) { ?>
								<div class="forma-pre-referencia"></div>
							<?php } ?>
						</div>

<!-- FINAL DE FORMA PARA REGISTRAR LA PRE-REFERENCIAS -->

<!-- INICIO DEL FORMULARIO MODAL -->
						<style type="text/css">
							.modal-content {
								margin-top: 150px;

							}
						</style>
						<div class="row">
							<?php
								Modal::begin([
									'header' => 'Forma de pagos',
									'id' => 'modal',
									'size' => 'modal-lg',
									'footer' => '<a href="#" class="btn btn-danger" data-dismiss="modal">Cerrar</a>',
								]);

								echo "<div id='modalContent' style='padding-left: 20px;'></div>";

								Modal::end();
							 ?>
						</div>

<!-- FINAL DEL FORMULARIO MODAL -->

						<div class="row" style="width: 100%;margin-top: 50px;">
							<div class="col-sm-2" style="margin-left: 10px;width: 15%;">
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

							<div class="col-sm-2" style="margin-left: 5px;width: 15%;">
								 <div class="form-group">
								 <!-- '../../common/docs/user/ayuda.pdf'  funciona -->
									<?= Html::a(Yii::t('backend', 'Ayuda'), $rutaAyuda,  [
															'id' => 'btn-help',
															'class' => 'btn btn-default',
															'name' => 'btn-help',
															'target' => '_blank',
															'value' => 1,
															'style' => 'width: 100%;'
														])?>

								</div>
							</div>


							<div class="col-sm-2" style="margin-left: 25px;width: 25%;">
								<div class="form-group">
									<?= Html::submitButton(Yii::t('backend', 'Pre-Referencias'),
																	  [
																		'id' => 'btn-pre-referencia',
																		'class' => 'btn btn-primary',
																		'value' => 2,
																		'style' => 'width: 100%',
																		'name' => 'btn-pre-referencia',
																	  ])
									?>
								</div>
							</div>
						</div>

<!-- Fin de Boton para salir de la actualizacion -->
					</div>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>


<?php
$this->registerJs(
    '$(document).on("click", "#link-add-detail", (function() {
        $.get(
            $(this).data("url"),
            function (data) {
                //$(".modal-body").html(data);
                $("#modalContent").html(data);
                $("#modal").modal();
            }
        );
    }));
	if ( $( "#id-monto-recibo").val() == $( "#id-monto-agregado").val() ) {
		$( "#btn-pre-referencia").attr("disabled", false);
		$( "#id-monto-ok").show("slow");
	} else {
		$( "#btn-pre-referencia").attr("disabled", true);
		$( "#id-monto-ok").hide("slow");
	}
    '
); ?>

<?php
	$this->registerJs(
		'$("#id-monto").keypress(function(e) {
    		if ( e.which == 13 ) {
    			sobrante = $( "#id-monto-sobrante" ).val();
				valor = sobrante.replace(".", "");
				monto = valor.replace(",", ".");
    			sobrante = parseFloat(monto);

        		if ( sobrante > 0 ) {
        			$( "#id-monto" ).val( sobrante );
        		}
    		}
		});return false;'
	);
 ?>