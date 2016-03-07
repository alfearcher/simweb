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

                            <div class="fecha-inicio">
														<?= $form->field($model, 'fecha_inicio')->widget(\yii\jui\DatePicker::classname(),['id' => 'fecha-inicio',
																																			'clientOptions' => [
																																				'maxDate' => '+0d',	// Bloquear los dias en el calendario a partir del dia siguiente al actual.
																																			],
																																			'language' => 'es-ES',
																																			'dateFormat' => 'dd-MM-yyyy',
																																			'options' => [
																																					'class' => 'form-control',
																																					'readonly' => true,
																																					'style' => 'background-color: white;',

																																			]
																																			])->label(false) ?>
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