<?php


    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\web\View;
    use yii\helpers\ArrayHelper;
    use backend\models\registromaestro\TipoNaturaleza;
    use frontend\models\usuario\TelefonoCodigo;
    use yii\helpers\Url;
    use yii\jui\DatePicker;




?>




<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('backend', 'Registration Basic Information') ?> </h3>
</div>

<div><br></div>





<!-- FORMULARIO PERSONA NATURAL -->






 <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            
             'enableClientValidation' => true,
             'enableAjaxValidation' => false,
             'enableClientScript' => true,

        ]);

?>

    <div class="col-sm-7">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Registration Basic Information') ?> | <?= Yii::t('frontend', 'Natural') ?>
            </div>
            <div class="panel-body" >

 


<!-- RAZON SOCIAL-->
                <div class="row">
                    <div class="col-sm-5">
                        <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
                    </div>
     </div>
                    
<!-- FIN DE RAZON SOCIAL -->



<!-- FIN DE TELEFONO CELULAR -->

                <div class="row">
                    <div class="col-sm-4">
                        <?= Html::submitButton(Yii::t('frontend', 'Create') , ['class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                    </div>

                    <div class="col-sm-4">
                        <?= Html::a('Return',['site/menu-vertical'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:-55px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                    </div>
                </div>


       
        </div>
    </div>
</div>


<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO PERSONA NATURAL -->