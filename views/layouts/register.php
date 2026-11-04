<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use app\widgets\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\dialog\Dialog;

AppAsset::register($this);
\app\assets\RegisterAsset::register($this);
johnitvn\ajaxcrud\CrudAsset::register($this);
app\assets\RemarkIconsAsset::register($this);
app\assets\NumeralAsset::register($this);

echo Dialog::widget();
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

<body class="page-register-v2 layout-full bg-orange-900">
    <?php $this->beginBody() ?>

    <!-- Page -->
    <div class="page animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <div class="page-content">
            <?= $content ?>
        </div>
    </div>
    <!--        <div class="page animsition vertical-align text-center" data-animsition-in="fade-in"
          data-animsition-out="fade-out">
                    <div class="page-content vertical-align-middle">
            
                    </div>
                </div>-->
    <!-- End Page -->
    <?php
    Modal::begin([
        "id" => "ajaxCrudModal",
        "options" => [
            "tabindex" => FALSE,
            'class' => 'modal-primary',
        ],
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => FALSE,
        ],
        "footer" => "", // always need it for jquery plugin
    ])
    ?>
    <?php Modal::end(); ?>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>