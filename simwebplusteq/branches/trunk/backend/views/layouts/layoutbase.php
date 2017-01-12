<?php
    use backend\assets\AppAsset;
    use yii\helpers\Html;
    use yii\widgets\Breadcrumbs;
    use yii\base\Exception;
    use yii\web\Controller;

/* @var $this \yii\web\View */
/* @var $content string */
    
    try {
        if (!(Yii::$app->user->identity)) {
            // No existe usuario definido
            //Controller::redirect(['/site/login']);
            die('No existe user');
        }
    } catch (Exception $e) {
        die('Error ' . $e->getName());
    }

// Encabezado
require('layoutencabezado.php');

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
        <!-- <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div> -->

        <div class="menu-principal" >
            <?= $content ?>    
        </div>
    </div>
    
    <?php $this->endBody() ?>
</body>
<!-- Aqui comienza el footer -->
    <?php require('layoutfooter.php');  ?>
<!-- Aqui finaliza el footer -->
</html>
<?php $this->endPage() ?>