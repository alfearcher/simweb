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
 *  @file view-solicitud-desincorporacion-vehiculo.php
 *
 *  @author Manuel Zapata
 *
 *  @date 05-07-2016
 *
 *  @view view-solicitud-desincorporacion-vehiculo.php
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
	
	//die('llegue a vista');
?>

<div class="row" style="width: 70%;">
	<div class="info-solicitud">
		<div class="row">
			<h3><?= Html::encode($caption) ?></h3>
				<?= DetailView::widget([
						'model' => $model,
						
		    			'attributes' => [
		    				
		    				[
	    					'label' => 'Causa Desincorporacion',

	    					'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
	    					 VehiculoSearch::getDescripcionCausaDesincorporacion($model->causa_desincorporacion),
	    						
		    				],
		    				
		    				'observacion',
		    				'usuario',
		    				'fecha_hora',



		    			

		    			
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