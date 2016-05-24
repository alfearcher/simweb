<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;



$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');


$this->title = 'Busqueda Persona Juridica';

?>




<?php $form = ActiveForm::begin([
   'method' => 'post',
    'id' => 'formulario',

    
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'enableClientScript' => true,
    

    'options' => ['class' => 'form-horizontal'],

]);
?>

 <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Taxpayer Search') ?> 
            </div>
            <div class="panel-body" >

  

            <!--FORMULARIO PARA BUSQUEDA DE NATURALEZA Y CEDULA DEL CONTRIBUYENTE-->

            <div class="row">  
                <div class="col-sm-5" style="margin-left:15px;">
                                        <div class="naturaleza">
                                            <?= $form->field($model, 'naturaleza')->dropDownList($listaNaturaleza,[
                                                                                                    'id' => 'naturaleza',
                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                    'style' => 'height:32px;width:150px;',
                                                                                                    
                                                                                                    ])
                                            ?>
                                        </div>
                </div>

          
      <div class="col-sm-2" style="margin-left:-220px;">
                        <?= $form->field($model, 'cedula')->textInput(
                                                                [
                                                               
                                                               
                                                                'id'=> 'cedula',
                                                                ]);
                    ?>
                
            </div>

            </div>

            <div class="row">
            <div class="col-sm-5" >
                        <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-busqueda-natural', 'name' => 'btn-busqueda-natural','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                    </div>

            </div>       
            

           <!--FIN DE FORMULARIO PARA BUSQUEDA DE NATURALEZA Y CEDULA DEL CONTRIBUYENTE-->
            <hr>

            
            <!--INICIO DE FORMULARIO PARA BUSQUEDA DE CONTRIBUYENTE POR SU ID-->

            <div class="row">           
            <div class="col-sm-2"  style="margin-left:15px;">
                        <?= $form->field($model, 'id')->textInput(
                                                                [
                                                               
                                                               
                                                                'id'=> 'id',
                                                                ]);
                        ?>
                
            </div>
         
            </div> 

            <div class="row">
            <div class="col-sm-5" >
                        <?= Html::submitButton(Yii::t('frontend', 'Search') , ['id' => 'btn-busqueda-id', 'name' => 'btn-busqueda-id' ,'class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
            </div>
            </div>
           

            <!--FIN DE FORMULARIO PARA BUSQUEDA DE CONTRIBUYENTE POR SU ID-->
            
            <hr>

           
             
    

    



            </div>
        </div>
      </div>



<?php $form->end() ?>

