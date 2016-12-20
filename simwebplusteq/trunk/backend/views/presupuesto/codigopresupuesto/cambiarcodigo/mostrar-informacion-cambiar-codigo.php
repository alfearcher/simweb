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
    use backend\models\presupuesto\codigopresupuesto\modificarinactivar\ModificarCodigoPresupuestarioForm;

    

?>


 <?php $form = ActiveForm::begin([
            'id' => 'form-datosNivelContable-inline',
            'method' => 'post',
            'action' => ['/presupuesto/codigopresupuesto/cambiarcodigo/cambiar-codigo-presupuestario/verificar-id-codigo'],
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

                    'label' =>" ID Codigo",

                   
                    'value'=> function($data){ 

                        return $data->id_codigo;
                    }

                ],
    
         

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
                    
                        return ModificarCodigoPresupuestarioForm::buscarNivelPresupuesto($data->nivel_contable);
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
                                    'header'=> Yii::t('backend','View'),
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
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
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:140px;']) ?>
    </p>
    </div>


  

    </div>
        </div>
    </div>
</div>