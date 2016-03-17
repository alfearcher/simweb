<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use frontend\models\usuario\ListaPreguntasContribuyente;
 
 
$this->title = 'Vehicle Specifications change';

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
							<div class="col-sm-4">
                            <?= $form->field($model, 'marcavieja')->textInput(                              [ 'id'=> 'preguntas', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['marca']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'marca')->textInput(                              [ 'id'=> 'preguntas', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['marca']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
						 
						

                          

                            
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'modeloviejo')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['modelo'] ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "modelo")->input("text") ?>   
                            </div>
                            </div>
						
						
                          
							
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'colorviejo')->textInput( [ 'id'=> 'preguntas', 
                                                                                                            'value' => $datos[0]['color']  ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
							
						
				   			
							<div class="col-sm-6">
                            <?= $form->field($model, "color")->input("text") ?>   
                            </div>
                            </div>

                            <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'no_ejes_viejo')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['no_ejes'] ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "no_ejes")->input("text") ?>   
                            </div>
                            </div>

                            
                            <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'nro_puestos_viejo')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['nro_puestos'] ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "nro_puestos")->input("text") ?>   
                            </div>
                            </div>
						

							         <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'pesoviejo')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['peso'].' Kgs' ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "peso")->input("text") ?>   
                            </div>
                            </div> 



                                 <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'nro_cilindros_viejo')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['nro_cilindros'] ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "nro_cilindros")->input("text") ?>   
                            </div>
                            </div>



							     <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'precio_inicial_viejo')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['precio_inicial'].' Bsf.' ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "precio_inicial")->input("text") ?>   
                            </div>
                            </div>
							
							<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'capacidadvieja')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['capacidad'].' ' ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>
						
						 
							
							<div class="col-sm-6">
                            <?= $form->field($model, "capacidad")->input("text") ?>   
                            </div>
                            </div>

							
                                 <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'medida_cap_vieja')->textInput(  [ 'id'=> 'preguntas', 
                                                                                                            'value' =>  $datos[0]['medida_cap'] ,
                                                                                                            'readOnly' =>true,  
                                                                                                            'style' => 'width:280px;',
                                                                                                           ]);    ?>
							</div>

							      <div class="col-sm-2" style="margin-bottom:40px;">
                       
                    <?= $form->field($model, 'medida_cap')->label(false)->radioList([
                                                                                                        'Kgs' => Yii::t('frontend', 'Kgs.'),

                                                                                                        'Ton' => Yii::t('frontend', 'Tns.'),
                                                                                                      ],
                                                                                                      [
                                                                                                        
                                                                                                      ]
                                                                                                      ) ?>
                               
                
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