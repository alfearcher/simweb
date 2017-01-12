<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use common\models\deuda\Solvente;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Select your Vehicle');


?>



 <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/vehiculo/calcomania/asignarcalcomaniacontribuyente/asignar-calcomania-contribuyente/verificar-vehiculo'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="inmuebles-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

       
           'id_vehiculo',
           'placa',
            'marca',
            'modelo',
            [
                'label' => 'Solvente',

                'value' => function($model){

                    $buscarSolvencia = new Solvente();
                    $buscarSolvencia->setImpuesto(3);
                    $buscarSolvencia->setIdImpuesto($model->id_vehiculo);

                    $r = $buscarSolvencia->determinarSolvencia();
                        if($r == true){
                            return 'Si';
                        }else{
                            return 'No';
                        }
                }
            ],
       
            //'liquidado',
            // 'manzana_limite',
            // 'lote_1',
            // 'lote_2',
            // 'nivel',
            // 'lote_3',
            // 'av_calle_esq_dom',
            // 'casa_edf_qta_dom',
            // 'piso_nivel_no_dom',
            // 'apto_dom',
         
            // 'medidor',
            // 'id_sim',
            // 'observacion:ntext',
            // 'inactivo',
            // 'catastro',
        
            // 'tipo_ejido',
            // 'propiedad_horizontal',
            // 'estado_catastro',
            // 'municipio_catastro',
            // 'parroquia_catastro',
            // 'ambito_catastro',
            // 'sector_catastro',
            // 'manzana_catastro',
            // 'parcela_catastro',
            // 'subparcela_catastro',
            // 'nivel_catastro',
            // 'unidad_catastro',

             [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'=> Yii::t('backend','View'),
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                                 $buscarSolvencia = new Solvente();
                                                        $buscarSolvencia->setImpuesto(3);
                                                        $buscarSolvencia->setIdImpuesto($model->id_vehiculo);

                                                        $r = $buscarSolvencia->determinarSolvencia();
                                                            if($r == true){
                                                                
                                                                  return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('backend', 'View'),
                                                                            'style' => 'margin: 0 auto; display: block;',
                                                                            //'disable' => $r,
                                                                        ]
                                                                    );
                                                            }else{
                                                                return '<span><center>---</span></center>'; 
                                                            }

                                          
                                        },
                                    ],
                                ],
        ],
    ]); ?>

    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
    </p>

</div>
<?php ActiveForm::end() ?>