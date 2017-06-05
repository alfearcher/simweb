<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;
// 
$this->title = 'Seleccione su tipo de Gestion';

?>
 



<?php $form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'formulario',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
        
]);
?>

<div class="col-sm-5">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <?= $this->title ?>
            </div>
            <div class="panel-body" >
               
                       <div class="row">
                       <div class="col-sm-6"; style="margin-left:100px; ">
                            <?= Html::a('<strong><center><span class="glyphicon glyphicon-user" style="font-size:20px"> Comprador</span></center></strong>', ['/vehiculo/cambiopropietario/cambio-propietario-comprador/comprador']); ?>
                       </div>
                       </div>
                       
                    <br>
                       <div class="row">
                        <div class="col-sm-6"; style="margin-left:100px; ">
                            <?= Html::a('<strong><center><span class="glyphicon glyphicon-home" style="font-size:20px"> Vendedor</span></center></strong>', ['/vehiculo/cambiopropietario/cambio-propietario-vendedor/vendedor']); ?>
                       </div>
                       </div>
               
            </div>
        </div>
    </div>
        

<?php $form->end() ?>

