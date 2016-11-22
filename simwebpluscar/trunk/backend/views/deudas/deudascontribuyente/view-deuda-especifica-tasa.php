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

$this->title = Yii::t('frontend', 'Deuda Especifica ');



//die(var_dump($st));


?>



 <?php $form = ActiveForm::begin([
           // 'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
          //  'action' => ['/deudas/deudascontribuyente/deudas-contribuyente/verificar-objeto-especifico'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>

<div class="panel panel-primary" style="width:650px;">
    <div class="panel-heading" style="height: 80px;">
       <p style="font-size:30px; margin-top:10px;">Detalle de la Tasa</p>
            </div>
                <div class="panel-body" >
<div class="deudas-index" style="width: 600px;">

   
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
                    'label' => 'Planilla',
                    'value' => function($data){
                        return $data['planilla'];
                    },
                    ],
                    
                     [
                     'label' => 'Impuesto',
                     'value' => function($data){
                         return $data['impuesto'];
                     },
                     ],

                    [
                     'label' => 'Año Impositivo',
                     'value' => function($data){
                         return $data['ano_impositivo'];
                     },
                     ],

                          [
                    'label' => 'Periodo',
                    'value' => function($data){
                        return $data['periodo'];
                    },
                    ],

                         [
                    'label' => 'Unidad',
                    'value' => function($data){
                        return $data['unidad'];
                    },
                    ],

                       [
                    'label' => 'Monto',
                    'value' => function($data){
                        return $data['monto'].' Bs.f';
                    },
                    ],

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






                 
             

                       

                        
            
          

        ]
]);

 
    
    ?>


   <div class="col-sm-5" >
    
     <?= Html::a('Generar Reporte',['/deudas/deudascontribuyente/deudas-contribuyente/generar-pdf-deuda-tasa'],
        [
         'class'=>'btn btn-success',
         'target'=> '_blank',
         'data-toggle'=>'tooltip',
         //'value' => 'hola',
        'title'=>'Generate the pdf']); 


         ?> 

    </div>

   </div>
      </div>

</div>
<?php ActiveForm::end() ?>