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
 *  @file lista-funcionario.php
 *
 *  @author Jose Rafael Perez Teran
 *
 *  @date 31-07-2016
 *
 *  @view lista-funcionario
 *  @brief vista principal de la lista de los funcionarios
 *
 */

 	use yii\web\Response;
 	//use kartik\icons\Icon;
 	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	//use yii\widgets\Pjax;

?>

<div class="lista">
 	<?php

 		$form = ActiveForm::begin([
 			'id' => 'id-lista-funcionario',
 			'method' => 'post',
 			'enableClientValidation' => true,
 			'enableAjaxValidation' => false,
 			'enableClientScript' => false,
 		]);
 	?>



	<meta http-equiv="refresh">
    <div class="panel panel-primary"  style="width: 90%;">
        <div class="panel-heading">
        	<h3><?= Html::encode($caption) ?></h3>
        </div>

<!-- Cuerpo del formulario -->
<!-- style="background-color: #F9F9F9;" -->
        <div class="panel-body" >
        	<div class="container-fluid">
        		<div class="col-sm-12">

		        	<div class="row" style="width:100%;">
						<div class="lista-funcionario">
							<?= GridView::widget([
								'id' => 'id-grid-funcionario',
								'dataProvider' => $dataProvider,
								'filterModel' => $model,
								'tableOptions' => [
                    				'class' => 'table table-hover',
              					],
								//'layout'=>"\n{pager}\n{summary}\n{items}",
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

									'id_funcionario',
									'ci',
									'apellidos',
									'nombres',

									[
	               		 		'contentOptions' => [
		                              'style' => 'font-size: 100%;',
		                        ],
	                            //'label' => 'Seleccionar',
	                            'format' => 'raw',
	                            'value' => function($data) {
	                            		return Html::submitButton('Seleccionar',
                													[
																		'id' => 'id-seleccion',
									            						'class' => 'btn btn-success',
									            						'data' => [
									            							'method' => 'post',
									            							'params' => [
									            								'id' => $data->id_funcionario,
									            							],
									            						],
									            					]);
	                            },
	                        ],

					            	// [
					             //        'label' => Yii::t('backend', 'ID.'),
					             //        'value' => function($data) {
                		// 								return $data->id_contribuyente;
        										// 	},
					             //    ],
					             //    [
					             //        'label' => Yii::t('backend', 'Current DNI'),
					             //        'value' => function($data) {
                		// 								return $data->naturaleza . '-' . $data->cedula . '-' . $data->tipo;
        										// 	},
					             //    ],


					        	]
							]);?>
						</div>
					</div>

<!-- Boton para salir del listado -->
					<div class="col-sm-3" style="margin-left: 50px;">
						<div class="form-group">
							<?= Html::submitButton(Yii::t('backend', 'Quit'),
																[
																	'id' => 'btn-quit',
																	'class' => 'btn btn-danger',
																	'value' => 1,
																	'style' => 'width: 100%',
																	'name' => 'btn-quit',
																])
							?>
						</div>
					</div>
<!-- Fin de Boton para salir del listado -->
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>