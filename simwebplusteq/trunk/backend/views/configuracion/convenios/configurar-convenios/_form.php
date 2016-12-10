<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\helpers\BaseHtml;
use yii\helpers\BaseModel;

use backend\models\impuesto\Impuesto;

/* @var $this yii\web\View */
/* @var $model backend\models\configuracion\convenios\ConfigConvenios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="config-convenios-form">


<div class="configuracionConvenios">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableClientScript' => true,
    'enableAjaxValidation' => true,
    'options' => ['class' => 'form-vertical'],]); ?>


<div class="container" style="width:1280px">
 <div class="col-sm-12" style="width:1230px">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
    
                    <div class="row">
                        
                            <div class="col-sm-2">
                                    <?php   $modelParametros = Impuesto::find()->asArray()->all();                                         
                                            $listaParametros = ArrayHelper::map($modelParametros,'impuesto','descripcion');
                                //die(var_dump($listaParametros));
                                    ?>
                                    <?= Yii::t('backend', 'Tax') ?>
                            </div>
                            <div class="col-sm-3"> 
                                    <?= $form->field($model, 'impuesto')->dropDownList($listaParametros, [ 
                                                                                                            'id'=> 'impuesto', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:200px;',
                                                                                                            ])->label(false); ?>
                            </div>
                            <!-- <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Only Bad Debt') ?>
                            </div> -->

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'solo_deuda_morosa')->checkBox()->label(false) ?>
                            </div>
                    </div>
<div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Minimum Amount of Agreement') ?> 
</div>
                    <div class="row" style="margin-left:20px; margin-top:20px;">

                        <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Amount Type') ?>
                            </div> 

                            <div class="col-sm-2"> 
                                    <?= $form->field($model, 'tipo_monto')->radioList(['1'=>'BsF','2'=>'UT'])->label(false) ?>
                            </div>
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Minimum Amount') ?>
                            </div> 
                            <div class="col-sm-2">
                                    <?= $form->field($model, 'monto_minimo')->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                    </div>

                    <div class="row" style="margin-left:20px;">
                            
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Year Tax Unit') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'ano_ut')->radioList(['1'=>'Anterior','2'=>'Actual'])->label(false) ?>
                            </div>
                    </div>
</div> 

<div class="panel panel-primary">
<div class="panel-heading">
                <?= Yii::t('frontend', 'Set Initial Amount and Fees') ?>  
</div>    
                    <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Maximum Number of Shares') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'nro_max_cuotas')->textInput()->label(false) ?>
                            </div>
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Time-Lapse') ?>
                            </div>     

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'lapso_tiempo')->textInput()->label(false) ?>
                            </div>
                    </div>  

                    <div class="row" style="margin-left:20px;">
                            
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Initial Amount') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'monto_inicial')->textInput()->label(false) ?>
                            </div>
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Initial Percentage') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'porcentaje_inicial')->textInput()->label(false) ?>
                            </div>       
                    </div>
                    <div class="row" style="margin-left:20px;">

                             <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Applying Interest') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'aplicar_interes')->radioList(['1'=>'Fijo','2'=>'variable'])->label(false) ?>
                            </div>
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Interest') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'interes')->textInput()->label(false) ?>
                            </div>
                    </div>
                    <div class="row" style="margin-left:20px;">
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Period Type') ?>
                            </div>
                                
                            <div class="col-sm-2">
                                    <?= $form->field($model, 'tipo_periodo')->textInput()->label(false) ?>
                            </div>
                    </div>
                            
</div> 
                    <div class="row">
                            <div class="col-sm-2"> 
                                    <?= Yii::t('backend', 'Id Time') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'id_tiempo')->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-sm-2">    
                                    <?= Yii::t('backend', 'Veceteo Date') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'vcto_dif_ano')->textInput()->label(false) ?>
                            </div>
                    </div>

                    <div class="row">
                           
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Id Tax') ?>
                            </div> 

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'id_impuesto')->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                    </div>  
                            

                            

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
            </div>
        </div>
    </div>
</div>
    <?= Html::endForm();?>

</div>
</div>
</div>
                           <!--  
                           <div class="col-sm-2">
                                    <?= Yii::t('backend', 'User') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Date-Time') ?>
                            </div><div class="col-sm-2">
                                    <?= $form->field($model, 'fecha_hora')->textInput() ?>
                            </div>
                            <div class="col-sm-2">
                                    <?= Yii::t('backend', 'Inactive') ?>
                            </div>

                            <div class="col-sm-2">
                                    <?= $form->field($model, 'inactivo')->textInput() ?>
                            </div> -->