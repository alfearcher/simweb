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
 *  @file datos-basicos.php
 *  
 *  @author Manuel Alejandro Zapata Canelon
 * 
 *  @date 14/01/2016
 * 
 *  @class datos-basicos
 *  @brief Vista de registro de datos basicos de un Usuario Juridico
*   @property
 *
 *  
 *  @method
 *    
 *  @inherits
 *  
 */

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use backend\models\registromaestro\TipoNaturaleza;
    use backend\models\TelefonoCodigo;
    use yii\helpers\Url;
    use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\DatosBasicoForm */
/* @var $form yii\widgets\ActiveForm */


/*
*****************************************************
*   Condicionales que permite mostrar un formulario *
*                   en especifico                   *
*   Las variables vienen cargadas del controlador   *
*****************************************************
*/

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
 <?//= $msg ?>

<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('backend', 'Registration Basic Information') ?> </h3>
</div>

    <div id="tJuridico" class="datosbasico-form" style="display:<?//= $noneJ?>;">
            <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);    
       
?> 
 </div>
    <!-- FIN SELECTOR DE TIPO DE PERSONA -->

    

<div><br></div>



<!-- FORMULARIO PERSONA NATURAL -->

 
           

<div id="tNatural" style="display:<?//= $noneN?>;" >

 <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/usuario/crear-usuario-natural/natural'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'enableClientScript' => true,

        ]);    
        
?> 

    <div class="col-sm-10">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('backend', 'Registration Basic Information') ?> | <?= Yii::t('backend', 'Natural') ?>
            </div>
            <div class="panel-body" >
                <table class="table table-striped">
<!-- RIF -->
                    
                        <div>
                            <?= Yii::t('frontend', 'Rif') ?>                         
                        </div>
                   
                        <div class="row">
                        <div class="col-sm-2">
                            <?= $form->field($model, 'naturaleza')->textInput(
                                                                   // ArrayHelper::map(TipoNaturaleza::find()->all(), 'siglas_tnaturaleza', 'siglas_tnaturaleza'),
                                                                    [
                                                                    'id'=> 'naturaleza',
                                                                    'value' => $model->naturaleza,
                                                                     'readOnly' =>true,
                                                                    ])->label(false);
                        ?>
                        </div>
                       <div class="col-sm-3">
                            <?= $form->field($model, 'cedula')->label(false)->textInput(['maxlength' => 8, 'value'=> $cedula, 'readOnly' =>true]) ?>
                        
                        </div>

                         <div class="col-sm-2">
                            <?= $form->field($model, 'tipo')->label(false)->textInput(['maxlength' => 1, 'value'=> $tipo, 'readOnly' =>true]) ?>
                          
                       </div>
                       </div>


                   
<!-- FIN DE RIF <--></-->

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
                           <div class="fecha-nac">
                          <?= $form->field($model, 'fecha_nac')->widget(\yii\jui\DatePicker::classname(),['id' => 'fecha_nac',
                                                                                    'clientOptions' => [
                                                                                    'maxDate' => '+0d', // Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                     ],
                                                                                    'language' => 'es-ES',
                                                                                    'dateFormat' => 'dd-MM-yyyy',
                                                                                    'options' => [
                                                                                        'class' => 'form-control',
                                                                                        //'readonly' => true,
                                                                                        'style' => 'background-color: white;',

                                                                                ]
                                                                                ]); ?>
                                                    </div>

                        </td>
                        <td width="150px"></td>
                        <td width="250px" colspan="1"></td>
                        <td width="250px"></td>             
                    </tr>
<!-- FIN DE FECHA DE NACIMIENTO -->

<!-- SEXO -->
                    <tr>
                        <td width="170px">
                        <?= $form->field($model, 'sexo')->dropDownList($model->getGenderOptions(), ['prompt' => 'Select']) ?> 
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
                            <?= Html::submitButton(Yii::t('frontend', 'Create') , ['class' =>'btn btn-success']) ?>
                        <td width="250px"></td> 
                        <td width="250px"></td> 
                        <td width="250px"></td> 
                    </tr>                   
                </table>
            </div>
        </div>
    </div>
</div>

    <?php 
    /*
    *****************************************************
    *   Segmento para setear variables predefinidas     *
    *                   Persona Natural                 *
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
    *       Fin del Segmento -Persona Natural-          *
    *****************************************************
    */
    ?>
</div>
<!-- FIN DEL FORMULARIO PERSONA NATURAL -->
<?php ActiveForm::end(); ?>