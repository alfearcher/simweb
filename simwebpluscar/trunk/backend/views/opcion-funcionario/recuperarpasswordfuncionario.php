<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\PreguntasSeguridad;
use backend\models\PreguntasUsuarios;

$this->title = 'Recuperar Password del Funcionario';
$usuario = $_GET['usuario'];

?>
 

<?php $form = ActiveForm::begin([
    'method' => 'post',
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
						    <?= $form->field($model, "user")->hiddenInput(['value' => $usuario])-> label(false)?>  
                            </div>
						 </td>
				   </tr>
				   <tr>
						<td><div class="col-lg-4">
						    
							<?php 
                                        $modelpreguntas = PreguntasUsuarios::find()->where(['estatus' => 0])->andwhere(['usuario' => $usuario])->andwhere(['tipo_pregunta' => 0])->asArray()->all();                                       
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); 

                                    ?>

                             <?= $form->field($model, 'pregunta1')->dropDownList($listapreguntas, [
                                                                                                            'id'=> 'preguntas', 
                                                                                                            /*'prompt' => Yii::t('backend', 'Select'),*/
                                                                                                            'style' => 'width:280px;',
                                                                                                            
                                                                                                            ]); 
                                    ?>

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
                            <?php 
                                         $modelpreguntas = PreguntasUsuarios::find()->where(['estatus' => 0])->andwhere(['usuario' => $usuario])->andwhere(['tipo_pregunta' => 1])->asArray()->all();                                       
                                        $listapreguntas = ArrayHelper::map($modelpreguntas,'pregunta','pregunta'); // primero el valor que guarda y segundo el valor que veras en el formulario

                                    ?>

                             <?= $form->field($model, 'pregunta2')->dropDownList($listapreguntas, [
                                                                                                            'id'=> 'preguntas', 
                                                                                                            /*'prompt' => Yii::t('backend', 'Select'),*/
                                                                                                            'style' => 'width:280px;',
                                                                                                            
                                                                                                            ]); 
                                    ?>
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
						<td>  
                            <?= Html::submitButton("recuperar Password", ["class" => "btn btn-primary"]) ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
<?php $form->end() ?>