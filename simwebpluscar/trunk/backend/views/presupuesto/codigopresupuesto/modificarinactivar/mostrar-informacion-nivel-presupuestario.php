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
 *  @file view-solicitud-inscripcion-propaganda.php
 *
 *  @author Manuel Zapata
 *
 *  @date 13-09-2016
 *
 *  @view view-solicitud-inscripcion-propaganda.php
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
  use kartik\icons\Icon;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\widgets\ActiveForm;
    use yii\web\View;
    use yii\widgets\DetailView;
    use yii\grid\GridView;
    use backend\models\vehiculo\VehiculoSearch;
    use frontend\models\propaganda\solicitudes\SlPropagandasForm;
    use common\models\propaganda\tarifaspropaganda\TarifasPropagandas;
    use common\models\propaganda\patrocinador\PropagandasPatrocinaodres;

    

?>


 <?php $form = ActiveForm::begin([
            'id' => 'form-datosNivelContable-inline',
            
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>

<div class="row" style="width: 70%;">
    <div class="info-solicitud">
        <div class="row">
            <h3><?= Html::encode('Seleccione el Codigo Contable') ?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                       // die(var_dump($dataProvider)),
                      'columns'  => [
                        
         

                [

                    'label' =>"Codigo",

                   
                    'value'=> function($data){ 

                        return $data->codigo;
                    }

                ],

                     [

                    'label' =>"Descripcion",

                   
                    'value'=> function($data){ 

                        return $data->descripcion;
                    }

                ],

                 [

                    'label' =>"Nivel Contable",

                   
                    'value'=> function($data){ 

                        return $data->nivelPresupuesto->descripcion;
                    }

                ],

                   [

                    'label' =>"Monto",

                   
                    'value'=> function($data){ 

                        return $data->monto;
                    }

                ],


                

                    
                    [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'=> Yii::t('backend','Modificar'),
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-pencil',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'btn-prueba',
                                                                            'title' => Yii::t('backend', 'View'),
                                                                            'style' => 'margin: 0 auto; display: block;',
                                                                            

                                                                        ]
                                                                       
                                                                    );
                                        },
                                    ],
                                ],

                                
                                [

                                    'class' => 'yii\grid\ActionColumn',
                                   
                                    'header'=> Yii::t('backend','Inactivar'),
                                    'template' => '{view}',
                                    'buttons' => [

                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-ban',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'idModificar',
                                                                            'title' => Yii::t('backend', 'View'),
                                                                            'style' => 'margin: 0 auto; display: block;',

                                                                        ]
                                                                    );
                                        },
                                    ],
                                ],
        ],
    ]); ?>

     <div class="row">
    <div class="col-sm-4">
    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/site/vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:140px;']) ?>
    </p>
    </div>


  

    </div>
        </div>
    </div>
</div>