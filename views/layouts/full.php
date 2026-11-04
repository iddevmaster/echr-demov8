<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\widgets\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\assets\LoginAsset;

AppAsset::register($this);
LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="nNnCWzTob4-dr7sMiP8rXtbplowG2lUnngBCY-on5oc" />
    <meta name="robots" content="noindex">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="page-login-v3 layout-full">
    <?php $this->beginBody() ?>

    <!-- Page -->
    <div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <div class="page-content vertical-align-middle">
            <?= $content ?>
        </div>
    </div>
    <!-- End Page -->

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>