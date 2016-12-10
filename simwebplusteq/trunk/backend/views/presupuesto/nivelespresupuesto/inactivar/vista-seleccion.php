<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Select your Countable Level');

?>



 <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/presupuesto/nivelespresupuesto/inactivar/inactivar-niveles-presupuesto/verificar-nivel-contable'],
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


           // 'id_contribuyente',
            'nivel_contable',
            'descripcion',
            
                    [
            'label' => 'Ingreso Propio',
            'format' => 'raw',
            
            'value' => function($data){

                if($data->ingreso_propio == 0){

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
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-inactivar-nivel-contable',
                        'checkboxOptions' => [
                                'id' => 'id-chk-inactivar-nivel-contable',
                               
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