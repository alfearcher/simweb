<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Resetear Password';

//die($pregunta1);
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
						<td><div class="col-lg-4">
                            <?= $form->field($model, "password1")->input("password") ?>   
							</div>
						 </td>
				   </tr>

				   <tr>
						<td><div class="col-lg-4">
                            <?= $form->field($model, "password2")->input("password") ?>   
							</div>
						 </td>
				   </tr>

				    <?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $id_contribuyente])->label(false) ?>    
				   
                           
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