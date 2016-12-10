<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;




/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


//die(var_dump($Hola));





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
<div class="panel panel-primary" style="width:450px;">
    <div class="panel-heading" style="height: 80px;">
       <p style="font-size:30px; margin-top:10px;">Deuda General</p>
            </div>
                <div class="panel-body" >

                        <div class="inmuebles-index" style="width: 400px; padding-top: 10px;">

                            
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
                    'contentOptions' => [
                        'style' => 'font-size: 90%;text-align:right;',
                    ],
                    'class' => 'yii\grid\ActionColumn',
                    'header'=> Yii::t('frontend', 'Monto'),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                                
                                
                                return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. Yii::$app->formatter->asDecimal($model['monto'], 2) .'</center></div>',
                                                [
                                                    'value' => json_encode(['impuesto' => $model['impuesto']]),
                                                    'id' => 'id-deuda-por-periodo',
                                                    'name' => 'id',
                                                    'class' => 'btn btn-default',
                                                    'title' => 'deuda '. $model['monto'],
                                                    'style' => 'text-align:right;',
                                                    
                                                ]
                                            );
                                },
                    ],
                ],
                                    
                                  

                                ],

                        ]);



                         
                            
                            ?>

                                <div class="row" style="padding-top: 0px;margin-top: -10px;background-color: #F1F1F1;">
                                <div class="col-sm-3" style="width: 45%;text-align: right;">
                                    <h3><strong><p>Total:</p></strong></h3>
                                </div>
                                <div class="col-sm-3" style="width: 55%;text-align: right;">
                                    <h3><strong><p><?=Html::encode(Yii::$app->formatter->asDecimal($total, 2))?></p></strong></h3>
                                </div>
                            </div>



</div>
</div>

</div>
<?php ActiveForm::end() ?>