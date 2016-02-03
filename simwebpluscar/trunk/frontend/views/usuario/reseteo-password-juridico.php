<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Resetear Password';

//die($pregunta1);
?>
 


<?php $form = ActiveForm::begin([
    
        
]);

?>

<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= $this->title ?>
			</div>
			<div class="panel-body" >
				
				
				  
				   			<div class="row">
				   			<div class="col-sm-5">
                            <?= $form->field($model, "password1")->input("password") ?>
                            </div>   
							</div>
							
							<div class="row">
				   			<div class="col-sm-5">
                            <?= $form->field($model, "password2")->input("password") ?>   
							</div>   
							</div>
				  

				            <?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $id_contribuyente])->label(false) ?>    
				   
                           
					 		<div class="row">
				   			<div class="col-sm-5">
                            <?= Html::submitButton("Registrar", ["class" => "btn btn-primary"]) ?>
                            </div>   
							</div> 
						
			</div>
		</div>
	</div>
<?php $form->end() ?>