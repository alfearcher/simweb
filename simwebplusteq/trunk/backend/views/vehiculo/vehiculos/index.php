<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VehiculosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Vehiculos Forms');
$this->params['breadcrumbs'][] = $this->title;
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

<div class="vehiculos-form-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
          <?= Yii::t('backend', 'Search') ?>
        </button>
        <!-- Fin del Button trigger modal -->
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
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id_vehiculo',
                    'id_contribuyente',
                    'placa',
                    'marca',
                    'modelo',
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'],             
                ],
            ]); ?>

</div>
