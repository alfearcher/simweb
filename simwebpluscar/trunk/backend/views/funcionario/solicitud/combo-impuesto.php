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
 *  @file combo-impuesto.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-04-2016
 *
 *  @view combo-impuesto.php
 *  @brief vista que muestra un combo de la entidad "impuestos"
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

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

?>
<div class="combo-impuesto">
	<?php $form = ActiveForm::begin([
		'id' => 'lista-impuesto-form',
	    'method' => 'post',
		//'enableClientValidation' => true,
		//'enableAjaxValidation' => true,
		//'enableClientScript' => true,
	]);?>

	<meta http-equiv="refresh">
    <div class="panel panel-default"  style="width: 85%;">
        <div class="panel-heading">
        	<div class="row">
        		<div class="col-sm-4" style="padding-top: 10px;">
        			<h4><?= Html::encode(Yii::t('backend', 'List of Request')) ?></h4>
        		</div>
        	</div>
        </div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="col-sm-12">
<!-- Inicio Impuesto -->
					<div class="row" style="padding-top: 15px;">
						<div class="col-sm-2">
							<div class="row">
								<p><strong><?= $modelImpuesto->getAttributeLabel(Yii::t('backend', 'impuesto')) ?></strong></p>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="row">
								 <?= $form->field($modelImpuesto, 'impuesto')
								          ->dropDownList($listaImpuesto, [
				                                                            'id'=> 'impuesto',
				                                                            'prompt' => Yii::t('backend', 'Select'),
				                                                            'style' => 'width:280px;',
				                                                            'onchange' =>
				                                                              '$.post( "' . Yii::$app->urlManager
                     ->createUrl('funcionario/solicitud/funcionario-solicitud/lista-impuesto-solicitud') . '&id=' . '" + $(this).val(),
                     function( data ) {
                           $( "#lista-impuesto-solicitud" ).html( data );
		             });'
				                                                            ])->label(false);
				                ?>
							</div>
						</div>
					</div>
<!-- Fin de Impuesto -->

					<div class="row" style="border-bottom: 0.5px solid #ccc;">
						<div class="lista-impuesto-solicitud" id="lista-impuesto-solicitud">
						</div>
					</div>


				</div>
			</div>	<!-- Fin de container-fluid -->
		</div>		<!-- Fin de panel-body -->
	</div>
	<?php ActiveForm::end(); ?>
</div>




