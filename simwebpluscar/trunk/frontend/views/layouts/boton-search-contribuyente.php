<?php
	use kartik\icons\Icon;
  	use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;

  	$typeIcon = Icon::FA;
  	$typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

   $idContribuyente = $_SESSION['idContribuyente'];


 ?>

        <div class="row" style="margin-left: 170px;">
        <div class="col-sm-7">
        <div class="panel panel-primary">
        <div class="panel-body" >
        </div>
               
                <div class="row">
                <div class="col-sm-5">
               
                        <div class="form-group" >
                            <input type="text" class="form-control" id="contribuyente" value="<?php echo $idContribuyente ?>" readonly style="width: 100px; margin-left: 240px;" >
                        </div>
                </div>

                        <div class="col-sm-4">
                            <div class="form-group" >
                            <input type="text" class="form-control" id="contribuyente" value="manuel zapata" readonly style="width: 200px; margin-left: -310px;">
                        </div>
                        </div>
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