<?php

use yii\helpers\Html;

$this->title = Yii::t( 'backend', 'Update Propaganda ');
$this->params['breadcrumbs'][] = [ 'label' => Yii::t( 'backend','Propagandas' ), 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="propaganda-form-create">
    <?= $this->render( '_form', [ 'model' => $model ] )?>
</div>
