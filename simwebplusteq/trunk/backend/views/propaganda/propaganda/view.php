<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="col-sm-8" style="margin-left:20%">
    <div class="panel panel-primary">
        <div class="panel-heading"><?=  Yii::t( 'backend', 'View Advertisement:&nbsp;'.$model[0]['id_impuesto'])?></div>
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
                                <p><i><small><?=  Yii::t( 'backend', 'Tax Year:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">
                            <div><?= $model[0]['ano_impositivo'];?></div>
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
                                <p><i><small><?=  Yii::t( 'backend', 'Class:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['clase'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Use:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['uso'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Start Date:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                           <div><?= $model[0]['fecha_desde'];?></div>
                        </td>   
                    </tr>
                    
                    <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Quantity:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['cantidad_tiempo'];?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Lapse:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['tiempo'] );?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Date End:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['fecha_fin'];?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Number:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= number_format( $model[0]['cantidad_base'],2,",","." );?></div>
                        </td>   
                    </tr>
                    
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Base:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['base'] );?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Units:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['cantidad_propagandas'];?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Cigarettes or Tobacco:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?php if($model[0]['cigarros'] == 0){ echo "NO";}else{ echo "SI";}?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Alcoholic Beverages:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?php if($model[0]['bebidas_alchoholicas'] == 0){ echo "NO";}else{ echo "SI";}?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Foreign Language:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?php if($model[0]['idioma'] == 0){ echo "NO";}else{ echo "SI";}?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Id Sim.:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['id_sim'];?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Kind:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['tipo'] );?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Through Construction:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['medio'] );?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Transport Means:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['transporte'] );?></div>
                        </td>   
                    </tr>
					
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Address:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['direccion'] );?></div>
                        </td>   
                    </tr>
                    
					<tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Observation:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= strtoupper( $model[0]['observacion'] );?></div>
                        </td>   
                    </tr>
					
                   <tr align="center">
                        <td width="50%">
                            <div>
                                <p><i><small><?=  Yii::t( 'backend', 'Location:' )?></p></i></small>
                            </div>
                        </td>
                            
                        <td width="50%">    
                            <div><?= $model[0]['id_cp'];?></div>
                        </td>   
                    </tr>
					
                   <tr>
                        <td colspan="2">
							<?php if( $create == 1 ) { ?>
															<?= Html::a(Yii::t('backend', 'Quit'), ['propaganda/propaganda/create'], ['class' => 'btn btn-danger']) ?>
							<?php } else { ?>
															<?= Html::a(Yii::t('backend', 'Quit'), ['propaganda/propaganda/index'], ['class' => 'btn btn-danger']) ?>
							<?php } ?>
                        </td>   
                    </tr>
                </table>
            </div>
    </div>        
</div>

