<?php
//die('llegue a la vista desincorporacion');
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

use common\models\desincorporacion\CausasDesincorporaciones;
use backend\models\inmueble\ParametrosNivelesCatastro;
use backend\models\inmueble\Estados;
use backend\models\inmueble\Municipios;
/* @var $this yii\web\View */
/* @var $model backend\models\InscripcionInmueblesUrbanosForm */
/* @var $form ActiveForm */
$this->title = Yii::t('frontend', 'Property Update');
?>
<div class="inscripcionInmueblesUrbanos">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>

<div class="container" style="width:1280px">
 <div class="col-sm-10" style="width:1230px">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                <table class="table table-striped "cellpadding="1px" cellspacing="1px">
                                             
<!-- Direccion de Catastro  -->      
                     


                   <div class="row">
                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Divestiture Causes') ?>
                            </div>  
                        

                        
                            <div class="col-sm-6"> 
                            <?php
                                $modelParametros = CausasDesincorporaciones::find()->asArray()->all();                                         
                                $listaParametros = ArrayHelper::map($modelParametros,'causa_desincorporacion','descripcion'); 
                                //echo'<pre>'; var_dump($listaParametros); echo '</pre>'; die(); ?> 
                            <?= $form->field($model, 'causa')->dropDownList($listaParametros, [ 
                                                                                                            'id'=> 'parametro', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:300px;',
                                                                                                           /*'onchange' =>
                                                                                                                '$.post( "' . Yii::$app->urlManager
                                                                                                                                       ->createUrl('parroquias/lists') . '&municipio=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                                            $( "select#parroquias" ).html( data );
                                                                                                                                                                                                            });' 
                                                                                                           */ ])->label(false);
                                                                                                          ?>
                            </div> 
                        
                                                  
                   </div>


                   <div class="row">
                        
                            <div class="col-sm-2"> 
                            <?= Yii::t('backend', 'Observation') ?>
                            </div>
                        

                        
                            <div class="col-sm-3">
                            <?= $form->field($model, 'observacion')->textarea(['maxlength' => true,'style' => 'width:300px;'])->label(false) ?>
                            </div> 

                   

                    </div>     
                    <div class="row">

                             
                                <?= Html::beginForm();?>
                                <div class="col-sm-1">
                                <?= Html::submitButton(Yii::t('frontend', 'Accept'), ['class' => 'btn btn-success', 'name'=>'Accept', 'value'=>'Accept']) ?>
                                </div>
                                <div class="col-sm-1">
                                <?= Html::a(Yii::t('frontend', 'Back'), ['/site/menu-vertical'], ['class' => 'btn btn-danger']) ?>
                                </div>
                                <?= Html::endForm();?> 
                                
                    </div>
                                                                       
                        
                   

                    
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Campos ocultos -->   
<?= $form->field($model, 'manzana_limite')->hiddenInput(['value' => 130])->label(false) ?> 
<?= $form->field($model, 'id_habitante')->hiddenInput(['value' => 123456])->label(false) ?>
<?= $form->field($model, 'liquidado')->hiddenInput(['value' => 0])->label(false) ?>
<?= $form->field($model, 'nivel')->hiddenInput(['value' => 0])->label(false) ?>



<?= $form->field($model, 'validacion')->hiddenInput(['value' => '2'])->label(false) ?> 
<?//= Html::endForm();?> 
<?php ActiveForm::end(); ?> 



</div><!-- inscripcionInmueblesUrbanos 


-->

