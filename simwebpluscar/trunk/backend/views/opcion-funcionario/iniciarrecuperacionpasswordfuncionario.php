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
				<table class="table table-striped">
					<tr>
						<td><div class="col-lg-4">
						    <?= $form->field($model, "username")->input("text")?>  
                            </div>
						 </td>
				   </tr>
				   <tr>
						<td>  
                            <?= Html::submitButton("recuperar Password", ["class" => "btn btn-primary"]) ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
<?php $form->end() ?>