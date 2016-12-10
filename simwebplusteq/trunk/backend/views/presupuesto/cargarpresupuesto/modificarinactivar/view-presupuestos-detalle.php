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
            <h3><?= Html::encode('Seleccione Presupuesto que desee Modificar o Inactivar') ?></h3>
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                       // die(var_dump($dataProvider)),
                      'columns'  => [
                
                 [

                    'label' =>"Nro de Presupuesto",

                   
                    'value'=> function($data){ 

                        return $data->ordenanzaPresupuesto->nro_presupuesto;
                    }

                ],
    
         

                [

                    'label' =>"Codigo de Presupuesto",

                   
                    'value'=> function($data){ 

                        return $data->codigoPresupuesto->codigo;
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
                    'header'=>'modificar',
                    'template' => '{modificar}',
                    'buttons' => [
                        'modificar' => function ($url, $model, $key) {
                            
                                return Html::a('<center><span class= "fa fa-pencil"></span></center>',['verificar-id-presupuesto', 'value'=> $key]);
                            
                        },
                    ],
                ],

                                
                         [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=>'inactivar',
                    'template' => '{inactivar}',
                    'buttons' => [
                        'inactivar' => function ($url, $model, $key) {
                           
                                return Html::a('<center><span class= "fa fa-ban"></span></center>',['inactivar-presupuesto', 'value'=> $key]);
                        
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