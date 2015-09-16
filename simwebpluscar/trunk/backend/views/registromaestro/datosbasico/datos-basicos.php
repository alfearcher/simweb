<?php 
/**
 *	@copyright © by ASIS CONSULTORES 2012 - 2016
 *  All rights reserved - SIMWebPLUS
 */

 /**
 * 
 *	> This library is free software; you can redistribute it and/or modify it under 
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
 *	@file datos-basicos.php
 *	
 *	@author Hansel Jose Colmenarez Guevara
 * 
 *	@date 07/07/2015
 * 
 *  @class datos-basicos
 *	@brief Vista de registro de datos basicos de un contribuyente
*	@property
 *
 *  
 *	@method
 * 	  
 *	@inherits
 *	
 */

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\ArrayHelper;
	use backend\models\registromaestro\TipoNaturaleza;
	use backend\models\TelefonoCodigo;
    use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\DatosBasicoForm */
/* @var $form yii\widgets\ActiveForm */


/*
*****************************************************
*	Condicionales que permite mostrar un formulario	*
*					en especifico					*
*	Las variables vienen cargadas del controlador	*
*****************************************************
*/
if ($generalVisible == 'visible' && $visibleNatural == 'visible' && $visibleJuridico == 'oculto') {
	$noneN = '';
	$noneJ = 'none';
	$noneMsg = 'none';
}
if ($generalVisible == 'visible' && $visibleJuridico == 'visible' && $visibleNatural == 'oculto') {
	$noneJ = '';
	$noneN = 'none';
	$noneMsg = 'none';
}
if ($generalVisible == 'noVisible') {
	$noneN = 'none';
	$noneJ = 'none';
	$noneMsg = '';
}
?>
<!-- SCRIPT DE OCULTAR CAPAS -->
<!-- JQuery que permite ocultar o mostrar las capas segun el selector -->
<script type="text/javascript">
	function tipoContribuyenteOnChange(sel) {
		if (sel.value=="mensaje"){		
			$("#tmensaje").hide();
			$("#tJuridico").hide();
			$("#subMenuTipoPersona").hide();
			$("#tNatural").hide();
			$("#tmensajeJuri").hide();
			$("#paneldataBasicRegister").show();
			location.reload(true);
		}	
		if (sel.value=="juridico"){	      	
			$("#subMenuTipoPersona").show();
			$("#tmensajeJuri").show();
			$("#tmensaje").show();
			$("#tNatural").hide();
			$("#paneldataBasicRegister").show();
		}else{
			$("#tJuridico").hide();
			$("#tmensajeJuri").hide();
			$("#subMenuTipoPersona").hide();
			$("#tmensaje").hide();
			$("#paneldataBasicRegister").hide();
			$("#tNatural").show();
		}	      	
	}
	function tipoNaturalezaContribuyente(tipoContri){
		if (tipoContri.value==1){
			$("#menuTipoPersona").show();
			$("#tJuridico").show();
			$("#subMenuTipoPersona").show();			
			$("#Empresa").show();
			$("#Sucesion").hide();
			$("#tmensajeJuri").show();	      	
			$("#tmensaje").show();
			$("#tNatural").hide();
			$("#paneldataBasicRegister").hide();
			$("#sucesionLabel").hide();
			$("#empresaLabel").show();
		}if (tipoContri.value == 2) {
			$("#menuTipoPersona").show();
			$("#tJuridico").show();
			$("#subMenuTipoPersona").show();
			$("#Sucesion").show();
			$("#Empresa").hide();
			$("#tmensajeJuri").show();	      	
			$("#tmensaje").show();
			$("#tNatural").hide();
			$("#paneldataBasicRegister").hide();
			$("#empresaLabel").hide();
			$("#sucesionLabel").show();
		}if (tipoContri.value == 0) {
			location.reload(true);
		}
	}
</script>
<!-- FIN DEL SCRIPT DE OCULTAR CAPAS -->

<!-- SCRIPT DE MOSTRAR PREFIJO TELEFONO -->
<script>
    function cambio() {
        $("#tlf_ofic").val($("#codigo").val() + "-");
    }
    function cambio1() {
        $("#tlf_ofic_otro").val($("#codigo_otro").val() + "-");
    }

    function cambioCelu1() {
        $("#tlf_celular").val($("#codigo_celuNat").val() + "-");
    }
    function cambioCelu() {
        $("#tlf_celularContri").val($("#codigo_celu").val() + "-");
    }
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
<!-- FIN DEL SCRIPT DE MOSTRAR PREFIJO TELEFONO -->

<!-- VARIABLE QUE MANEJA EL MENSAJE DE ERROR -->
<?= $msg ?>

<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
	<h3><?= Yii::t('backend', 'Registration Basic Information') ?> </h3>
</div>
<div class="datosbasico-form" >
<?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoNatural-inline',
            'method' => 'post',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,
        ]);    
?>   	
</div>
<div class="row" id="contenedorSelectores">
	<!-- SELECTOR DE TIPO DE PERSONA -->
	<div class="col-xs-2" id="menuTipoPersona" style="height:40px;display:;">
		<select class="form-control" name="tipoContribuyente" onChange="tipoContribuyenteOnChange(this)">
	   	  <option value="mensaje"><?= Yii::t('backend', 'Seleccionar') ?></option>
	      <option value="juridico"><?= Yii::t('backend', 'Juridica') ?></option>
	      <option value="natural"><?= Yii::t('backend', 'Natural') ?></option> 
	   </select>
	</div>
	<div id="tmensaje" class="col-xs-2 alert alert-info" style="height:40px;display:;">
		<?= Yii::t('backend', 'Type of taxpayer') ?> <span data-toggle="tooltip" data-placement="right" title="<?= Yii::t('backend', 'Type of taxpayer') ?>" class="glyphicon glyphicon-info-sign"></span>
	</div>
	<!-- FIN SELECTOR DE TIPO DE PERSONA -->

	<!-- SUBMENU DE PERSONA JURIDICA -->
	<div class="col-xs-2" id="subMenuTipoPersona" style="height:40px;display:none;">
		<select class="form-control" name="tipoPersonaNat" onChange="tipoNaturalezaContribuyente(this)">
			<option value="0"><?= Yii::t('backend', 'Seleccionar') ?></option>
			<option value="1"><?= Yii::t('backend', 'Empresa') ?></option>
			<option value="2"><?= Yii::t('backend', 'Sucesion') ?></option>
		</select>		
	</div>
	<div class="col-xs-3 alert alert-info" id="tmensajeJuri" style="height:40px;display:none;" >
		<?= Yii::t('backend', 'Condition of the legal person') ?> <span data-toggle="tooltip" data-placement="right" title="<?= Yii::t('backend', 'Condition of the legal person') ?>" class="glyphicon glyphicon-info-sign"></span>
	</div>
	<!-- FIN DEL SUBMENU DE PERSONA JURIDICA -->
</div>
<div><br></div>

<!-- FORMULARIO PERSONA NATURAL -->
<div id="tNatural" style="display:<?= $noneN?>;" >
	<div class="col-sm-10">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?= Yii::t('backend', 'Registration Basic Information') ?> | <?= Yii::t('backend', 'Natural') ?>
			</div>
			<div class="panel-body" >
				<table class="table table-striped">
<!-- CEDULA -->
					<tr>
						<td colspan="4">
							<?= Yii::t('backend', 'Identification') ?>							
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>
					</tr>
					<tr>
						<td width="90px" >
						<?= $form->field($model, 'naturaleza')->dropDownList(
                                                                    ArrayHelper::map(TipoNaturaleza::find()->where('id_tipo_naturaleza BETWEEN 2 and 3')->all(), 'siglas_tnaturaleza', 'siglas_tnaturaleza'),
                                                                    [
                                                                    'id'=> 'naturaleza',
                                                                    ])->label(false);
						?>						
						</td>
						<td width="150px">
							<?= $form->field($model, 'cedula')->label(false)->textInput(['maxlength' => 8]) ?>
						</td>
						<td width="60px"></td>
						<td width="250px" colspan="2"></td>
						<td width="250px"></td>
						<td width="250px"></td>
					</tr>
<!-- FIN DE CEDULA -->

<!-- APELLIDOS Y NOMBRES-->
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>
					</tr>					
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
						</td>	
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>				
					</tr>
<!-- FIN DE APELLIDOS Y NOMBRES -->

<!-- FECHA DE NACIMIENTO -->
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'fecha_nac')->input('date', 
	                                                                      [
	                                                                        'id' => 'fecha-nac',
	                                                                        'type' => 'date',
	                                                                        'style' => 'width:160px;height:32px;'
	                                                                      ]); 
	                    	?>
						</td>
						<td width="150px"></td>
						<td width="250px" colspan="1"></td>
						<td width="250px"></td>				
					</tr>
<!-- FIN DE FECHA DE NACIMIENTO -->

<!-- SEXO -->
					<tr>
						<td width="170px">
						<?= $form->field($model, 'sexo')->dropDownList($model->getGenderOptions()) ?>
						</td>	
						<td width="9px"></td>
						<td width="100px"></td>
						<td width="250px" colspan="2"></td>
						<td width="250px"></td>	
						<td width="250px"></td>			
					</tr>
<!-- FIN DE SEXO -->

<!-- DOMICILIO FISCAL -->
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'domicilio_fiscal')->textArea(['maxlength' => true]) ?>
						</td>	
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>					
					</tr>
<!-- FIN DE DOMICILIO FISCAL -->

<!-- EMAIL -->
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'email')->input('email') ?>
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>	
					</tr>
<!-- FIN DE EMAIL -->

<!-- TELEFONO CELULAR -->
					<tr>
						<td colspan="4">
						<?php 
                                $listaCelularCodigo = TelefonoCodigo::getListaTelefonoCodigo($is_celular=1);
                                $mtCelular = new TelefonoCodigo();
                            ?>
							<table>
								<tr>
									<td><?= $form->field($mtCelular, 'codigo')->dropDownList($listaCelularCodigo, ['inline' => true,
                                                                                             'prompt' => Yii::t('backend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo_celuNat',
                                                                                             'onchange' => 
                                                                                                'cambioCelu1()'
                                                                                             ]
                                                                    ) 
                        				?>
                        			</td>
									<td><?= $form->field($model, 'tlf_celular')->textInput(['maxlength' => 12,
                                                                        'style' => 'width:150px;',
                                                                        'placeholder' => false,
                                                                        'id' => 'tlf_celular',
                                                                       
                                                                        ]
                                                                    ) 
                        				?>
                        			</td>
								</tr>
							</table>
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>	
					</tr>
<!-- FIN DE TELEFONO CELULAR -->

					<tr>
						<td colspan="4">
							<?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</td>
						<td width="250px"></td>	
						<td width="250px"></td>	
						<td width="250px"></td>	
					</tr>					
				</table>
			</div>
		</div>
	</div>
	<?php 
	/*
	*****************************************************
	*	Segmento para setear variables predefinidas		*
	*					Persona Natural					*
	*****************************************************
	*/
	?>
	<?= Html::activeHiddenInput($model, 'tipo_naturaleza', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'tipo', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'razon_social', ['value' => '']) ?>
	<?= Html::activeHiddenInput($model, 'tlf_ofic', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'tlf_ofic_otro', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'ente', ['value' => Yii::$app->ente->getEnte()])?>
    <?= Html::activeHiddenInput($model, 'id_rif', ['value' => '0']) ?>
    <?= Html::activeHiddenInput($model, 'id_cp', ['value' => '0']) ?>    
    <?= Html::activeHiddenInput($model, 'representante', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'nit', ['value' => '0']) ?>
    <?= Html::activeHiddenInput($model, 'casa_edf_qta_dom', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'piso_nivel_no_dom', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'apto_dom', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'catastro', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'fax', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'cuenta', ['value' => '0']) ?>
    <?= Html::activeHiddenInput($model, 'reg_mercantil', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'num_reg', ['value' => '0']) ?>
    <?= Html::activeHiddenInput($model, 'tomo', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'extension_horario', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'tipo_contribuyente', ['value' => '0']) ?>
    <?= Html::activeHiddenInput($model, 'id_sim', ['value' => '0']) ?>
    <?= Html::activeHiddenInput($model, 'manzana_limite', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'fecha_inicio', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'horario', ['value' => '']) ?>
    <?= Html::activeHiddenInput($model, 'no_declara', ['value' => '1']) ?>
	<?= Html::activeHiddenInput($model, 'no_sujeto', ['value' => '0']) ?>

	<?= Html::activeHiddenInput($model, 'tlf_hab', ['value' => '000000']) ?>
	<?= Html::activeHiddenInput($model, 'tlf_hab_otro', ['value' => '000000']) ?>  
	<?= Html::activeHiddenInput($model, 'inactivo', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'folio', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'fecha', ['value' => '00-00-0000']) ?>
	<?= Html::activeHiddenInput($model, 'capital', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'num_empleados', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'licencia', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'agente_retencion', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'lote_1', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'lote_2', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'nivel', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'lote_3', ['value' => '0']) ?> 
	<?= Html::activeHiddenInput($model, 'foraneo', ['value' => '0']) ?>    
	<?= Html::activeHiddenInput($model, 'econ_informal', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'grupo_contribuyente', ['value' => '0']) ?>
	<?= Html::activeHiddenInput($model, 'fe_inic_agente_reten', ['value' => '00-00-0000']) ?>
	<?= Html::activeHiddenInput($model, 'ruc', ['value' => '0']) ?>

	<?php 
		$fecha = date('Y-m-d'); 
		echo Html::activeHiddenInput($model, 'fecha_inclusion', ['value' => $fecha]);
	?>
	<input type="hidden" name="visible" value="natural">
	<?php 
	/*
	*****************************************************
	*		Fin del Segmento -Persona Natural-			*
	*****************************************************
	*/
	?>
</div>
<!-- FIN DEL FORMULARIO PERSONA NATURAL -->
<?php ActiveForm::end(); ?>

<!-- FORMULARIO PERSONA JURIDICA -->
<div id="tJuridico" class="datosbasico-form" style="display:<?= $noneJ?>;">
<?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,
        ]);    
?> 
	<div class="col-sm-10">
		<div class="panel panel-primary">
<!-- CARGA DINAMICAMENTE LA CONDICION DE LA PERSONA JURICA -->
			<div class="panel-heading">
				<?= Yii::t('backend', 'Registration Basic Information') ?> | <?= Yii::t('backend', 'Legal') ?> | <span id="sucesionLabel" style="display:none;"><?= Yii::t('backend', 'Succession') ?></span> <span id="empresaLabel" style="display:none;"><?= Yii::t('backend', 'Company') ?></span>
			</div>
<!-- FIN DE LA CARGA DINAMICA DE LA CONDICION DE PERSONA JURIDICA -->
			<div class="panel-body" >
				<table class="table table-striped">
<!-- RIF -->
					<tr>
						<td colspan="4">
							<?= Yii::t('backend', 'Rif') ?>							
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>
					</tr>
					<tr>
						<td width="90px">
							<?= $form->field($model, 'naturaleza')->dropDownList(
                                                                    ArrayHelper::map(TipoNaturaleza::find()->all(), 'siglas_tnaturaleza', 'siglas_tnaturaleza'),
                                                                    [
                                                                    'id'=> 'naturaleza',
                                                                    ])->label(false);
						?>
						</td>
						<td width="150px">
							<?= $form->field($model, 'cedula')->label(false)->textInput(['maxlength' => 8]) ?>
						</td>
						<td width="60px">
							<?= $form->field($model, 'tipo')->label(false)->textInput(['maxlength' => 1]) ?>
						</td>
						<td width="250px" colspan="2"></td>
						<td width="150px"></td>
						<td width="250px"></td>
					</tr>
<!-- FIN DE RIF -->

<!-- RAZON SOCIAL -->
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>
					</tr>
<!-- FIN RAZON SOCIAL -->

<!-- DOMICILIO FISCAL -->				
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'domicilio_fiscal')->textArea(['maxlength' => true]) ?>
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>					
					</tr>
<!-- FIN DEL DOMICILIO FISCAL -->

<!-- EMAIL -->
					<tr>
						<td colspan="4">
							<?= $form->field($model, 'email')->input('email') ?>
						</td>
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>					
					</tr>
<!-- FIN EMAIL -->

<!-- TELEFONO DE OFICINA Y CELULAR -->
					<tr>
						<td colspan="4">
							<?php 
                                $listaTelefonoCodigo = TelefonoCodigo::getListaTelefonoCodigo($is_celular=0);
                                $mt = new TelefonoCodigo();
                            ?>
							<table>
								<tr>
									<td><?= $form->field($mt, 'codigo')->dropDownList($listaTelefonoCodigo, ['inline' => true,
                                                                                             'prompt' => Yii::t('backend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo',
                                                                                             'onchange' => 
                                                                                                'cambio()'
                                                                                             ]
                                                                    ) 
                        				?>
                        			</td>
									<td><?= $form->field($model, 'tlf_ofic')->textInput(['maxlength' => 12,
                                                                        'style' => 'width:150px;',
                                                                        'placeholder' => false,
                                                                        'id' => 'tlf_ofic',
                                                                       
                                                                        ]
                                                                    ) 
                        				?>
                        			</td>
								</tr>
							</table>                 
						</td>	
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>				
					</tr>

					<tr>
						<td colspan="4">
							<table>
								<tr>
									<td><?= $form->field($mt, 'codigo')->dropDownList($listaTelefonoCodigo, ['inline' => true,
                                                                                             'prompt' => Yii::t('backend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo_otro',
                                                                                             'onchange' => 
                                                                                                'cambio1()'
                                                                                             ]
                                                                    ) 
                        				?>
                        			</td>
									<td><?= $form->field($model, 'tlf_ofic_otro')->textInput(['maxlength' => 12,
                                                                        'style' => 'width:150px;',
                                                                        'placeholder' => false,
                                                                        'id' => 'tlf_ofic_otro',                                                                       
                                                                        ]
                                                                    ) 
                        				?>
                        			</td>
								</tr>
							</table>                 
						</td>	
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>			
					</tr>

					<tr>
						<td colspan="4">
							<?php 
                                $listaTelefonoCodigoCelu = TelefonoCodigo::getListaTelefonoCodigo($is_celular=1);
                                $mtCelu = new TelefonoCodigo();
                            ?>
							<table>
								<tr>
									<td><?= $form->field($mtCelu, 'codigo')->dropDownList($listaTelefonoCodigoCelu, ['inline' => true,
                                                                                             'prompt' => Yii::t('backend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo_celu',
                                                                                             'onchange' => 
                                                                                                'cambioCelu()'
                                                                                             ]
                                                                    ) 
                        				?>
                        			</td>
									<td><?= $form->field($model, 'tlf_celular')->textInput(['maxlength' => 12,
                                                                        'style' => 'width:150px;',
                                                                        'placeholder' => false,
                                                                        'id' => 'tlf_celularContri',
                                                                       
                                                                        ]
                                                                    ) 
                        				?>
                        			</td>
								</tr>
							</table>                 
						</td>	
						<td width="9px"></td>
						<td width="150px"></td>
						<td width="250px"></td>
					</tr>
<!-- FIN TELEFONO OFICINA Y CELULAR -->

					<tr>
						<td colspan="4">
							<?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
						</td>
						<td width="250px"></td>	
						<td width="250px"></td>	
						<td width="250px"></td>
					</tr>					
				</table>
			</div>
		</div>
	</div>
	<?php 
	/*
	*****************************************************
	*	Segmento para setear variables predefinidas		*
	*				Persona Juridica					*
	*****************************************************
	*/
	?>
	<?= Html::activeHiddenInput($model, 'tipo_naturaleza', ['value' => '1']) ?>
	<div id="Empresa" style="display:none;">
		<?= Html::activeHiddenInput($model, 'no_declara', ['value' => '0']) ?>
	    <?= Html::activeHiddenInput($model, 'no_sujeto', ['value' => '0']) ?>
    </div>
    <div id="Sucesion" style="display:none;">
		<?= Html::activeHiddenInput($model, 'no_declara', ['value' => '1']) ?>
	    <?= Html::activeHiddenInput($model, 'no_sujeto', ['value' => '1']) ?>
    </div>

	<?php 
	/*
	*****************************************************
	*		Fin del Segmento -Persona Juridica-			*
	*****************************************************
	*/
	?>
</div>
<?php 
/*
*****************************************************
*	Segmento para setear variables predefinidas		*
*				Fecha de Registro					*
*****************************************************
*/
$fecha = date('Y-m-d');
?>
<!-- WIDGET DE CALENDARIO (FECHA Y HORA) -->
<div id="fechaRegistro" style="z-index:9999;float:right;width:170px;padding-right: -50px;margin-top:30px">		
	<div class="alert panel panel-primary alert-dismissable">
		<button type="button" class="close" data-dismiss="alert">&times;</button>		
		<table align="center" border='0' width="130px">
			<thead>
				<th colspan="6"><?= Yii::t('backend', 'Registration date') ?>:</th>
			</thead>
			<tbody>
				<tr>
					<td colspan="6"><?= $fecha?></td>
				</tr>
				<tr>					
					<td><div style="width:5px" id='hora'></div></td>
					<td><div style="width:2px">:</div></td>
					<td><div style="width:5px" id='minuto'></div></td>
					<td><div style="width:2px">:</div></td>
					<td><div style="width:5px" id='segundo'></div></td>
					<td><div style="width:15px"></div></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!-- FIN DEL WIDGET DE CALENDARIO (FECHA Y HORA) -->
<?php 		   
   echo Html::activeHiddenInput($model, 'fecha_inclusion', ['value' => $fecha]);
/*
*****************************************************
*		Fin del Segmento -Fecha de Registro-		*
*****************************************************
*/
?>
<!-- SCRIPT DEL WIDGET CALENDARIO -->		
<script type="text/javascript">
	Reloj(); // PERMITE LA RECARGAR LOS MINUTOS Y SEGUNDOS AUTOMATICAMENTE
	function Reloj() {
		var tiempo = new Date(); // SE CREA LA INSTANCIA DE LA CLASE PROPIA DE JQUERY DATE
		var hora = tiempo.getHours(); // METODO PROPIO DE JQ PARA OBTENER LA HORA
		var minuto = tiempo.getMinutes(); // METODO PROPIO DE JQ PARA OBTENER EL MINUTO
		var segundo = tiempo.getSeconds(); // METODO PROPIO DE JQ PARA OBTENER LOS SEGUNDOS
		document.getElementById('hora').innerHTML = hora;
		document.getElementById('minuto').innerHTML = minuto;
		document.getElementById('segundo').innerHTML = segundo;
		setTimeout('Reloj()', 1000);
		str_hora = new String(hora);
		if (str_hora.length == 1) {
			document.getElementById('hora').innerHTML = '0' + hora;
		}
		str_minuto = new String(minuto);
		if (str_minuto.length == 1) {
			document.getElementById('minuto').innerHTML = '0' + minuto;
		}
		str_segundo = new String(segundo);
		if (str_segundo.length == 1) {
			document.getElementById('segundo').innerHTML = '0' + segundo;
		}
	}
</script>
<!-- FIN DEL SCRIPT DEL WIDGET CALENDARIO -->
<?php 
/*
*****************************************************
*	Segmento para setear variables predefinidas		*
*			 		JURIDICA						*
*****************************************************
*/
?>
<?= Html::activeHiddenInput($model, 'ente', ['value' => Yii::$app->ente->getEnte()])?>
<?= Html::activeHiddenInput($model, 'nombres', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'apellidos', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'sexo', ['value' => '']) ?>   
<?= Html::activeHiddenInput($model, 'representante', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'casa_edf_qta_dom', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'piso_nivel_no_dom', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'apto_dom', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'catastro', ['value' => '']) ?>  
<?= Html::activeHiddenInput($model, 'fax', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'reg_mercantil', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'tomo', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'extension_horario', ['value' => '']) ?>
<?= Html::activeHiddenInput($model, 'manzana_limite', ['value' => '0']) ?>   
<?= Html::activeHiddenInput($model, 'fecha_inicio', ['value' => '00-00-0000']) ?>
<?= Html::activeHiddenInput($model, 'horario', ['value' => '']) ?>

<?= Html::activeHiddenInput($model, 'num_reg', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'cuenta', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'id_rif', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'id_cp', ['value' => '0']) ?> 
<?= Html::activeHiddenInput($model, 'nit', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'tlf_hab', ['value' => '000000']) ?>
<?= Html::activeHiddenInput($model, 'tlf_hab_otro', ['value' => '000000']) ?>  
<?= Html::activeHiddenInput($model, 'inactivo', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'folio', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'fecha', ['value' => '00-00-0000']) ?>
<?= Html::activeHiddenInput($model, 'capital', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'num_empleados', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'tipo_contribuyente', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'licencia', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'agente_retencion', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'id_sim', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'lote_1', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'lote_2', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'nivel', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'lote_3', ['value' => '0']) ?> 
<?= Html::activeHiddenInput($model, 'foraneo', ['value' => '0']) ?>    
<?= Html::activeHiddenInput($model, 'econ_informal', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'grupo_contribuyente', ['value' => '0']) ?>
<?= Html::activeHiddenInput($model, 'fe_inic_agente_reten', ['value' => '00-00-0000']) ?>
<?= Html::activeHiddenInput($model, 'ruc', ['value' => '0']) ?>
<input type="hidden" name="visible" value="juridico">
<?php 
/*
*****************************************************
*		Fin del Segmento -Persona Juridica-			*
*****************************************************
*/
?>
<!-- FIN FORMULARIO PERSONA JURIDICA -->
<?php ActiveForm::end(); ?>