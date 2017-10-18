<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
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
 <div class="col-sm-15" style="width:900px">
        <div class="panel panel-primary" style="width: 100%;">
            <div class="panel-heading">
                <?= $this->title ?>
            </div> 
            <div class="panel-body" >
                
                        
                    <div class="row" class="informacion-contribuyente" id="informacion-contribuyente">
                        <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 25px;">
                            <h4><strong><?=Html::encode(Yii::t('frontend', 'Nombre del Grupo Perfil'))?></strong></h4>
                        </div>
                        
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-4">
                                
                            <?= $form->field($model, 'descripcion')->textinput( [ 
                                                                                                            'id'=> 'parametro', 
                                                                                                            'prompt' => Yii::t('backend', 'Select'),
                                                                                                            'style' => 'width:180px;',
                                                                                                           ])->label(false);
                                                                                                           ?>
                            </div>
                        </div>
                        </div> 
                    

                        
                    <div class="row" class="informacion-contribuyente2" id="informacion-contribuyente2">
                        <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 25px;">
                            <h4><strong><?=Html::encode(Yii::t('frontend', 'Acceso al menu'))?></strong></h4>
                        </div>
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="col-sm-10">
                            
                            <?= GridView::widget([
                                'id' => 'id-lista-ruta',
                                'dataProvider' => $rutas,
                                //'filterModel' => $model,
                                'headerRowOptions' => ['class' => 'success'],
                                //'caption' => Yii::t('backend', 'Lista de Accesos al Menú'),
                                //'summary' => '',
                                'columns' => [
           
                                    //'id_ruta_acceso_menu',
                                    'menu',
                                    'ruta',
                                    
                                    [
                                        'class' => 'yii\grid\CheckboxColumn',
                                        'name' => 'chk-ruta',
                                        'multiple' => true,
                                    ],

                                ],
                            ]);
                        ?>

                            
                            </div> 
                        </div>
                    </div>
                </div> 
                        
                        <div class="row" style="margin-left:20px; margin-top:20px;">
                            <div class="form-group"> 
                            <?= Html::submitButton(Yii::t('backend', 'Añadir Grupo Perfil'), ['class' => 'btn btn-success',
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

 