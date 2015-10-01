<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="col-sm-8" style="margin-left:20%">
    <div class="panel panel-primary">
        <div class="panel-heading"><?=  Yii::t( 'backend', 'View History Log Bet:&nbsp;'.$model[0]['id_impuesto'])?></div>
            <div class="panel-body" >
                <table class="table table-striped">

                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Id Tax:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">
                            <div><?= $model[0]['id_impuesto'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Class Bet:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">
                            <div><?= $model[0]['clase'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Type Bet:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['tipo'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Date From:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['fecha_desde'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'To Date:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                           <div><?= $model[0]['fecha_hasta'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Amount Bet:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= number_format($model[0]['monto_apuesta'],2,",",".");?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', '% Applied::' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['porcentaje'];?></div>
                        </td>   
                    </tr>
                    
                   <tr>
                        <td colspan="2">
                            <?= Html::a(Yii::t('backend', 'Quit'), ['apuestalicita/apuestas-licita/index'], ['class' => 'btn btn-danger']) ?>
               
                        </td>   
                    </tr>
                </table>
            </div>
    </div>        
</div>
