<?php

use app\models\SubmissionCommittee;
use yii\helpers\Url;
use app\models\Submission;

$sCommitteeId = SubmissionCommittee::find()->isDeleted(FALSE)->person(\Yii::$app->user->identity->person->id)->submission($searchModel->id)->andWhere(['deleted' => 0])->all();

$currentRole = \Yii::$app->session->get('currentRole');
$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.project_code',
        'value' => function($model) {
            if ($model->status >= 400) {
                return $model->project->project_code;
            } elseif ($model->status < 400) {
                return Yii::t('app', 'N/A');
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.name_thai',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'submissionType.name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'projectLeader.person.fullName',
        'value' => function($model) {
            if (isset($model->projectLeader)) {
                return $model->projectLeader->person->i18nFullName . $model->projectLeader->person->fullOrgEn . "<br>Mobile : " . $model->projectLeader->person->mobile_no;
            } else {
                return '';
            }
        }
    ],
];
if ($searchModel->status > 700 && $currentRole['role_id'] == app\models\Role::ADMIN) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsiblePerson.person.fullName',
    ];
}
if ($searchModel->status < 1000) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'status',
        'value' => function($model) {
            if ($model->status) {
                if ($model->status == Submission::STATUS_COMMITTEE_ACCEPTED) {
                    $count = $model->getSubmissionCommittees()->isDeleted(false)->status(SubmissionCommittee::STATUS_ACCEPTED)->count();
                    $message = " ({$count} " . Yii::t('app', 'คน') . ")";
                    if ($count == 0) {
                        $message = Yii::t('app', ' (กรรมการส่งครบแล้ว)');
                    }
                    return Submission::getStatusLabels()[$model->status] . $message;
                } else {
                    return Submission::getStatusLabels()[$model->status];
                }
            } else {
                return '';
            }
        }
    ];
} else {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'resolution',
        'value' => function($model) {
            if ($model->resolution != NULL) {
                return Submission::getResolutionLables()[$model->resolution];
            } else {
                return 'ยังไม่มีมติที่ประชุม';
            }
        }
    ];
}
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'attribute' => 'project_coordinator_id',
    'format' => 'raw',
    'value' => function($model) {
        if (isset($model->project_coordinator_id)) {
            return $model->projectCoordinator->person->i18nFullName;
        } else {
            return 'ไม่มี';
        }
    }
];
$items = array_merge($items, [
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'template' => '{view} {gencode} {meetingplan} {setagenda} {committeeAccepted} {committeeAssessment} {resubmit-c} {resubmit-r}',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
        'buttons' => [
            'view' => function($url, $model) {
                if ($model->status > 100) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> แสดงรายละเอียด', ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
                } else {

                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> แสดงรายละเอียด', ['submission/new', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
                }
            },
            'gencode' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok"></i> ออกเลขโครงการ', ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_GENERATECODE], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
            'meetingplan' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ประมาณวันที่เข้าประชุม', ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_MEETINGPLAN], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
            'setagenda' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> กำหนดวาระการประชุม', ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_SETAGENDA, 'panelId' => $model->project->panel_id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
            'committeeAccepted' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ตอบรับการอ่านงานวิจัย', ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_ACCEPTCOMMITTEE], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
            },
            'committeeAssessment' => function($url, $model) {
                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ประเมินงานวิจัย', ['questionnaire-answer/assessment', 'submissionId' => $model->id, 'projectId' => $model->project_id, 'sCommitteeId' => $sc->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
            },
            'resubmit-c' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="icon md-mail-reply"></i> ส่งแก้ไข', ['submission/new', 'refSubmissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
            },
            'resubmit-r' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="icon md-mail-reply"></i> ส่งแก้ไข', ['submission/new', 'refSubmissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
            },
//            'upfileAssessment' => function($url, $model) {
//                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();
//                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-upload"></i> อัฟโหลดไฟล์ประเมิน', ['submission-committee-document/update', 'submissionId' => $model->id, 'projectId' => $model->project_id, 'sCommitteeId' => $sc->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
//            },
//            'confirmAssessment' => function($url, $model) {
//                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();
//                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok"></i> ยืนยันการส่งการประเมิน', ['submission/update', 'submissionId' => $model->id, 'projectId' => $model->project_id, 'sCommitteeId' => $sc->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
//            },                    
////            'upfile' => function($url, $model) {
//                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> Upload File', ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_SETAGENDA], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']);
//            },
        ],
        'visibleButtons' => [
            'gencode' => function($model)use ($currentRole) {
                return $model->status == 300 && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN ) && $model->submissionType->submission_type_group_id != app\models\SubmissionTypeGroup::GROUP_CONT;
            },
            'meetingplan' => function($model)use ($currentRole) {
                return $model->status == 400 && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN );
            },
            'setagenda' => function($model)use ($currentRole) {
                return $model->status >= 900 && $model->status < 1000 && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN );
            },
            'committeeAccepted' => function($model)use ($currentRole) {
                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();

                return $model->status >= 700 && ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $sc->status <= 100 );
            },
            'committeeAssessment' => function($model)use ($currentRole) {
                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();

                return $model->status >= 800 && $model->status < 900 && ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $sc->status == 200);
            },
            'upfileAssessment' => function($model)use ($currentRole) {
                return $model->status >= 800 && ($currentRole['role_id'] == \app\models\Role::COMMITTEE );
            },
            'confirmAssessment' => function($model)use ($currentRole) {
                return $model->status >= 800 && ($currentRole['role_id'] == \app\models\Role::COMMITTEE );
            },
            'resubmit-c' => function($model) use ($currentRole) {
//                \yii\helpers\VarDumper::dump($model->attributes);
                return $model->resolution == Submission::RESOLUTION_C; // && ($currentRole['role_id'] == \app\models\Role::RESEARCHER );
            },
            'resubmit-r' => function($model) use ($currentRole) {
                return $model->resolution == Submission::RESOLUTION_R || $model->resolution == Submission::RESOLUTION_N; // && ($currentRole['role_id'] == \app\models\Role::RESEARCHER );
            },
        // 'upfile' => function($model)use ($currentRole) {
//                return $model->status >= 300 && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN );
//            },
        ],
    ],
        ]);
return $items;
