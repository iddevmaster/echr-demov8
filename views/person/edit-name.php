<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
$this->title = Yii::t('app', 'การจัดการข้อมูลส่วนตัวของผู้ใช้งาน');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="person-update">

    <?=
    $this->render('_form-edit-name', [
        'model' => $model,
    ])
    ?>


</div>

