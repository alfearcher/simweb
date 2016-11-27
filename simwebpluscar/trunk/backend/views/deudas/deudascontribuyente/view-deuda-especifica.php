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
          //  'action' => ['/deudas/deudascontribuyente/deudas-contribuyente/generar-pdf-deuda-especifica'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);

?>

<div class="panel panel-primary" style="width:1000px;">
    <div class="panel-heading" style="height: 80px;">
       <p style="font-size:30px; margin-top:10px;">Deuda Especifica</p>
            </div>
                <div class="panel-body" >
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
                     'label' => 'Id. Objeto',
                     'value' => function($data){

                       return $data['id_objeto'];



                     },
                     ],

                          [
                     'label' => 'Informacion del Objeto',
                     'value' => function($data){

                       return $data['informacion'];



                     },
                     ],




                    [
                    'label' => 'Monto',
                    'value' => function($data){
                        return $data['monto'].' Bs.f';
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

        </div>

</div>
<?php ActiveForm::end() ?>
