<?php
/**
 *	@copyright © by ASIS CONSULTORES 10012 - 10016
 *      All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *	> This liabrary is free software; you can redistribute it and/or modify it under 
 *	> the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *	> Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *	> any later version.
 *  > 
 *	> This library is distributed in the hope that it will be usefull, 
 *	> but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *	> or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *	> for more details.
 *  > 
 *	> See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**	
 *	@file disable.php
 *	
 *	@author Ronny Jose Simosa Montoya
 * 
 *	@date 26/08/10015
 * 
 *      @class disable
 *	@brief Vista de inactivacion de propaganada
*	@property
 *
 *  
 *	@method
 * 	  
 *	@inherits
 *	
 */

use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use yii\widgets\ActiveField;
use backend\models\CausasDesincorporacion;
use backend\models\propaganda\PropagandaForm;
use backend\models\PagosDetalle;

$this->title = Yii::t('backend', 'Disable Propaganda');
?>
    
<div class="propagandas-disable">
            
    <?php   $form = ActiveForm::begin([     
                                            'id' => 'form-propaganda-inline',
                                            'type' => ActiveForm::TYPE_HORIZONTAL,
                                            'formConfig' => ['showErrors' => true, 
                                            'deviceSize' => ActiveForm::SIZE_SMALL, 
                                            'labelSpan' => 2,
                                            'showLabels' => true]
                                    ]);
   
    ?>
    
    <?= Html::activeHiddenInput($model, 'ano_impo', ['value' => '0']) ?>
    
    <?php   
            /**
            *   Ciclo para obtener los id seleccionados en campos dinamicos que estan
            *   ocultos, ya que le paso el contador para incrementar
            * 
            */
            $c = 0;
            if($id > 0){
                
                foreach($id as $ids){
                    
                    $cont = $c++;
                    echo Html::activeHiddenInput($model, $cont, ['value' => $ids]);
                    echo Html::activeHiddenInput($model, 'contador'.$cont, ['value' => $cont]);
                }
            }
            
            if(count($selections) > 3){ 
                   
                foreach($selections as $ids){
                    
                    $cont = $c++;
                    $condicion=$selections['contador'.$cont];
                    if ($cont <= $condicion){
                        echo Html::activeHiddenInput($model, 'contador'.$cont, ['value' => $cont]);
                        echo Html::activeHiddenInput($model, $cont, ['value' => $selections[$cont]]);
                    }
                }
            }
    ?>
    
    <div class="col-sm-10">
        <div style="margin-left:15%">
            <div class="panel panel-primary">
                <div class="panel-heading"><?=  Yii::t('backend',$this->title)?></div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <tr>
                                <td>
                                    <div>
                                        <p><i><small><?=Yii::t('backend', 'Cause Desincorparacion:') ?></small></i></p>
                                        <?php   $modelCausasDesincorporacion = CausasDesincorporacion::find()->orderBy(['causa_desincorporacion' => SORT_ASC])->asArray()->all();                                        
                                                $listaCausasDesincorporacion = ArrayHelper::map($modelCausasDesincorporacion,'causa_desincorporacion', 'descripcion');
                                        ?>
                                        <?=     $form->field($model, 'causa_desincorporacion')->label(false)->dropDownList($listaCausasDesincorporacion,[   'id'=> 'causa_disincorporacion',
                                                                                                                                                'prompt' => Yii::t('backend', 'Select'),
                                                                                                                                                'style' => 'width:100%;',
                                                                                                                                            ]);
                                        ?>
                                    </div>  	
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div>
                                        <p><i><small><?=Yii::t('backend', 'Observation:') ?></small></i></p>
                                        <?= $form->field($model, 'comentario')->label(false)->textArea(['id' => 'comentario', 'style' => 'width:100%;height:100%;text-transform:uppercase;'])?>
                                    </div>  	
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div>
                                        <?= Html::submitButton($model->isNewRecord ? 'Disable' : 'Disable', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'name' => 'btn', 'value' => 'save'])?>
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