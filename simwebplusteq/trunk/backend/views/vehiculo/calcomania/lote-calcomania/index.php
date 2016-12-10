<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\vehiculo\calcomania\LoteCalcomaniaSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\vehiculo\calcomania\LoteCalcomaniaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<script type="text/javascript">
$(document).ready(function(){
    $btn = $('#boton1');
    
    $btn.on('click',function(e){
        e.preventDefault();
    
        $modal = $('#myModal');
        
        $modal.modal('show');
    });
});
</script>
<style type="text/css">
    .modal-content{
        margin-top: 150px;
    }
    .titleModal{
        color: #369;
    }
</style>
<div class="lote-calcomania-form-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
          <?= Yii::t('backend', 'Search') ?>
        </button>
        <!-- Fin del Button trigger modal -->

        <?= Html::a(Yii::t('backend', 'Create Lote Calcomania Form'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <!-- Botón para cerrar la ventana -->
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only"><?= Yii::t('backend', 'Close') ?></span>
                </button>
                <!-- Título de la ventana -->
                <h2 class="titleModal"><?= Yii::t('backend', 'Search') ?></h2>
            </div>
            <div class="modal-body">
            <p><?php echo $this->render('_search', ['model' => $searchModel, 'visible' => 'si']); ?></p>                
            </div>
        </div>
      </div>
    </div>
    <!-- Fin de Modal -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'captionOptions' => ['class' => 'success'],
        'rowOptions' => function($data) {
                            if ( $data->inactivo == 1 ) {
                                return ['class' => 'danger'];
                            }
                        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_lote_calcomania',
            'ano_impositivo',
            'rango_inicial',
            'rango_final',
            'observacion:ntext',
            ['label' => 'Estatus',
                                'value' => function($data) {
                                    return LoteCalcomaniaSearch::getEstatus($data->inactivo);
                                }
                            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
        ],
    ]); ?>

</div>
