<?php

namespace app\controllers;

use Yii;
use app\models\SubmissionCommittee;
use app\models\SubmissionCommitteeSearch;
use app\rbac\RbacController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\widgets\Alert;
use app\models\Submission;
use app\models\EmailQueue;
use app\models\Meeting;
use app\models\MeetingAgenda;
use app\models\ProjectSearch;
use app\models\Role;
use app\models\SubmissionSearch;
use app\models\SubmissionType;
use app\models\SubmissionTypeGroup;
use DateTime;
use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;

/**
 */
class ReportController extends RbacController
{

    public function actionDeviation($pdf = NULL, $isLeader = NULL, $isOngoing = NULL, $name = NULL)
    {
        $searchModel = new SubmissionSearch();
        $searchModel->submission_type_id = SubmissionType::TYPE_DEVIATION;
        $searchModel->onlySubmitted = true;
        $searchModel->deleted = 0;
        $searchModel->groupByProject = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($pdf)) {

            $searchModel = new SubmissionSearch();
            $searchModel->submission_type_id = SubmissionType::TYPE_DEVIATION;
            $searchModel->onlySubmitted = true;
            $searchModel->deleted = 0;
            $searchModel->groupByProject = 1;
            $searchModel->is_leader = $isLeader;
            $searchModel->isOngoing = $isOngoing;
            $searchModel->name = $name;

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'content' => $this->renderPartial('deviation', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isLeader' => $searchModel->is_leader,
                    'isOngoing' => $searchModel->isOngoing,
                    'name' => $searchModel->name,
                    'pdf' => $pdf
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                        'thsarabun' => [
                            'R' => "THSarabunNew.ttf",
                            'B' => "THSarabunNew-Bold.ttf",
                            'I' => "THSarabunNew-Italic.ttf",
                            'BI' => "THSarabunNew-BoldItalic.ttf"
                        ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }

        return $this->render('deviation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pdf' => $pdf
        ]);
    }

    public function actionSae($pdf = NULL, $isLeader = NULL, $isOngoing = NULL, $name = NULL)
    {
        $searchModel = new SubmissionSearch();
        $searchModel->onlySubmitted = true;
        $searchModel->sae = 1;
        $searchModel->deleted = 0;
        $searchModel->groupByProject = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (isset($pdf)) {

            $searchModel = new SubmissionSearch();
            $searchModel->onlySubmitted = true;
            $searchModel->sae = 1;
            $searchModel->deleted = 0;
            $searchModel->groupByProject = 1;
            $searchModel->is_leader = $isLeader;
            $searchModel->isOngoing = $isOngoing;
            $searchModel->name = $name;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $dd = date('Y-m-d H:i:s');
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'destination' => Pdf::DEST_BROWSER,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'content' => $this->renderPartial('sae', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isLeader' => $searchModel->is_leader,
                    'isOngoing' => $searchModel->isOngoing,
                    'name' => $searchModel->name,
                    'pdf' => $pdf
                ]),
                'options' => [
                    'fontDir' => array_merge($fontDirs, [
                        Yii::getAlias('@app/web/fonts'),
                    ]),
                    'fontdata' => $fontData + [
                        'thsarabun' => [
                            'R' => "THSarabunNew.ttf",
                            'B' => "THSarabunNew-Bold.ttf",
                            'I' => "THSarabunNew-Italic.ttf",
                            'BI' => "THSarabunNew-BoldItalic.ttf"
                        ]
                    ],
                ],
                'cssInline' => 'body { font-family: thsarabun !important }',
                'methods' => [
                    'SetTitle' => 'พิมพ์รายงาน',
                    'SetSubject' => 'พิมพ์รายงาน',
                    'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                    'SetFooter' => ['|หน้า {PAGENO}|'],
                ]
            ]);
            $mPdf = $pdf->getApi();
            $mPdf->SetDefaultFont('thsarabun');
            return $pdf->render();
        }
        return $this->render('sae', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pdf' => $pdf
        ]);
    }

    public function actionCommitteeReviewCount($pdf = NULL)
    {
        $currentRole = Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionCommitteeSearch(['scenario' => SubmissionCommitteeSearch::SCENARIO_COMMITTEE_ASSESSMENT_StATISTIC]);
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $searchModel->person_id = Yii::$app->user->identity->person->id;
        }
        $searchModel->load(Yii::$app->request->queryParams);
        $data = [];
        if ($searchModel->validate()) {
            $sql = <<<q
SELECT st.name, st.name_eng, COUNT(*) AS c
FROM submission_committee sc
	INNER JOIN submission s ON sc.submission_id = s.id
    INNER JOIN submission_type st ON s.submission_type_id = st.id
WHERE sc.deleted = 0 AND s.deleted = 0 AND sc.person_id = {$searchModel->person_id} AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = 400 AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$searchModel->startMeetingDate}' AND '{$searchModel->endMeetingDate}'
GROUP BY st.name, st.name_eng
q;
            // echo $sql;
            $data = Yii::$app->db->createCommand($sql)->queryAll();
            if (isset($pdf)) {
                $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $dd = date('Y-m-d H:i:s');
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                    'destination' => Pdf::DEST_BROWSER,
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'content' => $this->renderPartial('committee-review-count', [
                        'searchModel' => $searchModel,
                        'data' => $data,
                        'pdf' => $pdf
                    ]),
                    'options' => [
                        'fontDir' => array_merge($fontDirs, [
                            Yii::getAlias('@app/web/fonts'),
                        ]),
                        'fontdata' => $fontData + [
                            'thsarabun' => [
                                'R' => "THSarabunNew.ttf",
                                'B' => "THSarabunNew-Bold.ttf",
                                'I' => "THSarabunNew-Italic.ttf",
                                'BI' => "THSarabunNew-BoldItalic.ttf"
                            ]
                        ],
                    ],
                    'cssInline' => 'body { font-family: thsarabun !important }',
                    'methods' => [
                        'SetTitle' => 'พิมพ์รายงาน',
                        'SetSubject' => 'พิมพ์รายงาน',
                        'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                        'SetFooter' => ['|หน้า {PAGENO}|'],
                    ]
                ]);
                $mPdf = $pdf->getApi();
                $mPdf->SetDefaultFont('thsarabun');
                return $pdf->render();
            }
        }
        return $this->render('committee-review-count', [
            'searchModel' => $searchModel,
            'data' => $data,
            'pdf' => $pdf
        ]);
    }

    public function actionCommitteeReviewMeeting($pdf = NULL)
    {
        $currentRole = Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionCommitteeSearch(['scenario' => SubmissionCommitteeSearch::SCENARIO_COMMITTEE_ASSESSMENT_StATISTIC]);
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $searchModel->person_id = Yii::$app->user->identity->person->id;
        }
        $searchModel->load(Yii::$app->request->queryParams);
        // $dataProvider = new ArrayDataProvider([
        //     'allModels' => [],
        // ]);
        $data = [];
        if ($searchModel->validate()) {
            $cond = '';
            if (!empty($searchModel->person_id)) {
                $cond .= " AND sc.person_id = {$searchModel->person_id}";
            }
            $sql = <<<q
SELECT DIStINCT ma.meeting_id
FROM submission_committee sc
	INNER JOIN submission s ON sc.submission_id = s.id
    INNER JOIN submission_type st ON s.submission_type_id = st.id
    INNER JOIN meeting_agenda ma ON ma.submission_id = s.id AND ma.deleted = 0
WHERE sc.deleted = 0 AND s.deleted = 0 {$cond} AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = 400 AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$searchModel->startMeetingDate}' AND '{$searchModel->endMeetingDate}'
q;
            // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            // $dataProvider->pagination = false;
            $meetingIds = Yii::$app->db->createCommand($sql)->queryAll();
            $data = Meeting::find()->andWhere(['id' => \array_column($meetingIds, 'meeting_id')])->all();
            if (isset($pdf)) {
                $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $dd = date('Y-m-d H:i:s');
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                    'destination' => Pdf::DEST_BROWSER,
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'content' => $this->renderPartial('committee-review-meeting', [
                        'searchModel' => $searchModel,
                        'data' => $data,
                        'pdf' => $pdf
                    ]),
                    'options' => [
                        'fontDir' => array_merge($fontDirs, [
                            Yii::getAlias('@app/web/fonts'),
                        ]),
                        'fontdata' => $fontData + [
                            'thsarabun' => [
                                'R' => "THSarabunNew.ttf",
                                'B' => "THSarabunNew-Bold.ttf",
                                'I' => "THSarabunNew-Italic.ttf",
                                'BI' => "THSarabunNew-BoldItalic.ttf"
                            ]
                        ],
                    ],
                    'cssInline' => 'body { font-family: thsarabun !important }',
                    'methods' => [
                        'SetTitle' => 'พิมพ์รายงาน',
                        'SetSubject' => 'พิมพ์รายงาน',
                        'SetHeader' => ['พิมพ์รายงาน||พิมพ์เมื่อวันที่: ' . Yii::$app->thaiFormatter->asDateTime($dd, 'php:d-m-Y H:i:s')],
                        'SetFooter' => ['|หน้า {PAGENO}|'],
                    ]
                ]);
                $mPdf = $pdf->getApi();
                $mPdf->SetDefaultFont('thsarabun');
                return $pdf->render();
            }
        }
        return $this->render('committee-review-meeting', [
            'searchModel' => $searchModel,
            'data' => $data,
            'pdf' => $pdf
        ]);
    }

    public function actionSubmissionByFundingSource()
    {
        $d = new DateTime();
        $typeNew = SubmissionTypeGroup::GROUP_NEW;
        $status = Submission::STATUS_SUBMITTED;
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->load(Yii::$app->request->queryParams);

        $cond = '';
        if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
            $cond .= " AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$searchModel->startDate}' AND '{$searchModel->endDate}'";
        } else {
            $cond .= ' AND s.id = -1';
        }
        $sql = <<<q
            SELECT fs.name, COUNT(*) AS c
            FROM submission s
                INNER JOIN submission_type st ON s.submission_type_id = st.id
                INNER JOIN project p ON s.project_id = p.id
                INNER JOIN funding_source fs ON p.funding_source_id = fs.id
            WHERE s.deleted = 0 AND st.submission_type_group_id = {$typeNew}
                {$cond}
            GROUP BY fs.name
q;
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        //        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('submission-by-funding-source', [
            'searchModel' => $searchModel,
            'data' => $data,
            //                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmissionByProjectAgendaAnswer()
    {
        $d = new DateTime();
        $typeNew = SubmissionTypeGroup::GROUP_NEW;
        $status = Submission::STATUS_CODE_GENERATED;
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->load(Yii::$app->request->queryParams);

        $cond = '';
        if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
            $cond .= " AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$searchModel->startDate}' AND '{$searchModel->endDate}'";
        } else {
            $cond .= ' AND s.id = -1';
        }
        $sql = <<<q
            SELECT pt.name, pn.name_eng, COUNT(*) AS c
            FROM submission s
                INNER JOIN submission_type st ON s.submission_type_id = st.id
                INNER JOIN project p ON s.project_id = p.id
                INNER JOIN panel pn ON p.panel_id = pn.id
                INNER JOIN project_agenda_answer paa ON paa.submission_id = s.id
                INNER JOIN project_type pt ON paa.project_type_id = pt.id
            WHERE s.deleted = 0 AND st.submission_type_group_id = {$typeNew}
                {$cond}
            GROUP BY pt.name, pn.name_eng
q;
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        //        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('submission-by-project-agenda-answer', [
            'searchModel' => $searchModel,
            'data' => $data,
            //                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmissionByAgendaGraph()
    {
        $d = new DateTime();
        $typeNew = SubmissionTypeGroup::GROUP_NEW;
        $status = Submission::STATUS_AGENDA_ADDED;
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startDate = $d->format('Y-m-d');
        $searchModel->endDate = $d->format('Y-m-d');
        $searchModel->load(Yii::$app->request->queryParams);

        $cond = '';
        if (!empty($searchModel->startDate) || !empty($searchModel->endDate)) {
            $cond .= " AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$searchModel->startDate}' AND '{$searchModel->endDate}'";
        } else {
            $cond .= ' AND s.id = -1';
        }
        $sql = <<<q
            SELECT a.label AS agenda_label, pn.id AS panel_id, st.submission_type_group_id, st.is_new, st.is_fullboard, st.is_exemption, COUNT(*) AS c
            FROM submission s
                INNER JOIN submission_type st ON s.submission_type_id = st.id
                INNER JOIN project p ON s.project_id = p.id
                INNER JOIN panel pn ON p.panel_id = pn.id
                INNER JOIN meeting_agenda ma ON ma.submission_id = s.id AND ma.deleted = 0
                INNER JOIN agenda a ON ma.agenda_id = a.id
            WHERE s.deleted = 0
                {$cond}
            GROUP BY a.label, pn.id, st.submission_type_group_id, st.is_new, st.is_fullboard, st.is_exemption
q;
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        //        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('submission-by-agenda-graph', [
            'searchModel' => $searchModel,
            'data' => $data,
            //                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmissionByAgendaYearGraph()
    {
        $d = new DateTime();
        $typeNew = SubmissionTypeGroup::GROUP_NEW;
        $status = Submission::STATUS_AGENDA_ADDED;
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startYear = $d->format('Y');
        $searchModel->endYear = $d->format('Y');
        $searchModel->load(Yii::$app->request->queryParams);

        $cond = '';
        if (!empty($searchModel->startYear) || !empty($searchModel->endYear)) {
            $startDate = date($searchModel->startYear.'-01-01');
            $endDate = date($searchModel->endYear.'-12-31 23:59:59');
            $cond .= " AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$startDate}' AND '{$endDate}'";
        } else {
            $cond .= ' AND s.id = -1';
        }
        if (!empty($searchModel->panel_id)) {
            $cond .= " AND p.panel_id = {$searchModel->panel_id}";
        }
        $sql = <<<q
            SELECT agenda_label, panel_id, year, month, COUNT(*) AS c
            FROM (
                SELECT a.label AS agenda_label, pn.id AS panel_id, (
                    SELECT YEAR(ssh.created_at) FROM submission_status_history ssh 
                    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
                    ORDER BY ssh.id DESC
                    LIMIT 1
                ) AS year, (
                    SELECT MONTH(ssh.created_at) FROM submission_status_history ssh 
                    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
                    ORDER BY ssh.id DESC
                    LIMIT 1
                ) AS month
                FROM submission s
                    INNER JOIN submission_type st ON s.submission_type_id = st.id
                    INNER JOIN project p ON s.project_id = p.id
                    INNER JOIN panel pn ON p.panel_id = pn.id
                    INNER JOIN meeting_agenda ma ON ma.submission_id = s.id AND ma.deleted = 0
                    INNER JOIN agenda a ON ma.agenda_id = a.id
                WHERE s.deleted = 0
                    {$cond}
                ) tb1
            GROUP BY agenda_label, panel_id, year, month
q;
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        //        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('submission-by-agenda-year-graph', [
            'searchModel' => $searchModel,
            'data' => $data,
            //                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubmissionByTypeYearGraph()
    {
        $d = new DateTime();
        $typeNew = SubmissionTypeGroup::GROUP_NEW;
        $status = Submission::STATUS_AGENDA_ADDED;
        $searchModel = new \app\models\SubmissionSearch();
        $searchModel->startYear = $d->format('Y');
        $searchModel->endYear = $d->format('Y');
        $searchModel->load(Yii::$app->request->queryParams);

        $cond = '';
        if (!empty($searchModel->startYear) || !empty($searchModel->endYear)) {
            $startDate = date($searchModel->startYear . '-01-01');
            $endDate = date($searchModel->endYear . '-12-31 23:59:59');
            $cond .= " AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$startDate}' AND '{$endDate}'";
        } else {
            $cond .= ' AND s.id = -1';
        }
        if (!empty($searchModel->panel_id)) {
            $cond .= " AND p.panel_id = {$searchModel->panel_id}";
        }
        $sql = <<<q
            SELECT agenda_label, submission_type_id, st_name
                , is_new, is_fullboard, is_exemption
                , year, COUNT(*) AS c
            FROM (
                SELECT a.label AS agenda_label, s.submission_type_id, st.name_eng AS st_name
                    , st.is_new, st.is_fullboard, st.is_exemption
                    , (
                    SELECT YEAR(ssh.created_at) FROM submission_status_history ssh 
                    WHERE ssh.status = {$status} AND ssh.submission_id = s.id
                    ORDER BY ssh.id DESC
                    LIMIT 1
                ) AS year
                FROM submission s
                    INNER JOIN submission_type st ON s.submission_type_id = st.id
                    INNER JOIN project p ON s.project_id = p.id
                    INNER JOIN panel pn ON p.panel_id = pn.id
                    INNER JOIN meeting_agenda ma ON ma.submission_id = s.id AND ma.deleted = 0
                    INNER JOIN agenda a ON ma.agenda_id = a.id
                WHERE s.deleted = 0
                    {$cond}
                ) tb1
            GROUP BY agenda_label, submission_type_id, st_name, is_new, is_fullboard, is_exemption, year
q;
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        //        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('submission-by-type-year-graph', [
            'searchModel' => $searchModel,
            'data' => $data,
            //                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReviewCountByReviewerGraph()
    {
        // $currentRole = Yii::$app->session->get('currentRole');
        $searchModel = new SubmissionCommitteeSearch();
        // if ($currentRole['role_id'] == Role::COMMITTEE) {
        //     $searchModel->person_id = Yii::$app->user->identity->person->id;
        // }
        $searchModel->load(Yii::$app->request->queryParams);
        $data = [];
        if (!empty($searchModel->startMeetingDate) && !empty($searchModel->endMeetingDate)) {
            $sql = <<<q
SELECT CONCAT_WS(' ', p.first_name, p.last_name) AS reviewer_name, cp.id AS position_id, pn.id AS panel_id, COUNT(*) AS c
FROM submission_committee sc
    INNER JOIN committee_position cp ON sc.committee_position_id = cp.id
    INNER JOIN person p ON sc.person_id = p.id
	INNER JOIN submission s ON sc.submission_id = s.id
    INNER JOIN project pj ON s.project_id = pj.id
    INNER JOIN panel pn ON pj.panel_id = pn.id
    INNER JOIN submission_type st ON s.submission_type_id = st.id
WHERE sc.deleted = 0 AND s.deleted = 0 AND
(
    SELECT ssh.created_at FROM submission_status_history ssh 
    WHERE ssh.status = 400 AND ssh.submission_id = s.id
    ORDER BY ssh.id DESC
    LIMIT 1
) BETWEEN '{$searchModel->startMeetingDate}' AND '{$searchModel->endMeetingDate}'
GROUP BY p.first_name, p.last_name, cp.id, pn.id
q;
            // echo $sql;
            $data = Yii::$app->db->createCommand($sql)->queryAll();
        //        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        return $this->render('review-count-by-reviewer-graph', [
            'searchModel' => $searchModel,
            'data' => $data,
            //                    'dataProvider' => $dataProvider,
        ]);
    }
}
