<?php

use app\admin\assets\AdminAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */


AdminAsset::register($this);

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

    <?= $this->render(
        '_header.php',
        ['directoryAsset' => $directoryAsset]
    ) ?>

    <?= $this->render(
        '_left.php',
        ['directoryAsset' => $directoryAsset]
    )
    ?>

    <?= $this->render(
        '_content.php',
        ['content' => $content, 'directoryAsset' => $directoryAsset]
    ) ?>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

