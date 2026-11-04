<?php

use app\models\Project;
use app\models\Role;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Submission;

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
                    $typename .= Yii::t('app', '<br>วันที่ประมาณการส่งผลการประเมิน : ') . Yii::$app->formatter->format($model->meeting_plan_date, 'date');
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
        'hAlign' => 'center',
        'format' => 'raw',
        'value' => function($model) {
            $color = $model->project->is_active ? 'blue-600' : 'red-600';
            if (isset($model->project->is_active)) {
                if (Yii::$app->util->checkPermission('project.toggle-active')) {
                    if (isset($model->project->is_active)) {
                        $newLabel = Project::isActiveLabels()[!$model->project->is_active];
                        return Html::a($model->project->isActiveLabel, ['project/toggle-active', 'id' => $model->project_id, 'reload' => '#crud-datatable-submission-' . $model->project->panel_id], [
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
                    return Html::a('<i class="glyphicon glyphicon-check"></i>', ['submission/active', 'projectId' => $model->project_id, 'reload' => '#crud-datatable-submission-' . $model->project->panel_id], [
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


//}
$items = array_merge($items, [
    [
        'class' => '\kartik\grid\DataColumn',
        'format' => 'raw',
        'label' => Yii::t('app', 'หนังสือแจ้งผล'),
        'value' => function($model) {
            if (isset($model->resultDocument)) {
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
        'template' => '{note}{view}{change-responsible}{delete}',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'note' => function($url, $model) {
                if (isset($model->note)) {
                    return \yii\helpers\Html::a('<i class="icon wb-info-circle green-600"></i><span class="green-600"> ' . Yii::t('app', 'NOTE') . '</span>', ['submission/submission-note', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => yii::t('app', ':: อ่านหรือแก้ไขหมายเหตุของโครงการ ')]) . '<br>';
                } else {
                    return \yii\helpers\Html::a('<i class="icon wb-info-circle"></i> ' . Yii::t('app', 'NOTE'), ['submission/submission-note', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip', 'title' => yii::t('app', ':: บันทึกหมายเหตุของโครงการ ')]) . '<br>';
                }
            },
            'view' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-edit"></i> ' . Yii::t('app', 'แสดงรายละเอียด'), ['submission/project-submission', 'submissionId' => $model->id], ['data-pjax' => 0, 'data-toggle' => 'tooltip']) . '<br>';
            },
            'change-responsible' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-share-alt"></i> ' . Yii::t('app', 'เปลี่ยนผู้ดูแล'), ['submission/change-responsible', 'id' => $model->id], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']) . '<br>';
            },
            'change-panel' => function($url, $model) {
                return \yii\helpers\Html::a('<i class="glyphicon glyphicon-random"></i> ' . Yii::t('app', 'เปลี่ยน Panel'), ['submission/change-panel', 'id' => $model->id], ['role' => 'modal-remote']);
            },
            'delete' => function ($url, $model) {
                // $reload = "#crud-datatable-complaint-problem-product-{$model->complaintProblem->complaint_id}-{$model->prodbrn}-pjax";
                $reload = "#crud-datatable-submission-" . $model->project->panel_id;
                return Html::a(
                                '<i class="icon fa fa-trash fa-w-14"></i>',
                                ['submission/delete', 'id' => $model->id, 'reload' => $reload],
                                [
                                    'role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
                                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                    'data-request-method' => 'post',
                                    // 'data-toggle' => 'tooltip',
                                    'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
                                    'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
                                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                    'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                                ]
                );
            },
        ],
        'visibleButtons' => [
            'note' => function($model)use ($currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::COMMITTEE || ($currentRole['role_id'] == \app\models\Role::SECRETARY && $model->secretary_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN;
            },
            'change-panel' => function($model) {
//                \yii\helpers\VarDumper::dump(($model->status < Submission::STATUS_AGENDA_ADDED && isset($model->project->projectCode)));
                return ($model->status < Submission::STATUS_AGENDA_ADDED && isset($model->project->project_code) && $model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) || ($model->status == Submission::STATUS_MEETING_DONE && $model->resolution == Submission::RESOLUTION_P && !$model->hasPanelChanged);
            },
            'change-responsible' => function($model)use ($currentRole) {
                return isset($model->responsible_person) && (($currentRole['role_id'] == \app\models\Role::STAFF && $model->responsible_person == \Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN );
            },
            'delete' => function($model) use ($currentRole) {
                return ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) && (($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW && $model->status <= Submission::STATUS_DOC_APPROVED) || ($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT && $model->status <= Submission::STATUS_COMMITTEE_ASSESSED));
            },
        ]
    ],
        ]);
return $items;
