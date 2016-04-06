<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Car Plate Change';

//die($pregunta1);
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
				
				
				  


						    
							
							
								
							<div class="row">
							<div class="col-sm-3">
                            <?= $form->field($model, 'placavieja')->textInput(                              [ 'id'=> 'preguntas', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['placa']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-3" style="margin-left: 130px;">
                            <?= $form->field($model, 'placa')->textInput(                              [ 'id'=> 'preguntas', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            //'value' => $datos[0]['placa']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
						 
						
                            <div class="row">
							<div class="col-sm-6">
                            <?= Html::submitButton("Registrar", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;margin-rigth:200px;']) ?>
							</div>
                           

                            <div class="col-sm-3" >
                                        
                                            <?= Html::a('Return',['site/menu-vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:140px;margin-left:-100px;' ]) //Retornar a seleccionar tipo usuario ?>
                                        
                            </div>
                             </div>
						
			</div>
		</div>
	</div>
<?php $form->end() ?>