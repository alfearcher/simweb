<?php
    use backend\assets\AppAsset;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;
    use kartik\icons\Icon;


    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';

    Icon::map($this, $typeIcon);

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
</head>
<body>
    <?php $this->beginBody() ?>
    
    <div class="wrap">
        
<!-- Aqui comienza el encabezado -->
            <?php
                NavBar::begin([
                    'brandLabel' => 'SIM Web Plus',
                    'brandUrl' => Yii::$app->homeUrl,
                    'options' => [
                        //-inverse
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                $menuItems = [
                    //'fa fa-list-alt
                    ['label' => '<span class="glyphicon glyphicon-home"></span> Home', 'enabled' => false, 'url' => ['/menu/vertical']],
                    //['label' => '<span class="fa fa-list-alt"></span><i>Hola</i> ', 'url' => ['/site/index']],
                    //['label' => 'class="fa-list-alt"', 'url' => ['/site/index']],
                ];
                
                if (Yii::$app->user->isGuest) {
                    //Icon::show('fa fa-book',['class' => 'fa-2x'], $typeIcon) . '&nbsp; 

                    //$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                    //fa fa-user
                    
                    //$menuItems[] = ['label' => '<span class="fa fa-user fa-2x"></span>&nbsp;<i> Login</i>', 'url' => ['/site/login']];

                    //'url' => ['/site/login']
                    $menuItems[] = ['label' => Icon::show('user') .  'Login ', 'url' => '#', 
                        'items' => [['label' => 'Elaborar preguntas de seguridad', 'url' => '#'],
                                    ['label' => 'Cambiar clave', 'url' => '#']
                                ]
                    ];
                } else {
                    $menuItems[] = [
                        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ];
                }
           
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems,
                    'encodeLabels' => false,
                ]);
                NavBar::end();
            ?>
 <!-- Aqui finaliza encabezado -->


        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
                    
            <div class="menu-principal" style="float: left;">
                <?= $content ?>    
            </div>
        </div>
    </div>


<!-- Aqui comienza el footer -->

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; ASIS CONSULTORES, C.A <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
<!-- Aqui finaliza el footer -->

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
