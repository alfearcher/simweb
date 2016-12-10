<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Inmuebles */

$this->title = Yii::t('backend', 'View Property Urban. Id Tax: '.$model->id_impuesto);

?>
<div class="inmuebles-view">

    

    <p>
        <?= Html::a(Yii::t('backend', 'Update other data of property'), ['inmueble/cambio-otros-datos-inmuebles-urbanos/cambio-otros-datos-inmuebles', 'id_impuesto' => $model->id_impuesto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Horizontal property change'), ['inmueble/cambio-a-propiedad-horizontal-inmuebles-urbanos/cambio-a-propiedad-horizontal-inmuebles', 'id_impuesto' => $model->id_impuesto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Change of cadastral number'), ['inmueble/cambio-numero-catastral-inmuebles-urbanos/cambio-de-numero-catastral-inmuebles', 'id_impuesto' => $model->id_impuesto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Property owner change'), ['inmueble/cambio-propietario-inmuebles-urbanos/cambio-propietario-inmuebles','id_contribuyente' =>$model->id_contribuyente  ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Cadastral urban real estate appraised value'), ['inmueble/avaluo-catastral-inmuebles-urbanos/avaluo-catastral-inmuebles','id_contribuyente' =>$model->id_contribuyente  ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Urban property integration'), ['inmueble/integracion-inmuebles-urbanos/integracion-inmuebles','id_contribuyente' =>$model->id_contribuyente  ], ['class' => 'btn btn-primary']) ?> 
        <?= Html::a(Yii::t('backend', 'Urban decay properties'), ['inmueble/desintegracion-inmuebles-urbanos/desintegracion-inmuebles','id_contribuyente' =>$model->id_contribuyente  ], ['class' => 'btn btn-primary']) ?>      
    </p> 

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [ 
            'id_impuesto',
            'id_contribuyente',
            'catastro',
            'id_habitante',
            'ano_inicio',
            'direccion',
            //'liquidado',
            //'manzana_limite',
            //'lote_1',
            //'lote_2',
            //'nivel',
            //'lote_3',
            //'av_calle_esq_dom',
            //'casa_edf_qta_dom',
            //'piso_nivel_no_dom',
            //'apto_dom',
            'tlf_hab',
            //'medidor',
            //'id_sim',
            'observacion:ntext',
            'inactivo',
            //'tipo_ejido',
            //'propiedad_horizontal',
            //'estado_catastro',
            //'municipio_catastro',
            //'parroquia_catastro',
            //'ambito_catastro',
            //'sector_catastro',
            //'manzana_catastro',
            //'parcela_catastro',
            //'subparcela_catastro',
            //'nivel_catastro',
            //'unidad_catastro',
        ], 
    ]) ?>

</div>


<!-- <? /*= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id_impuesto], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ], 
        ]) */ ?> -->