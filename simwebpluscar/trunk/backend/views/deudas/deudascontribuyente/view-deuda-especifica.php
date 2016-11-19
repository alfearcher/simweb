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

$this->title = Yii::t('frontend', 'Detalle de la Deuda');



//die(var_dump($st));


?>



 <?php $form = ActiveForm::begin([
           // 'id' => 'id-chk-seleccionar-calcomania',
            'method' => 'post',
          //  'action' => ['/deudas/deudascontribuyente/deudas-contribuyente/generar-pdf-deuda-especifica'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>


<div class="deudas-index" style="width: 600px;">

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
                     'label' => 'Año Impositivo',
                     'value' => function($data){
                         return $data['ano_impositivo'];
                     },
                     ],

                    [
                     'label' => 'Id. Objeto',
                     'value' => function($data){

                       return $data['id_impuesto'];



                     },
                     ],

                    [
                     'label' => 'Planilla',
                     'value' => function($data){

                       return $data['planilla'];



                     },
                     ],

                          [
                     'label' => 'Informacion del Objeto',
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
                    'label' => 'Descuento',
                    'value' => function($data){
                        return $data['descuento'].' Bs.f';
                    },
                    ],

                    [
                    'label' => 'Recargo',
                    'value' => function($data){
                        return $data['recargo'].' Bs.f';
                    },
                    ],

                    [
                    'label' => 'Monto Reconocimiento',
                    'value' => function($data){
                        return $data['monto_reconocimiento'].' Bs.f';
                    },
                    ],

                       [
                    'label' => 'Monto Total',
                    'value' => function($data){
                        return $data['monto_total'].' Bs.f';
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















        ]
]);



    ?>



   <div class="col-sm-5" >

     <?= Html::a('Generar Reporte',['/deudas/deudascontribuyente/deudas-contribuyente/generar-pdf-deuda-especifica'],
        [
         'class'=>'btn btn-success',
         'target'=> '_blank',
         'data-toggle'=>'tooltip',
         //'value' => 'hola',
        'title'=>'Generate the pdf']);


         ?>

    </div>

</div>
<?php ActiveForm::end() ?>
