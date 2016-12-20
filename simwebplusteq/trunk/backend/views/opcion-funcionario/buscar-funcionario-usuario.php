<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\PreguntasSeguridad;

$this->title = 'Recuperar Password del Funcionario';
?>
 

<?php $form = ActiveForm::begin([
    'method' => 'post',
	'id' => 'formulario1',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
	'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-10">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= $this->title ?>
			</div>
			<div class="panel-body" >
				
						<div class="row">
                            <div class="col-sm-4"; style="margin-left:50px; ">
						    <?= $form->field($model, "cedula")->input("text")?>  
                            </div>
                        </div>

						<div class="row">
                            <div class="col-sm-4"; style="margin-left:40px; ">
                            <?= Html::submitButton(Yii::t('backend', 'Search'), ["class" => "btn btn-primary"]) ?>
                            </div>
						</div>
			</div>
		</div>
	</div>
<?php $form->end() ?>