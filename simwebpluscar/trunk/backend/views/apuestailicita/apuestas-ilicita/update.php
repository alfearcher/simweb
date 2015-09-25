<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Apuestas Ilicita Form',
]) . ' ' . $model->id_impuesto;

$this->title = Yii::t('backend', 'Update Illicit Bets');
?>
<div class="apuestas-ilicita-form-update">

   <?= $this->render( '_form', [ 'model' => $model, 'operacion' => $operacion, 'consulta_historico' => $consulta_historico ] )?>

</div>
