<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\db\DataReader;
use yii\data\ActiveDataProvider;

$this->title =Yii::t( 'backend', 'Propagandas' );
?>
<script type="text/javascript">

    function recargar_pagina(){

    
               btn.disabled = false;
    
}
    function seleccion(){

    if (document.getElementById('checkbox').checked == 1)
    {     
               btn.disabled = false;
    } else { 
               btn.disabled = true; 
    }
}
/*function selec(){

if (btn.disabled != !this.checked ){
        btn.disabled = false;
    }else{
        btn.disabled = false;
    }
}*/
</script>

<body onload="recargar_pagina()"/>
<div class="propaganda-form-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::beginForm( [ 'propaganda/propaganda/disable' ], 'post' );?>
        
        <?= Html::submitButton( 'Inactive', [ 'class' => 'btn btn-info', 'name' => 'btn', 'id' => "btn", 'value' => 'inactive', 'disabled' => 'disabled' ] );?>
    </p>

    <?= GridView::widget([
           'dataProvider' => $dataProvider,
           'filterModel' =>  $searchModel,
           'columns' =>    [
                                [ 'class' => 'yii\grid\CheckboxColumn' ],
                                [ 'class' => 'yii\grid\SerialColumn' ],
                                'id_impuesto',
                                'ano_impositivo',
                                'contribuyenteName',
                                'usoName',
                                'claseName',
                                'inactivoName',            
                                'fecha_guardado',
                            ]
    ] ); ?>
    <?= Html::endForm();?> 
</div>

