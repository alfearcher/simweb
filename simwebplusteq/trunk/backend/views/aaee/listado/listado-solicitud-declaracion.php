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
 *  @file listado-solicitud-declaracion.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 29-08-2016
 *
 *  @view listado-solicitud-declaracion.php
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

 	//use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\jui\DatePicker;
	use yii\widgets\Pjax;
	use backend\models\aaee\listado\ListadoSolicitudDeclaracion;


	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

 ?>


 <div class="listado-solicitud-declaracion-form">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-listado-solicitud-declaracion-form',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>

	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 110%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($this->title) ?></h3>
        </div>

<!-- Cuerpo del formulario style="background-color: #F9F9F9;"-->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">
<!--  -->
		        	<div class="row">
						<?= GridView::widget([
							'id' => 'grid-lista',
							'dataProvider' => $dataProvider,
							'headerRowOptions' => ['class' => 'success'],
							//'filterModel' => $listadoModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								'nro_solicitud',
								'fecha_hora_creacion',
								[
				                    'label' => Yii::t('frontend', 'Año'),
				                    'value' => function($data) {
            										return $data->declaracion->ano_impositivo;
    											},
				                ],

				            	[
				                    'label' => Yii::t('frontend', 'Descripcion'),
				                    'value' => function($data) {
            										return $data->getDescripcionTipoSolicitud($data->nro_solicitud);
    											},
				                ],
				                [
				                    'label' => Yii::t('frontend', 'Estatus'),
				                    'value' => function($data) {
            										return $data->estatusSolicitud->descripcion;
    											},
				                ],
				                [
				                    'label' => Yii::t('frontend', 'ID.'),
				                    'value' => function($data) {
            										return $data->id_contribuyente;
    											},
				                ],
				                [
				                    'label' => Yii::t('frontend', 'Contribuyente'),
				                    'value' => function($data) {
            										return $data->getContribuyente($data->id_contribuyente);
    											},
				                ],
				                [
				                    'label' => Yii::t('frontend', 'Suma declaracion'),
				                    'contentOptions' => [
				                    	'style' => 'text-align: right',
				                    ],
				                    'value' => function($data) {
            										return Yii::$app->formatter->asDecimal($data->getSumaMontoDeclarado($data->nro_solicitud), 2);
    											},
				                ],

								[
				                    'label' => Yii::t('frontend', 'Liquidado'),
				                    'contentOptions' => [
				                    	'style' => 'text-align: right',
				                    ],
				                    'value' => function($data) {
				                    				$tipo = 0;
				                    				if ( $data->declaracion->tipo_declaracion == 1 ) {
				                    					$tipo = 0;
				                    				} elseif ( $data->declaracion->tipo_declaracion == 2 ) {
				                    					$tipo = 1;
				                    				}

				                    				$listado = New ListadoSolicitudDeclaracion();
				                    				$suma = $listado->getMontoLiquidacion($tipo, $data->declaracion->ano_impositivo, $data->id_contribuyente);
            										return Yii::$app->formatter->asDecimal($suma, 2);
    											},
				                ],

				        	]
						]);?>
					</div>
				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->
		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


