<?php


    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\web\View;
    use yii\helpers\ArrayHelper;
    use backend\models\registromaestro\TipoNaturaleza;
    use frontend\models\usuario\TelefonoCodigo;
    use yii\helpers\Url;
    use yii\jui\DatePicker;




?>




<div class="dataBasicRegister" id="paneldataBasicRegister" style="display:;">
    <h3><?= Yii::t('backend', 'Registration Basic Information') ?> </h3>
</div>

<div><br></div>





<!-- FORMULARIO PERSONA NATURAL -->






 <?php $form = ActiveForm::begin([
            'id' => 'form-datosBasicoJuridico-inline',
            'method' => 'post',
            'action' => ['/usuario/crear-usuario-natural/natural'],
             'enableClientValidation' => true,
             'enableAjaxValidation' => false,
             'enableClientScript' => true,

        ]);

?>

    <div class="col-sm-7">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= Yii::t('frontend', 'Registration Basic Information') ?> | <?= Yii::t('frontend', 'Natural') ?>
            </div>
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
                                                                'value' => $rifNatural['naturaleza'],
                                                                //die($rifNatural['naturaleza']),
                                                                 'readOnly' =>true,
                                                                ])->label(false);
                    ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'cedula')->label(false)->textInput(['maxlength' => 8, 'value'=> $rifNatural['cedula'], 'readOnly' =>true,'style' => 'margin-left:-25px;']) ?>
                    </div>
                </div>
<!-- FIN DE RIF <-->


<!-- APELLIDOS Y NOMBRES-->
                <div class="row">
                    <div class="col-sm-5">
                        <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-sm-5">
                        <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true,'style' => 'margin-left:-15px;']) ?>
                    </div>
                </div>
<!-- FIN DE APELLIDOS Y NOMBRES -->


<!-- FECHA DE NACIMIENTO -->
                <div class="row">
                    <div class="col-sm-4">
                        <div class="fecha-nac">
                            <?= $form->field($model, 'fecha_nac')->widget(\yii\jui\DatePicker::classname(),[
                                                                                        //'type' => 'date',
                                                                                        'clientOptions' => [
                                                                                           // 'maxDate' => '+0d', // Bloquear los dias en el calendario a partir del dia siguiente al actual.
                                                                                           'yearRange'=>'-90:+0',
                                                                                        'changeYear' => 'true', 
                                                                                       // 'changeMonth' => 'true', 
                                                                                        'showAnim'=>'fold',
                                                                                       
                                                                                         ],
                                                                                       'language' => 'es-ES',
                                                                                       'dateFormat' => 'dd-MM-yyyy',
                                                                                        'options' => [
                                                                                            //'onClick' => 'alert("calendario")',
                                                                                            'id' => 'fecha-nac',
                                                                                            'class' => 'form-control',
                                                                                           'readonly' => true,
                                                                                            //'type' => 'date',
                                                                                            'style' => 'background-color: white;',
                                                                                        ],

                                                                                      
                                                                                    ])
                            ?>
                        </div>
                    </div>

<!-- FIN DE FECHA DE NACIMIENTO -->

<!-- SEXO -->
                    <div class="col-sm-4">
                        <?= $form->field($model, 'sexo')->dropDownList($model->getGenderOptions(), ['prompt' => 'Select','style' => 'margin-left:-15px;']) ?>
                    </div>
                </div>
<!-- FIN DE SEXO -->

<!-- DOMICILIO FISCAL -->
                <div class="row">
                    <div class="col-sm-10">
                        <?= $form->field($model, 'domicilio_fiscal')->textArea(['maxlength' => true]) ?>
                    </div>
                </div>

<!-- FIN DE DOMICILIO FISCAL -->

<!-- EMAIL -->
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'email')->input('email') ?>
                    </div>
                </div>
<!-- FIN DE EMAIL -->

<!-- TELEFONO CELULAR -->

                <?php
                    $listaCelularCodigo = TelefonoCodigo::getListaTelefonoCodigo($is_celular=1);
                ?>

                <div class="row">
                    <div class="col-sm-2">
                                <?= $form->field($model, 'codigo')->dropDownList($listaCelularCodigo, ['inline' => true,
                                                                                         'prompt' => Yii::t('backend', 'Select'),
                                                                                         'style' => 'width:100px;',
                                                                                         'id' => 'codigo_celuNat',
                                                                                         'onchange' =>
                                                                                            'cambioCelu1()'
                                                                                         ]
                                                                )
                                    ?>

                    </div>
                    <did class="col-sm-3" >
                        <?= $form->field($model, 'tlf_celular')->textInput(['maxlength' => 7,
                                                            'style' => 'width:150px;',
                                                            'placeholder' => false,
                                                            'id' => 'tlf_celular',

                                                            ]);
                            ?>
                    </did>
                </div>
                

<!-- FIN DE TELEFONO CELULAR -->

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


    <input type="hidden" name="visible" value="natural">
    <?php
    /*
    *****************************************************
    *       Fin del Segmento -Persona Natural-          *
    *****************************************************
    */
    ?>
<?php ActiveForm::end() ?>
<!-- FIN DEL FORMULARIO PERSONA NATURAL -->