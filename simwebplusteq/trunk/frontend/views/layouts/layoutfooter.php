<?php
    //use backend\assets\AppAsset;
    use yii\helpers\Html;
    //use yii\bootstrap\Nav;
    //use yii\bootstrap\NavBar;
    //use yii\widgets\Breadcrumbs;
    //use kartik\icons\Icon;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>

    </head>
    <body>
    </body>

    <?php $this->beginBody() ?>

    <footer class="footer" style="padding: 0px;padding-top:5px;">
        <div class="row" style="padding:0px;width: 55%;">
            <div class="col-sm-2" style="width: 10%;margin:0px;padding:0px; padding-top:5px;padding-left:25px;">
                <?=Html::img('@web/imagen/logo-asis-fondo-blanco.jpg',[
                                            'style' => 'width: 85%;padding: 0px;'
                    ]);
                ?>
            </div>
            <div class="col-sm-2" style="width: 40%;padding-top: 15px;padding-left: -45px;">
                <p class="pull-left">&copy; ASIS CONSULTORES, C.A <?= date('Y') ?></p>
            </div>

            <div class="col-sm-2" style="width: 30%;padding:0px;margin::0px;padding-top:45;margin-top: 15px;float: right;">
                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </div>

    </footer>
<!-- Aqui finaliza el footer -->

    <?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>
