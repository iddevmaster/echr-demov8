<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "alert".
 *
 * @property int $id
 * @property string $message ข้อความเตือน
 * @property int $type ประเภทการเตือน
 * @property int $is_acknowledged รับรู้การเตือน
 * @property int $acknowledged_by รับรู้โดย
 * @property string $acknowledged_at รับรู้เมื่อ
 * @property string $alerted_at เตือนเมื่อ
 * @property string $created_at สร้างเมื่อ
 * @property int $user_id เตือนผู้ใช้
 * @property int $submission_id การส่งพิจารณาโครงการ
 *
 * @property User $acknowledgedBy
 * @property Submission $submission
 * @property User $user
 */
class Alert extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'alert';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'is_acknowledged', 'acknowledged_by', 'user_id', 'submission_id'], 'integer'],
            [['acknowledged_at', 'alerted_at', 'created_at'], 'safe'],
            [['message'], 'string', 'max' => 255],
            [['acknowledged_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['acknowledged_by' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::className(), 'targetAttribute' => ['submission_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'message' => Yii::t('app', 'ข้อความเตือน'),
            'type' => Yii::t('app', 'ประเภทการเตือน'),
            'is_acknowledged' => Yii::t('app', 'รับรู้การเตือน'),
            'acknowledged_by' => Yii::t('app', 'รับรู้โดย'),
            'acknowledged_at' => Yii::t('app', 'รับรู้เมื่อ'),
            'alerted_at' => Yii::t('app', 'เตือนเมื่อ'),
            'created_at' => Yii::t('app', 'สร้างเมื่อ'),
            'user_id' => Yii::t('app', 'เตือนผู้ใช้'),
            'submission_id' => Yii::t('app', 'การส่งพิจารณาโครงการ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcknowledgedBy() {
        return $this->hasOne(User::className(), ['id' => 'acknowledged_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission() {
        return $this->hasOne(Submission::className(), ['id' => 'submission_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return AlertQuery the active query used by this AR class.
     */
    public static function find() {
        return new AlertQuery(get_called_class());
    }

    public static function buildAlertItems($alerts) {
        $items = [];
        if (count($alerts) > 0) {
            $items[] = [
                'label' => '<div class="text-center"><i class="icon md-check"></i> ' . \Yii::t('app', 'รับทราบทั้งหมด') . '</div>',
                'url' => 'javascript:acknowledgeAll()',
                'options' => ['class' => 'dropdown-menu-footer'],
            ];
        }
        foreach ($alerts as $alert) {
            $iconColor = "bg-success";
            $body = '<h6 class="media-heading" style="white-space:normal">' . $alert->message . '</h6>
                          <time class="media-meta">' . \Yii::$app->formatter->asDateTime($alert->created_at) . '</time>';
            $itemClass = $alert->is_acknowledged ? "bg-grey-300" : "";
            if (!$alert->is_acknowledged) {
                $js = "event.preventDefault();acknowledge({$alert->id});";
                $iconColor = "bg-danger";
                if (isset($alert->submission_id)) {
                    $url = Url::to(['submission/project-submission', 'submissionId' => $alert->submission_id]);
                    $js .= "window.open('{$url}', '_blank');";
                }
                $body .= \yii\helpers\Html::tag('div', \yii\helpers\Html::button('<i class="icon md-check"></i> ' . \Yii::t('app', 'รับทราบ'), ['class' => 'btn btn-xs btn-primary', 'onclick' => $js]), [
                            'class' => 'pull-right'
                ]);
            }

            $items[] = [
                'label' => '
                    
                      <div class="media" style="border-top: 1px solid #e0e0e0">
                        <div class="media-left padding-right-10">
                          <i class="icon md-calendar ' . $iconColor . ' white icon-circle" aria-hidden="true"></i>
                        </div>
                        <div class="media-body">
                          ' . $body . '
                        </div>
                      </div>',
                'url' => "javascript:void(0)",
//                                    'encode' => FALSE,
                'linkOptions' => ['class' => "list-group-item {$itemClass}", 'role' => 'menuitem'],
            ];
        }
        $items[] = [
            'label' => \Yii::t('app', 'แสดงทั้งหมด'),
            'url' => Url::to(['alert/index']),
            'options' => ['class' => 'dropdown-menu-footer'],
        ];
        return $items;
    }

    public static function addCommitteeAcknowledge($submissionCommittee) {
        $message = 'โครงการ {0} กรรมการ {1} {2} การอ่าน';
        if (isset($submissionCommittee->submission->responsible_person)) {
            $alert = new Alert();
            $alert->message = \Yii::t('app', $message, [
                        isset($submissionCommittee->submission->project->project_code) ? $submissionCommittee->submission->project->project_code : $submissionCommittee->submission->project->name_thai,
                        Yii::$app->user->identity->person->fullName,
                        SubmissionCommittee::getStatusLabels()[$submissionCommittee->status]
            ]);
            $alert->submission_id = $submissionCommittee->submission_id;
            $alert->user_id = $submissionCommittee->submission->responsible_person;
            $alert->created_at = date('Y-m-d H:i:s');

            $alert->save(FALSE);
        }
    }

    public static function addNewSubmission($submission) {

        if (isset($submission->ref_submission_id)) {
            $alert = new Alert();
            $message = 'โครงการ {0} ส่งแก้ไขแล้ว';
            $alert->message = \Yii::t('app', $message, [
                        $submission->project->project_code
            ]);
            $alert->submission_id = $submission->id;
            $alert->user_id = $submission->refSubmission->responsible_person;
            $alert->created_at = date('Y-m-d H:i:s');

            $alert->save(FALSE);
        } else {
            $message = 'มีการยื่นเสนอโครงการ {0} {1}';
            $staffs = Person::find()->joinWith('personRoles')->isDeleted(FALSE)->role(Role::STAFF)->all();
            foreach ($staffs as $s) {
                if (empty($submission->project->project_code)) {
                    $pname = $submission->project->name_thai;
                } else {
                    $pname = $submission->project->project_code;
                }
                if (!isset($s->user_id)) {
                    continue;
                }
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            $submission->submissionType->name,
                            $pname
                ]);
                $alert->user_id = $s->user_id;
                $alert->created_at = date('Y-m-d H:i:s');

                $alert->submission_id = $submission->id;
                $alert->save(FALSE);
            }
        }
    }

    public static function addNewCertifiedSubmission($submission) {

        $message = 'มีการยื่นเสนอโครงการใหม่ที่ผ่านการรับรองแล้ว {0}';
        $staffs = Person::find()->joinWith('personRoles')->isDeleted(FALSE)->role(Role::STAFF)->all();
        foreach ($staffs as $s) {
            if (!isset($s->user_id)) {
                continue;
            }
            $alert = new Alert();
            $alert->message = \Yii::t('app', $message, [
                        $submission->project->name_thai
            ]);
            $alert->user_id = $s->user_id;
            $alert->created_at = date('Y-m-d H:i:s');

            $alert->submission_id = $submission->id;
            $alert->save(FALSE);
        }
    }

    public static function addCrecReuploadDocument($submissionDocument) {
        if (!isset($submissionDocument->submission->responsible_person)) {
            return;
        }

        $alert = new Alert();
        $message = 'เอกสาร {0} โครงการ {1} ได้รับการ Upload ใหม่จาก CREC';
        $alert->message = \Yii::t('app', $message, [
                    $submissionDocument->name,
                    $submissionDocument->submission->project->project_code
        ]);
        $alert->submission_id = $submissionDocument->submission->id;
        $alert->user_id = $submissionDocument->submission->responsible_person;
        $alert->created_at = date('Y-m-d H:i:s');

        if (!$alert->save(FALSE)) {
            Yii::warning(\implode(", ", $alert->firstErrors));
        }
    }

    public static function addCrecUpdateResultDocument($submission) {
        if (!isset($submission->responsible_person)) {
            return;
        }

        $alert = new Alert();
        $message = 'เลขที่โครงการ {0} ({1}) ได้รับหนังสือแจ้งผลจาก CREC';
        $alert->message = \Yii::t('app', $message, [
                    $submission->project->project_code,
                    $submission->submissionType->name
        ]);
        $alert->submission_id = $submission->id;
        $alert->user_id = $submission->responsible_person;
        $alert->created_at = date('Y-m-d H:i:s');

        if (!$alert->save(FALSE)) {
            Yii::warning(\implode(", ", $alert->firstErrors));
        }
    }

    public static function addCrecUpdateResolution($submission) {
        if (!isset($submission->responsible_person)) {
            return;
        }

        $alert = new Alert();
        $message = 'เลขที่โครงการ {0} ได้รับมติประชุม {1} จาก CREC';
        $alert->message = \Yii::t('app', $message, [
                    $submission->project->project_code,
                    $submission->crec_resolution
        ]);
        $alert->submission_id = $submission->id;
        $alert->user_id = $submission->responsible_person;
        $alert->created_at = date('Y-m-d H:i:s');

        if (!$alert->save(FALSE)) {
            Yii::warning(\implode(", ", $alert->firstErrors));
        }
    }

    public static function addCrecResubmitLocalIssue($submission) {
        if (!isset($submission->responsible_person)) {
            return;
        }

        $alert = new Alert();
        $message = 'CREC ขอให้ประเมิน Local Issue เลขที่โครงการ {0} ใหม่อีกครั้ง';
        $alert->message = \Yii::t('app', $message, [
                    $submission->project->project_code,
        ]);
        $alert->submission_id = $submission->id;
        $alert->user_id = $submission->responsible_person;
        $alert->created_at = date('Y-m-d H:i:s');

        if (!$alert->save(FALSE)) {
            Yii::warning(\implode(", ", $alert->firstErrors));
        }
    }

}
