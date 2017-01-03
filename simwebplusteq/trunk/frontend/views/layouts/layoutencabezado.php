<?php
    use frontend\assets\AppAsset;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;
   // use kartik\nav\NavX;
    use kartik\icons\Icon;
    use yii\web\AssetBundle;
    //use yii\widgets\Pjax;
    //use yii\bootstrap\Modal;
    //
    //session_start();
    //
     $sesion = yii::$app->user->identity;

    $_SESSION['sesion'] = $sesion;

    //die(var_dump($sesion));

    $typeIcon = Icon::FA;
    $typeLong = 'fa-2x';


    Icon::map($this, $typeIcon);

	/* @var $this \yii\web\View */
	/* @var $content string */

    if (YII_DEBUG) {
        //$this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
    }


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
    <div class="encabezado-padre">
        <div class="encabezado">
    <!-- Aqui comienza el encabezado -->
                <?php
                    NavBar::begin([
                        'brandLabel' => strtoupper(Yii::$app->ente->getAlcaldia()),  //Yii::$app->name . ' '. Yii::$app->ente->getAlias(),

                        'brandUrl' => Yii::$app->homeUrl,
                        'options' => [
                            //-inverse
                            //'class' => 'navbar-inverse navbar-fixed-top',

                        ],
                    ]);

                    if (!Yii::$app->user->isGuest) {
                        $menuItems = [
                            ['label' =>  '<p>' . Icon::show('fa fa-list-alt',['class' => 'fa-2x'], $typeIcon) . Yii::t('frontend', 'Main Menu') . '</p>', 'url' => ['site/menu-vertical']],

                        ];
                    }

                    //'visible' => false
                    if (Yii::$app->user->isGuest) {
                        $menuItems[] = ['label' => '<p>' . Icon::show('user',['class' => 'fa-2x'], $typeIcon) .  Yii::t('frontend', 'Ingresar ') . '</p>', 'url' => ['/site/login'],
                            //'items' => []
                        ];
                    } else {
                       $menuItems[] = [
                            'label' => Icon::show('user',['class' => 'fa-2x'], $typeIcon) . ' ' . Yii::t('frontend', 'user') . ' (' . Yii::$app->user->identity->login . ')',

                            'url' => '#',
                            //'linkOptions' => ['data-method' => 'post'],
                                    'items' => [['label' => Yii::t('backend', 'Change Security Questions'), 'url' =>  ['usuario/cambiar-pregunta-seguridad/buscar-cambiar-pregunta-seguridad']],
                                            ['label' => Yii::t('backend', 'Change Password'), 'url' =>  ['usuario/mostrar-pregunta-seguridad/buscar-mostrar-pregunta-seguridad']],
                                            ['label' => 'Logout', 'url' => ['site/logout2'],'linkOptions' => ['data-method' => 'post'],
                                            ],
                                    ]

                        ];




                    }

                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-right'],
                        'items' => $menuItems,
                        'encodeLabels' => false,
                    ]);

                    NavBar::end();
                ?>



        </div>
        <div class="barra-inferior">

            <?php

                if (!Yii::$app->user->isGuest) {

                    require('boton-search-contribuyente.php');
                    //require('barra-inferior.php');
                    //require('boton-undo.php');
                    //require('opciones-nav.php');

                }
            ?>
        </div>
    </div>  <!-- Fin de encanezado-padre -->
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


