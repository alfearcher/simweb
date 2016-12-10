<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Preguntas de Seguridad';

?>
 


<?php $form = ActiveForm::begin([
   
        
]);

?>

<div class="col-sm-7">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= $this->title ?>
			</div>
			<div class="panel-body" >
				
						 	  <?= $form->field($model, "usuario")->hiddenInput()-> label(false)?>  
                           
						
						    
							<?php       $modelpreguntas = ListaPreguntasContribuyente::find()->where(['estatus' => 0])->asArray()->all();                                         
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); 
                                               ?>
							<div class="row">
							<div class="col-sm-6">
                          	<?= $form->field($model, 'pregunta1')->dropDownList($listapreguntas, [ 'id'=> 'preguntas', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:280px;',                                                                                                            
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
                            <?= $form->field($model, 'pregunta2')->dropDownList($listapreguntas, [ 'id'=> 'preguntas', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
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
                            <?= $form->field($model, 'pregunta3')->dropDownList($listapreguntas, [ 'id'=> 'preguntas', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
                            </div>
                             </div>

							 <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, "respuesta3")->input("text") ?>   
                            </div>
                             </div>
						 	
						 	 <div class="row">
							<div class="col-sm-6">
                            <?= Html::submitButton("Registrar", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;margin-left:0px;']) ?>
							 </div>
                             
                             <div class="col-sm-3" >
                                        
                            <?= Html::a('Return',['/site/login'], ['class' => 'btn btn-primary','style' => 'height:30px;width:140px;margin-left:-120px;' ]) //Retornar al login ?>
                                        
                            </div>
                            </div>

                            </div>
			</div>
		</div>
	</div>
<?php $form->end() ?>