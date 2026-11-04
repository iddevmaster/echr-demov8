<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\bootstrap\Tabs;

\app\assets\TabsAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\models\MeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$currentRole = \Yii::$app->session->get('currentRole');
if($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN){
$this->title = Yii::t('app', 'จัดการประชุม');
$this->params['breadcrumbs'][] = $this->title;
}

//\yii\helpers\VarDumper::dump(Yii::$app->session->get('currentRole'));
//\yii\helpers\VarDumper::dump(Yii::$app->language);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php
//        if ($currentRole['role_id'] == \app\models\Role::COMMITTEE || $currentRole['role_id'] == \app\models\Role::PRESIDENT || $currentRole['role_id'] == \app\models\Role::COPRESIDENT) {
//            echo $this->render('_calendar-committee');
//        }else{
        echo $this->render('_calendar');
//        }
        ?>
    </div>
</div>

