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
 *  @file add-rubro-lista.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 19-10-2015
 *
 *  @view add-rubro-lista.php
 *  @brief vista que.
 *
 */

 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	//use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;


	/**
	*@var $this yii\web\View */

	// $this->title = Yii::t('backend', 'List of Category');

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

    $form = ActiveForm::begin([
    						'id' => 'lista-rubro-remove-form',
    						'enableClientValidation' => true,
 							'enableAjaxValidation' => true,
 							'enableClientScript' => true,
    ]);


	?>
	<div class="lista-rubro-agregado">
	    <?= GridView::widget([
        	'id' => 'grid-list-rubro-agregado',
            'dataProvider' => $dataProvider,
            //'filterModel' => $model,
            //'filterModel' => $searchModel,
            //'layout'=>"n{pager}\n{items}",
            //'layout'=>"{pager}{items}",

            //'headerRowOptions' => ['class' => 'success'],
            // 'rowOptions' => function($data) {
            //     if ( $data->inactivo == 1 ) {
            //         return ['class' => 'danger'];
            //     }
            // },
            'columns' => [
            		//['class' => 'yii\grid\CheckboxColumn'],
                	//['class' => 'yii\grid\SerialColumn'],
                	[
	                    'label' => 'ID.',
	                    'value' => 'id_rubro',
	                ],
	                [
	                    'label' => Yii::t('backend', 'Category'),
	                    'value' => 'rubro',
	                    //'attribute' => 'rubro',
	                ],
	                [
	                    'label' => Yii::t('backend', 'Description'),
	                    'value' => 'descripcion',
	                ],
	                [
	                    'label' => Yii::t('backend', 'Year'),
	                    'value' => 'ano_impositivo',
	                ],
	                [
                    	'class' => 'yii\grid\ActionColumn',
                    	'header'=> Yii::t('backend', 'Remove'),
                    	'template' => '{remove}',
                    	'buttons' => [
                    		'remove' => function ($url, $modelRamo, $key) {
                    						$url = Url::toRoute('remove-rubro');
                     						return Html::button('<center><span class= "fa fa-minus-square"></span></center>',[
                 																												'id' => 'btn-remove-rubro',
                 																												'name' => 'btn-remove-rubro',
                 																												'title' => Yii::t('backend', 'Remove Category'),
                 																												'onClick' => 'removeRubro("'. $url .'",' . $key .')'
                 																											]
                     									);
                     					}
                    	],
                	],
            ]
		]);?>
	</div>

<?php ActiveForm::end(); ?>

<?php $this->registerJs(
	"function removeRubro(url, id) {
		//alert(url + ' ' + id);
		var url2 = url + '&idRubro=' + id.toString();
		$.ajax({
			type: 'get',
			url: url2,
			data: $('.lista-rubro-remove-form').serialize(),
			success: function(data) {
						$('#lista-rubros-agregados').html('<p>' + data + '</p>');
			}
		});
		return false;
	}"
);?>