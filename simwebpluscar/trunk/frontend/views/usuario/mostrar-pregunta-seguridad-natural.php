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
				
				
				  


						    
							<?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); 
                                                 ?>
							
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'pregunta1')->textInput(                              [ 'id'=> 'preguntas', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $preguntaSeguridad[0]['pregunta']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							  </div>
						
                            
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, "respuesta1")->input("text") ?>   
							</div>
							</div>
						 
						

                            <?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); // primero el valor que guarda y segundo el valor que veras en el formulario ?>

                            
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'pregunta2')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $preguntaSeguridad[1]['pregunta'] ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
							</div>
						 
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, "respuesta2")->input("text") ?>   
                            </div>
                            </div>
						
						
                            <?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); // primero el valor que guarda y segundo el valor que veras en el formulario ?>
							
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'pregunta3')->textInput( [ 'id'=> 'preguntas', 
                                                                                                            'value' => $preguntaSeguridad[2]['pregunta']  ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
							</div>
						
				   			<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, "respuesta3")->input("text") ?>   
                            </div>
                            </div>
						

					 
                            <?= $form->field($model, 'id_contribuyente')->hiddenInput(['value' => $preguntaSeguridad[0]['id_contribuyente']])->label(false) ?>    
                           
							<div class="row">
							<div class="col-sm-6">
                            <?= Html::submitButton("Registrar", ["class" => "btn btn-primary"]) ?>
                             </div>
                            </div>
						
			</div>
		</div>
	</div>
<?php $form->end() ?>