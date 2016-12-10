<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Cambio de Clave del Funcionario';

?>


<?php $form = ActiveForm::begin([
    'method' => 'post',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
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
						<td><div class="col-lg-5">
						    <?= $form->field($model, "username")->hiddenInput(['value' => $usuario])-> label(false)?> 
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
						 <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                                  'template' => '<div class="row"><div class="col-lg-3">{image}</div></div><div class="col-lg-3">{input}</div>',
                                                ]) ?>						
						</td>
				   </tr>
				 
					<tr>
						<td> 
						   <?= Html::submitButton(Yii::t('backend', 'Change Password'), ["class" => "btn btn-primary"]) ?>						
						</td>
				   </tr>
				</table>
			</div>
		</div>
	</div>

<?php $form->end() ?>


