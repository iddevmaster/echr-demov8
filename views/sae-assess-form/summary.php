<?php

use app\models\SubmissionType;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\SubmissionVolunteer;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionVolunteerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$subVols = $submission->getSubmissionVolunteers()->isDeleted(false)->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => $subVols,
    'modelClass' => SubmissionVolunteer::class,
]);
?>
<?php if ($submission->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE): ?>
<?= $this->renderFile('@app/views/sae-assess-form/_volunteer.php', [
    'dataProvider' => $dataProvider,
    'sCommitteeId' => $sCommitteeId,
]); ?>
<?php endif; ?>
