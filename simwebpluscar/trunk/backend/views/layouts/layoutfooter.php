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

    <footer class="footer">
        <p class="pull-left">&copy; ASIS CONSULTORES, C.A <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    
    </footer>
<!-- Aqui finaliza el footer -->

    <?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>
