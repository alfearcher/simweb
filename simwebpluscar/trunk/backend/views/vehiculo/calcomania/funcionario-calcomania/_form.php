<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\vehiculo\calcomania\FuncionarioCalcomaniaForm */
/* @var $form yii\widgets\ActiveForm */
/* $('#estatus').removeAttr('checked'); */
?>

<?php 

    if ($result['btnAccion'] == 'Update') {
        $requerido = '';
    }else{
        $requerido = 'required';
    }

	if ($result['condicion'] == 0) {
		$active = "";
		$valor = 'value = "0"';
		$condi = 'NO';
	}
	if ($result['condicion'] == 1) {
		$active = "checked = 'checked'";
		$valor = 'value = "1"';
		$condi = '';	
	}	
?>

<script type="text/javascript">
	
	function estado(val){
		valor = $("#estatus").is(':checked') ? 1 : 0;
		if (valor == 0) {
			$('#estatus').removeAttr('value');
			$('#estatus').attr('value', 0);
			$('#estatusNew').removeAttr('value');
			$('#estatusNew').attr('value', 0);
		}if (valor == 1){
			$('#estatus').removeAttr('value');
			$('#estatus').attr('value', 1);
			$('#estatusNew').removeAttr('value');
			$('#estatusNew').attr('value', 1);
		};
	}
</script>
<div class="funcionario-calcomania-form-form">

    <?php $form = ActiveForm::begin([
                                'id' => 'form-funcionario-calcomania-form-inline',
                                'method' => 'post',
                            ]);    
                        ?>

    <div style="margin-left:150px;margin-top:50px;" class="container">
        <div class="col-sm-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?= Yii::t('backend', 'New License Plate') ?>
                </div>
                <div class="panel-body">
                	<!-- NATURALEZA Y CI -->
                    <div class="row">
                        <div class="col-md-4"><b><?= $model->getAttributeLabel('ci'); ?></b></div>
                        <div class="col-md-3"><?= $result[0]['naturaleza']; ?>-<?= $result[0]['ci']; ?></div>
                        <?= Html::activeHiddenInput($model, 'naturaleza', ['value' => $result[0]['naturaleza']]) ?>
                        <?= Html::activeHiddenInput($model, 'ci', ['value' => $result[0]['ci']]) ?>
                    </div>
                    <!-- FIN DE NATURALEZA Y CI -->

                    <!-- APELLIDOS Y NOMBRES -->
                    <div class="row">
                        <div class="col-md-4"><b><?= $model->getAttributeLabel('apellidos'); ?> / <?= $model->getAttributeLabel('nombres'); ?></b></div>
                        <div class="col-md-3"><?= $result[0]['apellidos']; ?></div>
                        <div class="col-md-3"><?= $result[0]['nombres']; ?></div>
                    </div>
                    <!-- FIN DE APELLIDOS Y NOMBRES -->

                    <!-- CARGO -->
                    <div class="row">
                        <div class="col-md-4"><b><?= $model->getAttributeLabel('cargo'); ?></b></div>
                        <div class="col-md-3"><?= $result[0]['cargo']; ?></div>
                    </div>
                    <!-- FIN DE CARGO -->

                    <!-- EMAIL -->
                    <div class="row">
                        <div class="col-md-4"><b><?= $model->getAttributeLabel('email'); ?></b></div>
                        <div class="col-md-3"><?= $result[0]['email']; ?></div>
                    </div>
                    <!-- FIN DE EMAIL -->

                    <!-- EMAIL -->
                    <div class="row">
                        <div class="col-md-4"><b><?= $model->getAttributeLabel('estatus'); ?></b></div>
                        <div class="col-md-6">
                        	<?php 
                        		if ($result['condicion'] == 1) {
                        			echo "<input ".$requerido." type='checkbox' onclick='estado(this.value)' ".$valor." name='estatus' id='estatus' ".$active."> ";
                        			echo "El funcionario ".$condi." esta habilitado";
                        		}else{
                        			echo "<input ".$requerido." type='checkbox' onclick='estado(this.value)' ".$valor." name='estatus' id='estatus' ".$active."> ";
                        			echo "El funcionario ".$condi." esta habilitado";
                        		}
                        		echo "<input type='hidden' name='estatusNew' id='estatusNew' value='".$result['condicion']."'>";
                        	?>
                        </div>
                    </div>
                    <!-- FIN DE EMAIL -->                    
                </div>
                <div class="modal-footer"> 
                	<?php 
                		if ($result['btnAccion'] == 'Update') {
                			$claseBtn = 'btn btn-primary';
                		}else{
                			$claseBtn = 'btn btn-success';
                		}
                	?> 
                    <?= Html::submitButton(Yii::t('backend', $result['btnAccion']), ['class' => $claseBtn]) ?>
                    <?= Html::activeHiddenInput($model, 'id_funcionario', ['value' => $result[0]['id_funcionario']]) ?>
                    <?php echo '<input type="hidden" name="btnAccion" id="btnAccion" value="'.$result['btnAccion'].'">'; ?>
                    <?= Html::a(Yii::t('backend', 'Back'), ['busqueda-funcionario'], ['class' => 'btn btn-primary']) ?>
                    <input type="hidden" name="formAsignate" id="formAsignate" value="1">
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
