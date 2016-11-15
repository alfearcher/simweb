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

$this->title = Yii::t('frontend', 'Deuda por Impuesto ');



//die(var_dump($st));


?>



 <?php $form = ActiveForm::begin([
           // 'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
            'action' => ['/deudas/deudascontribuyente/deudas-contribuyente/verificar-objeto-especifico'],
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
                    'label' => 'Id Impuesto',
                    'value' => function($data){
                        return $data['id_impuesto'];
                    },
                    ],
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
                    'label' => 'Año Impositivo',
                    'value' => function($data){
                        return $data['ano_impositivo'];
                    },

                    ],

                          [
                    'label' => 'Codigo Contable',
                    'value' => function($data){
                        return CodigosContables::getDescripcionCodigoContable($data['id_codigo']);
                    },
                    ],

                          [
                    'label' => 'Grupo Subnivel',
                    'value' => function($data){
                        return GrupoSubnivel::getGrupoSubnivel( $data['grupo_subnivel']);
                    },
                    ],

                          [
                    'label' => 'Codigo',
                    'value' => function($data){
                        return $data['codigo'];
                    },
                    ],

                          [
                    'label' => 'Concepto',
                    'value' => function($data){
                        return $data['concepto'];
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
                                                                            'value' => $model['id_impuesto'],
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