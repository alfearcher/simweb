<?php 


    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use backend\models\registromaestro\TipoNaturaleza;
    use frontend\models\usuario\TelefonoCodigo;
    use yii\helpers\Url;
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
           // $("#tNatural").hide();
            $("#tmensajeJuri").hide();
            $("#paneldataBasicRegister").show();
            location.reload(true);
        }   
        if (sel.value=="juridico"){         
            $("#subMenuTipoPersona").show();
            $("#tmensajeJuri").show();
            $("#tmensaje").show();
           // $("#tNatural").hide();
            $("#paneldataBasicRegister").show();
        }else{
            $("#tJuridico").hide();
            $("#tmensajeJuri").hide();
            $("#subMenuTipoPersona").hide();
            $("#tmensaje").hide();
            $("#paneldataBasicRegister").hide();
            //$("#tNatural").show();
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


<!-- FIN DEL SCRIPT DE MOSTRAR PREFIJO TELEFONO -->

<!-- VARIABLE QUE MANEJA EL MENSAJE DE ERROR -->
 <?//= $msg ?>

<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('backend', 'Registration Basic Information') ?> </h3>
</div>
<div class="datosbasico-form" >

</div>

    <!-- FIN SELECTOR DE TIPO DE PERSONA -->

    
</div>
<div><br></div>



<!-- FORMULARIO PERSONA JURIDICA -->
    <div id="tJuridico" class="datosbasico-form" style="display:<?//= $noneJ?>;">
            <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/usuario/crear-usuario-juridico/juridico'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'enableClientScript' => true,

        ]);    
        
?> 

<?= $form->field($model, 'id_contribuyente')->hiddenInput(['id' => 'id_contribuyente'])->label(false); ?>
<?= $form->field($model, 'naturaleza')->hiddenInput(['naturaleza' => 'naturaleza'])->label(false); ?>
<?= $form->field($model, 'id_rif')->hiddenInput(['id_rif' => 'id_rif'])->label(false); ?>
    <div class="row">
    <div class="col-sm-7">
        <div class="panel panel-primary">
<!-- CARGA DINAMICAMENTE LA CONDICION DE LA PERSONA JURICA -->
            <div class="panel-heading">
                <?= Yii::t('backend', 'Registration Basic Information') ?> | <?= Yii::t('frontend', 'Legal') ?> | <span id="sucesionLabel" style="display:none;"><?= Yii::t('backend', 'Succession') ?></span> <span id="empresaLabel" style="display:none;"><?= Yii::t('backend', 'Company') ?></span>
            </div>
<!-- FIN DE LA CARGA DINAMICA DE LA CONDICION DE PERSONA JURIDICA -->

            <div class="panel-body" >


               
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
                                                                    'value' => $rifJuridico['naturaleza'],
                                                                     'readOnly' =>true,
                                                                    ])->label(false);
                        ?>
                       </div>
                       <div class="col-sm-3">
                            <?= $form->field($model, 'cedula')->label(false)->textInput(['maxlength' => 8, 'value'=> $rifJuridico['cedula'], 'readOnly' =>true,'style' => 'margin-left:-25px;']) ?>
                        
                       </div>

                         <div class="col-sm-2">
                            <?= $form->field($model, 'tipo')->label(false)->textInput(['maxlength' => 1, 'value'=> $rifJuridico['tipo'], 'readOnly' =>true,'style' => 'margin-left:-50px;']) ?>
                          
                        </div>
                        </div>


                   
<!-- FIN DE RIF -->



<!-- RAZON SOCIAL -->
                    
                       <div class="row">
                       <div class="col-sm-6">
                            <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
                       </div>
                       </div>

                   
<!-- FIN RAZON SOCIAL -->


    
<!-- DOMICILIO FISCAL -->               
                       <div class="row">
                       <div class="col-sm-6">
                            <?= $form->field($model, 'domicilio_fiscal')->textArea(['maxlength' => true]) ?>
                       </div>
                       </div>            
                   
<!-- FIN DEL DOMICILIO FISCAL -->



<!-- EMAIL -->
                        <div class="row">
                        <div class="col-sm-6">
                        
                            <?= $form->field($model, 'email')->input('email') ?>

                            </div>
                      </div> 
                        
<!-- FIN EMAIL -->



<!-- TELEFONO DE OFICINA Y CELULAR -->
                            
                            <?php 
                                $listaTelefonoCodigo = TelefonoCodigo::getListaTelefonoCodigo($is_celular=0);
                                
                            ?>


                          
                           <div class="row">
                            <div class="col-sm-2">
                                 <?= $form->field($model, 'codigo')->dropDownList($listaTelefonoCodigo, ['inline' => true,
                                                                                             'prompt' => Yii::t('frontend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo',
                                                                                             'onchange' => 
                                                                                                'cambio()'
                                                                                             ]
                                                                    ) 
                            ?>
                            </div>
                            <div class="col-sm-4">
                            <?= $form->field($model, 'tlf_ofic')->textInput(['maxlength' => 7,
                                                                        'style' => 'width:150px;',
                                                                        'placeholder' => false,
                                                                        'style' => 'margin-left:-5px;',
                                                                        'id' => 'tlf_ofic',
                                                                       
                                                                        ]
                                                                    ) 
                            ?>

                                     
                            </div>
                            </div>


                                 
                             
                                   
                  

                            <div class="row">
                            <div class="col-sm-2">
                                    <?= $form->field($model, 'codigo2')->dropDownList($listaTelefonoCodigo, ['inline' => true,
                                                                                             'prompt' => Yii::t('frontend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo_otro',
                                                                                             'onchange' => 
                                                                                                'cambio1()'
                                                                                             ]
                                                                    ) 
                            ?>
                            </div>

                            <div class="col-sm-4"> 
                            <?= $form->field($model, 'tlf_ofic_otro')->textInput(['maxlength' => 7,
                                                                        'style' => 'width:150px;',
                                                                        'placeholder' => false,
                                                                        'style' => 'margin-left:-5px;',
                                                                        'id' => 'tlf_ofic_otro',                                                                       
                                                                        ]
                                                                    ) 
                                        ?>
                                    
                               
                            </div>
                            </div>



                           
                            <?php 
                                $listaTelefonoCodigoCelu = TelefonoCodigo::getListaTelefonoCodigo($is_celular=1);
                               
                            ?>
                            
                                 <div class="row">
                                    <div class="col-sm-2">
                                    <?= $form->field($model, 'codigo3')->dropDownList($listaTelefonoCodigoCelu, ['inline' => true,
                                                                                             'prompt' => Yii::t('frontend', 'Select'), 
                                                                                             'style' => 'width:100px;',
                                                                                             'id' => 'codigo_celu',
                                                                                             'onchange' => 
                                                                                                'cambioCelu()'
                                                                                             ]
                                                                    ) 
                                        ?>
                                        </div>
                                        <div class="col-sm-4">
                                    
                                    <?= $form->field($model, 'tlf_celular')->textInput(['maxlength' => 7,
                                                                        'style' => 'width:150px;',
                                                                        'style' => 'margin-left:-5px;',
                                                                        'placeholder' => false,
                                                                        'id' => 'tlf_celularContri',
                                                                       
                                                                        ]
                                                                    ) 
                                        ?>
                                    
                                </div>
                                </div>
                                             
                        
<!-- FIN TELEFONO OFICINA Y CELULAR -->

                                 <div class="row">
                            <div class="col-sm-4">
                            <?= Html::submitButton(Yii::t('frontend', 'Create') , ['class' =>'btn btn-success', 'style' => 'height:30px;width:100px;margin-left:0px;']) ?>
                            </div>
                            
                            <div class="col-sm-4">
                             <?= Html::a('Return',['/usuario/opcion-crear-usuario/seleccionar-tipo-usuario'], ['class' => 'btn btn-primary','style' => 'height:30px;width:100px;margin-left:-55px;' ]) //boton para volver al menu de seleccion tipo usuario ?>
                            </div>
                           </div>
        </div>
      </div>
    </div>
  </div>

    <?php 
    /*
    *****************************************************
    *   Segmento para setear variables predefinidas     *
    *               Persona Juridica                    *
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
*   Segmento para setear variables predefinidas     *
*                   JURIDICA                        *
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
*       Fin del Segmento -Persona Juridica-         *
*****************************************************
*/
?>
<!-- FIN FORMULARIO PERSONA JURIDICA -->
<?php ActiveForm::end(); ?>