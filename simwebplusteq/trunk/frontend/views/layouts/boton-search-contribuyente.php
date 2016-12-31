<?php
  use kartik\icons\Icon;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use frontend\models\usuario\CrearUsuarioJuridico;


    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);
 ?>
<!-- <nav class="navbar navbar-default1">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" >

                <div class="search" id="search" >
                    <i class="fa fa-search fa-2x"></i>search
                </div>

                <div class="undo" id="undo" style="padding-right: 15px;">
                    <i class="fa fa-undo fa-2x"></i>undo
                </div>

                <div class="contribuyente" style="padding-right: 5px;">
                    <form>
                        <div class="form-group" >
                            <input type="text" class="form-control" id="contribuyente" readonly style="width: 500px;">
                        </div>
                    </form>
                </div>

                <div class="id-cont" style="padding-right: 5px;">
                    <form>
                        <div class="form-group" >
                            <input type="text" class="form-control" id="id-contribuyente" readonly style="width: 150px;">
                        </div>
                    </form>
                </div>

                <div class="view-ok" id="view-ok" style="padding-right: 13px;">
                    <i class="glyphicon glyphicon-user fa-2x"></i>
                </div>

                <div class="multa" id="multa" >
                    <i class="fa fa-file-text-o fa-2x"></i>Multa<span class="badge">4</span>
                </div>

                <div class="alerta" id="alerta" >
                    <i class="fa fa-flag-o fa-2x"></i>alerta<span class="badge">2</span>
                </div>

            </ul>
        </div>
    </div>
</nav> -->

<!--  -->

<!-- <nav class="navbar navbar-default1">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" >

            </ul>
        </div>
    </div>
</nav> -->



<?php

    $buscar = CrearUsuarioJuridico::find()
                                    ->where([

                                        'id_contribuyente' => yii::$app->user->identity->id_contribuyente,
                                        'inactivo' => 0,

                                        ])
                                    ->all();

    if ($buscar[0]['tipo_naturaleza'] == 0){

        $id_contribuyente = $buscar[0]['id_contribuyente'];
        $contribuyente =$buscar[0]['nombres'].' '.$buscar[0]['apellidos'];



    } if($buscar[0]['tipo_naturaleza'] == 1){

            $id_contribuyente = $buscar[0]['id_contribuyente'];
           $contribuyente =$buscar[0]['razon_social'];
    }


    $menuItems1[] = ['label' => '<div class="contribuyente" style="padding-right: 5px; ">
                    <form>
                        <div class="form-group" >
                            <input type="text" value="' . $contribuyente . '" class="form-control" id="contribuyente" readonly style="width: 450px;">
                        </div>
                    </form>
                </div>'];

    $menuItems1[] = ['label' => '<div class="id-cont" style="padding-right: 5px;">
                    <form>
                        <div class="form-group" >
                            <input type="text" value="' .'ID:'.' '. $id_contribuyente . '" class="form-control" id="id-contribuyente" readonly style="width: 150px;">
                        </div>
                    </form>
                </div>'];

     // $menuItems1[] = ['label' => '<div class="view-ok" id="view-ok" style="padding-right: 13px;">
     //                                <i class="glyphicon glyphicon-user fa-2x"></i>
     //                            </div>', 'url' => ['/buscargeneral/buscar-general/view','idContribuyente' => isset($idContribuyente) ? $idContribuyente : null]];

    // $menuItems1[] = [
    //         'label' => Icon::show('glyphicon glyphicon-user',['class' => 'fa-2x'], $typeIcon),
    //                         'url' => '#',
    // ];

    // $menuItems1[] = ['label' => '<div class="multa" id="multa" >
    //                                 <i class="fa fa-file-text-o fa-2x"></i>Multa<span class="badge">4</span>
    //                             </div>'];

    // $menuItems1[] = ['label' => '<div class="alerta" id="alerta" >
    //                                 <i class="fa fa-flag-o fa-2x"></i>alerta<span class="badge">2</span>
    //                             </div>'];

    echo Nav::widget([
        'options' => ['class' => 'navbar navbar-right1',
        ],
        'items' => $menuItems1,
        'encodeLabels' => false,
    ]);

?>