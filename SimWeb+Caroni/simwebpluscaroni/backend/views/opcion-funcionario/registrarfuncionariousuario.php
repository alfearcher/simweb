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
    'enableAjaxValidation' => false,
	'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-10">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= $this->title ?>
			</div>
			<div class="panel-body" >
				
						<div class="row" style="margin-left:20px; margin-top:20px;">
							<div class="col-sm-4">
                            <?= $form->field($model, "nombre")->textInput(['maxlength' => true,'value'=>$datos['nombres'],'style' => 'width:100px;', 'readOnly'=> true])  ?> 
							</div>
						
				   
							<div class="col-sm-4">
                            <?= $form->field($model, "apellido")->textInput(['maxlength' => true,'value'=>$datos['apellidos'],'style' => 'width:100px;', 'readOnly'=> true])  ?> 
							</div>
						
				   
							<div class="col-sm-2">
                            <?= $form->field($model, "cedula")->textInput(['maxlength' => true,'value'=>$datos['ci'],'style' => 'width:100px;', 'readOnly'=> true])  ?> 
							</div>
						</div>
				   
						<div class="row" style="margin-left:20px; margin-top:20px;">
							<div class="col-sm-4">
							<?= $form->field($model, 'username')->textInput(['maxlength' => true,'value'=>$datos['login'],'style' => 'width:150px;', 'readOnly'=> true]) ?>
							</div>																		    
                        	
                        	<div class="col-sm-6">
                            <?= $form->field($model, "email")->input("email")->textInput(['maxlength' => true,'style' => 'width:300px;']) ?> 
							</div>
						</div>
				   
						<div class="row" style="margin-left:20px; margin-top:20px;">
							<div class="col-sm-4">
                            <?= $form->field($model, "password")->passwordInput(['maxlength' => true,'style' => 'width:200px;']) ?> 
							</div>
						
							<div class="col-sm-4">
                            <?= $form->field($model, "password_repeat")->passwordInput(['maxlength' => true,'style' => 'width:200px;']) ?>
							</div> 
						</div>
					
						<div class="row" style="margin-left:20px; margin-top:20px;">
							<div class="col-sm-5">
                            <?= Html::submitButton("Registrar", ["class" => "btn btn-primary"]) ?>
							</div> 
						</div>
					
			</div>
		</div>
	</div>
	    
<?php ActiveForm::end(); ?> 


