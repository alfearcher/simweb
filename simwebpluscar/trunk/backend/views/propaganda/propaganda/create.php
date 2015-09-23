<?php

use yii\helpers\Html;

$this->title = Yii::t('backend', 'Create Propaganda');
?>

<div class="propaganda-form-create">
    <?= $this->render( '_form', [ 'model' => $model ] )?>
</div>
