<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Iniciar Sesion';
//$this->params['breadcrumbs'][] = $this->title; // guia de la direccion de la pagina ejemplo: /home/registrar
//
//die(var_dump(yii::$app->user->identity));
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
			


			<div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-3"> 
                            <?= Yii::t('backend', 'Correo Electronico:') ?>
                            </div> 

                            <div class="col-sm-7">                         
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true,'style' => 'width:200px;'])->label(false) ?>
							</div>
						</div>
							
						 <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-3"> 
                            <?= Yii::t('backend', 'Contraseña:') ?>
                            </div>

                            <div class="col-sm-7"> 
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'style' => 'width:200px;'])->label(false) ?>
							</div>
						</div>

		
			
			<div class="row" style="margin-left: 10px;">
					
                    <?= Html::submitButton('Iniciar Sesion', ['class' => 'btn btn-primary', 'name' => 'login-button']) // BOTON DE INICIAR SESION ?>
					<?= Html::a('Crear Usuario', ['/usuario/opcion-crear-usuario/seleccionar-tipo-usuario'], ['class' => 'btn btn-primary', 'name' => 'create-user']) // BOTON DE CREAR USUARIO ?>
					<?= Html::a('Cambiar Password',['/usuario/cambiar-password-contribuyente/seleccionar-tipo-contribuyente'], ['class' => 'btn btn-primary']) //BOTON DE RECUPERAR CONTRASENA ?>
					<?= Html::a('Recuperar Password',['/usuario/recuperar-password-contribuyente/seleccionar-tipo-contribuyente'], ['class' => 'btn btn-primary']) //BOTON DE RECUPERAR CONTRASENA ?>
              </div>
						
			
		</div>
	</div>
  </div>	


    <?php ActiveForm::end(); ?>
	
	 <?php // <?= $form->field($model, 'rememberMe', ['template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",])->checkbox() ?>
	                        
						