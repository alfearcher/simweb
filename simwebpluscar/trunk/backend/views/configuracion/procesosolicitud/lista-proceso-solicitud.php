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
 *  @file lista-proceso-solicitud.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 26-02-2016
 *
 *  @view lista-proceso-solicitud.php
 *  @brief
 *
 */

 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	//use yii\helpers\ArrayHelper;
	//use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;

?>
<div class="row" style="padding-top: 15px;">
    <div class="proceso-generado">
        <?= GridView::widget([
                'id' => 'grid-lista-proceso',
                'dataProvider' => $dataProvider,
                'summary' => '',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'ID.',
                        'value' => 'id_proceso',
                    ],
                    [
                        'label' => 'Descripcion',
                        'value' => 'descripcion',
                    ],
                    [
                        'class' => 'yii\data\DataColumn',
                        'attribute' => 'ejecutar_en',
                        'filter' => [1 => "1" , 2 => "2"],
                    ],
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-proceso-generado',
                    ],
                ]
            ]);
        ?>
    </div>
</div>