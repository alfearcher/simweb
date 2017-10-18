<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\icons\Icon;

use common\models\Users;
use common\models\User;
use backend\models\usuario\RutaAccesoMenu;
/* @var $this yii\web\View */
/* @var $model backend\models\InscripcionInmueblesUrbanosForm */
/* @var $form ActiveForm */
$this->title = Yii::t('backend', 'Perfil del Usuario'); 
?>


<body onload = "bloquea()"/>
<div class="inscripcionInmueblesUrbanos">

    <?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-vertical'],]); ?>


<div class="container" style="width:1280px">
 <div class="col-sm-12" style="width:900px">
        <div class="panel panel-primary ancho-alto ">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-3"> 
                            <?= Yii::t('backend', 'Nombre de usuario:') ?>
                            </div> 
                        
                            <div class="col-sm-12">
                                

                                <?= GridView::widget([
                                'id' => 'id-lista-funcionario',
                                'dataProvider' => $funcionarios,
                                //'filterModel' => $model,
                                'headerRowOptions' => ['class' => 'success'],

                                //'caption' => Yii::t('backend', 'Lista de Accesos al Menú'),
                                'summary' => '',
                                'columns' => [
           
                                    //'id_funcionario',
                                    'ci',
                                    'apellidos',
                                    'nombres',
                                    'login',
                                    
                                    [
                                        'class' => 'yii\grid\CheckboxColumn',
                                        'name' => 'chk-funcionario',
                                        'multiple' => true,
                                    ],

                                ],
                            ]);
                        ?>
                            
                            </div> 
                        </div> 

                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-3"> 
                            <?= Yii::t('backend', 'Acceso al Menu:') ?>
                            </div> 
                        
                            <div class="col-sm-4">
                            

                            <?= $form->field($model, 'ruta')-> dropDownList($rutas,[
                                            'id' => 'id-lista-menu',
                                            'prompt' => 'Seleccionar',
                                            'style' => 'width:380px;',
                                            'onchange' =>
                                                                              '$.post( "' . Yii::$app->urlManager
                     ->createUrl('/usuario/registrar-perfil-usuario-grupo/lista-acceso-menu') . '&id=' . '" + $(this).val(),
                     function( data ) {
                           $( "#lista-acceso-menu" ).html( data );
                     });return false;'
                                                                            
                                        ])->label(false); ?>
                            </div> 
                        </div> 

                        <div class="row" style="border-bottom: 0.5px solid #ccc;">
                    <?php Pjax::begin()?>
                        <div class="lista-acceso-menu" id="lista-acceso-menu">
                        </div>
                    <?php Pjax::end()?>
                    </div>
                        
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="form-group"> 
                            <?= Html::submitButton(Yii::t('backend', 'Añadir Permiso al Usuario'), ['class' => 'btn btn-success',
                                      'data' => [
                                                  'confirm' => Yii::t('app', 'Estas seguro de guardar la siguiente informacion?'),
                                                  'method' => 'post',],]) ?>
                            <?= Html::a(Yii::t('backend', 'Back'), ['/menu/vertical'], ['class' => 'btn btn-danger']) ?>
                            </div>  
                        </div>                                                                       
                        
                   
            </div>
        </div>
    </div>
</div>



<?php ActiveForm::end(); ?> 
<?//= Html::endForm();?>

</div><!-- inscripcionInmueblesUrbanos -->

  