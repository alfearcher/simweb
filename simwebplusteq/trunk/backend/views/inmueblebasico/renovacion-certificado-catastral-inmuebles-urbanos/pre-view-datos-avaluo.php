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
 *  @file pre-view-datos-licencia.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 14-02-2017
 *
 *  @view pre-view-datos-licencia.php
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

 	use yii\web\Response;
 	use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\data\ArrayDataProvider;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\widgets\DetailView;
	use yii\helpers\BaseHtml;

	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);
 
 ?>


<div class="pre-view-datos-licencia">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-pre-view-certificado',
 			'method' => 'post',
 			//'action' => $url,
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => true,
 		]);
 	?>


	<meta http-equiv="refresh">
    <div class="panel panel-primary" style="width: 100%;">
        <div class="panel-heading">
        	<h3><?= Html::encode(Yii::t('frontend', 'Datos preliminares del Avaluo Catastral. Id del Inmueble: ' . $modelInmueble['id_impuesto'])) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9; -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

					<div class="row" style="padding: 0px;width:100%;">
					</div>





<!-- INFORMACION DE AVALUOS -->

                     <div class="row" class="informacion-licencia" id="informacion-licencia">
                        <div class="row" style="border-bottom: 1px solid #ccc;background-color:#F1F1F1;padding: 0px;width: 100%;padding-left: 15px;">
                            <h4><strong><?=Html::encode(Yii::t('frontend', 'Aspectos Valorativos del Inmueble'))?></strong></h4>
                        </div>

                        <div class="row">
                            <div class="col-sm-2" style="width: 20%;padding: 0px;padding-top: 10px;">
                                <div class="id-contribuyente" style="margin-left: 0px;">
                                    <?= $form->field($model, 'mts')->textInput([
                                                                                'id' => 'id-contribuyente',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['mts'],
                                                                                'readOnly' => true,

                                                                        ])->label('Metros de Construcción') ?>
                                </div>
                            </div>

                            <div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
                                <div class="licencia" style="margin-left: 0px;">
                                    <?= $form->field($model, 'valor_por_mts2')->textInput([
                                                                                'id' => 'id-licencia',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['valor_por_mts2'],
                                                                                'readOnly' => true,

                                                                        ])->label('Valor Metros de Construcción') ?>
                                </div>
                            </div>

                            <div class="col-sm-2" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
                                <div class="fecha-emision" style="margin-left: 0px;">
                                    <?= $form->field($model, 'mts2_terreno')->textInput([
                                                                                'id' => 'id-fecha_emision',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['mts2_terreno'],
                                                                                'readOnly' => true,

                                                                        ])->label('Metros de Terreno') ?>
                                </div>
                            </div>

                            <div class="col-sm-3" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
                                <div class="fecha-vcto" style="margin-left: 0px;">
                                    <?= $form->field($model, 'valor_por_mts2_terreno')->textInput([
                                                                                'id' => 'id-fecha_vcto',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['valor_por_mts2_terreno'],
                                                                                'readOnly' => true,

                                                                        ])->label('Valor Metros de Terreno') ?>
                                </div>
                            </div>

                            

                        </div>

                        <div class="row">

                        	<div class="col-sm-3" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
                                <div class="fecha-vcto" style="margin-left: 0px;">
                                    <?= $form->field($model, 'valor_construccion')->textInput([
                                                                                'id' => 'id-fecha_vcto',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['valor_construccion'],
                                                                                'readOnly' => true,

                                                                        ])->label('Avaluo de la Construccion') ?>
                                </div>
                            </div>

                            <div class="col-sm-3" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
                                <div class="fecha-vcto" style="margin-left: 0px;">
                                    <?= $form->field($model, 'valor_terreno')->textInput([
                                                                                'id' => 'id-fecha_vcto',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['valor_terreno'],
                                                                                'readOnly' => true,

                                                                        ])->label('Avaluo del Terreno') ?>
                                </div>
                            </div>

                        	<div class="col-sm-3" style="width: 20%;padding: 0px;padding-left: 5px;padding-top: 10px;">
                                <div class="fecha-vcto" style="margin-left: 0px;">
                                    <?= $form->field($model, 'valor')->textInput([
                                                                                'id' => 'id-fecha_vcto',
                                                                                'style' => 'width:100%;background-color:white;',
                                                                                'value' => $modelAvaluo['valor'],
                                                                                'readOnly' => true,

                                                                        ])->label('Avaluo del Inmueble') ?>
                                </div>
                            </div>
                        </div>
                    </div>





<!-- BOTONES -->

					
					<div class="row" style="border-bottom: 2px solid #ccc;padding: 0px;width: 103%;margin-left: -30px;">
					</div>

				</div>  <!-- Fin de col-sm-12 -->
			</div>  	<!-- Fin de container-fluid -->

		</div>			<!-- Fin panel-body-->

	</div>	<!-- Fin de panel panel-primary -->

 	<?php ActiveForm::end(); ?>
</div>	 <!-- Fin de inscripcion-act-econ-form -->


