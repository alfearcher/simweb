<?php
/* @var $this yii\web\View */
    use yii\helpers\Html;
    use yii\helpers\Url;

//$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <div class="row" style="margin-top:-70px;">
            <?=Html::img('@web/imagen/logo-caroni.jpg',[
                                            'style' => 'width: 50%;'
                ]);
            ?>
        </div>
        <h1>MANTENIMIENTO</h1>

        <p class="lead">AL SERVICIO AUTOMATIZADO DE INFORMACION Y TRAMITES TRIBUTARIOS </p>
        <div class="row">
            <?=Html::img('@web/imagen/logo-sin-fondo-blanco.jpg',[
                                            'style' => 'width: 15%;'
                ]);
            ?>
        </div>

        <?=Html::a('Ingresar',Url::to(['site/login']),[
                                    'class' => 'btn btn-lg btn-primary',
                                    'style' => 'width:25%;',
        ])?>

        <!-- <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p> -->
    </div>

    <div class="body-content">

        <div class="row">

        </div>

    </div>
</div>
