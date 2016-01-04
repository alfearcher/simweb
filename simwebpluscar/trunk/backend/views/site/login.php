<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Iniciar Sesion';
//$this->params['breadcrumbs'][] = $this->title; // guia de la direccion de la pagina ejemplo: /home/registrar
?>

    

    

    <?php $form = ActiveForm::begin([
        'id' => 'login-form', 
        'options' => ['class' => 'form-horizontal'], // aqui estan las forma que tendra el formulario en la vista
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'], // aqui estan las obciones para el formulario de logueo
        ],
    ]); ?>


<div class="col-sm-10">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= $this->title ?>
			</div>
			<div class="panel-body" >
				<table class="table table-striped">
					<tr>
						<td>
                            <?= $form->field($model, 'username') ?>
							
						 </td>
				   </tr>
				   <tr>
						<td>
                            <?= $form->field($model, 'password')->passwordInput() ?>
							
						 </td>
				   </tr>
				   
				   <tr>
						<td><div class="col-lg-offset-0 col-lg-5">
                            <?= Html::submitButton('Iniciar Sesion', ['class' => 'btn btn-primary', 'name' => 'login-button']) // BOTON DE INICIAR SESION ?>
							
							<?= Html::a('Cambiar Password', ['opcion-funcionario/iniciarrecuperacionpasswordfuncionario'], ['class' => 'btn btn-primary']) //BOTON DE RECUPERAR CONTRASENA ?>
                            </div>
						</td>
					</tr>
				</table>
			</div>
		</div>
  </div>	


    <?php ActiveForm::end(); ?>
	
	 <?php // <?= $form->field($model, 'rememberMe', ['template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",])->checkbox() ?>
	                        
						