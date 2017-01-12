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
 *  @file solicitud-documento-consigando.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 18-07-2016
 *
 *  @view solicitud-documento-consigando.php
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
	use yii\web\View;


?>
<div class="row">
	<div class="row">
		<small><strong><?= Yii::t('backend', 'Documentos y/o Requisitos Consignados') ?></strong></small>
	</div>
	<div class="row">
	    <div class="solicitud-documento-consignado" id="solicitud-documento-consignado">
			<?= GridView::widget([
	                'id' => 'grid-lista-documento-consignado',
	                'dataProvider' => $dataProviderDocumento,
	                'headerRowOptions' => ['class' => 'success'],
	                'summary' => '',
	                'columns' => [
	                    ['class' => 'yii\grid\SerialColumn'],
	                    [
	                        'label' => 'ID.',
	                        'value' => 'id_documento',
	                    ],
	                    [
	                        'label' => 'Descripcion',
	                        'value' => function($model) {
	                        			return $model->descripcion;
	                        },
	                    ],
	                    // [
	                    //     'class' => 'yii\grid\CheckboxColumn',
	                    //     'name' => 'chk-documento-requisito',
	                    // ],
	                ]
	            ]);
	        ?>
		</div>
	</div>
</div>

