<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;










?>



 <?php $form = ActiveForm::begin([

            'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
            'action' => ['/propaganda/patrocinador/asignar-patrocinador-propaganda/asignar-patrocinador-natural'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>
    <div class="row">
    <div class="col-sm-4">
     <h2><?= Html::encode('Datos del Patrocinador') ?></h2>


    <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'summary' => '',
      
        'columns' => [

          
        'naturaleza',
            'cedula',
             'nombres',
             'apellidos',
             'id_contribuyente',

        ],
    ]); ?>
   

  
</div>
</div>

<div class="inmuebles-index" style="width: 500px;">

    <h2><?= Html::encode('Datos Propagandas') ?></h2>
    <?php

    echo GridView::widget([
        'dataProvider' => $provider,
        'summary' => '',

        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            
                     'id',
           // 'Calcomania',
                    [
                    'label' => 'Propaganda',
                    'value' => function($data){
                        return $data['Propaganda'];
                    },
                    ],
            

        ]
]);

 
    
    ?>

   <div class="row">
    <div class="col-sm-4">
    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger', 'style' => 'height:30px;width:130px; margin-left:200px;']) ?>
    </p>
    </div>

    <div class="col-sm-5" style="margin-left: -200px;">
    
     <?= Html::submitButton("Submit", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;margin-left:80px;']) ?>

    </div>
  
    
    </div>

</div>
<?php ActiveForm::end() ?>