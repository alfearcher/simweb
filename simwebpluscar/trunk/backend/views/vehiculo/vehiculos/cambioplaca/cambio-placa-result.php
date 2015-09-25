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
 *  @file cambio-placa-result.php
 *  
 *  @author Hansel Jose Colmenarez Guevara
 * 
 *  @date 23/07/2015
 * 
 *  @class Vehiculos
 *  @brief Vista del listado de Vehiculos asociados a la palca que se consulto anteriormente en la visat cambio-placa
*   @property
 *
 *  
 *  @method
 *    
 *  @inherits
 *  
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VehiculosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Vehiculos Forms');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="vehiculos-form-index">
    <h1><?= Html::encode($this->title) ?></h1>

   <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id_vehiculo',
                'id_contribuyente',
                'placa',
                'marca',
                'modelo',
                'color',
                'ano_vehiculo',

                [
                    'header'=>'Update',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'buttons' => [                        
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['placa-update','idVehiculo' => $key]);
                        },
                    ],
                ],

                [
                    'header'=>'View',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view-final-vehiculo','idVehiculo' => $key]);
                        },
                    ],
                ],
            ],
        ]); 
    ?>
</div>
