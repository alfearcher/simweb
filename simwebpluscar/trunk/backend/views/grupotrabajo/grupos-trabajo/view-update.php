<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\VehiculosForm */

?>

<div class="col-sm-8" style="margin-left:20%">
    <div class="panel panel-primary">
        <div class="panel-heading"><?=  Yii::t( 'backend', 'View Groups of Modified Work:&nbsp;'.$model[0]['id_grupo'])?></div>
            <div class="panel-body" >
                <table class="table table-striped">

                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Id Workgroup:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">
                            <div><?= $model[0]['id_grupo'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Group Description:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['descripcion']?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Description Department:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['departamento']?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Description Unit:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['unidad']?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><div><?=  Yii::t( 'backend', 'Status Group:' )?></div></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?php if($model[0]['inactivo'] == 0){ echo "ACTIVO";}else{ echo "INACTIVO";}?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Date Creation:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['fecha']?></div>
                        </td>   
                    </tr>
                    
                   <tr>
                        <td colspan="2">
                            <?= Html::a(Yii::t('backend', 'Quit'), ['grupotrabajo/grupos-trabajo/index'], ['class' => 'btn btn-danger']) ?>
                        </td>   
                    </tr>
                </table>
            </div>
    </div>        
</div>

