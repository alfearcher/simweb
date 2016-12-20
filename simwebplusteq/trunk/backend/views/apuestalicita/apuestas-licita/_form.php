<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;
use backend\models\TiposApuesta;
use backend\models\Contribuyente;
use backend\models\ClasesApuesta;
use backend\models\TarifasApuesta;

$ano = date('Y')+1;
$fecha_comprobar = $ano.'-01-01';
?>

<script type = "text/javascript">

function fecha_validacion0(val) {

	var fecha = val;
	var array = fecha.split("-");

	var fecha_actual = new Date();
	var ano_actual = fecha_actual.getFullYear();

	if( array[0]  > ano_actual ) {
    
				document.getElementById("aimpositivo").value='';
    } else {
				document.getElementById("aimpositivo").value=array[0];
   } 
}
   
function puntitos( donde, caracter, campo ) {
        
    var decimales = true
    dec = campo
    pat = /[\*,\+,\(,\),\?,\\,\$,\[,\],\^]/
    valor = donde.value
    largo = valor.length
    crtr = true
    
    if( isNaN( caracter ) || pat.test( caracter ) == true ) {
        
        if ( pat.test(caracter) == true ) {
            
                caracter = '\\' + caracter
        }
                    carcter = new RegExp( caracter, 'g' )
                    valor = valor.replace( carcter, '' )
                    donde.value = valor
                    crtr = false
        } else {
                    var nums = new Array()
                    
                    cont = 0
                    for( m = 0; m < largo; m++ ) {
                
                        if( valor.charAt( m ) == ',' || valor.charAt( m ) == '' || valor.charAt( m ) == '.' ) {

                                    continue;
                        } else  {
                                    nums[cont] = valor.charAt( m )
                                    cont++
                        }
                    }
        }

        if( decimales == true ) {
            
                    ctdd = eval( 1 + dec );
                    nmrs = 1
        } else {
                    ctdd = 1 
                    nmrs = 3
        }
        
        var cad1 = '', cad2 = '', cad3 = '', tres=0
        if( largo > nmrs && crtr == true ) {
            
            for ( k = nums.length-ctdd;k>=0;k-- ) {
                cad1 = nums[k]
                cad2 = cad1 + cad2
                tres++
                
                if( ( tres%3 ) == 0 ) {
                    
                    if( k != 0 ) {
                        cad2 = '.' + cad2
                    }
                }
            }
                
            for ( dd = dec; dd > 0; dd-- ) {
                
                cad3 += nums[nums.length-dd] 
            }
            if( decimales == true ) {
                
                cad2 += ',' + cad3
            }
                donde.value = cad2
        }
            donde.focus()
}
</script>

<div class="apuestas-licita-form">
    <?php   $form = ActiveForm::begin( [	'id' => 'form-apuestas-licita-form-inline',
											'type' => ActiveForm::TYPE_HORIZONTAL,
											'formConfig' => [ 'showErrors' => true, 
											'deviceSize' => ActiveForm::SIZE_SMALL, 
											'labelSpan' => 2,
											'showLabels' => true ]
										] );
    ?>
    
    <?= Html::activeHiddenInput( $operacion, 'fecha_comprobar', [ 'value' => $fecha_comprobar ] ) ?>
    <?= Html::activeHiddenInput( $model, 'id_contribuyente', [ 'value' => $_SESSION['idContribuyente'] ] ) ?>
    
    <div class="col-sm-11" style="margin-left:5%;">
        <div class="panel panel-primary">
            <div class="panel-heading"><?=  Yii::t( 'backend',$this->title )?></div>
                <div class="panel-body" >
                            
                    <h3>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#Betting" data-toggle="tab"><p><i><small><?= Yii::t( 'backend', 'Betting' ) ?></small></i></p></a></li>

                            <?php if( $_GET['r'] == 'apuestalicita/apuestas-licita/create' ) { ?>

                                            <li class="disabled"><a href="#Historic" data-toggle="tab disabled"> <p><i><small><?= Yii::t( 'backend', 'Historics' ) ?></small></i></p></a></li>

                            <?php }else{ ?>

                                            <li><a href="#Historic" data-toggle="tab"> <p><i><small><?= Yii::t( 'backend', 'Historic' ) ?></small></i></p></a></li>

                            <?php } ?>
                        </ul>
                    </h3>
                        
                    <div class="options">
                        <a href="javascript:;"><i class="icon-cog"></i></a>
                        <a href="javascript:;"><i class="icon-wrench"></i></a> 
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="Betting">
                            <table class="table table-striped">
                                   
                                <tr>
                                    <td>
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Id Tax:' ) ?></small></i></p>
                                            <?= $form->field( $model, 'id_impuesto' )->label( false )->textInput( [ 'value' => $model->id_impuesto,'inline' => true, 'style' => 'width:100%;text-transform:uppercase;', 'readonly' => 'readonly' ] )?>	
                                        </div> 
                                    </td>
                                        
                                    <td>
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Description of the Bet:' )?></small></i></p>
                                            <?= $form->field( $model, 'descripcion' )->label( false )->textInput( [ 'inline' => true, 'style' => 'width:120%;text-transform:uppercase;' ] )?>	
                                        </div>  
                                    </td> 
                                </tr>
                                            
                                <tr>
                                    <td width="20%">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Id Sim.:' ) ?></small></i></p>
                                            <?= $form->field( $model, 'id_sim' )->label( false )->textInput( [ 'inline' => true, 'style' => 'width:100%;text-transform:uppercase;' ] )?>	
                                        </div> 
                                    </td>
                                
                                    <td width="80%">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Address:' ) ?></small></i></p>
                                            <?= $form->field( $model, 'direccion' )->label( false )->textArea( [ 'style' => 'width:120%;text-transform:uppercase;', 'maxlength' => true,'id' => 'id_tipo_propaganda' ] )?>
                                        </div> 
                                    </td>
                                </tr>
                            </table> 
                        
                            <table class="table table-striped">
                                    
                                <tr>
                                    <td width="66%">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'State&nbsp;/&nbsp;Town&nbsp;/&nbsp;Parish&nbsp;/&nbsp;Population center:' )?></small></i></p>
                                            <?= $form->field( $model, 'est_mun_parr_cp' )->label( false )->textInput( [ 'maxlength' => true, 'style' => 'width:120%;' ] )?> 
                                        </div>
                                    </td> 
                                    
                                    <td width="34">
                                        <div class="col-md-8">
                                            <p><i><small><?= Yii::t( 'backend', 'Location:' )?></small></i></p>
                                            <?= $form->field( $model, 'id_cp' )->label( false )->textInput( [ 'maxlength' => true, 'style' => 'width:110%;' ] )?> 
                                        </div>
                                 
                                        <div class="col-md-3"> 
                                            <p><i><small><?= Yii::t('backend', '&nbsp;') ?></small></i></p>
                                            <?= Html::Button( Yii::t( 'backend', 'UPJ' ), [ 'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary' ] )?>
                                        </div> 
                                    </td> 
                                </tr>
                                                         
                                <tr>
                                    <td colspan="2">
                                        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                                        
										<?php if( $_GET['r'] == 'apuestalicita/apuestas-licita/update' ) { ?>
								
																								<?= Html::a(Yii::t('backend', 'Quit'), ['apuestalicita/apuestas-licita/index'], ['class' => 'btn btn-danger']) ?>
            					<?php } else { ?>
																								<?= Html::a(Yii::t('backend', 'Quit'), ['menu/vertical'], ['class' => 'btn btn-danger']) ?>
								<?php } ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                                
						<div class="tab-pane" id="Historic">
                            <table class="table table-striped" border="0px">
                                    
                                <tr>
                                    <td width="20%">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Id Tax:' ) ?></small></i></p>
                                            <?= $form->field( $operacion, 'id_impuesto' )->label( false )->textInput( [ 'value' => $model->id_impuesto,'inline' => true, 'style' => 'width:100%;text-transform:uppercase;', 'readonly' => 'readonly' ] )?>	
                                        </div> 
                                    </td>
                                
                                    <td width="80%">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Class:' ) ?></small></i></p>
                                            <?php   $modelClasesApuesta = ClasesApuesta::find()->orderBy( [ 'clase_apuesta' => SORT_ASC ] )->asArray()->all();                                         
                                                    $listaClasesApuesta = ArrayHelper::map( $modelClasesApuesta, 'clase_apuesta', 'descripcion' ); 
                                            ?>
                                            <?= $form->field( $operacion, 'clase_apuesta' )->label( false )->dropDownList( $listaClasesApuesta, [   'id'=> 'claseapuesta', 
                                                                                                                                                    'prompt' => Yii::t('backend', 'Select'),
                                                                                                                                                    'style' => 'width:120%;',
                                                                                                                                                    'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl( 'tipos-apuesta/lists' ) . '&id=' . '" + $(this).val(), function( data ) {$( "select#tipoapuesta" ).html( data );});' 
                                                                                                                                                ] )
                                            ?> 
                                        </div> 
                                    </td>
                                </tr>
                                   
                                <tr>
                                    <td colspan="3">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Types:' ) ?></small></i></p>
                                            <?php   $modelTipoApuesta = TiposApuesta::find()->orderBy( [ 'tipo_apuesta' => SORT_ASC ])->asArray()->all();                                         
                                                    $listaTipoApuesta = ArrayHelper::map( $modelTipoApuesta, 'tipo_apuesta', 'descripcion' ); 
                                            ?>
                                            <?= $form->field( $operacion, 'tipo_apuesta' )->label( false )->dropDownList( $listaTipoApuesta,  [     'id' => 'tipoapuesta',
                                                                                                                                                    'prompt' => Yii::t( 'backend', 'Select' ),
                                                                                                                                                    'style' => 'width:120%;',
                                                                                                                                                    'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl( 'tipos-apuesta/porcentaje' ) . '&id=' . '" + $(this).val(), function( data ) {$( "select#porcentaje" ).html( data );});' 
																																				] );
                                            ?>
                                        </div> 
                                    </td>
                                </tr>
                            </table>
                                    
                            <table class="table table-striped">
                            
                                <tr>
                                    <td width="40%">
                                        <div>
                                            <p><i><small><?= Yii::t( 'backend', 'Date From:' )?></small></i></p>
                                            <?= $form->field( $operacion, 'fecha_desde' )->label( false )->input( 'date',   [   'id' => 'fdesde',
                                                                                                                                'type' => 'date',
                                                                                                                                'style' => 'width:100%;',
                                                                                                                                //'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl( 'apuestailicita/apuestas-ilicita/lists' ) . '&id=' . '" + $(this).val(), function( data ) {$( "select#fdesde" ).html( data );});' 
                                                                                                                                'onchange' => 'fecha_validacion0(this.value)'
																															] )
                                            ?> 
                                        </div>
                                    </td>
                                            
                                    <td width="40%">   
                                        <div> 
                                            <p><i><small><?=Yii::t('backend', 'To Date:') ?></small></i></p>
                                            <?= $form->field( $operacion, 'fecha_hasta' )->label( false )->input( 'date',  [    'id' => 'fhasta',
                                                                                                                                'type' => 'date',
                                                                                                                                'style' => 'width:100%;',
																															] )
                                            ?> 
                                        </div> 
                                    </td> 
                                            
                                    <td width="20%">   
                                        <div class="col-md-8"> 
                                            <p><i><small><?=Yii::t('backend', 'Tax Year:') ?></small></i></p>
                                            <?= $form->field( $operacion, 'ano_impositivo' )->label( false )->textInput( [ 'id' => 'aimpositivo','maxlength' => true, 'style' => 'width:150%;', 'readonly' => 'readonly' ] )?> 
                                        </div> 
                                    </td> 
                                </tr>
                                            
                                <tr>
                                    <td  width="40%">   
                                        <div> 
                                            <p><i><small><?=Yii::t('backend', 'Amount:') ?></small></i></p>
                                            <?= $form->field( $operacion, 'monto_apuesta' )->label( false )->textInput( [ 'id' => 'aimpositivo','maxlength' => true, 'style' => 'width:100%;', 'onkeyup' => 'puntitos(this,this.value.charAt(this.value.length-1),2)' ] )?> 
                                        </div> 
                                    </td>
                                 
                                    <td width="40%">    
                                        <div> 
                                            <p><i><small><?=Yii::t('backend', '% Applied:') ?></small></i></p>
                                            <?php   $modelTarifa = TarifasApuesta::find()->orderBy( [ 'tipo_apuesta' => SORT_ASC ])->asArray()->all();                                         
                                                    $listaTarifa = ArrayHelper::map( $modelTarifa, 'tipo_apuesta', 'porcentaje' ); 
                                            ?>

                                            <?= $form->field( $operacion, 'tipo_apuesta' )->label( false )->dropDownList( $listaTarifa, [   'id' => 'porcentaje',
                                                                                                                                            'prompt' => Yii::t( 'backend', '' ),
                                                                                                                                            'style' => 'width:100%;',
                                                                                                                                            'disabled' => 'disabled',
                                                                                                                                            //'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl( 'tipos-apuesta/porcentaje' ) . '&id=' . '" + $(this).val(), function( data ) {$( "select#tipoprocentaje" ).html( data );});' 
                                                                                                                                        ] );
                                            ?>
                                        </div> 
                                    </td> 
                                            
                                    <td width="20%">
                                        
                                        <div class="col-md-5">
                                            <p><i><small><?=Yii::t('backend', '&nbsp;') ?></small></i></p>
                                            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Save') : Yii::t('backend', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success', 'name' => 'btn', 'value' => 'save' ]) ?>
                                        </div>
                                        
                                        <div class="col-md-5">
                                            <p><i><small><?=Yii::t('backend', '&nbsp;') ?></small></i></p>
                                             <?= Html::a(Yii::t('backend', 'Quit'), ['apuestalicita/apuestas-licita/index'], ['class' => 'btn btn-danger']) ?>
                                        </div>
                                     
                                    </td>
                                </tr>
                            </table>
                                    
                            <?php if( count($consulta_historico[0]) > 0)  { ?>
                                    
                                <table class="table table-striped">
                            
                                    <tr align="center">
                                        <td colspan="10">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Historical Bet ID.&nbsp;').$model->id_impuesto."." ?></small></i></p>
                                            </div>
                                        </td>
                                    </tr>
                                
                                    <tr align="center">
                                        <td width="3%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'ID:') ?></small></i></p>
                                            </div>
                                       </td>
                                            
                                        <td  width="10%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Date From:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="10%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'To Date:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="10%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Bet Amount:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="20%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Class:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="25%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Kind:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="5%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', '%:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="6%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Amount Bs:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td width="6%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Amount UT:') ?></small></i></p>
                                            </div>
                                        </td>
                                            
                                        <td  width="6%">
                                            <div>
                                                <p><i><small><?=Yii::t('backend', 'Form:') ?></small></i></p>
                                            </div>
                                        </td>
                                    </tr>
                                        
                                    <?php foreach ($consulta_historico as $row): ?>
                                        
                                        <tr align="justify" style="font-size:12px;">
                                            <td width="3%">
                                                <div>
                                                    <?php echo $row["id_impuesto"];?>
                                                </div>
                                            </td>
                                            
                                            <td width="10%">
                                                <div>
                                                    <?php echo $row["fecha_desde"];?>
                                                </div>
                                            </td>
                                            
                                             <td width="10%">
                                                <div>
                                                    <?php echo $row["fecha_hasta"];?>
                                                </div>
                                            </td>
                                            
                                            <td width="10%">
                                                <div>
                                                    <?php echo number_format($row["monto_apuesta"],2,",",".");?>
                                                </div>
                                            </td>
                                            
                                              <td width="20%">
                                                <div>
                                                    <?php echo $row["clase_apuesta"];?>
                                                </div>
                                            </td>
                                            
                                               <td width="25%">
                                                <div>
                                                    <?php echo $row["tipo_apuesta"];?>
                                                </div>
                                            </td>
                                            
                                            <td width="5%">
                                                <div>
                                                    <?php echo number_format($row["porcentaje"],4,",",".");?>
                                                </div>
                                            </td>
                                            
                                            <td width="6%">
                                                <div>
                                                    <?php echo number_format($row["montobs"],4,",",".");?>
                                                </div>
                                            </td>
                                            
                                            <td width="6%">
                                                <div>
                                                    <?php echo number_format($row["montout"],4,",",".");?>
                                                </div>
                                            </td>
                                            
                                            <td width="6%">
                                                <div>
                                                    <?php echo $row["planilla"];?>
                                                </div>
                                            </td>
                                        </tr>
                                  
                                    <?php endforeach; ?>
                                </table>
                                   
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
     </div>
        <!--/div>
    </div-->
            <?php ActiveForm::end(); ?>
</div>