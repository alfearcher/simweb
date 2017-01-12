<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Apuestas licita Form',
]) . ' ' . $model->id_impuesto;

$this->title = Yii::t('backend', 'Update Lawful Bets');
?>
<div class="apuestas-licita-form-update">

   <?= $this->render( '_form', [ 'model' => $model, 'operacion' => $operacion, 'consulta_historico' => $consulta_historico ] )?>

</div>
