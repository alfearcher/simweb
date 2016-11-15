<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;




/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


//die(var_dump($Hola));

$this->title = Yii::t('frontend', 'Deuda General');



//die(var_dump($st));


?>



 <?php $form = ActiveForm::begin([
           // 'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
            'action' => ['/deudas/deudascontribuyente/deudas-contribuyente/verificar-impuesto'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="inmuebles-index" style="width: 300px;">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
       //die(var_dump($dataProvider)),
      // 'st' => $st,
       // die(var_dump($st)),
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
            
           // 'id',
           // 'Calcomania',
                    [
                    'label' => 'Impuesto',
                    'value' => function($data){
                        return $data['impuesto'];
                    },
                    ],

                       [
                    'label' => 'Descripcion',
                    'value' => function($data){
                        return $data['descripcion'];
                    },
                    ],


                    [
                    'label' => 'Monto',
                    'value' => function($data){
                        return $data['monto'].' Bs.f';
                    },
                    ],

             

                       

                                  [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header'=> Yii::t('backend','View'),
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => json_encode(['impuesto' => $model['impuesto']]),
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('backend', 'View'),
                                                                            'style' => 'margin: 0 auto; display: block;',
                                                                        ]
                                                                    );
                                        },
                                    ],
                                ],
            
          

        ]
]);

 
    
    ?>



</div>
<?php ActiveForm::end() ?>