<?php

namespace app\components;

use app\models\Person;
use app\models\Project;
use app\models\ProjectResearcher;
use app\models\Role;
use app\models\Setting;
use app\models\Submission;
use app\models\SubmissionCommittee;
use app\models\SubmissionDocument;
use app\models\SubmissionEvent;
use app\models\SubmissionResultDocument;
use app\models\SubmissionVolunteer;
use GuzzleHttp\Exception\ConnectException;
use Throwable;
use Yii;
use yii\helpers\VarDumper;

class Crec extends \yii\base\Component
{
  const DOWNLOAD_SUBMISSION_RESULT_DOCUMENT_FILE = 'DOWNLOAD_SUBMISSION_RESULT_DOCUMENT_FILE';
  const VIEW_SUBMISSION_RESULT_DOCUMENT_FILE = 'VIEW_SUBMISSION_RESULT_DOCUMENT_FILE';
  const DOWNLOAD_SUBMISSION_DOCUMENT_FILE = 'DOWNLOAD_SUBMISSION_DOCUMENT_FILE';
  const VIEW_SUBMISSION_DOCUMENT_FILE = 'VIEW_SUBMISSION_DOCUMENT_FILE';
  const CHECK_SUBMISSION_DOCUMENT = 'CHECK_SUBMISSION_DOCUMENT';
  const UPDATE_LOCAL_ISSUE_RESULT = 'UPDATE_LOCAL_ISSUE_RESULT';
  const UPDATE_RESULT = 'UPDATE_RESULT';
  const DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE = 'DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE';
  const DOWNLOAD_DOCUMENT_TEMPLATE = 'DOWNLOAD_DOCUMENT_TEMPLATE';
  const GET_CREC_SUBMISSION_COMMITTEE_DOCUMENT_TEMPLATE_FILES = 'GET_CREC_SUBMISSION_COMMITTEE_DOCUMENT_TEMPLATE_FILES';
  const CREATE_CONTINUE_EC = 'CREATE_CONTINUE_EC';

  public static function getApiPath($api)
  {
    switch ($api) {
      case self::DOWNLOAD_SUBMISSION_DOCUMENT_FILE:
        return "/api/submission-document/download-file";
      case self::VIEW_SUBMISSION_DOCUMENT_FILE:
        return "/api/submission-document/view-file";
      case self::CHECK_SUBMISSION_DOCUMENT:
        return "/api/submission-document/check";
      case self::UPDATE_LOCAL_ISSUE_RESULT:
        return "/api/submission/update-local-issue-result";
      case self::DOWNLOAD_SUBMISSION_RESULT_DOCUMENT_FILE:
        return "/api/submission-result-document/download-file";
      case self::VIEW_SUBMISSION_RESULT_DOCUMENT_FILE:
        return "/api/submission-result-document/view-file";
      case self::UPDATE_RESULT:
        return "/api/submission/update-result";
      case self::DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE:
        return "/api/submission-document/download-merge-file";
      case self::DOWNLOAD_DOCUMENT_TEMPLATE:
        return "/api/document/download-template";
      case self::GET_CREC_SUBMISSION_COMMITTEE_DOCUMENT_TEMPLATE_FILES:
        return "/api/document/get-submission-committee-document-template-files";
      case self::CREATE_CONTINUE_EC:
        return "/api/submission/create-continue-ec";
    }
  }

  public static function getApiParams($api)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    switch ($api) {
      case self::DOWNLOAD_SUBMISSION_DOCUMENT_FILE:
      case self::DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE:
      case self::DOWNLOAD_DOCUMENT_TEMPLATE:
      case self::GET_CREC_SUBMISSION_COMMITTEE_DOCUMENT_TEMPLATE_FILES:
        return [
          'r' => self::getApiPath($api),
          'tk' => $accessToken,
        ];
      case self::VIEW_SUBMISSION_DOCUMENT_FILE:
      case self::VIEW_SUBMISSION_RESULT_DOCUMENT_FILE:
        return [
          'r' => self::getApiPath($api),
          'tk' => $accessToken,
        ];
      case self::CHECK_SUBMISSION_DOCUMENT:
        return [
          'r' => self::getApiPath($api),
        ];
      case self::UPDATE_LOCAL_ISSUE_RESULT:
        return [
          'r' => self::getApiPath($api),
        ];
      case self::DOWNLOAD_SUBMISSION_RESULT_DOCUMENT_FILE:
        return [
          'r' => self::getApiPath($api),
          'tk' => $accessToken,
        ];
      case self::UPDATE_RESULT:
      case self::CREATE_CONTINUE_EC:
        return [
          'r' => self::getApiPath($api),
        ];
    }
  }

  public static function getApiUrl($api)
  {
    $url = Setting::getValue(Setting::CREC_URL);
    $path = self::getApiPath($api);
    $params = self::getApiParams($api);
    if (\strpos($url, 'index.php') === false) {
      $t = \time();
      $url .= $path . "?t={$t}";
      if (\key_exists('tk', $params)) {
        $url .= "&tk={$params['tk']}";
      }
    } else {
      $url .= "?r={$path}";
      if (\key_exists('tk', $params)) {
        $url .= "&tk={$params['tk']}";
      }
    }
    return $url;
  }

  public static function checkDocument($submissionDocument)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    $url = self::getApiUrl(self::CHECK_SUBMISSION_DOCUMENT) . "&id={$submissionDocument->sd_crec_id}";
    $params = self::getApiParams(self::CHECK_SUBMISSION_DOCUMENT);
    // VarDumper::dump($url);
    // exit;
    $headers = [
      'CREC-AUTH-KEY' => $accessToken,
    ];

    $data = \array_merge([
      'ec_status' => $submissionDocument->status,
      'remark_site' => $submissionDocument->remark,
    ], $params);
    // VarDumper::dump($data);
    // exit;
    $results = ExternalApi::call(ExternalApi::METHOD_POST, $url, $headers, $data);
    return $results;
  }

  public static function updateLocalIssueResult($submission)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    $url = self::getApiUrl(self::UPDATE_LOCAL_ISSUE_RESULT);
    $params = self::getApiParams(self::UPDATE_LOCAL_ISSUE_RESULT);
    // VarDumper::dump($url);
    // exit;
    $headers = [
      'CREC-AUTH-KEY' => $accessToken,
    ];

    $smCommittees = $submission->getSubmissionCommittees()->isDeleted(false)->all();

    $data = \array_merge([
      'submissionEc' => [
        'ec_submission_id' => $submission->id,
        'ec_project_code' => $submission->project->project_code,
      ],
      'submissionCommittees' => SubmissionCommittee::toArrayData($smCommittees),
    ], $params);
    // VarDumper::dump($data);
    // exit;
    $results = ExternalApi::call(ExternalApi::METHOD_POST, $url, $headers, $data);
    return $results;
  }

  public static function updateResult($submission)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    $url = self::getApiUrl(self::UPDATE_RESULT);
    $params = self::getApiParams(self::UPDATE_RESULT);
    // VarDumper::dump($url);
    // exit;
    $headers = [
      'CREC-AUTH-KEY' => $accessToken,
    ];

    $data = \array_merge([
      'submissionEc' => [
        'is_close' => $submission->submissionType->close,
        'ec_submission_id' => $submission->id,
        'ec_project_code' => $submission->project->project_code,
        'ec_certificate_no' => $submission->certificate_no,
        'ec_certificate_at' => $submission->certified_date,
        'ec_expire_at' => $submission->expire_at,
        'ec_resolution' => $submission->resolution,
        'ec_upload_result_date' => $submission->getStatusDate(Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT),
      ],
    ], $params);
    // VarDumper::dump($data);
    // exit;
    $results = ExternalApi::call(ExternalApi::METHOD_POST, $url, $headers, $data);
    return $results;
  }

  public static function downloadMergeFile($docIds)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    $url = self::getApiUrl(self::DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE);
    $params = self::getApiParams(self::DOWNLOAD_MERGE_SUBMISSION_DOCUMENT_FILE);
    // VarDumper::dump($url);
    // exit;
    $headers = [
      'CREC-AUTH-KEY' => $accessToken,
    ];

    $data = \array_merge([
      'selections' => $docIds
    ], $params);
    // VarDumper::dump($data);
    // exit;
    // $results = ExternalApi::call(ExternalApi::METHOD_POST, $url, $headers, $data);

    try {
      $client = new \GuzzleHttp\Client([
        'verify' => false,
        // 'debug' => true,
      ]);

      $response = $client->request(ExternalApi::METHOD_GET, $url, [
        //                'verify' => false,
        'headers' => $headers,
        'query' => $data,
        'http_errors' => false,
      ]);
      return $response;
    } catch (ConnectException $e) {
      // echo "Connection Time out";\
      Yii::warning($e->getMessage(), 'external-api');
    } catch (Throwable $e) {
      // echo "Throwable";
      Yii::warning($e->getMessage(), 'external-api');
    }
    // VarDumper::dump($response->getBody()->getContents(), 10, true);
    // exit;
    // return $results;
  }

  public static function getCrecSubmissionCommitteeDocumentTemplateFiles($submissionCommittee)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    $url = self::getApiUrl(self::GET_CREC_SUBMISSION_COMMITTEE_DOCUMENT_TEMPLATE_FILES);
    $params = self::getApiParams(self::GET_CREC_SUBMISSION_COMMITTEE_DOCUMENT_TEMPLATE_FILES);
    // VarDumper::dump($url);
    // exit;
    $headers = [
      'CREC-AUTH-KEY' => $accessToken,
    ];

    $data = \array_merge([
      'committeePositionId' => $submissionCommittee->committee_position_id,
      'submissionTypeId' => $submissionCommittee->submission->submissionType->crec_id,
    ], $params);
    // VarDumper::dump($data);
    // exit;
    $results = ExternalApi::call(ExternalApi::METHOD_GET, $url, $headers, $data);
    return $results;
  }

  public static function createContinueEc($submission)
  {
    $accessToken = Setting::getValue(Setting::CREC_ACCESS_TOKEN);
    $url = self::getApiUrl(self::CREATE_CONTINUE_EC);
    $params = self::getApiParams(self::CREATE_CONTINUE_EC);
    // VarDumper::dump($url);
    // exit;
    $apiPerson = Person::find()->isDeleted(false)->andWhere(['role_id' => Role::WEB_API])->one();
    if (!isset($apiPerson)) {
      throw new \Exception('API User not found');
    }
    $headers = [
      'CREC-AUTH-KEY' => $accessToken,
      'EC-ACCESS-TOKEN' => $apiPerson->user->auth_key,
    ];
    $submissionDocuments = $submission->getSubmissionDocuments()->isDeleted(false)->all();
    $data = \array_merge([
      'submission' => Submission::toArrayData($submission),
      'project' => Project::toArrayData($submission->project),
      'ecSubmissionDocuments' => SubmissionDocument::toArrayData($submissionDocuments),
      'ecSubmissionVolunteers' => SubmissionVolunteer::toArrayData($submission->getSubmissionVolunteers()->isDeleted(false)->all()),
      'ecSubmissionEvents' => SubmissionEvent::toArrayData($submission->getSubmissionEvents()->isDeleted(false)->all()),
      'ecSubmissionResultDocuments' => SubmissionResultDocument::toArrayData($submission->getSubmissionResultDocuments()->isDeleted(false)->all()),
    ], $params);
    // VarDumper::dump($data);
    // exit;
    $results = ExternalApi::call(ExternalApi::METHOD_POST, $url, $headers, $data);
    return $results;
  }
    
}
