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
 *  @file prueba-cedula-rif-form.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 10-08-2015
 *
 *  @view prueba-cedula-rif-form.php
 *  @brief vista que muestra un formulario donde se carga los campos para ingresar la cedula de una persona natural o el *	brief rif de una persona juridica.
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
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use backend\models\registromaestro\TipoNaturaleza;


	if ( $tipoNaturaleza == 0 ) {
		$label = 'Cedula';
		$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
	} elseif ( $tipoNaturaleza == 1 ) {
		$label = 'RIF';
		$modeloTipoNaturaleza = TipoNaturaleza::find()->all();
	}
	$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');
 ?>

 <div class="naturaleza-select-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'naturaleza-select-form',
 			//'enableClientValidation' => true,
 			//'enableAjaxValidation' => true,
 			//'enableClientScript' => true,
 		]);

 	 ?>

<div class="col-sm-8" style="padding-left: 0px;padding-right: 0px;">
	<div class="row" style="width:100%;">
		<p style="margin-left: 15px;margin-top: 0px;margin-bottom: 0px;"><i><small><?=Yii::t('backend', $label) ?></small></i></p>
	</div>

<!-- COMBO NATURALEZA -->
	<div class="row" style="width:100%; padding-left: 0px;padding-right: 0px;">
		<div class="container-fluid" style="margin-left: 0px;margin-right: 0px;padding-left: 0px;padding-right: 0px;">
			<div class="col-sm-5" style="padding-right: 12px;">
      			<div class="naturaleza">
            		<?= Html::dropDownList('naturaleza', null,$listaNaturaleza,[
            																	 'id' => 'naturaleza',
                                                                 				 'prompt' => Yii::t('backend', 'Select'),
                                                                 				 'style' => 'height:32px;width:135px;'
                                                                				])
					?>
				</div>
			</div>
		</div>
	</div>
</div>	<!-- Fin de col-sm-8-->

<?php ActiveForm::end(); ?>
