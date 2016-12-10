<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\registromaestro\TipoNaturaleza;
use yii\helpers\ArrayHelper;






$this->title = 'Verification';


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
                <?= Yii::t('frontend', 'Verification') ?> 
            </div>
            <div class="panel-body" >

  

            <!--FORMULARIO PARA ACEPTAR DATOS A CAMBIAR-->
            
            <div class="row" style="margin-left: 10px;">
           
            <p><b>Datos de Verificacion</b></p>
            </div>
            <hr>
            <div class="row">  
            <div class="col-sm-2" style="margin-left: 20px;">
                        <?= $form->field($model, 'nro_calcomania')->textInput(
                                                                [
                                                               
                                                                'value' => $idCalcomania,
                                                                'readonly' => true,
                                                                'id'=> 'nro_calcomania',
                                                                ]);
                        ?>
                
            </div>
            
            </div>

            


    <div class="row">  
            <div class="col-sm-2" style="margin-left: 20px;">
                        <?= $form->field($model, 'placa')->textInput(
                                                                [
                                                               
                                                                'value' => $datos[0]->placa,
                                                                'readonly' => true,
                                                                'id'=> 'placa',
                                                                ]);
                        ?>
                
            </div>


            <div class="col-sm-2" style="margin-left: 20px;">
                        <?= $form->field($model, 'marca')->textInput(
                                                                [
                                                               
                                                                'value' => $datos[0]->marca,
                                                                'readonly' => true,
                                                                'id'=> 'marca',
                                                                ]);
                        ?>
                
            </div>


            <div class="col-sm-2" style="margin-left: 20px;">
                        <?= $form->field($model, 'modelo')->textInput(
                                                                [
                                                               
                                                                'value' => $datos[0]->modelo,
                                                                'readonly' => true,
                                                                'id'=> 'modelo',
                                                                ]);
                        ?>
                
            </div>

            


    </div>

        <hr>

        <div class="row" style="margin-left: 50px;">
            
            <div class="col-sm-5" >
                <p><b>¿Esta Seguro que desea continuar?</b></p>
            </div>

            <div class="col-sm-3" >
                <?= Html::submitButton(Yii::t('frontend', 'Si') , ['id' => 'btn-busqueda', 'name' => 'btn-busqueda','class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
            </div>

            
            <div class="col-sm-3" >
            <?= Html::a(Yii::t('backend', 'No'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
            </div> 


        </div>      

           <!--FIN DE FORMULARIO PARA ACEPTAR DATOS A CAMBIAR -->
      

            
       

           
             
    

    



            </div>
        </div>
      </div>



<?php $form->end() ?>

