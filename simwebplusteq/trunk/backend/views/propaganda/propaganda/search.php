<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use backend\models\propaganda\PropagandaForm;

$fecha=date("Y-m-d");
$inactivo='0';
$this->title = Yii::t( 'backend', 'Search Catalogs Propaganda' );
?>

<div class="grupotrabajo-create">
    <?php   $form = ActiveForm::begin( [ 'id' => 'form-propaganda-inline',
                                         'type' => ActiveForm::TYPE_HORIZONTAL,
                                         'formConfig' => ['showErrors' => true, 
                                         'deviceSize' => ActiveForm::SIZE_SMALL, 
                                         'labelSpan' => 2,
                                         'showLabels' => true]
                                    ] );
    ?>
    
    <?= Html::activeHiddenInput( $model, 'causa_desincorporacion', [ 'value' => '0' ] ) ?>
    <?= Html::activeHiddenInput( $model, 'comentario', [ 'value' => '0' ] )?>
    
    <div class="col-sm-8">
        <div style="margin-left:40%">
            <div class="panel panel-primary">
                <div class="panel-heading"><?=  Yii::t( 'backend', $this->title )?></div>
                    <div class="panel-body" >
                        <table class="table table-striped">
                                            
                            <tr>
                                <td>
                                    <div>
                                        <p><i><small><?=Yii::t( 'backend', 'Consult Tax Year:' ) ?></small></i></p>
                                        <?php   $modelAnoImpositivo = PropagandaForm::find()->distinct()->orderBy( [ 'ano_impositivo' => SORT_DESC ] )->asArray()->all();                                         
                                                $listaAnoImpositivo = ArrayHelper::map( $modelAnoImpositivo, 'ano_impositivo', 'ano_impositivo' ); 
                                        ?>
                                        <?= $form->field( $model, 'ano_impo' )->label( false )->dropDownList( $listaAnoImpositivo, [    'id' => 'ano_impositivo', 
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
                                        <?= Html::submitButton( Yii::t( 'backend', 'Search' ),[ 'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'name' => 'btn', 'value' => 'search' ] )?>
                                        <?= Html::a(Yii::t('backend', 'Quit'), ['propaganda/propaganda/create'], ['class' => 'btn btn-danger']) ?>
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
