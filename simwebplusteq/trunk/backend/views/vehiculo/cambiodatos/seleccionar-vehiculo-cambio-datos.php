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
                            <?= $form->field($model, 'placa')->textInput(                              [ 'id'=> 'placa', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['placa']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
                              </div>
                            </div>
							
								
							<div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'marcavieja')->textInput(                              [ 'id'=> 'marcavieja', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['marca']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'marca')->textInput(                              [ 'id'=> 'marca', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['marca']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
						 
						

                          

                            
							<div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'modeloviejo')->textInput(                              [ 'id'=> 'modeloviejo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['modelo']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'modelo')->textInput(                              [ 'id'=> 'modelo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['modelo']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
						
						
                          
							
							<div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'colorviejo')->textInput(                              [ 'id'=> 'colorviejo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['color']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'color')->textInput(                              [ 'id'=> 'color', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['color']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>

                           <div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'no_ejes_viejo')->textInput(                              [ 'id'=> 'no_ejes_viejo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['no_ejes']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'no_ejes')->textInput(                              [ 'id'=> 'no_ejes', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['no_ejes']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
                            
                           <div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'nro_puestos_viejo')->textInput(                              [ 'id'=> 'nro_puestos_viejo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['nro_puestos']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'nro_puestos')->textInput(                              [ 'id'=> 'nro_puestos', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['nro_puestos']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
						

							         <div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'pesoviejo')->textInput(                              [ 'id'=> 'pesoviejo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['peso']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'peso')->textInput(                              [ 'id'=> 'peso', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['peso']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>



                                <div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'nro_cilindros_viejo')->textInput(                   [ 'id'=> '  nro_cilindros_viejo', 
                                                                                                            
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['nro_cilindros']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'nro_cilindros')->textInput(                         [ 'id'=> 'nro_cilindros', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['nro_cilindros']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>



							    <div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'precio_inicial_viejo')->textInput(                    [ 'id'=> 'precio_inicial_viejo', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['precio_inicial']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'precio_inicial')->textInput(                           [ 'id'=> 'precio_inicial', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['precio_inicial']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>
							
							<div class="row">
							<div class="col-sm-4">
                            <?= $form->field($model, 'capacidad_vieja')->textInput(                              [ 'id'=> 'capacidad_vieja', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['capacidad']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>true,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							 
						
                            
							
							<div class="col-sm-4" style="margin-left: 80px;">
                            <?= $form->field($model, 'capacidad')->textInput(                              [ 'id'=> 'capacidad', 
                                                                                                           
                                                                                                            'style' => 'width:280px;',
                                                                                                            'value' => $datos[0]['capacidad']  ,
                                                                                                           // die(var_dump($preguntaSeguridad[0]['pregunta'])),
                                                                                                            'readOnly' =>false,                                                                                                          
                                                                                                            ]); ?>
							  </div>
							</div>

							
                                 <div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'medida_cap_vieja')->textInput(  [ 'id'=> 'medida_cap_vieja', 
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