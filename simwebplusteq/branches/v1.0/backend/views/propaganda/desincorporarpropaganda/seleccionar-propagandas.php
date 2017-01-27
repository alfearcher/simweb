<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use backend\models\propaganda\Propaganda;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Select your Advertising');

?>



 <?php $form = ActiveForm::begin([
            'id' => 'form-datosPropaganda-inline',
            'method' => 'post',
            'action' => ['/propaganda/desincorporarpropaganda/desincorporar-propaganda/verificar-desincorporacion'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="propaganda-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

        'id_impuesto',


            [
                 'label' => 'Tipo de Propaganda',

                'value' => 
                
                function($data){

                    return $data->tipoPropaganda->descripcion;
                }
                
                                    
             ],
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

             [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-desincorporar-propaganda',
                        'checkboxOptions' => [
                                'id' => 'id-chk-desincorporar-propaganda',
                               
                                //'onClick' => 'alert("hola " + $(this).val());'
                                //$(this).is(":checked"), permite determinar si un checkbox esta tildado.
                        ],
                        'multiple' => true,
                    ],
        ],
    ]); ?>

     <div class="row">
    <div class="col-sm-4">
    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:140px;']) ?>
    </p>
    </div>

    <div class="col-sm-5" style="margin-left: -200px;">
    
     <?= Html::submitButton("Submit", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;']) ?>

    </div>
  
    <div class="col-sm-2" style="float:right; color:red; font: comic sans ms">
   
    <p><?php echo $errorCheck ?></p>

   
    </div>
    </div>

</div>
<?php ActiveForm::end() ?>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use backend\models\propaganda\Propaganda;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Select your Advertising');

?>



 <?php $form = ActiveForm::begin([
            'id' => 'form-datosPropaganda-inline',
            'method' => 'post',
            'action' => ['/propaganda/desincorporarpropaganda/desincorporar-propaganda/verificar-desincorporacion'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="propaganda-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

        'id_impuesto',


            [
                 'label' => 'Tipo de Propaganda',

                'value' => 
                
                function($data){

                    return $data->tipoPropaganda->descripcion;
                }
                
                                    
             ],
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

             [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-desincorporar-propaganda',
                        'checkboxOptions' => [
                                'id' => 'id-chk-desincorporar-propaganda',
                               
                                //'onClick' => 'alert("hola " + $(this).val());'
                                //$(this).is(":checked"), permite determinar si un checkbox esta tildado.
                        ],
                        'multiple' => true,
                    ],
        ],
    ]); ?>

     <div class="row">
    <div class="col-sm-4">
    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:140px;']) ?>
    </p>
    </div>

    <div class="col-sm-5" style="margin-left: -200px;">
    
     <?= Html::submitButton("Submit", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;']) ?>

    </div>
  
    <div class="col-sm-2" style="float:right; color:red; font: comic sans ms">
   
    <p><?php echo $errorCheck ?></p>

   
    </div>
    </div>

</div>
<?php ActiveForm::end() ?>
