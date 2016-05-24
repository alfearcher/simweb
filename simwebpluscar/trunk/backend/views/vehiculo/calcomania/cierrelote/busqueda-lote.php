<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\calcomania\calcomaniamodelo\Calcomania;


    $busquedaAno = Calcomania::find()
                            ->select('ano_impositivo')
                            ->distinct()
                            ->asArray()
                            ->all();

    
    $listaAnoImpositivo = ArrayHelper::map($busquedaAno, 'ano_impositivo' , 'ano_impositivo' );

 
 
$this->title = 'Impositive Year Search';

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
					           <div class="col-sm-5" style="padding-right: 12px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'ano_impositivo')->dropDownList($listaAnoImpositivo,[
                                                                                                    'id' => 'ano_impositivo',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                </div>
							</div>
						 
						
                            <div class="row">
							<div class="col-sm-6">
                            <?= Html::submitButton("Search", ["class" => "btn btn-success", 'style' => 'height:30px;width:140px;margin-rigth:200px;']) ?>
							</div>
                           

                            <div class="col-sm-3" >
                                        
                                            <?= Html::a('Return',['/menu/vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:140px;margin-left:-100px;' ]) //Retornar a seleccionar tipo usuario ?>
                                        
                            </div>
                             </div>
						
			</div>
		</div>
	</div>
<?php $form->end() ?>