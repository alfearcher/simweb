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
 *  @file _view-consulta.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 21-11-2016
 *
 *  @view _view vista que renderiza al formulario principal de consulta de deudas.
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
	use common\mensaje\MensajeController;

 ?>


 <div class="row">
 	<div class="well well-sm" style="padding:10px;">
		<div class="list-group" style="padding-left: 20px;font-size: 110%;">
			<strong><h3 class="list-group-item-heading" style="color:blue;"><?=Yii::t('backend', 'Notificación');?></h3></strong>
			<p class="list-group-item-text">
				<?=Html::tag('li', Yii::t('frontend', 'A partir del 01-10-2017 entrará en vigencia el nuevo instrumento de pago,denominado <strong>Recibo de Pago"</strong>.')); ?>
				<?=Html::tag('li', Yii::t('frontend', 'En este nuevo instrumento de pago podrá incluir todas las planillas pendientes que desea saldar (pagar).')); ?>
				<?=Html::tag('li', Yii::t('frontend', 'Las planillas serán actualizadas antes de ser mostradas en el listado de <strong>"Deuda - Detalle"</strong>.'),
										 [
										 	'style' => 'color:blue;background-color:#F5F7F8;'
										 ]);
				?>
				<?=Html::tag('li', Yii::t('frontend', '<strong>Podrá crear su recibo de pago a través de la opción:</strong>')); ?>
				<ol>
					<?=Html::tag('li', Yii::t('frontend', 'Solicitudes')); ?>
					<?=Html::tag('li', Yii::t('frontend', 'Recibo')); ?>
					<?=Html::tag('li', Yii::t('frontend', 'Crear Recibo de Pago')); ?>
				</ol>
				<?=Html::tag('li', Yii::t('frontend', 'Si lo desea descargue la ayuda ') .
								Html::a(Yii::t('frontend', 'aquí'),
						   		Yii::$app->ayuda->getRutaAyuda(999, 'frontend') . 'CREAR RECIBO.pdf',
						   		[
						   			'target' => '_blank',
						   		])
						   	)
				 ?>
			</p>
		</div>
	</div>

	<div class="lista-impuesto">
		<?= $this->render('@frontend/views/planilla/consulta/lista-impuesto-planilla', [
													'model' => $model,
													'caption' => $caption,
													'subCaption' => $subCaption,
													'listaImpuesto' => $listaImpuesto,
													'collapseDeuda' => $collapseDeuda,
													'url' => $url,
    					]);
    	?>
	</div>
</div>