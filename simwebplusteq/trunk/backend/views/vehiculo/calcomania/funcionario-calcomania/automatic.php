<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm */
// echo "<pre>"; var_dump($resultLote); echo "</pre>"; die();

?>

<div class="funcionario-calcomania-form-view">
<?= Html::beginForm( [ 'vehiculo/calcomania/funcionario-calcomania/intervalo' ], 'post' );?>
	<div class="container" style="width: 1024px">
	        <div class="container-fluid">
	        	<div class="panel panel-primary">
	        		<div class="panel-heading">
	        		</div>
	        		<div class="panel-body">
	        			<div class="row">
	                        <div class="col-md-1"><b><?= Yii::t('backend', 'Lote') ?></b></div>
	                        <div class="col-md-2"><?= $rangosArray['resultLote'][0]["id_lote_calcomania"] ?></div>
	                        <div class="col-md-1"><b><?= Yii::t('backend', 'A&ntilde;o') ?></b></div>
	                        <div class="col-md-2"><?= $rangosArray['resultLote'][0]["ano_impositivo"] ?></div>
	                    </div>
	                    <br>
	                    <div class="row">
	                    	<div class="col-md-3"><b><?= Yii::t('backend', 'Rango Disponible') ?></b></div>
	                        <div class="col-md-1"><b><?= Yii::t('backend', 'Intervalo') ?></b></div>
	                        <div class="col-md-2">
	                        	<select class="form-control" name="intervalo" >
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
									<option value="60">60</option>
									<option value="70">70</option>
									<option value="80">80</option>
									<option value="90">90</option>
									<option value="100">100</option>
								</select>
	                        </div>
	                        <div class="col-md-1"><?= Html::submitButton( 'Asignate Intervalo', [ 'class' => 'btn btn-primary', 'name' => 'btn', 'id' => "btn" ] );?></div>
	                    </div>	                    
	                    <div class="row">
	                    	<div class="col-md-1"><?= Yii::t('backend', 'Desde') ?></div>
	                    	<div class="col-md-1"><?= $rangosArray['resultLote'][0]["rango_inicial"] ?></div>
	                    </div>
	                    <div class="row">
	                    	<div class="col-md-1"><?= Yii::t('backend', 'Hasta') ?></div>
	                    	<div class="col-md-1"><?= $rangosArray['resultLote'][0]["rango_final"] ?></div>
	                    </div>
	        		</div>
	        	</div>
	            <div class="panel panel-primary">
	                <div class="panel-heading">
	                    <div class="row">
	                    	<div class="col-md-1" style="width: 15px"><?= Yii::t('backend', 'Sel') ?></div>
	                        <div class="col-md-1" style="width: 150px"><?= Yii::t('backend', 'Cedula de Identidad') ?></div>
	                        <div class="col-md-1" style="width: 150px"><?= Yii::t('backend', 'Apellidos') ?></div>
	                        <div class="col-md-1" style="width: 150px"><?= Yii::t('backend', 'Nombres') ?></div>
	                        <div class="col-md-1" style="width: 150px"><?= Yii::t('backend', 'Rango Inicial') ?></div>
	                        <div class="col-md-1" style="width: 150px"><?= Yii::t('backend', 'Rango Final') ?></div>
	                    </div>
	                </div>
	                <div class="panel-body">	                	
	                	<?php 
	                		foreach ($model as $key => $value) {
		                		echo '<div class="row">
		                				<div class="col-md-1" style="width: 15px">
		                					<input type="checkbox" checked name="seleccion[]" id="seleccion[]" value="'.$value[0]["id_funcionario_calcomania"].'">
		                				</div>
		                				<div class="col-md-1" style="width: 150px">'.$value[0]["naturaleza"].'-'.$value[0]["ci"].'</div>
				                        <div class="col-md-1" style="width: 150px">'.$value[0]["apellidos"].'</div>
				                        <div class="col-md-1" style="width: 150px">'.$value[0]["nombres"].'</div>
				                        <div class="col-md-1" style="width: 150px">'.$rangosArray['loteArray'][$key]["inicio"].'</div>
				                        <div class="col-md-1" style="width: 150px">'.$rangosArray['loteArray'][$key]["fin"].'</div>
		                    	</div>';
	                		} 
	                	?>
	                    <div class="row">
	                        <div class="col-md-1" style="width: 150px">
	                        </div>
	                    </div>
	                </div>
	                <div class="panel-footer">
	                </div>
	            </div>
			</div>
		</div>
	</div>
<?= Html::endForm();?> 
</div>