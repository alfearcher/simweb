<?php 
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use yii\web\View;

	$prueba = 'hola vale';

 ?>

<table repeat_header="1" cellpadding="1" cellspacing="1" width="100%" border="0">
	<caption>INFORMACION GENERAL DEL CONTRIBUYENTE</caption>

	<tr>
		<th class="label-contribuyente" colspan="1"><?=Html::encode('Prueba'); ?></th>
		
	</tr>
	<tr class="cuerpo">
		<td class="info-contribuyente" colspan="1"><?=Html::encode($datos[0]['descripcion']); ?></td>
		
	</tr>

</table>