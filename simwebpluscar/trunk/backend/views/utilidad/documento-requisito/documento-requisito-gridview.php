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
 *  @file documento-requisito-gridview.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 23-02-2016
 *
 *  @view documento-requisito-gridview.php
 *  @brief
 *
 */

 	//use kartik\icons\Icon;
    use yii\web\Response;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;

?>
<div class="row" style="padding-top: 15px;">
    <div class="lista-documento-requisito">
        <?= GridView::widget([
                'id' => 'grid-lista-documento',
                'dataProvider' => $dataProvider,
                'summary' => '',
                'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => 'ID.',
                            'value' => 'id_documento',
                        ],
                        [
                            'label' => 'Descripcion',
                            'value' => 'descripcion',
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'chk-documento-requisito',
                        ],
                ]
            ]);
        ?>
    </div>
</div>