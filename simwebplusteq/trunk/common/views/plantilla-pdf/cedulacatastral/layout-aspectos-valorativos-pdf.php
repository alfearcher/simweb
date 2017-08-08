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
 *  @file layout-declaracion-pdf.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 27-08-2016
 *
 *  @view layout-declaracion-pdf.php
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
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use common\models\calculo\liquidacion\aaee\CalculoRubro;

	// $typeIcon = Icon::FA;
 //  	$typeLong = 'fa-2x';

 //    Icon::map($this, $typeIcon);

 ?>    
   
<?php 
	$fechaEmision = date('d-m-Y');
	$sumaImpuesto = 0;
?>

<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0" margin-top:"50">
	<caption>ASPECTOS VALORATIVOS DEL INMUEBLE</caption>
    
	  
<!-- Datos especificos de la declaracion -->
	<tr>
		<th class="label-declaracion" colspan="2.5"><?=Html::encode('VALOR TERRENO (mts2)'); ?></th>
		<th class="label-declaracion" colspan="2.5"><?=Html::encode('AVALUO TERRENO (mts2)'); ?></th>
		<th class="label-declaracion" colspan="2.5"><?=Html::encode('VALOR CONSTRUCCION (mts2)'); ?></th>
		<th class="label-declaracion" colspan="2.5"><?=Html::encode('AVALUO CONSTRUCCION (mts2)'); ?></th>
		<th class="label-declaracion" colspan="2"><?=Html::encode('VALOR CATASTRAL'); ?></th>
		
	</tr>  
  
	
		<tr class="cuerpo">
			<td class="info-declaracion" colspan="2.5"><?=Html::encode($resumen['valor_por_mts2_terreno']); ?></td>
			<td class="info-declaracion" colspan="2.5"><?=Html::encode($resumen['mts2_terreno']*$resumen['valor_por_mts2_terreno']); ?></td>
			<td class="info-declaracion" colspan="2.5"><?=Html::encode($resumen['valor_por_mts2']); ?></td>
			<td class="info-declaracion" colspan="2.5"><?=Html::encode($resumen['mts']*$resumen['valor_por_mts2']); ?></td>
			<td class="info-declaracion" colspan="2"><?=Html::encode($resumen['valor']); ?></td>
			
			
		  	
		</tr>  
	
                     

</table>

<style type="text/css">

	.label-total, .info-total {
		border-top: solid 2px #000;
	}


	.info-declaracion-principal,
	.label-declaracion {
		border-bottom: solid 2px #000;
	}

	.info-declaracion,
	.info-declaracion-x {
		border-bottom: solid 1px #ccc;
	}

	.label-declaracion-principal {
		text-align: center;
		font-size: 70%;
	}

	.info-declaracion-principal {
		text-align: center;
		font-size: 70%;
	}


	.label-declaracion {
		text-align: center;
		font-size: 60%;
	}

	.info-declaracion,
	.info-declaracion-x {
		text-align: center;
		font-size: 65%;
	}

	.info-declaracion-x {
		text-align: normal;
	}


	.label-total, .info-total {
		text-align: right;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 100%;
		font-weight: bold;
	}


	caption {
		color: white;
		text-align: center;
		font-size: 100%;
		border: 0.5px solid #175778;
		background-color: #175778;
		border-radius: 50px;
		padding: 0px;
		font-family: Arial, Helvetica, sans-serif;
		height: 2.2%;
		width: 180%;
		padding-top: 2px;
		/*margin-top: 35px;*/
	}

	.cuerpo {
		border-bottom: solid 1px #ccc;
	}
</style>