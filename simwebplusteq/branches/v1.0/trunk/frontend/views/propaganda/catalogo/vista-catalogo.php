<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use backend\models\propaganda\Propaganda;
use common\models\propaganda\tarifaspropaganda\TarifasPropagandas;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Advertising Catalogue');

?>



 <?php $form = ActiveForm::begin([
            'id' => 'form-datosPropaganda-inline',
            'method' => 'post',
           // 'action' => ['/propaganda/desincorporarpropaganda/desincorporar-propaganda/verificar-desincorporacion'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="catalogo-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

        'id_ordenanza',


           
        [
        'label' => 'Clase Propaganda',

        'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
            
            function($data){

                return $data->descripcionClasePropaganda->descripcion;
            }
     
         ],

               [
        'label' => 'Tipo Propaganda',

        'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
            
            function($data){

                return $data->descripcionTipoPropaganda->descripcion;
            }
     
         ],

                [
        'label' => 'Uso Propaganda',

        'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
            
            function($data){

                return $data->descripcionUsoPropaganda->descripcion;
            }
     
         ],

        [
        'label' => 'Cigarrillos',
        'format' => 'raw',
        
        'value' => function($data){

                if($data->cigarro == 0){

                return Html::tag('strong', Html::tag('h3',
                                                    'NO',
                        ['class' => 'label label-danger']));
                }else{

                return Html::tag('strong', Html::tag('h3',
                                                    'SI',
                        ['class' => 'label label-primary']));
                }
        }
        ],

        [
        'label' => 'Alcohol',
        'format' => 'raw',
        
        'value' => function($data){

                if($data->alcohol == 0){

                return Html::tag('strong', Html::tag('h3',
                                                    'NO',
                        ['class' => 'label label-danger']));
                }else{

                return Html::tag('strong', Html::tag('h3',
                                                    'SI',
                        ['class' => 'label label-primary']));
                }
        }
        ],


             [
        'label' => 'Idioma',
        'format' => 'raw',
        
        'value' => function($data){

                if($data->idioma == 0){

                return Html::tag('strong', Html::tag('h3',
                                                    'NO',
                        ['class' => 'label label-danger']));
                }else{

                return Html::tag('strong', Html::tag('h3',
                                                    'SI',
                        ['class' => 'label label-primary']));
                }
        }
        ],

        'monto_aplicar',

        'monto_adicional',

        'monto_deduccion',


        [ 
        'label' => 'Tipo Monto',


        'value' => //$search->getDescripcionUsoVehiculo($model->uso_vehiculo),//function($model){
            
            function($data){

                return $data->descripcionTipoMonto->descripcion;
            }
     
         ],

        







                                    
            //  ],
           // 'id_contribuyente',
            //'ano_inicio',
        
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

          
        ],
    ]); ?>



</div>
<?php ActiveForm::end() ?>