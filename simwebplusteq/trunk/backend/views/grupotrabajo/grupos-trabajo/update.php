<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use backend\models\Departamento;
use backend\models\UnidadDepartamento;

$fecha = date("Y-m-d");
?>

<div class="grupotrabajo-create">
    <?php   $form = ActiveForm::begin( [ 'id' => 'form-grupo-trabajo-inline',
                                         'type' => ActiveForm::TYPE_HORIZONTAL,
                                         'formConfig' => [ 'showErrors' => true, 
                                         'deviceSize' => ActiveForm::SIZE_SMALL, 
                                         'labelSpan' => 2,
                                         'showLabels' => true ]
                                    ] );
    ?>
    
    <div style="margin-left:20%">
        <div class="col-sm-10">
            <div class="panel panel-primary">
                <div class="panel-heading"><?=  Yii::t( 'backend', 'Update Workgroups' )?></div>
                    <div class="panel-body" >
                        <table class="table table-striped">
                                            
                            <tr>
                                <td>
                                    <div>
                                        <p><i><small><?=Yii::t( 'backend', 'Group Description:' ) ?></small></i></p>
                                        <?= $form->field( $model, 'descripcion' )->label( false )->textInput( [ 'inline' => true, 'style' => 'width:100%;text-transform:uppercase;', 'readonly' => 'readonly' ] )?>	
                                    </div>  
                                </td> 
                            </tr>
                                            
                            <tr>
                                <td>
                                    <div>
                                        <p><i><small><?=Yii::t( 'backend', 'Department of the Company:' ) ?></small></i></p>
                                        <?php   $modelDepartamento = Departamento::find()->where( [ 'inactivo' => 0 ] )->asArray()->all();                                         
                                                $listaDepartamento = ArrayHelper::map( $modelDepartamento, 'id_departamento', 'descripcion' ); 
                                        ?>
                                        <?= $form->field( $model, 'id_departamento' )->label( false )->dropDownList($listaDepartamento, [   'id' => 'departamentos', 
                                                                                                                                            'prompt' => Yii::t( 'backend', 'Select' ),
                                                                                                                                            'style' => 'width:100%;',
                                                                                                                                            'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl( 'unidad-departamento/lists' ) . '&id=' . '" + $(this).val(), function( data ) {$( "select#unidades" ).html( data );});' 
                                                                                                                                       ] ); 
                                        ?>
                                    </div> 
                                </td> 
                            </tr>
                                            
                            <tr>
                                <td>
                                    <div>
                                        <p><i><small><?=Yii::t( 'backend', 'Business Unit:' ) ?></small></i></p>
                                        <?php   $modelUnidad = UnidadDepartamento::find()->where( [ 'inactivo' => 0 ] )->asArray()->all();                                         
                                                $listaUnidad = ArrayHelper::map( $modelUnidad, 'id_unidad', 'descripcion' ); 
                                        ?>
                                        <?= $form->field( $model, 'id_unidad' )->label( false )->dropDownList( $listaUnidad, [  'id' => 'unidades',
                                                                                                                                'prompt' => Yii::t( 'backend', 'Select' ),
                                                                                                                                'style' => 'width:100%;',
                                                                                                                             ] );
                                        ?>
                                    </div>
                                </td> 
                            </tr>
                    
                            <tr>
                                <td>
                                    <div>
                                        <?= Html::submitButton( Yii::t( 'backend', 'Update' ), [ 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'btn' , 'value' => 'Update' ] )?>
                                        <?= Html::a(Yii::t('backend', 'Quit'), ['grupotrabajo/grupos-trabajo/index'], ['class' => 'btn btn-danger']) ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
    </div>
        <?php ActiveForm::end(); ?>
</div>