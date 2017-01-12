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
    <div class="container" style="width:390px">
        <div class="col-sm-10" style="width:390px">
            <div class="panel panel-primary">
                <div class="panel-heading">
                <h1><?= Yii::t('backend', 'Search vehicle data') ?></h1>
                </div>
                <div class="panel-body" style="width:350px">
                    <?php echo $this->render('_search', ['model' => $searchModel, 'visible' => 'si']); ?>
                </div>
            </div>
        </div>
    </div>    
</div>
