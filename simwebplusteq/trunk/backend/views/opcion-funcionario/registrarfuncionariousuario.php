<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
// 
$this->title = 'Registro Funcionario';
$this->params['breadcrumbs'][] = $this->title;
?>
 



<?php $form = ActiveForm::begin([
    'method' => 'post',
	'id' => 'formulario',
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
						<td><div class="col-lg-3">
						<?= $form->field($model, 'username')->textInput(['maxlength' => true,'value'=>$datos,'style' => 'width:100px;', 'onload'=> true]) ?>
							</div>																		    
                        </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-5">
                            <?= $form->field($model, "email")->input("email") ?> 
							</div>
						</td>
				   </tr>

				   <tr>
						<td><div class="col-lg-3">
                            <?= $form->field($model, "password")->input("password") ?> 
							</div>
						</td>
					</tr>

					<tr>
						<td><div class="col-lg-3">
                            <?= $form->field($model, "password_repeat")->input("password") ?>
							</div> 
						</td>
					</tr>

					<tr>
						<td> 
                            <?= Html::submitButton("Registrar", ["class" => "btn btn-primary"]) ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	    

<?php $form->end() ?>

