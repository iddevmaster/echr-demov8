<?php

use app\models\SubmissionCommittee;
use yii\helpers\Url;
use app\models\Submission;
use app\models\Project;
use yii\helpers\Html;
use app\models\Role;

$sCommitteeId = SubmissionCommittee::find()->isDeleted(FALSE)->person(\Yii::$app->user->identity->person->id)->submission($searchModel->id)->andWhere(['deleted' => 0])->all();

$currentRole = \Yii::$app->session->get('currentRole');
$items = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'project.project_code',
        'value' => function($model) {
            if (isset($model->project->project_code)) {
                $codes = $model->project->projectCodeHistoriesHtml;
                $crecNumber = !empty($model->project->crec_number) ? '<br><font class="green-700">' . Yii::t('app', 'CREC No.') . $model->project->crec_number . '</font>' : "";
                $submissionNumber = !empty($model->submission_number) ? '<br><font class="teal-700">(' . $model->submission_number . ')</font>' : "";

                return $model->project->project_code . (empty($codes) ? '' : "({$codes})") . $crecNumber . $submissionNumber;
            } else {
                $crecNumber = !empty($model->project->crec_number) ? '<br><font class="green-700">' . Yii::t('app', 'CREC No.') . $model->project->crec_number . '</font>' : "";
                $submissionNumber = !empty($model->submission_number) ? '<br><font class="teal-700">(' . $model->submission_number . ')</font>' : "";

                return Yii::t('app', 'N/A') . $crecNumber . $submissionNumber;
            }
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.name_thai',
        'format' => 'raw',
        'value' => function($model) {
            $name = "";
            if ($model->is_legacy == 1) {
                $name .= $model->project->i18nName . ' <font class="purple-700">' . Yii::t('app', '(โครงการเดิมที่ผ่านการรับรองแล้ว)') . '</font>';
//                if ($model->submission_type_id == \app\models\SubmissionType::TYPE_DEVIATION) {
//                    $events = \app\models\SubmissionEvent::find()->isDeleted(false)
//                                    ->submission($model->id)->all();
//                    foreach ($events as $event):
//                        $name .= " " . $event->code . " ";
//                    endforeach;
//                } else if ($model->submission_type_id == \app\models\SubmissionType::TYPE_INTERNAL_SAE) {
//                    $volunteers = \app\models\SubmissionVolunteer::find()->isDeleted(false)
//                                    ->submissionId($model->id)->all();
//                    foreach ($volunteers as $volunteer):
//                        $name .= $volunteer->volunteer->code . " ";
//                    endforeach;
//                }
            } else {
                $name .= $model->project->i18nName;
//                if ($model->submission_type_id == \app\models\SubmissionType::TYPE_DEVIATION) {
//                    $events = \app\models\SubmissionEvent::find()->isDeleted(false)
//                                    ->submission($model->id)->all();
//                    foreach ($events as $event):
//                        $name .= " " . $event->code . " ";
//                    endforeach;
//                } else if ($model->submission_type_id == \app\models\SubmissionType::TYPE_INTERNAL_SAE) {
//                    $volunteers = \app\models\SubmissionVolunteer::find()->isDeleted(false)
//                                    ->submissionId($model->id)->all();
//                    foreach ($volunteers as $volunteer):
//                        $name .= $volunteer->volunteer->code . " ";
//                    endforeach;
//                }
            }
            $color = $model->project->name_changed ? "blue-600" : "";
            $title = $model->project->name_changed ? "โครงการวิจัยนี้ได้รับการเปลี่ยนชื่อตามการแก้ไขของผู้วิจัย" : "";
            return "<span class='{$color}' title='{$title}' data-toggle='tooltip' data-placement='bottom'>{$name}</span>";
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'typeAndRef',
        'value' => function($model) use ($currentRole) {
            $typename = $model->typeAndRef;
            if ($currentRole['role_id'] == \app\models\Role::COMMITTEE) {
                if (isset($model->meeting_plan_date)) {
                    $typename .= Yii::t('app', '<br>วันที่ประมาณการประชุม : ') . Yii::$app->formatter->format($model->meeting_plan_date, 'date');
                }
                if (isset($model->send_plan_date)) {
                    $typename .= Yii::t('app', '<br>วันที่ประมาณการส่งผลการประเมิน : ') . Yii::$app->formatter->format($model->send_plan_date, 'date');
                }
            }
            return $typename;
        }
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
//if ($searchModel->status == 700) {
//    $items[] = [
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'status',
//        'value' => function($model) {
//            return Submission::getStatusLabels()[$model->status];
//        }
//    ];
//}
//if ($searchModel->status < 1000) {
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'attribute' => 'status',
    'value' => function($model) {
        if ($model->status) {
            if ($model->status == Submission::STATUS_COMMITTEE_ACCEPTED) {
                $count = $model->getSubmissionCommittees()->isDeleted(false)->status(app\models\SubmissionCommittee::STATUS_ACCEPTED)->count();
                $message = " ({$count} " . Yii::t('app', 'คน') . ")";
                if ($count == 0) {
                    $message = Yii::t('app', ' (กรรมการส่งครบแล้ว)');
                }
                return Submission::getStatusLabels()[$model->status] . $message;
            } else {
                return Submission::getStatusLabels()[$model->status];
            }
        }
    }
];
$currentRole = \Yii::$app->session->get('currentRole');
if (in_array($currentRole['role_id'], [Role::STAFF, Role::ADMIN, Role::RESEARCHER, Role::COORDINATOR])) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'project.isActiveLabel',
        'format' => 'raw',
        'hAlign' => 'center',
        'value' => function($model) {
            $color = $model->project->is_active ? 'blue-600' : 'red-600';
            if (isset($model->project->is_active)) {
                if (Yii::$app->util->checkPermission('project.toggle-active')) {
                    if (isset($model->project->is_active)) {
                        $newLabel = Project::isActiveLabels()[!$model->project->is_active];
                        return Html::a($model->project->isActiveLabel, ['project/toggle-active', 'id' => $model->project_id], [
                                    'class' => $color,
                                    'role' => 'modal-remote',
                                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                    'data-request-method' => 'post',
                                    // 'data-toggle' => 'tooltip',
                                    'data-confirm-title' => Yii::t('app', 'ยืนยันเปลี่ยนสถานะ'),
                                    'data-confirm-message' => Yii::t('app', "ต้องการเปลี่ยนสถานะจาก {$model->project->isActiveLabel} เป็น {$newLabel}"),
                                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                    'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                    }
                } else {
                    return "<span class='{$color}'>{$model->project->isActiveLabel}</span>";
                }
            } else {
                if (Yii::$app->util->checkPermission('submission.active') && isset($model->project->project_code)) {
                    return Html::a('<i class="glyphicon glyphicon-check"></i>', ['submission/active', 'projectId' => $model->project_id], [
                                'class' => $color,
                                'role' => 'modal-remote',
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                // 'data-toggle' => 'tooltip',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันเปลี่ยนสถานะเป็น Active'),
                                'data-confirm-message' => Yii::t('app', "ต้องการเปลี่ยนสถานะ Active หรือไม่"),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                    ]);
                }
            }
        }
    ];
}
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'attribute' => 'responsiblePerson.person.fullName',
    'value' => function($model) {
        if ($model->responsible_person != NULL) {
            return $model->responsiblePerson->person->i18nFullName;
        } else {
            return Yii::t('app', 'N/A');
        }
    }
];
$items[] = [
    'class' => '\kartik\grid\DataColumn',
    'attribute' => 'project_coordinator_id',
    'format' => 'raw',
    'value' => function($model) {
        if (isset($model->project_coordinator_id)) {
            $mobile = isset($model->projectCoordinator->person->mobile_no) ? $model->projectCoordinator->person->mobile_no : "N/A";
            $email = isset($model->projectCoordinator->person->email) ? $model->projectCoordinator->person->email : "N/A";
            return $model->projectCoordinator->person->i18nFullName . "<br>Mobile : " . $mobile . "<br>Email : " . $email;

//            return $model->projectCoordinator->person->i18nFullName;
        } else {
            return Yii::t('app', 'N/A');
        }
    }
];
//} else {
$items[] = [
    'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',

    'attribute' => 'resolution',
    'value' => function($model) {
//            return Submission::getResolutionLables()[$model->resolution];
        $res = isset($model->assess_type) ? "<span class='badge badge-info bg-cyan-500'><i class='icon wb-star' aria-hidden='true'></i> " . submission::getAssessTypeLabel()[$model->assess_type] . '</span><br>' : "";
        if (isset($model->meetingAgenda)) {
            $res .= "[{$model->meetingAgenda->meeting->yearNo}:{$model->meetingAgenda->sort_label}]<br>";
        }
        if ($model->resolution != NULL) {
            $res .= Submission::getResolutionLables()[$model->resolution];
        } else {
            $res .= 'N/A';
        }
        return $res;
    }
];
if ($searchModel->status == Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER && isset($searchModel->project_coordinator_id) && ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($searchModel->project_coordinator_id == \Yii::$app->user->id || $searchModel->project_coordinator_2nd_id == \Yii::$app->user->id || $searchModel->project_coordinator_3rd_id == \Yii::$app->user->id ))) {
    $items[] = [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'leader_comment',
        'format' => 'raw',
        'value' => function($model) {
            return $model->leader_comment;
        }
    ];
}
$items = array_merge($items, [
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'label' => Yii::t('app', 'หนังสือแจ้งผล'),
        'value' => function($model) {
            if (isset($model->resultDocument)) {

//                return '<span class="badge badge-info bg-blue-500">' . Yii::t('app', 'อัฟโหลดแล้ว') . '</span>';
                return \yii\helpers\Html::a('<span class="badge badge-info bg-blue-500">' . Yii::t('app', 'อัฟโหลดแล้ว') . '</span>', ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']);
            } else {
                return '<span class="badge badge-info bg-red-500">' . Yii::t('app', 'ยังไม่อัฟโหลด') . '</span>';
            }
        }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'noWrap' => true,
        'vAlign' => 'middle',
        'hAlign' => 'left',
        'template' => '{note}{view}{setagenda} {committeeAccepted} {committeeAssessment} {resubmit-c} {resubmit-r} {setresponsibleperson} {pm-accept-again} {pm-accept} {change-responsible} {change-panel} {acceptPr} {acceptCs} <br>{delete}',
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
            'note' => function($url, $model) {
                if (isset($model->note)) {
                    return \yii\helpers\Html::a('<i class="icon wb-info-circle green-600"></i><span class="green-600"> ' . Yii::t('app', 'NOTE') . '</span>', ['submission/submission-note', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => yii::t('app', ':: อ่านหรือแก้ไขหมายเหตุของโครงการ ')]) . '<br>';
                } else {
                    return \yii\helpers\Html::a('<i class="icon wb-info-circle"></i> ' . Yii::t('app', 'NOTE'), ['submission/submission-note', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => yii::t('app', ':: บันทึกหมายเหตุของโครงการ ')]) . '<br>';
                }
            },
            'view' => function($url, $model)use ($currentRole) {
                $link = \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> <font class="green-700">' . Yii::t('app', 'แสดงรายละเอียด'). '</font>', ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                if ((($currentRole['role_id'] == \app\models\Role::RESEARCHER) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->project_coordinator_id == \Yii::$app->user->id || $model->project_coordinator_2nd_id == \Yii::$app->user->id || $model->project_coordinator_3rd_id == \Yii::$app->user->id || $model->project_viewer_id == \Yii::$app->user->id )))) {
                    if ($model->is_legacy == 0) {
                        if ($model->status > 100) {
                            $link = \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> <font class="green-700">' . Yii::t('app', 'แสดงรายละเอียด'). '</font>', ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                        } elseif ($model->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_NEW) {
                            $link = \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งข้อมูลอีกครั้ง'), ['submission/new', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                        } elseif ($model->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_CONT) {
                            $link = \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งข้อมูลอีกครั้ง'), ['submission/continue', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                        }
                    } else {
                        if ($model->status > 100) {
                            $link = \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> <font class="green-700">' . Yii::t('app', 'แสดงรายละเอียด'). '</font>', ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                        } else {
                            $link = \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งข้อมูลอีกครั้ง'), ['submission/new-certified', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
                        }
                    }
                }
                return $link;
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
                if ($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT) {
                    $url = 'submission/continue';
                }
                return \yii\helpers\Html::a('<i class="icon md-mail-reply"></i> ' . Yii::t('app', 'ส่งแก้ไข'), [$url, 'refSubmissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
            },
            'resubmit-r' => function($url, $model) {
                $url = 'submission/new';
                if ($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT) {
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
            'pm-accept' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'ส่งผลการตรวจสอบ/ยืนยัน'), ['submission/pm-accept', 'id' => $model->id, 'ind' => 1], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
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
            }, 'acceptPr' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ตอบรับร่วมโครงการ'), ['project-researcher/accept', 'id' => $model->id, 'personId' => \Yii::$app->user->identity->person->id], ['role' => 'modal-remote', 'title' => 'ตอบรับร่วมโครงการ',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการตอบรับร่วมโครงการใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่')]) . '<br>';
            }, 'acceptCs' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ตอบรับเป็นที่ปรึกษาโครงการ'), ['project-consultant/accept', 'id' => $model->id, 'personId' => \Yii::$app->user->identity->person->id], ['role' => 'modal-remote', 'title' => 'ตอบรับเป็นที่ปรึกษาโครงการ',
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการตอบรับร่วมโครงการใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่')]) . '<br>';
            },
        ],
        'visibleButtons' => [
            'note' => function($model)use ($currentRole) {
                return ((($model->status < Submission::STATUS_CODE_GENERATED) && ($currentRole['role_id'] == \app\models\Role::STAFF) || $currentRole['role_id'] == \app\models\Role::ADMIN ) || (($model->status >= Submission::STATUS_CODE_GENERATED) && ($currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::COMMITTEE || ($currentRole['role_id'] == \app\models\Role::SECRETARY && $model->secretary_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN));
            },
//            'view' => function($model)use ($currentRole) {
//                return $currentRole['role_id'] != \app\models\Role::COMMITTEE;
//            },
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
                return !isset($refSub) && $model->resolution == Submission::RESOLUTION_C && (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->project_coordinator_id == \Yii::$app->user->id || $model->project_coordinator_2nd_id == \Yii::$app->user->id || $model->project_coordinator_3rd_id == \Yii::$app->user->id ))) && $model->status == Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
            },
            'resubmit-r' => function($model) use ($currentRole) {
                $refSub = Submission::find()->isDeleted(FALSE)->refSubmission($model->id)->one();
                return !isset($refSub) && ($model->resolution == Submission::RESOLUTION_R || $model->resolution == Submission::RESOLUTION_N) && (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->project_coordinator_id == \Yii::$app->user->id || $model->project_coordinator_2nd_id == \Yii::$app->user->id || $model->project_coordinator_3rd_id == \Yii::$app->user->id ))) && $model->status == Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT;
            },
            'setresponsibleperson' => function($model) use ($currentRole) {
                $refSub = Submission::find()->isDeleted(FALSE)->refSubmission($model->id)->one();
                return $model->status == Submission::STATUS_SUBMITTED && ($currentRole['role_id'] == \app\models\Role::STAFF or $currentRole['role_id'] == \app\models\Role::ADMIN) && $model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT && $model->responsible_person == NULL;
            },
            'delete' => function($model) use ($currentRole) {
//                return ($model->status <= Submission::STATUS_SUBMITTED && ($currentRole['role_id'] == \app\models\Role::STAFF or $currentRole['role_id'] == \app\models\Role::ADMIN)) || ((empty($model->project->project_code) && (($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) || ($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT && isset($model->meeting_plan_date)))) && $model->status <= Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER  && ($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR));
                return ($model->status <= Submission::STATUS_SUBMITTED && ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)) || (((($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW)) || ($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT && empty($model->meeting_plan_date))) && ($model->status <= Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER) && ((isset($model->projectLeader->person->id) && ($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->project_coordinator_id == \Yii::$app->user->id || $model->project_coordinator_2nd_id == \Yii::$app->user->id || $model->project_coordinator_3rd_id == \Yii::$app->user->id ))) || (!isset($model->projectLeader->person->id) && (($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR ) && $model->created_by == \Yii::$app->user->id))));
            },
            'pm-accept-again' => function($model)use ($currentRole) {
                return $model->status == Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER && ($currentRole['role_id'] == \app\models\Role::COORDINATOR );
            },
            'pm-accept' => function($model)use ($currentRole) {
                return $model->status == Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER && ($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person->id == \Yii::$app->user->identity->person->id);
            },
            'change-responsible' => function($model)use ($currentRole) {
                return isset($model->responsible_person) && (($currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN );
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
                return (($currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN ) && ($model->status < Submission::STATUS_AGENDA_ADDED && isset($model->project->project_code) && $model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) || ($model->status >= Submission::STATUS_SECRETARY_APPROVE_AGENDA && $model->resolution == Submission::RESOLUTION_P && !$model->hasPanelChanged);
            },
            'acceptPr' => function($model)use ($currentRole) {

                $pr = $model->getProjectResearchers()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->acknowledgeStatus(100)->one();
                $cs = $model->getProjectConsultants()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->acknowledgeStatus(100)->one();
                if (isset($pr) && $currentRole['role_id'] == \app\models\Role::RESEARCHER) {
                    return $pr->person_id == \Yii::$app->user->identity->person->id && $pr->acknowledge_status == 100;
                }
            },
            'acceptCs' => function($model)use ($currentRole) {
                $cs = $model->getProjectConsultants()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->acknowledgeStatus(100)->one();
                if (isset($cs) && $currentRole['role_id'] == \app\models\Role::RESEARCHER) {
                    return $cs->person_id == \Yii::$app->user->identity->person->id && $cs->acknowledge_status == 100;
                }
            }
        ],
    ],
        ]);
return $items;
