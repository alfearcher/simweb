<?php


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
            'method' => 'post',
            'action' => ['/presupuesto/cargarpresupuesto/registrar/cargar-presupuesto/verificar-ordenanza-presupuesto'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>

<div class="row" style="width: 70%;">
    <div class="info-solicitud">
        <div class="row">
            <h3><?= Html::encode('Seleccione la Ordenanza') ?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                       // die(var_dump($dataProvider)),
                      'columns'  => [
                
                 [

                    'label' =>" Nro de Presupuesto",

                   
                    'value'=> function($data){ 

                        return $data->nro_presupuesto;
                    }

                ],
    
         

                [

                    'label' =>"Año Impositivo",

                   
                    'value'=> function($data){ 

                        return $data->ano_impositivo;
                    }

                ],

                     [

                    'label' =>"Fecha Inicial",

                   
                    'value'=> function($data){ 

                        return $data->fecha_desde;
                    }

                ],

                        [

                    'label' =>"Fecha Final",

                   
                    'value'=> function($data){ 

                        return $data->fecha_hasta;
                    }

                ],

                 [

                    'label' =>"Descripcion",

                   
                    'value'=> function($data){ 
                     //   die(var_dump($data->nivelPresupuesto));

                        return $data->descripcion;
                    }

                ],

                   [

                    'label' =>"Observacion",

                   
                    'value'=> function($data){ 

                        return $data->observacion;
                    }

                ],

                  [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'=> Yii::t('backend','View'),
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('backend', 'Seleccionar'),
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
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:140px;']) ?>
    </p>
    </div>


  

    </div>
        </div>
    </div>
</div>