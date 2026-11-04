<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Alert;
use app\models\Setting;
use app\models\Submission;
use app\models\Person;
use app\models\Role;
use app\models\Project;
use app\models\EmailQueue;
use app\models\ProjectResearcher;
use app\models\SubmissionCommittee;
use app\models\SubmissionCommitteeDocument;
use app\models\SubmissionDocument;
use app\models\SubmissionEvent;
use app\models\SubmissionResultDocument;
use app\models\SubmissionVolunteer;
use yii\helpers\VarDumper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AlertController extends Controller {

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionCheck() {
        $docCheckPeriod = Setting::getValue(Setting::ALERT_DOC_CHECK);
        $submissions = Submission::find()->isDeleted(FALSE)->status(Submission::STATUS_SUBMITTED)->statusDay($docCheckPeriod)->all();
        $staffs = Person::find()->joinWith(['personRoles'])->isDeleted(FALSE)->role(Role::STAFF)->all();
        foreach ($submissions as $sub) {
            foreach ($staffs as $st) {
                if (!isset($st->user)) {
                    continue;
                }
                $alert = new Alert();
                $message = 'โครงการ {0} ยังไม่ได้รับการตรวจสอบเอกสาร';
                if ($sub->submissionType->is_new) {
                    $message = 'โครงการ {0} ยังไม่ได้รับการตรวจสอบเอกสารและยังไม่แจ้งเลขที่โครงการวิจัย';
                }
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $st->user->id;
                $alert->save(FALSE);
            }
        }

        $committeeRepickPeriod = Setting::getValue(Setting::ALERT_COMMITTEE_REPICK_NOTIFY);
        $submissions = Submission::find()->joinWith(['submissionType'])->isDeleted(FALSE)->status(Submission::STATUS_COMMITTEE_SELECTED)->statusDay($committeeRepickPeriod)->committeeRepick()->all();
        foreach ($submissions as $sub) {
            $message = 'โครงการ {0} กรรมการไม่รับอ่าน ยังไม่เสนอกรรมการท่านใหม่';
            if (isset($sub->secretary_person)) {
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $sub->secretary_person;
                $alert->save(FALSE);
            }
            if (isset($sub->responsible_person)) {
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $sub->responsible_person;
                $alert->save(FALSE);
            }
        }

        $committeeAssessPeriod = Setting::getValue(Setting::ALERT_COMMITTEE_ASSESS);
        $submissions = Submission::find()->joinWith(['submissionType'])->isDeleted(FALSE)->status(Submission::STATUS_COMMITTEE_ACCEPTED)->statusDay($committeeAssessPeriod)->all();
        foreach ($submissions as $sub) {
            $message = 'โครงการ {0} กรรมการยังไม่ตอบรับการอ่าน';
            $comittees = $sub->getSubmissionCommittees()->isDeleted(FALSE)->status(\app\models\SubmissionCommittee::STATUS_PENDING)->all();
            $sendAlert = false;
            foreach ($comittees as $c) {
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $c->person_id;
                $alert->save(FALSE);
                $sendAlert = true;
            }
            if (isset($sub->responsible_person) && $sendAlert) {
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $sub->responsible_person;
                $alert->save(FALSE);
            }
        }

        $resubmitPeriod = Setting::getValue(Setting::ALERT_RESUBMIT);
        $submissions = Submission::find()->joinWith(['submissionType'])->isDeleted(FALSE)->status(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->statusDay($resubmitPeriod)->resolution([Submission::RESOLUTION_C, Submission::RESOLUTION_R])->noResubmit()->all();
        foreach ($submissions as $sub) {
            $message = 'โครงการ {0} ยังไม่ส่งแก้ไข';
            if (isset($sub->project->projectLeader)) {
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $sub->project->projectLeader;
                $alert->save(FALSE);
            }
            if (isset($sub->responsible_person)) {
                $alert = new Alert();
                $alert->message = \Yii::t('app', $message, [
                            isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai
                ]);
                $alert->submission_id = $sub->id;
                $alert->user_id = $sub->responsible_person;
                $alert->save(FALSE);
            }
        }

//        $periods = [30, 15, 7, 1];
        $periods = [30, 15];
        $progressPeriods = [3, 6];
        $message1 = 'โครงการ {0} ต้องส่งความก้าวหน้าในอีก {1} วัน';
        $message2 = 'โครงการ {0} ต้องต่ออายุในอีก {1} วัน';
        foreach ($periods as $p) {
            $projects = Project::find()->isDeleted(FALSE)->isClosed(FALSE)->isActive(true)->nextProgressDay($p)->progressPeriod($progressPeriods)->all();
            foreach ($projects as $pr) {
                $sub = $pr->lastSubmission;
                EmailQueue::addQueueNoExec(EmailQueue::TYPE_PROGRESS_REMINDER, $sub->id);
                if (isset($sub->responsible_person)) {
                    $alert = new Alert();
                    $alert->message = \Yii::t('app', $message1, [
                                isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai,
                                $p
                    ]);
                    $alert->submission_id = $sub->id;
                    $alert->user_id = $sub->responsible_person;
                    $alert->save(FALSE);
                }
                if (isset($sub->projectLeader)) {
                    $alert = new Alert();
                    $alert->message = \Yii::t('app', $message1, [
                                isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai,
                                $p
                    ]);
                    $alert->submission_id = $sub->id;
                    $alert->user_id = $sub->projectLeader->person->user_id;
                    $alert->save(FALSE);
                }
            }

            $projects = Project::find()->isDeleted(FALSE)->isClosed(FALSE)->isActive(true)->nextRenewDay($p)->all();
            foreach ($projects as $pr) {
                $sub = $pr->lastSubmission;
                EmailQueue::addQueueNoExec(EmailQueue::TYPE_RENEW_REMINDER, $sub->id);
                if (isset($sub->responsible_person)) {
                    $alert = new Alert();
                    $alert->message = \Yii::t('app', $message2, [
                                isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai,
                                $p
                    ]);
                    $alert->submission_id = $sub->id;
                    $alert->user_id = $sub->responsible_person;
                    $alert->save(FALSE);
                }
                if (isset($sub->projectLeader)) {
                    $alert = new Alert();
                    $alert->message = \Yii::t('app', $message2, [
                                isset($sub->project->project_code) ? $sub->project->project_code : $sub->project->name_thai,
                                $p
                    ]);
                    $alert->submission_id = $sub->id;
                    $alert->user_id = $sub->projectLeader->person->user_id;
                    $alert->save(FALSE);
                }
            }
        }

        // แจ้งเตือนเอกสารการอบรมใกล้หมดอายุ (เช่น ล่วงหน้า 60/30 วัน)
        // เฉพาะนักวิจัยที่มีโครงการ Active และเตือนเพียงครั้งเดียวต่อ (การอบรม, จำนวนวัน)
        $expirePeriodsRaw = \app\models\Setting::getValueOrDefault(\app\models\Setting::TRAINING_EXPIRE_ALERT_PERIODS, '60,30');
        $expirePeriods = array_filter(array_map('trim', explode(',', $expirePeriodsRaw)), function($v) {
            return $v !== '' && is_numeric($v);
        });
        $messageExpire = 'เอกสารการอบรม "{0}" จะหมดอายุในอีก {1} วัน';
        foreach ($expirePeriods as $d) {
            $d = (int) $d;
            $trainings = \app\models\PersonTraining::find()
                    ->isDeleted(FALSE)
                    ->expiringInDays($d)
                    ->andWhere(['EXISTS', (new \yii\db\Query())
                        ->from(['pr' => 'project_researcher'])
                        ->innerJoin(['p2' => 'project'], 'p2.id = pr.project_id')
                        ->where('pr.person_id = person_training.person_id')
                        ->andWhere(['pr.deleted' => 0, 'p2.deleted' => 0, 'p2.is_active' => 1])])
                    ->andWhere(['NOT EXISTS', (new \yii\db\Query())
                        ->from(['nh' => 'notification_history'])
                        ->where('nh.person_training_id = person_training.id')
                        ->andWhere([
                            'nh.deleted' => 0,
                            'nh.notify_type' => \app\models\NotificationHistory::TYPE_TRAINING_EXPIRE,
                            'nh.notify_days' => $d,
                        ])])
                    ->all();
            foreach ($trainings as $t) {
                $typeName = isset($t->trainingType) ? $t->trainingType->name : ($t->name_thai_course ?: '');
                if (isset($t->person) && isset($t->person->user)) {
                    $alert = new Alert();
                    $alert->message = \Yii::t('app', $messageExpire, [$typeName, $d]);
                    $alert->user_id = $t->person->user->id;
                    $alert->save(FALSE);
                }
                EmailQueue::addQueueNoExec(EmailQueue::TYPE_TRAINING_EXPIRE_REMINDER, $t->id);

                $nh = new \app\models\NotificationHistory();
                $nh->person_id = $t->person_id;
                $nh->person_training_id = $t->id;
                $nh->notify_type = \app\models\NotificationHistory::TYPE_TRAINING_EXPIRE;
                $nh->notify_days = $d;
                $nh->deleted = 0;
                $nh->save(FALSE);
            }
        }

        // ปรับสถานะเป็น inactive ถ้า โครงการหมดอายุเกิน 1 ปี (365 วัน)
        \Yii::$app->db->createCommand("
            UPDATE project p
            INNER JOIN (
                SELECT p.id
                FROM `project` p
                WHERE p.is_active = 1 AND DATEDIFF(CURDATE(), p.expire_at) > 365
            ) tb ON p.id = tb.id
            SET p.is_active = 0

        ")->execute();

        // ปรับสถานะเป็น inactive ถ้ารับรองวาระ 3.3 เกิน 3 ปี (1095 วัน)
        \Yii::$app->db->createCommand("
            UPDATE project p
            INNER JOIN (
                SELECT ma.project_id, ma.updated_at
                FROM meeting_agenda ma
                INNER JOIN (
                    SELECT ma.project_id, MAX(id) AS ma_id
                    FROM meeting_agenda ma
                    WHERE ma.deleted = 0 AND ma.project_id IS NOT NULL
                    GROUP BY ma.project_id
                ) tb ON ma.id = tb.ma_id
                WHERE ma.agenda_id = 8 AND ma.resolution = 'Y'
            ) tb1 ON p.id = tb1.project_id
            SET p.is_active = 0
            WHERE p.is_active = 1 AND DATEDIFF(CURDATE(), tb1.updated_at) > 1095

        ")->execute();


        EmailQueue::execSendMailCmd();
    }


    public function actionTest() {
        $sc = SubmissionResultDocument::find()->one();
        VarDumper::dump($sc->attributes);
    }

}
