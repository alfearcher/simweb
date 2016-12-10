<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\registromaestro\TipoNaturaleza;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm */

?>
<?= $msg ?>
<div class="funcionario-calcomania-form-form">
    <?php $form = ActiveForm::begin([
            'id' => 'form-funcionario-calcomania-inline',
            'method' => 'post',
        ]);    
    ?>

    <div class="panel panel-primary" style="width:450px;margin-left:285px">
        <div class="panel-heading">
        <h1><?= Yii::t('backend', 'Search Details Officer') ?></h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-1" style="width:100px;">
                    <?= $form->field($model, 'naturaleza')->dropDownList(
                                                                ArrayHelper::map(TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->orderBy(' id_tipo_naturaleza DESC')->all(), 'siglas_tnaturaleza', 'siglas_tnaturaleza'),
                                                                [
                                                                'id'=> 'naturaleza',
                                                                ])->label(false);
                    ?>
                </div>
                <div class="col-md-1" style="width:150px;">
                    <?= $form->field($model, 'ci')->textInput(['maxlength' => 8])->label(false) ?>
                </div>                
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                </div>              
            </div>
        </div>
    </div>    

    <?php ActiveForm::end(); ?>

</div>