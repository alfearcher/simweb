<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use common\models\desincorporacion\CausasDesincorporaciones;
use backend\models\propaganda\Propaganda;
 
 
$this->title = 'Select your Impositive Year';

//die($pregunta1);
?>
 


<?php $form = ActiveForm::begin([
    
        
]);

?>

<div class="col-sm-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= $this->title ?>
			</div>
			<div class="panel-body" >
				
				
				   
						


						    
							<?php       $modelAnoImpositivo = Propaganda::find(['ano_impositivo'])->asArray()->distinct()->all();         //die(var_dump($modelpreguntas));                           
                                        $listaImpositivo = ArrayHelper::map($modelAnoImpositivo,'ano_impositivo','ano_impositivo'); 
                                                    ?>
							<div class="row">
							<div class="col-sm-3">
                          	<?= $form->field($model, 'ano_impositivo')->dropDownList($listaImpositivo, [ 'id'=> 'ano_impositivo', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:100px;',                                                                                                            
                                                                                                            ]); ?>
							</div>
							</div>
				  			
				  	


						
				   			
                          
                          	
                           
					 		<div class="row">
							<div class="col-sm-3">
                            <?= Html::submitButton("Search", ["class" => "btn btn-success", 'style' => 'height:30px;width:70px;margin-rigth:200px;']) ?>
							</div>
                           

                            <div class="col-sm-3" >
                                        
                                            <?= Html::a('Return',['site/menu-vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:70px;margin-left:60px;' ]) //Retornar a seleccionar tipo usuario ?>
                                        
                            </div>
                             </div>
			</div>
		</div>
	</div>
<?php $form->end() ?>