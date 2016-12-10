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
 *  @file view-solicitud-cambio-datos-propaganda.php
 *
 *  @author Manuel Zapata
 *
 *  @date 24-08-2016
 *
 *  @view view-solicitud-cambio-datos-propaganda.php
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
	use yii\web\View;
	use yii\widgets\DetailView;
	use backend\models\vehiculo\VehiculoSearch;
	use frontend\models\propaganda\solicitudes\SlPropagandasForm;
	use common\models\propaganda\tarifaspropaganda\TarifasPropagandas;

//die(var_dump($modelPropaganda));
?>

<div class="row" style="width: 50%;">
	<div class="info-solicitud">
		<div class="row">
			<div class="col-sm-5">
			<h3><?= Html::encode('Datos Antiguos') ?></h3>
				<?= DetailView::widget([
						'model' => $modelPropaganda,

		    			'attributes' => [
		    				'ano_impositivo',
		    				'direccion',
		    				'nombre_propaganda',
		    				//'nro_solicitud',





						   	[
					        'label' => 'Clase Propaganda',

					        'value' => $model->descripcionClasePropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					         ],

					        	  [
					        'label' => 'Tipo Propaganda',

					        'value' => $model->descripcionTipoPropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					        [
					        'label' => 'Uso Propaganda',

					        'value' => $model->descripcionUsoPropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					        [
					        'label' => 'Medio de Difusion',

					        'value' => $model->descripcionMedioDifusionPropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					            [
					        'label' => 'Medio de Transporte',

					        'value' => $model->descripcionMedioTransportePropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					        'fecha_desde',
					        'cantidad_tiempo',
					        'cantidad_base',
					        'alto',
					        'ancho',
					        'profundidad',
					        'unidad',

					        [
					        'label' => 'Cigarrillos',

					        'value' => $model['cigarros'] == 1 ? 'SI' : 'NO',
					        ],

		    				[
					        'label' => 'Bebidas Alcoholicas',

					        'value' => $model['bebidas_alcoholicas'] == 1 ? 'SI' : 'NO',
					        ],

					        [
					        'label' => 'Idioma',

					        'value' => $model['idioma'] == 1 ? 'SI' : 'NO',
					        ],

		    				'observacion',

		    				'fecha_fin',

		    				//'usuario',



		    				// 'color',

		    				// 'precio_inicial',
		    				// 'fecha_inicio',
		    				// 'ano_compra',
		    				// 'ano_vehiculo',
		    				// 'no_ejes',
		    				// 'liquidado',
		    				// 'status_vehiculo',
		    				// 'exceso_cap',
		    				// 'medida_cap',
		    				// 'capacidad',
		    				// 'nro_puestos',
		    				// 'peso',

		    				// 'serial_motor',
		    				// 'serial_carroceria',
		    				// 'nro_calcomania',

		    				// 'nro_cilindros',
		    				// 'fecha_hora',

		    				// [
		    				// 	'label' => 'Uso Vehiculo',

		    				// 	'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
		    				// 	 VehiculoSearch::getDescripcionUsoVehiculo($model->uso_vehiculo),

		    				// ],

		    				// [
		    				// 	'label' => 'Tipo Vehiculo',

		    				// 	'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
		    				// 	 VehiculoSearch::getDescripcionTipoVehiculo($model->tipo_vehiculo),

		    				// ],

		    				// [
		    				// 	'label' => 'Clase Vehiculo',

		    				// 	'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
		    				// 	 VehiculoSearch::getDescripcionClaseVehiculo($model->clase_vehiculo),

		    				// ],


		    			],
					])
				?>
			</div>

			<div class="col-sm-5" style="margin-left: 70px; ">
				<h3><?= Html::encode('Datos Nuevos') ?></h3>
				<?= DetailView::widget([

						'model' => $model,

		    			'attributes' => [
		    				'ano_impositivo',
		    				'direccion',
		    				'nombre_propaganda',
		    				'nro_solicitud',





						   	[
					        'label' => 'Clase Propaganda',

					        'value' => $model->descripcionClasePropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					         ],

					        	  [
					        'label' => 'Tipo Propaganda',

					        'value' => $model->descripcionTipoPropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					        [
					        'label' => 'Uso Propaganda',

					        'value' => $model->descripcionUsoPropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					        [
					        'label' => 'Medio de Difusion',

					        'value' => $model->descripcionMedioDifusionPropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					            [
					        'label' => 'Medio de Transporte',

					        'value' => $model->descripcionMedioTransportePropaganda->descripcion, //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){



					        ],

					        'fecha_desde',
					        'cantidad_tiempo',
					        'cantidad_base',
					        'alto',
					        'ancho',
					        'profundidad',
					        'unidad',

					        [
					        'label' => 'Cigarrillos',

					        'value' => $model['cigarros'] == 1 ? 'SI' : 'NO',
					        ],

		    				[
					        'label' => 'Bebidas Alcoholicas',

					        'value' => $model['bebidas_alcoholicas'] == 1 ? 'SI' : 'NO',
					        ],

					        [
					        'label' => 'Idioma',

					        'value' => $model['idioma'] == 1 ? 'SI' : 'NO',
					        ],

		    				'observacion',

		    				'fecha_fin',

		    				'usuario',



		    				// 'color',

		    				// 'precio_inicial',
		    				// 'fecha_inicio',
		    				// 'ano_compra',
		    				// 'ano_vehiculo',
		    				// 'no_ejes',
		    				// 'liquidado',
		    				// 'status_vehiculo',
		    				// 'exceso_cap',
		    				// 'medida_cap',
		    				// 'capacidad',
		    				// 'nro_puestos',
		    				// 'peso',

		    				// 'serial_motor',
		    				// 'serial_carroceria',
		    				// 'nro_calcomania',

		    				// 'nro_cilindros',
		    				// 'fecha_hora',

		    				// [
		    				// 	'label' => 'Uso Vehiculo',

		    				// 	'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
		    				// 	 VehiculoSearch::getDescripcionUsoVehiculo($model->uso_vehiculo),

		    				// ],

		    				// [
		    				// 	'label' => 'Tipo Vehiculo',

		    				// 	'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
		    				// 	 VehiculoSearch::getDescripcionTipoVehiculo($model->tipo_vehiculo),

		    				// ],

		    				// [
		    				// 	'label' => 'Clase Vehiculo',

		    				// 	'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
		    				// 	 VehiculoSearch::getDescripcionClaseVehiculo($model->clase_vehiculo),

		    				// ],


		    			],
					])
				?>
			</div>
		</div>
	</div>
</div>
