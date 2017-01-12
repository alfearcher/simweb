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
 *  @file datos-contribuyente.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 28-08-2016
 *
 *  @view datos-contribuyente.php
 *  @brief vista
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
	use yii\helpers\Url;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use kartik\icons\Icon;
	use yii\web\View;
	use yii\bootstrap\Modal;
	use backend\controllers\menu\MenuController;
	use yii\widgets\Pjax;
	use yii\widgets\DetailView;

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

?>

<?php if ( $model['tipo_naturaleza'] == 0 ) { ?>
	<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				[
					'label' => Yii::t('frontend', 'Id. Taxpayer'),
					'value' => $model['id_contribuyente'],
				],
				[
					'label' => Yii::t('frontend', 'DNI'),
					'value' => $model['naturaleza'] . '-'. $model['cedula'],
				],
				[
					'label' => Yii::t('frontend', 'Taxpayer'),
					'value' => $model['apellidos'] . ' ' . $model['nombres'],
				],
				[
					'label' => Yii::t('frontend', 'Addrres'),
					'value' => $model['domicilio_fiscal'],
				],
				[
					'label' => Yii::t('frontend', 'Type'),
					'value' => $model->getTipoNaturalezaDescripcion($model->tipo_naturaleza),
				],
				[
					'label' => Yii::t('frontend', 'Condition'),
					'value' => $model->getActivoInctivoDescripcion($model->inactivo),
				],
			],
		])
	?>

<?php } elseif ( $model['tipo_naturaleza'] == 1 ) { ?>
	<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				[
					'label' => Yii::t('frontend', 'Id. Taxpayer'),
					'value' => $model['id_contribuyente'],
				],
				[
					'label' => Yii::t('frontend', 'DNI'),
					'value' => $model['naturaleza'] . '-'. $model['cedula'] . '-'. $model['tipo'],
				],
				[
					'label' => Yii::t('frontend', 'Taxpayer'),
					'value' => $model['razon_social'],
				],
				[
					'label' => Yii::t('frontend', 'Addrres'),
					'value' => $model['domicilio_fiscal'],
				],
				[
					'label' => Yii::t('frontend', 'Type'),
					'value' => $model->getTipoNaturalezaDescripcion($model->tipo_naturaleza),
				],
				[
					'label' => Yii::t('frontend', 'Condition'),
					'value' => $model->getActivoInctivoDescripcion($model->inactivo),
				],
				[
					'label' => Yii::t('frontend', 'License'),
					'value' => $model['id_sim'],
				],
				[
					'format'=>['date', 'dd-MM-yyyy'],
					'label' => Yii::t('frontend', 'Begin Date'),
					'value' => $model['fecha_inicio'],
				],
			],
		])
	?>
<?php } ?>