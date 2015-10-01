<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use backend\models\propaganda\PropagandaForm;

$fecha=date("Y-m-d");
$inactivo='0';
$this->title = Yii::t( 'backend', 'Catalogs Propaganda' );
?>

<div class="grupotrabajo-create">
    <?php   $form = ActiveForm::begin( [ 'id' => 'form-propaganda-inline',
                                         'type' => ActiveForm::TYPE_HORIZONTAL,
                                         'formConfig' => [ 'showErrors' => true, 
                                         'deviceSize' => ActiveForm::SIZE_SMALL, 
                                         'labelSpan' => 2,
                                         'showLabels' => true ]
                                       ] );
    ?>
    
    <div class="col-sm-15">
        <div>
            <div class="panel panel-primary">
                <div class="panel-heading"><?=  Yii::t( 'backend',$this->title."&nbsp;Year:&nbsp;".$model->ano_impo )?></div>
                    <div class="panel-body" >
                        <table class="table table-striped">
                            
                            <tr align="center">
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Kind' )?></small></i></p>
                                     </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Description' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                 <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Cigars' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Alcohol' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Foreign Language' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Amount Apply' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Description Amount' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Calculation Basis' )?></small></i></p>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <p><i><small><?= Yii::t( 'backend', 'Id Ordinance' )?></small></i></p>
                                    </div>  
                                </td>
                            </tr>
                            
                           
                    <?php   $c = 1; 
                            foreach ( $command as $row ): 
                    ?>
                            
                            <tr align="center">
                                <td>
                                    <div>
                                            <?= $c++;?>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div align="justify">
                                        <?= strtoupper( $row['propaganda'] )?>
                                    </div>  
                                </td>
                                
                                 <td>
                                    <div>
                                        <?php if( $row['cigarro'] == 0 ){ $row['cigarro'] = 'NO'; echo $row['cigarro']; }else{ $row['cigarro'] = 'SI'; echo $row['cigarro']; }?>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <?php if( $row['alcohol'] == 0 ){ $row['alcohol'] = 'NO'; echo $row['alcohol']; }else{ $row['alcohol'] = 'SI'; echo $row['alcohol']; }?>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                         <?php if( $row['idioma'] == 0 ){ $row['idioma'] = 'NO'; echo $row['idioma']; }else{ $row['idioma'] = 'SI'; echo $row['idioma']; }?>
                                     </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <?= strtoupper( $row['monto_aplicar'] )?>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <?= 'UNIDAD TRIBUTARIA'?>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <?= strtoupper( $row['base'] )?>
                                    </div>  
                                </td>
                                
                                <td>
                                    <div>
                                        <?= strtoupper( $row['id_ordenanza'] )?>
                                    </div>  
                                </td>
                            </tr>
                                            
                            <?php endforeach; ?>   
                        
                            <tr>
                                <td colspan="9">
                                    <div>
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