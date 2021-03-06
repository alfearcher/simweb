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
            
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>

<div class="row" style="width: 70%;">
    <div class="info-solicitud">
        <div class="row">
            <h3><?= Html::encode('Seleccione la tasa que desee modificar o inactivar') ?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                       // die(var_dump($dataProvider)),
                      'columns'  => [
                
                 [

                    'label' =>"Codigo Contable",

                   
                    'value'=> function($data){ 
                   //  die(var_dump($data['codigoContable'][0]->descripcion));

                        return $data['codigoContable'][0]->codigo.' -'.$data['codigoContable'][0]->descripcion;
                    }

                ],


                 [

                    'label' =>"Impuesto",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data['impuestos'][0]->descripcion;
                    }

                ],

                 [

                    'label' => "Año Impositivo",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data->ano_impositivo;
                    }

                ],


                      [

                    'label' => "Grupo SubNivel",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data['grupoSubNivel'][0]->descripcion;
                    }

                ],


                       [

                    'label' => "Codigo",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data->codigo;
                    }

                ],

                [

                    'label' => "Descripcion",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data->descripcion;
                    }

                ],

                    [

                    'label' => "Monto",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data->monto;
                    }

                ],


                       [

                    'label' => "Tipo Rango",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data['tipoRango'][0]->descripcion;
                    }

                ],


                       [

                    'label' => "Cantidad Unidad Tributaria",

                   
                    'value'=> function($data){ 
                  // die(var_dump($data['impuestos'][0]->descripcion));

                        return $data->cantidad_ut;
                    }

                ],


    
         

               

                

             [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'modificar',
                    'template' => '{modificar}',
                    'buttons' => [
                        'modificar' => function ($url, $model, $key) {
                            
                                return Html::a('<center><span class= "fa fa-pencil"></span></center>',['modificar-tasa', 'value'=> $key]);
                            
                        },
                    ],
                ],

                                
                         [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'inactivar',
                    'template' => '{inactivar}',
                    'buttons' => [
                        'inactivar' => function ($url, $model, $key) {
                           
                                return Html::a('<center><span class= "fa fa-ban"></span></center>',['inactivar-tasa', 'value'=> $key]);
                        
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