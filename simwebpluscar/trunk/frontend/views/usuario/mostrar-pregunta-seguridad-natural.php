<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Preguntas de Seguridad';

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


						    
							<?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); 
                                       // die(var_dump($listapreguntas));             ?>

                            <?= $form->field($model, 'pregunta1')->textInput(                              [ 'id'=> 'preguntas', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $preguntaSeguridad[0]['pregunta']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
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

                            <?= $form->field($model, 'pregunta2')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $preguntaSeguridad[1]['pregunta'] ,
                                                                                                            'readOnly' =>true,  
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

                            <?= $form->field($model, 'pregunta3')->textInput( [ 'id'=> 'preguntas', 
                                                                                                            'value' => $preguntaSeguridad[2]['pregunta']  ,
                                                                                                            'readOnly' =>true,  
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

					 
                            <?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $preguntaSeguridad[0]['id_contribuyente']])->label(false) ?>    
                           
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