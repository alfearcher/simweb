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
						<td><div class="col-sm-4">
                            <?= $form->field($model, "nombre")->textInput(['maxlength' => true,'value'=>$datos['nombres'],'style' => 'width:100px;', 'readOnly'=> true])  ?> 
							</div>
						</td>
				   
						<td><div class="col-sm-4">
                            <?= $form->field($model, "apellido")->textInput(['maxlength' => true,'value'=>$datos['apellidos'],'style' => 'width:100px;', 'readOnly'=> true])  ?> 
							</div>
						</td>
				   
						<td><div class="col-sm-2">
                            <?= $form->field($model, "cedula")->textInput(['maxlength' => true,'value'=>$datos['ci'],'style' => 'width:100px;', 'readOnly'=> true])  ?> 
							</div>
						</td>
				   </tr>

				   <tr>
						<td><div class="col-sm-5">
						<?= $form->field($model, 'username')->textInput(['maxlength' => true,'value'=>$datos['login'],'style' => 'width:100px;', 'readOnly'=> true]) ?>
							</div>																		    
                        </td>
				   
						<td><div class="col-sm-5">
                            <?= $form->field($model, "email")->input("email") ?> 
							</div>
						</td>
				   </tr>

				   <tr>
						<td><div class="col-sm-3">
                            <?= $form->field($model, "password")->input("password") ?> 
							</div>
						</td>
					</tr>

					<tr>
						<td><div class="col-sm-3">
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

