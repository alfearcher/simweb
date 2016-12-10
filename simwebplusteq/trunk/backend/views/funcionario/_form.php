<?php 
/**
 *  @copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *  > This library is free software; you can redistribute it and/or modify it under 
 *  > the terms of the GNU Lesser Gereral Public Licence as published by the Free 
 *  > Software Foundation; either version 2 of the Licence, or (at your opinion) 
 *  > any later version.
 *  > 
 *  > This library is distributed in the hope that it will be usefull, 
 *  > but WITHOUT ANY WARRANTY; without even the implied warranty of merchantability 
 *  > or fitness for a particular purpose. See the GNU Lesser General Public Licence 
 *  > for more details.
 *  > 
 *  > See [LICENSE.TXT](../../LICENSE.TXT) file for more information.
 *
 */

 /**    
 *  @file _form.php
 *  
 *  @author Jose Rafael Perez Teran
 * 
 *  @date 07-07-2015
 * 
 *  @view _form.php
 *  @brief vista del formualario que se utilizara para capturar los datos a guardar.
 * 
 *  
 *  @property
 *
 *  
 *  @method
 *  
 *  
 *  @inherits
 *  
 */

	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use backend\models\Departamento;
	use backend\models\UnidadDepartamento;
	use backend\models\TelefonoCodigo;
	use backend\models\Nivel;
	use backend\models\TipoNaturaleza;
	use kartik\icons\Icon;
	use yii\web\View;		

 ?>
 <?= $msg ?>
 <div class="funcionario-form">
 	<?php 

 		$form = ActiveForm::begin([
 			'id' => 'funcionario-form-create',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => true,
 			'enableClientScript' => true,
                                                                                            
 		]);

 	 ?>

	<div class="col-lg-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= $this->title ?>
            </div>
            <div class="panel-body" >
                <table class="table table-striped">

<!-- CEDULA DE IDENTIDAD -->
	                	<?php 
	                		$modeloTipoNaturaleza = TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all();
			            	$listaNaturaleza = ArrayHelper::map($modeloTipoNaturaleza, 'siglas_tnaturaleza', 'nb_naturaleza');	
			            ?>
			           
			            <div class="form-group">
							<td>
	        					<p><i><small><?=Yii::t('backend', 'Identification') ?></small></i></p>
	        				</td>
	        				<td colspan="3">
		                      	<div class="naturaleza">
			                        <?= $form->field($model, 'naturaleza')->dropDownList($listaNaturaleza,[
			                        																//'inline' => true,
			                                                                                               	'prompt' => Yii::t('backend', 'Select'), 
			                                                                                                'style' => 'height:32px;
			                                                                                                			width:140px;
			                                                                                                			'
			                                                                                             ])->label(false) 
		                			?>
	                			</div>
	                			<div class="cedula" style="float:left; margin-top:-47px; margin-left: 145px; ">
	                				<?= $form->field($model, 'ci')->textInput(['id' => 'ci', 'maxlength' => 8, 'style' => 'width:140px;height:32px;'])->label(false) ?>
	                			</div>
	                		</td>
						</div>
					
<!-- FIN DE CEDULA -->

<!-- APELLIDOS Y NOMBRES-->
					<div class="form-group">
						<tr>

						<!-- Apellidos -->
							<td>
								<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('apellidos')) ?></small></i></p>
							</td>
							<td>
								<?= $form->field($model,'apellidos')->textInput(['id' => 'apellidos','style' => 'width:260px;height:32px;'])->label(false)  ?>
							</td>

						<!-- Nombres -->
							<td>
								<div class="nombres" style="float: right">
									<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('nombres')) ?></small></i></p>
								</div>
							</td>
							<td>
								<?= $form->field($model,'nombres')->textInput(['id' => 'nombres','style' => 'width:260px;height:32px;'])->label(false)  ?>
							</td>

						</tr>
					</div>
<!-- FIN DE APELLIDOS Y NOMBRES-->

<!-- DEPARTAMENTO Y UNIDAD DE TRABAJO -->
					<div class="form-group">
						<tr>
							<?php 
		                        $modelDepartamento = Departamento::find()->where(['inactivo' => 0])->asArray()->all();                                         
		                        $listaDepartamento = ArrayHelper::map($modelDepartamento,'id_departamento','descripcion'); 
		                    ?>
		                    <td>
								<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('id_departamento')) ?></small></i></p>
							</td>
							<td>
								<?php $url = Yii::$app->urlManager->createUrl('unidad-departamento/lists');   
							
								?>
                                <?= $form->field($model, 'id_departamento')->dropDownList($listaDepartamento, [
                                                                                                        'id'=> 'departamentos', 
                                                                                                        'prompt' => Yii::t('backend', 'Select'),
                                                                                                        'style' => 'width:280px;',
                                                                                                        'onchange' => 
                                                                                                          '$.post( "' . Yii::$app->urlManager
                                                                                                                                   ->createUrl('unidad-departamento/lists') . '&id=' . '" + $(this).val(), function( data ) {
                                                                                                                                                                                                            $( "select#unidades" ).html( data );
                                                                                                                                                                                                        });'
                                                                                                        ])->label(false); 
                                ?>
                            </td>
<!-- Unidad de trabajo -->
							<td>
								<div class="unidades" style="float: right">
									<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('id_unidad')) ?></small></i></p>
								</div>
							</td>
							<td>
                                <?= $form->field($model, 'id_unidad')->dropDownList([],
	                                                                                    [
	                                                                                        'prompt' => Yii::t('backend', 'Select'),
	                                                                                        'id'=> 'unidades',
	                                                                                    ])->label(false);
                                ?>	
							</td>
						</tr>
					</div>
<!-- FIN DE DEPARTAMENTO Y UNIDAD DE TRABAJO -->

<!-- CARGO QUE OCUPA Y NIVELES -->
		<!-- Cargo que Ocupa -->
					<div class="form-group">
						<tr>
							<td>
								<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('cargo')) ?></small></i></p>
							</td>
							<td>
								<?= $form->field($model, 'cargo')->textInput(['id' => 'cargo','style' => 'width:360px;height:32px;'])->label(false) ?>
							</td>							
		<!-- Niveles (Roles) -->
							<?php $listaNiveles = ArrayHelper::map(Nivel::find()->asArray()->all(),'nivel', 'descripcion')  ?>	

							<td>
								<div class="niveles" style="float: right">
									<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('niveles_nivel')) ?></small></i></p>
								</div>
							</td>
							<td>
								<?= $form->field($model, 'niveles_nivel')->dropDownList($listaNiveles, [
                                                                                                'inline' => true,
                                                                                                'id' => 'niveles',
                                                                                                'prompt' => Yii::t('backend', 'Select'),
                                                                                                'style' => 'width: 150px;',
                                                                                            ])->label(false); 

                                ?>    
							</td>
						</tr>
					</div>		
<!-- FIN DE CARGO QUE OCUPA Y NIVELES -->

<!-- FECHA DE INICIO -->
					<div class="form-group">
						<tr>
                            <td>
								<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('fecha_inicio')) ?></small></i></p>
							</td>	
                            <td colspan="3">
								

                            	<?= $form->field($model, 'fecha_inicio')->input('date', 
                            													      [
                            													       	'id' => 'fecha-inicio',
                                    													'type' => 'date',
                                    													//'onchange' => 'mifecha()',
                                    													'style' => 'width:160px;height:32px;'
                                                                                      ])->label(false);
                                    ?>
                            </td>
                        </tr>
                    </div>
<!-- FIN DE FECHA DE INICIO -->

<!-- EMAIL Y TELEFONO -->
			<!-- email -->
					<div class="form-group">
						<tr>
							<td>
								<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('email')) ?></small></i></p>
							</td>	
							<td>
								<?= $form->field($model, 'email')->textInput(['id' => 'email','style' => 'width:360px;height:32px;'])->label(false)  ?>
							</td>

			<!-- Telefono -->
							<?php 
                            	$listaTelefonoCodigo = TelefonoCodigo::getListaTelefonoCodigo(true);
                                $mt = new TelefonoCodigo();
                            ?>
							<td>
								<div class="codigo" style="float: right">
									<p><i><small><?=Yii::t('backend', $model->getAttributeLabel('celular')) ?></small></i></p>
								</div>
							</td>	
                            <td>
                            	<div class="lista-telefono">
                                <?= $form->field($mt, 'codigo')->dropDownList($listaTelefonoCodigo, [
                                																	 //'inline' => true,
                                                                                                     'prompt' => Yii::t('backend', 'Select'), 
                                                                                                     'style' => 'width:100px;',
                                                                                                     'id' => 'codigo',
                                                                                                     'onchange' => 'cambio()'
                                                                                                    ])->label(false) 
                                ?>
                            	</div>
                            	<div class="celular" style="float:left; margin-top:-49px; margin-left: 103px; ">
                                <?= $form->field($model, 'celular')->textInput(['id' => 'celular',
                                	                                            'maxlength' => 12,
                                                                                'style' => 'width:150px;',
                                                                                'placeholder' => false,                                                                                         
                                                                                ])->label(false)
                                                                                         
                                ?>
                                </div>
							</td>
						</tr>
					</div>
<!-- FIN DE EMAIL Y TELEFONO -->
					<tr>
						<td colspan="5">
							<div class="form-group">
								<?= Html::submitButton(Yii::t('backend', 'Create'), 
																			[
																				'id' => 'btn-create',
																				'class' => 'btn btn-success',
																				'name' => 'btn-create',
																			])
								?>
							</div>	  
						</td>
					</tr>
                </table>
            </div> <!-- panel-body -->
        </div>	<!-- panel panel-primary -->
    </div>	<!-- col-lg-10 -->
     <?php ActiveForm::end(); ?>
 </div> 	
 
 <script>
    function cambio() {
        $("#celular").val($("#codigo").val() + "-");
    }

</script>

<script> 
	$(function()  {
    		$( "#fecha_inicio" ).datepicker({ dateFormat: "dd-mm-yy",
    		changeMonth: true,
      		changeYear: true,

      		 });

</script>