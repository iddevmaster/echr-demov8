<?php

namespace app\components;

use app\api\v1\models\sales\ApiTran;
use app\api\v1\models\sales\Setting;
use Throwable;
use GuzzleHttp\Exception\ConnectException;
use Yii;

class ExternalApi extends \yii\base\Component
{
  const METHOD_GET = 'GET';
  const METHOD_POST = 'POST';
  const METHOD_PUT = 'PUT';
  const METHOD_PATCH = 'PATCH';
  const METHOD_DELETE = 'DELETE';

  const STATUS_ERROR = 'ERROR';
  const STATUS_OK = 'OK';

  public static function getParamName($method)
  {
    if (in_array($method, [self::METHOD_GET, self::METHOD_DELETE])) {
      return 'query';
    } else {
      return 'json';
    }
  }

  public static function call($method, $url, $headers, $data)
  {
    $result = [];
    $paramName = self::getParamName($method);
    $internalId = uniqid() . "-" . microtime(true);
    try {
      $client = new \GuzzleHttp\Client([
        'verify' => false,
        // 'debug' => true,
      ]);

      $response = $client->request($method, $url, [
        //                'verify' => false,
        'headers' => $headers,
        $paramName => $data,
        'http_errors' => false,
      ]);
      $body = (string) $response->getBody();
      $json = json_decode($body, true);
      $result = [
        'data' => $json,
        'method' => $method,
        'message' => $body,
        'url' => $url,
        'headers' => $headers,
        'sentData' => $data,
        'status' => $response->getStatusCode(),
        'internalId' => $internalId,
      ];
      if ($result['status'] >= 400) {
        $result['error'] = true;
        Yii::warning($result, 'external-api');
      } else {
        $result['error'] = false;
      }
    } catch (ConnectException $e) {
      // echo "Connection Time out";
      $result = [
        'type_error' => 'network_error',
        'error' => true,
        'data' => [
          'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        ],
        'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        'url' => $url,
        'method' => $method,
        'headers' => $headers,
        'sentData' => $data,
        'status' => $e->getCode(),
        'internalId' => $internalId,
      ];
      Yii::warning($result, 'external-api');
    } catch (Throwable $e) {
      // echo "Throwable";
      $result = [
        'error' => true,
        'data' => [
          'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        ],
        'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        'url' => $url,
        'method' => $method,
        'headers' => $headers,
        'sentData' => $data,
        'status' => $e->getCode(),
        'internalId' => $internalId,
      ];
      Yii::warning($result, 'external-api');
    }
    $internalId = isset($result['internalId']) ? $result['internalId'] : null;
    $externalId = isset($result['data']['transaction_id']) ? $result['data']['transaction_id'] : null;
    $result['externalId'] = $externalId;
    // ApiTran::addTran($url, $headers, $data, $result['data'], $result['status'], $internalId, $externalId);
    // Yii::warning($result, 'external-api');
    return $result;
  }

  public static function callFile($method, $url, $headers, $data)
  {
    $result = [];
    $internalId = uniqid() . "-" . microtime(true);
    try {
      $client = new \GuzzleHttp\Client([
        'verify' => false,
      ]);

      $response = $client->request($method, $url, [
        'headers' => $headers,
        'multipart' => $data,
        'http_errors' => false,
      ]);
      $body = (string) $response->getBody();
      $json = json_decode($body, true);
      $result = [
        'data' => $json,
        'method' => $method,
        'message' => $body,
        'url' => $url,
        'headers' => $headers,
        'sentData' => $data,
        'status' => $response->getStatusCode(),
        'internalId' => $internalId,
      ];
      if ($result['status'] >= 400) {
        $result['error'] = true;
      } else {
        $result['error'] = false;
      }
    } catch (ConnectException $e) {
      // echo "Connection Time out";
      $result = [
        'type_error' => 'network_error',
        'error' => true,
        'data' => [
          'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        ],
        'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        'url' => $url,
        'method' => $method,
        'headers' => $headers,
        'sentData' => $data,
        'status' => $e->getCode(),
        'internalId' => $internalId,
      ];
    } catch (Throwable $e) {
      // echo "Throwable";
      $result = [
        'error' => true,
        'data' => [
          'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        ],
        'message' => iconv('UTF-8', 'UTF-8//IGNORE', $e->getMessage()),
        'url' => $url,
        'method' => $method,
        'headers' => $headers,
        'sentData' => $data,
        'status' => $e->getCode(),
        'internalId' => $internalId,
      ];
    }
    $internalId = isset($result['internalId']) ? $result['internalId'] : null;
    $externalId = isset($result['data']['transaction_id']) ? $result['data']['transaction_id'] : null;
    $result['externalId'] = $externalId;
    // ApiTran::addTran($url, $headers, $data, $result['data'], $result['status'], $internalId, $externalId);
    Yii::warning($result, 'external-api');
    return $result;
  }
}
