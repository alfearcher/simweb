<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;




/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


//die(var_dump($Hola));

$this->title = Yii::t('frontend', 'Select the Stickers');



?>



 <?php $form = ActiveForm::begin([
            'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
            'action' => ['/vehiculo/calcomania/administrarcalcomaniafuncionario/administrar-calcomania-funcionario/verificar-calcomania'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="inmuebles-index" style="width: 300px;">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php

    echo GridView::widget([
        'dataProvider' => $provider,

        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            
           // 'id',
           // 'Calcomania',
                    [
                    'label' => 'Calcomania',
                    'value' => function($data){
                        return $data['Calcomania'];
                    },
                    ],
            
                    [

                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-seleccionar-calcomania',
                        'checkboxOptions' => [
                        'id' => 'id-chk-seleccionar-calcomania',
                               
                                //'onClick' => 'alert("hola " + $(this).val());'
                                //$(this).is(":checked"), permite determinar si un checkbox esta tildado.
                        ],

                        'multiple' => true,
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
  
     <div class="col-sm-3" style="margin-left:170px;color:red; font: comic sans ms">
   
    <p><?php echo $errorCheck ?></p>

   
    </div>
    </div>

</div>
<?php ActiveForm::end() ?>