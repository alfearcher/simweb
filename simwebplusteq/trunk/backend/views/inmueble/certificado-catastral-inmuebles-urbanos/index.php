<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use kartik\icons\Icon;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\InmueblesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Property Urban');

?>
<div class="inmuebles-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
<?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'action'=> ['/inmueble/certificadocatastral/certificado-catastral-inmuebles-urbanos/view'],
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-vertical'],]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id_impuesto',
            'id_contribuyente',
            //'ano_inicio',
            'direccion',
            //'liquidado',
             'manzana_limite',
            // 'lote_1',
            // 'lote_2',
            // 'nivel',
            // 'lote_3',
            // 'av_calle_esq_dom',
            // 'casa_edf_qta_dom',
            // 'piso_nivel_no_dom',
            // 'apto_dom',
          'tlf_hab',
            // 'medidor',
            // 'id_sim',
            // 'observacion:ntext',
            // 'inactivo',
            // 'catastro',
          'id_habitante',
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

             ['class' => 'yii\grid\ActionColumn', 'template' => '{view}', 'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::submitButton('<div class="item-list" style="color: #337AB7;"><center>'. Icon::show('fa fa-thumbs-up',['class' => 'fa-1x'], Icon::FA) .'</center></div>',
                                                                        [
                                                                            'value' => $key,
                                                                            'name' => 'id',
                                                                            'title' => Yii::t('frontend', 'View'),
                                                                            'style' => 'margin: 0 auto; display: block;',

                                                                        ]
                                                                    );
                                        },
                                    ],
            ],
        ],
    ]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Register Property Urban'), ['inmueble/inscripcion-inmuebles-urbanos/inscripcion-inmuebles-urbanos'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger']) ?>
    </p>

</div>
<?//= Html::endForm();?>
<?php ActiveForm::end(); ?> 