<?php

namespace app\controllers\api;

use app\components\ExternalApi;
use app\models\Person;
use app\models\PersonSearch;
use app\models\PersonTraining;
use app\models\PersonTrainingSearch;
use app\models\Setting;
use app\models\User;
use Codeception\Util\HttpCode;
use Throwable;
use Yii;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\HttpException;
use yii\web\Response;

class AdminController extends Controller
{
  public function actionUpdateAuthKey()
  {
    $headers = Yii::$app->getRequest()->getHeaders();
    $bodyParams = \Yii::$app->getRequest()->getBodyParams();
    $stackTrace = '';
    $tran = Yii::$app->db->beginTransaction();
    try {
      $s = Setting::find()->key(Setting::CREC_ACCESS_TOKEN)->one();
      $s->value = $bodyParams['new_crec_access_token'];
      if (!$s->save(false)) {
        throw new HttpException(HttpCode::UNPROCESSABLE_ENTITY, \implode(", ", $s->firstErrors));
      }

      $user = User::find()->andWhere(['auth_key' => $bodyParams['old_auth_key']])->one();
      $user->auth_key = $bodyParams['new_auth_key'];
      if (!$user->save(false)) {
        throw new HttpException(HttpCode::UNPROCESSABLE_ENTITY, \implode(", ", $user->firstErrors));
      }
      Yii::$app->response->setStatusCode(HttpCode::OK);
      $response = [
        'status' => ExternalApi::STATUS_OK,
        'message' => 'Update Auth Key เรียบร้อยแล้ว',
        'params' => $bodyParams,
        'data' => [
          'setting' => $s->attributes,
          'user' => $user->attributes,
        ]
      ];
      Yii::warning($response, 'update-auth-key');
      $tran->commit();
      return $response;
    } catch (Throwable $ex) {
      $tran->rollBack();
      Yii::$app->response->setStatusCode(HttpCode::INTERNAL_SERVER_ERROR);

      $stackTrace = $ex->getTraceAsString();
      $response = [
        'status' => ExternalApi::STATUS_ERROR,
        'message' => $ex->getMessage(),
        'stackTrace' => $stackTrace,
      ];
      Yii::warning($response, 'create-initial-crec-api-called');
      return $response;
    }
    
  }
}