<?php
/* @var $this yii\web\View */
    use yii\helpers\Html;
    use yii\helpers\Url;

//$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <div class="row" style="margin-top:-70px;">
            <?=Html::img('@web/imagen/logo-teq.png',[
                                            'style' => 'width: 50%;'
                ]);
            ?>
        </div>
        <h1>Bienvenido!</h1>

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
            <!-- <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div> -->
        </div>

    </div>
</div>
