<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Create Lawful Bets');

?>
<div class="apuestas-licita-form-create">

   <?= $this->render( '_form', [ 'model' => $model, 'operacion' => $operacion ] )?>

</div>
