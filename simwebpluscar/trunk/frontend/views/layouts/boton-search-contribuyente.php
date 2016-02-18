<?php
	use kartik\icons\Icon;
  	use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
   use yii\widgets\DetailView;
   use frontend\models\usuario\Afiliacion;
   use frontend\models\usuario\CrearUsuarioNatural;
  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);


  // die(var_dump(Yii::$app->user->getId;

    $this->title = 'Informacion del Contribuyente';

    $id = $_SESSION['idContribuyente'];
    $nombre = $_SESSION['nombre'];
    $apellido = $_SESSION['apellido'];

    
   $modelContribuyente = ['id_contribuyente' => $id, 'nombre' => $nombre.' '.$apellido];
    
   


 ?>   <div class="row">
      <div class="col-sm-5" style="margin-left:200px; width: 660px; height: 120px;">
      <div class="panel panel-primary">
       <div class="panel-heading">
       <?= $this->title ?>
       </div>
      <div class="panel-body">
     
       <?= DetailView::widget([
                                                       'model' => $modelContribuyente,
                                                       
                                                       'attributes' => [ 
                                                       'id_contribuyente',
                                                        'nombre' ,
                                                        ],
                                                        'options' =>[ 
                                                        'style' => 'width:600px; margin-left:0px;',
                                                        'class' =>'table table-hover table-bordered',
                                                          
       
                                                        ],
                                                        
                             ]) ?> 


      </div>
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