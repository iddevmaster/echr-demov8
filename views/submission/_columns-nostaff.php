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
            if (isset($model->project->project_code)) {
                $codes = $model->project->projectCodeHistoriesHtml;
                return $model->project->project_code . (empty($codes) ? '' : "({$codes})");
            } else {
                return Yii::t('app', 'N/A');
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.name_thai',
        'value' => function($model) {
            if ($model->is_legacy == 1) {
                return $model->project->name_thai . Yii::t('app', '(โครงการเดิมที่ผ่านการรับรองแล้ว)');
            } else {
                return $model->project->name_thai;
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'typeAndRef',
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
    [
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
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsiblePerson.person.fullName',
        'value' => function($model) {
            if ($model->responsible_person != NULL) {
                return $model->responsiblePerson->person->fullName;
            } else {
                return 'ยังไม่กำหนดเจ้าหน้าที่';
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'resolution',
        'value' => function($model) {
            $res = '';
            if (isset($model->meetingAgenda)) {
                $res .= "[{$model->meetingAgenda->meeting->yearNo}] ";
            }
            if ($model->resolution != NULL) {
                $res .= Submission::getResolutionLables()[$model->resolution];
            } else {
                $res .= 'ยังไม่มีมติที่ประชุม';
            }
            return $res;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'label' => Yii::t('app', 'สถานะการจัดการไฟล์หนังสือแจ้งผล'),
        'value' => function($model) {
            if (isset($model->resultDocument)) {
                return '<span class="badge badge-info bg-blue-500">' . Yii::t('app', 'อัฟโหลดแล้ว') . '</span>';
            } else {
                return '<span class="badge badge-info bg-red-500">' . Yii::t('app', 'ยังไม่อัฟโหลด') . '</span>';
            }
        }
    ]
];
//$items[] = [
//    'class' => '\kartik\grid\DataColumn',
//    'attribute' => 'responsiblePerson.person.fullName',
//    'value' => function($model) {
//        if ($model->responsible_person != NULL) {
//            return $model->responsiblePerson->person->fullName;
//        } else {
//            return 'ยังไม่กำหนดเจ้าหน้าที่';
//        }
//    }
//];
//$items[] = [
//    'class' => '\kartik\grid\DataColumn',
//    'attribute' => 'resolution',
//    'value' => function($model) {
//        if ($model->resolution != NULL) {
//            return Submission::getResolutionLables()[$model->resolution];
//        } else {
//            return 'ยังไม่มีมติที่ประชุม';
//        }
//    }
//];
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
        'hAlign' => 'left',
        'template' => '{view}{setagenda} {committeeAccepted} {committeeAssessment} {resubmit-c} {resubmit-r} {setresponsibleperson} {delete} {pm-accept-again} {change-responsible} {change-panel}',
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
            'view' => function($url, $model)use ($currentRole) {
                if ($model->status > 100) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'แสดงรายละเอียด'), ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                } elseif ($model->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_NEW) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งข้อมูลอีกครั้ง'), ['submission/new', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                } elseif ($model->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_CONT) {
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งข้อมูลอีกครั้ง'), ['submission/continue', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                }
            },
            'gencode' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-ok"></i> ' . Yii::t('app', 'ออกเลขโครงการ'), ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_GENERATECODE], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'meetingplan' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ประมาณวันที่เข้าประชุม'), ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_MEETINGPLAN], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'setagenda' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'กำหนดวาระการประชุม'), ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_SETAGENDA, 'panelId' => $model->project->panel_id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'committeeAccepted' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ตอบรับการอ่านงานวิจัย'), ['submission/update', 'id' => $model->id, 'projectId' => $model->project_id, 'mode' => Submission::MODE_ACCEPTCOMMITTEE], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'setresponsibleperson' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'รับผิดชอบโครงการต่อเนื่อง'), ['submission/responsible', 'id' => $model->id], ['role' => 'modal-remote', 'title' => 'รับผิดชอบโครงการต่อเนื่อง',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการกำหนดผู้รับผิดชอบโครงการต่อเนื่อง'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการกำหนดผู้รับผิดชอบโครงการต่อเนื่องนี้ใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่')]) . '<br>';
            },
            'committeeAssessment' => function($url, $model) {
                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ประเมินงานวิจัย'), ['questionnaire-answer/assessment', 'submissionId' => $model->id, 'projectId' => $model->project_id, 'sCommitteeId' => $sc->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
            },
            'resubmit-c' => function($url, $model) {
                $url = 'submission/new';
                if (!$model->submissionType->is_new) {
                    $url = 'submission/continue';
                }
                return \yii\helpers\Html::a('<i class="icon md-mail-reply"></i> ' . Yii::t('app', 'ส่งแก้ไข'), [$url, 'refSubmissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
            },
            'resubmit-r' => function($url, $model) {
                $url = 'submission/new';
                if (!$model->submissionType->is_new) {
                    $url = 'submission/continue';
                }
                return \yii\helpers\Html::a('<i class="icon md-mail-reply"></i> ' . Yii::t('app', 'ส่งแก้ไข'), [$url, 'refSubmissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
            },
            'pm-accept-again' => function($url, $model) {
                if ($model->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_CONT) {
                    $url = 'submission/continue';
                } else {
                    $url = 'submission/new';
                }
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งข้อมูลอีกครั้ง'), [$url, 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
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
            'change-responsible' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-share-alt"></i> ' . Yii::t('app', 'เปลี่ยนผู้ดูแล'), ['submission/change-responsible', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'staff-check-result' => function($url, $model) {
                $ma = $model->getMeetingAgenda();
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-share-alt"></i> ' . Yii::t('app', 'เจ้าหน้าที่ตรวจสอบผลการพิจารณา'), ['meeting-agenda/update-info-staff', 'id' => $ma->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'secretary-check-result' => function($url, $model) {
                $ma = $model->getMeetingAgenda();
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-share-alt"></i> ' . Yii::t('app', 'เลขาตรวจสอบผลการพิจารณา'), ['meeting-agenda/update-info-secretary', 'id' => $ma->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'change-panel' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-random"></i> ' . Yii::t('app', 'เปลี่ยน Panel'), ['submission/change-panel', 'id' => $model->id], ['role' => 'modal-remote']) . '<br>';
            }
        ],
        'visibleButtons' => [
            'gencode' => function($model)use ($currentRole) {
                return $model->status == Submission::STATUS_DOC_APPROVED && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN ) && $model->submissionType->submission_type_group_id != app\models\SubmissionTypeGroup::GROUP_CONT;
            },
            'meetingplan' => function($model)use ($currentRole) {
                return $model->status == Submission::STATUS_CODE_GENERATED && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN );
            },
            'setagenda' => function($model)use ($currentRole) {
                return $model->status >= Submission::STATUS_COMMITTEE_ASSESSED && $model->status < Submission::STATUS_AGENDA_ADDED && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN );
            },
            'committeeAccepted' => function($model)use ($currentRole) {
                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();
                if (isset($sc)) {
                    return $model->status >= Submission::STATUS_COMMITTEE_SELECTED && ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $sc->status <= SubmissionCommittee::STATUS_REJECTED );
                }
            },
            'committeeAssessment' => function($model)use ($currentRole) {
                $sc = $model->getSubmissionCommittees()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->one();
                if (isset($sc)) {
                    return ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $sc->status == SubmissionCommittee::STATUS_ACCEPTED);
                }
            },
            'upfileAssessment' => function($model)use ($currentRole) {
                return $model->status >= Submission::STATUS_COMMITTEE_ACCEPTED && ($currentRole['role_id'] == \app\models\Role::COMMITTEE );
            },
            'confirmAssessment' => function($model)use ($currentRole) {
                return $model->status >= Submission::STATUS_COMMITTEE_ACCEPTED && ($currentRole['role_id'] == \app\models\Role::COMMITTEE );
            },
            'resubmit-c' => function($model) use ($currentRole) {
//                \yii\helpers\VarDumper::dump($model->attributes);
//                $revise = \app\models\SubmissionCommitteeRevise::find()->submission($model->id)->isDeleted(FALSE)->one();
                $refSub = Submission::find()->isDeleted(FALSE)->refSubmission($model->id)->one();
                return !isset($refSub) && $model->resolution == Submission::RESOLUTION_C && (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->project->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && $model->project_coordinator_id == \Yii::$app->user->id)) && $model->status == Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
            },
            'resubmit-r' => function($model) use ($currentRole) {
                $refSub = Submission::find()->isDeleted(FALSE)->refSubmission($model->id)->one();
                return !isset($refSub) && ($model->resolution == Submission::RESOLUTION_R || $model->resolution == Submission::RESOLUTION_N) && (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->project->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && $model->project_coordinator_id == \Yii::$app->user->id)) && $model->status == Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
            },
            'setresponsibleperson' => function($model) use ($currentRole) {
                $refSub = Submission::find()->isDeleted(FALSE)->refSubmission($model->id)->one();
                return $model->status == Submission::STATUS_SUBMITTED && ($currentRole['role_id'] == \app\models\Role::STAFF or $currentRole['role_id'] == \app\models\Role::ADMIN) && $model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT && $model->responsible_person == NULL;
            },
            'delete' => function($model) use ($currentRole) {
                return ($model->status <= Submission::STATUS_SUBMITTED && ($currentRole['role_id'] == \app\models\Role::STAFF or $currentRole['role_id'] == \app\models\Role::ADMIN)) or ( $model->status <= Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER && ($currentRole['role_id'] == \app\models\Role::RESEARCHER));
            },
            'pm-accept-again' => function($model)use ($currentRole) {
                return $model->status == Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER && ($currentRole['role_id'] == \app\models\Role::COORDINATOR );
            },
            'change-responsible' => function($model)use ($currentRole) {
                return isset($model->responsible_person) && $currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id;
            },
            'staff-check-result' => function($model)use ($currentRole) {
                $ma = $model->getMeetingAgenda();
                return isset($model->responsible_person) && $currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id && $model->status == Submission::STATUS_MEETING_DONE && isset($ma);
            },
            'secretary-check-result' => function($model)use ($currentRole) {
                $ma = $model->getMeetingAgenda();
                return isset($model->secretary_person) && $currentRole['role_id'] == \app\models\Role::SECRETARY && $model->secretary_person == \Yii::$app->user->identity->id && $model->status == Submission::STATUS_STAFF_APPROVE_AGENDA && isset($ma);
            },
            // 'upfile' => function($model)use ($currentRole) {
//                return $model->status >= 300 && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN );
//            },
            'change-panel' => function($model)use ($currentRole) {
//                \yii\helpers\VarDumper::dump(($model->status < Submission::STATUS_AGENDA_ADDED && isset($model->project->projectCode)));
                return (($currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN ) && ($model->status < Submission::STATUS_AGENDA_ADDED && isset($model->project->project_code) && !isset($model->resolution) && $model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) || ($model->status == Submission::STATUS_MEETING_DONE && $model->resolution == Submission::RESOLUTION_P && !$model->hasPanelChanged);
            },
        ],
    ],
        ]);
return $items;
