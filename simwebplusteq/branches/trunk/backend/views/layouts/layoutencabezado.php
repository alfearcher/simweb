<?php
    use backend\assets\AppAsset;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\bootstrap\Nav;
    use yii\bootstrap\NavBar;
    use yii\widgets\Breadcrumbs;
   // use kartik\nav\NavX;
    use kartik\icons\Icon;
    //use yii\widgets\Pjax;
    //use yii\bootstrap\Modal;

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
                        // 'brandLabel' => Html::img('@web/imagen/logo-teq.png',[
                        //                     'style' => 'width: 10%;padding:0px;']),
                        // 'brandUrl' => Yii::$app->homeUrl,
                        'options' => [
                            //-inverse
                            //'class' => 'navbar-inverse navbar-fixed-top',
                            //'style' => 'height:25px;'
                        ],
                    ]);

                    if (!Yii::$app->user->isGuest) {
                        $menuItems = [
                            ['label' =>  '<p>' . Icon::show('fa fa-list-alt',['class' => 'fa-2x'], $typeIcon) . Yii::t('backend', 'Main Menu') . '</p>', 'url' => ['/menu/vertical']],

                        ];
                    }

                    //'visible' => false
                    if (Yii::$app->user->isGuest) {
                        $menuItems[] = ['label' => '<p>' . Icon::show('user',['class' => 'fa-2x'], $typeIcon) .  Yii::t('backend', 'Ingresar ') . '</p>', 'url' => ['/site/login'],
                            //'items' => []
                        ];
                    } else {
                        $menuItems[] = [
                            'label' => Icon::show('user',['class' => 'fa-2x'], $typeIcon) . ' ' . Yii::t('backend', 'user') . ' (' . Yii::$app->user->identity->username . ')',
                            'url' => '#',
                            //'linkOptions' => ['data-method' => 'post'],
                                'items' => [['label' => Yii::t('backend', 'Create Security Questions'), 'url' =>  ['pregunta-seguridad/asignarpreguntasecreta']],
                                            ['label' => Yii::t('backend', 'Change Password'), 'url' =>  ['opcion-funcionario/cambiarpasswordfuncionario']],
                                            ['label' => 'Salir', 'url' => ['site/logout'],'linkOptions' => ['data-method' => 'post'], ],
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
                    require('boton-search.php');
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


<script>
    $("#lupa").click(function(event) {
        //alert('lupa');
        //$("#modal").modal("show")
        //    .find("#modalForm")
        //    .load($(this).attr("url"));
    });

</script>