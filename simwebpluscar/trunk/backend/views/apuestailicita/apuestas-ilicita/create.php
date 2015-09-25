<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Create Illicit Bets');

?>
<div class="apuestas-ilicita-form-create">

   <?= $this->render( '_form', [ 'model' => $model, 'operacion' => $operacion ] )?>

</div>
