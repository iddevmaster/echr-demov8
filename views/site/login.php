<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = yii::t('app', 'เข้าสู่ระบบ');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel whiteframe-10dp">
    <div class="panel-heading">
        <div class="pull-right">
            <?php
            $languageItems = Yii::$app->util->getLanguageItems();
            $languageItem = new \cetver\LanguageSelector\items\DropDownLanguageItem($languageItems);
            $languageItem = $languageItem->toArray();
            $languageDropdownItems = \yii\helpers\ArrayHelper::remove($languageItem, 'items');
             echo \yii\bootstrap\ButtonDropdown::widget([
                 'label' => $languageItem['label'],
                 'encodeLabel' => false,
                 'options' => ['class' => 'btn-inverse'],
                 'dropdown' => [
                     'items' => $languageDropdownItems
                 ]
             ]);
            ?>
        </div>
    </div>
    <div class="panel-body text-left">
        <div class="brand">
            <div class="text-center"><?= Html::img('@web/images/logo.png', ['width' => 90]); ?></div>
            <div class="brand-text font-size-18 text-center text-primary"><?= yii::t('app', 'Research Ethics Review: Online Submission System'); ?></div>
            <!--<div class="font-size-20 font-weight-900">CORE</div>-->
        </div>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['autocomplete' => 'off'], //['class' => 'form-horizontal'],
        ]);
        ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= Html::submitButton(Yii::t('app', 'เข้าสู่ระบบ'), ['class' => 'btn btn-primary btn-raised btn-block btn-lg margin-top-20', 'name' => 'login-button']) ?>
        <div class="margin-top-10">
            <?= Html::a(Yii::t('app', 'ลงทะเบียน'), ['person/register'], ['class' => 'margin-left-0 font-size-16']) ?>
            <?= Html::a(Yii::t('app', 'ลืมรหัสผ่าน'), ['person/forget-password'], ['class' => 'margin-left-0 font-size-16 pull-right']) ?>
        </div>
        <div class="green-600"><?= Yii::t('app', 'รองรับการทํางานบน google chrome : '); ?></div>
        <?php ActiveForm::end(); ?>

    </div>
</div>