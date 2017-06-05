<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\icons\Icon;
    use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Select the Stickers');

?>

<?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/vehiculo/calcomania/administrarlotecalcomania/administrar-lote-calcomania/verificar-calcomanias-ano'],
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

        'id_calcomania',
           // 'id_contribuyente',
            //'ano_inicio',
        'nro_calcomania',
            //'liquidado',
            // 'manzana_limite',
            // 'lote_1',
            // 'lote_2',
            // 'nivel',
            // 'lote_3',
            // 'av_calle_esq_dom',
            // 'casa_edf_qta_dom',
            // 'piso_nivel_no_dom',
            // 'apto_dom',
          //'rango_inicial',
            // 'medidor',
            // 'id_sim',
            // 'observacion:ntext',
            // 'inactivo',
            // 'catastro',
         // 'rango_final',
            // 'tipo_ejido',
            // 'propiedad_horizontal',
            // 'estado_catastro',
            // 'municipio_catastro',
            // 'parroquia_catastro',
            // 'ambito_catastro',
            // 'sector_catastro',
            // 'manzana_catastro',
            // 'parcela_catastro',
            // 'subparcela_catastro',
            // 'nivel_catastro',
            // 'unidad_catastro',

                [
                        'class' => 'yii\grid\CheckboxColumn',
                        'name' => 'chk-deshabilitar-calcomania',
                        'checkboxOptions' => [
                                'id' => 'id-chk-deshabilitar-calcomania',
                               
                                //'onClick' => 'alert("hola " + $(this).val());'
                                //$(this).is(":checked"), permite determinar si un checkbox esta tildado.
                        ],
                        'multiple' => true,
                    ],
        ],
    ]); ?>
        <div class="col-sm-5">
    
     <?= Html::submitButton("Submit", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;']) ?>

    </div>

    <p>
       
        <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
    </p>

    <div class="col-sm-2" style="float:right; color:red; font: comic sans ms">
   
    <p><?php echo $errorCheck ?></p>

   
    </div>

</div>
<?php ActiveForm::end() ?>