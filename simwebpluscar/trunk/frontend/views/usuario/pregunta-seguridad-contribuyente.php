<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Preguntas de Seguridad';

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
						<td><div class="col-lg-8">
						 	  <?= $form->field($model, "usuario")->hiddenInput()-> label(false)?>  
                            </div>
						 </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-4">
						    
							<?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); 
                                       // die(var_dump($listapreguntas));             ?>

                            <?= $form->field($model, 'pregunta1')->dropDownList($listapreguntas, [ 'id'=> 'preguntas', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:280px;',                                                                                                            
                                                                                                            ]); ?>

							  </div>
						 </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-4">
                            <?= $form->field($model, "respuesta1")->input("text") ?>   
							</div>
						 </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-4">
                            <?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); // primero el valor que guarda y segundo el valor que veras en el formulario ?>

                            <?= $form->field($model, 'pregunta2')->dropDownList($listapreguntas, [ 'id'=> 'preguntas', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						 </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-4">
                            <?= $form->field($model, "respuesta2")->input("text") ?>   
                            </div>
						</td>
					</tr>

					<tr>
						<td><div class="col-lg-4">
                            <?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); // primero el valor que guarda y segundo el valor que veras en el formulario ?>

                            <?= $form->field($model, 'pregunta3')->dropDownList($listapreguntas, [ 'id'=> 'preguntas', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						 </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-4">
                            <?= $form->field($model, "respuesta3")->input("text") ?>   
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