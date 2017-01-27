<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


use common\models\desincorporacion\CausasDesincorporaciones;
 
 
$this->title = 'Cause of Desincorporation';

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
				
				
				   
						


						    
							<?php       $modelpreguntas = CausasDesincorporaciones::find()->where(['inactivo' => 0])->asArray()->all();         //die(var_dump($modelpreguntas));                           
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'causa_desincorporacion','descripcion'); 
                                                    ?>
							<div class="row">
							<div class="col-sm-7">
                          	<?= $form->field($model, 'causa')->dropDownList($listapreguntas, [ 'id'=> 'causa', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:360px;',                                                                                                            
                                                                                                            ]); ?>
							</div>
							</div>
				  			
				  			<div class="row">
							<div class="col-sm-6">
                            <?= $form->field($model, 'observacion')->textArea(['rows' => '6' ,  'style' => 'width:360px;']) ?>  
							</div>
							</div>


						
				   			
                          
                          	
                           
					 		<div class="row">
							<div class="col-sm-6">
                            <?= Html::submitButton("Save", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;margin-rigth:200px;']) ?>
							</div>
                           

                            <div class="col-sm-3" >
                                        
                                            <?= Html::a('Return',['/menu/vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:140px;margin-left:-100px;' ]) //Retornar a seleccionar tipo usuario ?>
                                        
                            </div>
                             </div>
			</div>
		</div>
	</div>
<?php $form->end() ?>
