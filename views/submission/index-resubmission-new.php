<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Submission;
use app\models\SubmissionCommittee;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ส่งแก้ไขโครงการวิจัยใหม่');
$this->params['breadcrumbs'][] = $this->title;

if (isset($status)) {
    $st = Submission::getStatusLabels()[$status];
} elseif (isset($resolution)) {
    $st = Submission::getResolutionLables()[$resolution];
}
//CrudAsset::register($this);

$currentRole = Yii::$app->session->get('currentRole');
?>
<div class="submission-index ">
    <div class="page-content container-fluid has-page-toolbar">
        <div class="row">
            <div class="col-md-12">
                <?=
                $this->render('/submission/index-re', [
                    'searchModel' => $searchModelEdit,
                    'dataProvider' => $dataProviderEdit,
                    'label' => yii::t('app', 'เอกสารไม่ครบถ้วน/ไม่ถูกต้อง'),
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?=
                $this->render('/submission/index-re', [
                    'searchModel' => $searchModelC,
                    'dataProvider' => $dataProviderC,
                    'label' => yii::t('app', 'แก้ไขมติ C , มติ R'),
                ])
                ?>
            </div>
        </div>
    </div>
</div>

