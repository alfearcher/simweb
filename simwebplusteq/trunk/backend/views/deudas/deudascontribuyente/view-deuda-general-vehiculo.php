<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
use yii\widgets\ActiveForm;
use common\models\presupuesto\codigopresupuesto\CodigosContables;
use common\models\tasas\GrupoSubnivel;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


//die(var_dump($Hola));





//die(var_dump($st));


?>



 <?php $form = ActiveForm::begin([
           // 'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
            'action' => ['/deudas/deudascontribuyente/deudas-contribuyente/view-deuda-especifica-por-objeto'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>
<div class="panel panel-primary" style="width:460px;">
    <div class="panel-heading" style="height: 80px;">
       <p style="font-size:30px; margin-top:10px;">Deuda Por Impuesto</p>
            </div>
                <div class="panel-body" >

<div class="deudas-index" style="width: 400px;">

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
                
                    
                     // [
                     // 'label' => 'Impuesto',
                     // 'value' => function($data){
                     //     return $data['impuesto'];
                     // },
                     // ],

                    [
                     'label' => 'Descripcion',
                     'value' => function($data){
                        
                        $t = ($data['tipo'] == 'periodo=0') ? ' (tasa)' : '';

                            
                            
                             return  $data['descripcion'].$t;

                            

                     },
                     ],

              

                    // [
                    // 'label' => 'Periodo',
                    // 'value' => function($data){
                    //     if( $data['tipo'] == 'periodo>0'){

                    //         return 'Vehiculo';
                    //     }else{
                    //         return 'Vehiculo(tasa)';
                    //     } ;
                    // },
                    // ],

                    //       [
                    // 'label' => 'Año Impositivo',
                    // 'value' => function($data){
                    //     return $data['ano_impositivo'];
                    // },

                    // ],

                    //       [
                    // 'label' => 'Codigo Contable',
                    // 'value' => function($data){
                    //     return CodigosContables::getDescripcionCodigoContable($data['id_codigo']);
                    // },
                    // ],

                    //       [
                    // 'label' => 'Grupo Subnivel',
                    // 'value' => function($data){
                    //     return GrupoSubnivel::getGrupoSubnivel( $data['grupo_subnivel']);
                    // },
                    // ],

                    //       [
                    // 'label' => 'Codigo',
                    // 'value' => function($data){
                    //     return $data['codigo'];
                    // },
                    // ],

                    //       [
                    // 'label' => 'Concepto',
                    // 'value' => function($data){
                    //     return $data['concepto'];
                    // },
                    // ],

        [
                    'contentOptions' => [
                        'style' => 'font-size: 90%;',
                    ],
                    'class' => 'yii\grid\ActionColumn',
                    'header'=> Yii::t('frontend', 'Monto'),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                                
                                
                                return Html::submitButton('<div class="item-list" style="color: #000000;"><center>'. Yii::$app->formatter->asDecimal($model['monto'], 2) .'</center></div>',
                                                [
                                                    'value' => json_encode(['impuesto' => $model['impuesto'] , 'id_impuesto' => $model['id_impuesto'] , 'tipo' => $model['tipo']]),
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
             

                       

                        
            
          

        ]
]);

 
    
    ?>


</div>
</div>

</div>
<?php ActiveForm::end() ?>