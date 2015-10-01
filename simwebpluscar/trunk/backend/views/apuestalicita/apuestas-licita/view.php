<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="col-sm-8" style="margin-left:20%">
    <div class="panel panel-primary">
        <div class="panel-heading"><?=  Yii::t( 'backend', 'View Lawful Bets:&nbsp;'.$model[0]['id_impuesto'])?></div>
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
                                <p><i><small><?=  Yii::t( 'backend', 'Taxpayer Name:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">
                            <div><?= $model[0]['razon_social'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Description of Lawful Bets:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['descripcion'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Address:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['direccion'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Status Bet:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                           <div><?php if($model[0]['status_apuesta'] == 0){ echo "ACTIVO";}else{ echo "INACTIVO";}?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Date Creation:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['fecha_creacion'];?></div>
                        </td>   
                    </tr>
                    
                   <tr>
                        <td colspan="2">
							<?php if( $create == 1 ) { ?>
															<?= Html::a(Yii::t('backend', 'Quit'), ['apuestalicita/apuestas-licita/create'], ['class' => 'btn btn-danger']) ?>
							<?php } else { ?>
															<?= Html::a(Yii::t('backend', 'Quit'), ['apuestalicita/apuestas-licita/index'], ['class' => 'btn btn-danger']) ?>
							<?php } ?>
                        </td>   
                    </tr>
                </table>
            </div>
    </div>        
</div>

