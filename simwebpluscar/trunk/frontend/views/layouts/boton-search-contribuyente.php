<?php
	use kartik\icons\Icon;
  	use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\kartik\DetailView;

  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

   $idContribuyente = $_SESSION['idContribuyente'];


 ?>

       <?= DetailView::widget([
                                                       'model' => $modelContribuyente,
                                                       'attributes' => [ 
                                                       'id_contribuyente',
                                                       'cedula',
                                                       'nombres',
                                                       'apellidos',
                                                       'domicilio_fiscal',
                                                       'email',
                                                        ],
                                                        
                             ]) ?> 
Alvaro Jose • 10:37
Alvaro Jose Fernandez Archer


Envía un mensaje



        </div>
        </div>
        </div>
<!--  </div>


<!-- <nav class="navbar navbar-default1">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" >

            </ul>
        </div>
    </div>
</nav> 




?>