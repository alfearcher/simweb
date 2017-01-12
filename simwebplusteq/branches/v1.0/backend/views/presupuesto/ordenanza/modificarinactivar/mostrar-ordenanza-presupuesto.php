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
                    'header'=>'modificar',
                    'template' => '{modificar}',
                    'buttons' => [
                        'modificar' => function ($url, $model, $key) {
                            
                                return Html::a('<center><span class= "fa fa-pencil"></span></center>',['verificar-ordenanza-presupuesto', 'value'=> $key]);
                            
                        },
                    ],
                ],

                                
                         [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'inactivar',
                    'template' => '{inactivar}',
                    'buttons' => [
                        'inactivar' => function ($url, $model, $key) {
                           
                                return Html::a('<center><span class= "fa fa-ban"></span></center>',['inactivar-ordenanza-presupuesto', 'value'=> $key]);
                        
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