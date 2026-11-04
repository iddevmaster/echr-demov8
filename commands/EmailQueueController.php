<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\EmailQueue;
use app\models\Person;
use app\models\Submission;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EmailQueueController extends Controller {

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world') {
        $adminName = '=?utf-8?B?' . base64_encode(\Yii::$app->params['adminName']) . '?=';
        $p = Person::find()->isDeleted(false)->email('patipat.tip@gmail.com')->one();
        $msg = \Yii::$app->mailerTest->compose('registration-verification', [
            'user' => $p->user,
        ]);
        $res = $msg->setSubject(\Yii::t('app', 'ยืนยันการลงทะเบียน'))
                ->setFrom([\Yii::$app->params['adminEmail'] => $adminName])
                ->setTo($p->email)
                ->send();
    }

    public function actionSendMail() {
        $queues = EmailQueue::find()->isDeleted(FALSE)->unsent()->all();
        foreach ($queues as $q) {
            $q->sendMail();
        }
    }

    public function actionCheckCommitteeAssessDue() {
//        $submissions = Submission::find()->isDeleted(false)->andWhere(['DATEDIFF(send_plan_date, CURDATE())' => 3])->all();
//        foreach ($submissions as $submission) {
//            $committees = $submission->getSubmissionCommittees()->isDeleted(false)->status(\app\models\SubmissionCommittee::STATUS_ACCEPTED)->all();
//            foreach ($committees as $committee) {
//                EmailQueue::addQueueNoExec(EmailQueue::TYPE_COMMITTEE, $committee->id);
//            }
//        }
//        EmailQueue::execSendMailCmd();
    }

    public function actionCheckCoResearcherAcknowledgePending() {
        $submissions = Submission::find()->isDeleted(false)
//                ->hasPendingResearcher()
                ->status(Submission::STATUS_PENDING_SUBMISSION)
                ->hasLeader()
                ->andWhere(['>=', 'TIMESTAMPDIFF(DAY,submission.created_at, NOW())', 1])
                ->andWhere(['or', ['TIMESTAMPDIFF(DAY,submission.created_at, NOW())' => 7], [
                        'MOD(TIMESTAMPDIFF(DAY,submission.created_at, NOW()), 14)' => 0
            ]])
                ->all();
        foreach ($submissions as $submission) {
            $leader = $submission->getProjectLeader();
            EmailQueue::addQueueNoExec(EmailQueue::TYPE_CO_RESEARCHER_ACK_REMINDER, $leader->id);
        }
        EmailQueue::execSendMailCmd();
    }

    public function actionSubmissionOther() {

        $submissions = Submission::find()->isDeleted(false)->comTypeSubmission()->andWhere(['DATEDIFF(send_plan_date, CURDATE())' => 1])->all();
        foreach ($submissions as $submission) {
            $committees = $submission->getSubmissionCommittees()->isDeleted(false)->status(\app\models\SubmissionCommittee::STATUS_ACCEPTED)->all();
            foreach ($committees as $committee) {
                EmailQueue::addQueueNoExec(EmailQueue::TYPE_COMMITTEE, $committee->id);
            }
        }


        $submissionC = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_C)->overDay(90)->noResubmit()->all();
        $submissionCPending = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_C)->overDay(90)->hasPendingSubmission()->all();
        $submissionCs = array_merge($submissionC, $submissionCPending);
        $submissionR = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_R)->overDay(90)->noResubmit()->all();
        $submissionRPending = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_R)->overDay(90)->hasPendingSubmission()->all();
        $submissionRs = array_merge($submissionR, $submissionRPending);

        $submissionC30 = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_C)->overDay(30)->noResubmit()->all();
        $submissionCPending30 = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_C)->overDay(30)->hasPendingSubmission()->all();
        $submissionC30s = array_merge($submissionC30, $submissionCPending30);
        $submissionR30 = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_R)->overDay(30)->noResubmit()->all();
        $submissionRPending30 = Submission::find()->isDeleted(false)->resolution(Submission::RESOLUTION_R)->overDay(30)->hasPendingSubmission()->all();
        $submissionR30s = array_merge($submissionR30, $submissionRPending30);

        foreach ($submissionC30s as $c30):
            EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFORM_STAFF_OVER30DAY, $c30->id);
        endforeach;
        foreach ($submissionR30s as $r30):
            EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFORM_STAFF_OVER30DAY, $r30->id);
        endforeach;

        foreach ($submissionCs as $c):
            $submission = new Submission();
            $submission->project_id = $c->project_id;
            $submission->status = Submission::STATUS_COMMITTEE_ASSESSED;
            $submission->submission_type_id = 15;
            $submission->subject = 'โครงการที่ไม่มีการแก้ไขผลการพิจารณา C ภายในระยะเวลา 90 วัน ';
            $submission->ref_submission_id = $c->id;
            $submission->responsible_person = $c->responsible_person;
            if (isset($c->project_coordinator_id)) {
                $submission->project_coordinator_id = $c->project_coordinator_id;
            }
            $submission->save(false);

            $prs = \app\models\ProjectResearcher::find()->isDeleted(false)->submission($c->id)->all();
            foreach ($prs as $pr) {
                $projectC = new \app\models\ProjectResearcher();
                $projectC->project_id = $submission->project_id;
                $projectC->submission_id = $submission->id;
                $projectC->person_id = $pr->person_id;
                $projectC->is_leader = $pr->is_leader;
                $projectC->save(false);
            }

            EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFORM_STAFF_C_OVER90DAY, $c->id);

            if ($c->status == 100) {
                $c->deleted = 1;
                $c->save(false);
            }

        endforeach;

        foreach ($submissionRs as $r):
            $submission = new Submission();
            $submission->project_id = $r->project_id;
            $submission->status = Submission::STATUS_COMMITTEE_ASSESSED;
            $submission->submission_type_id = 15;
            $submission->subject = 'โครงการที่ไม่มีการแก้ไขผลการพิจารณา R ภายในระยะเวลา 90 วัน';
            $submission->ref_submission_id = $r->id;
            $submission->responsible_person = $r->responsible_person;
            if (isset($r->project_coordinator_id)) {
                $submission->project_coordinator_id = $r->project_coordinator_id;
            }
            $submission->save(false);

            $prsR = \app\models\ProjectResearcher::find()->isDeleted(false)->submission($r->id)->all();
            foreach ($prsR as $prR) {
                $projectR = new \app\models\ProjectResearcher();
                $projectR->project_id = $submission->project_id;
                $projectR->submission_id = $submission->id;
                $projectR->person_id = $prR->person_id;
                $projectR->is_leader = $prR->is_leader;
                $projectR->save(false);
            }

            EmailQueue::addQueueNoExec(EmailQueue::TYPE_INFORM_STAFF_R_OVER90DAY, $r->id);

            if ($r->status == 100) {
                $r->deleted = 1;
                $r->save(false);
            }
        endforeach;
        
        EmailQueue::execSendMailCmd();
    }

}
