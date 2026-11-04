<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\SubmissionEvent;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionVolunteerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$subEvents = $submission->getSubmissionEvents()->isDeleted(false)->all();
$dataProvider = new ArrayDataProvider([
    'allModels' => $subEvents,
    'modelClass' => SubmissionEvent::class,
]);
?>
<?= $this->renderFile('@app/views/deviation-assess-form/_event.php', [
    'dataProvider' => $dataProvider,
    'sCommitteeId' => $sCommitteeId,
]); ?>
