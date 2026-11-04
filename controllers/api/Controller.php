<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers\api;

use app\components\JwtHttpBearerAuth;
use yii\base\Module;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class Controller extends \yii\rest\Controller
{
  public function behaviors()
  {
    $behaviors = parent::behaviors();
    unset($behaviors['authenticator']);
    $behaviors['authenticator'] = [
      'class' => CompositeAuth::class,
      'authMethods' => [
        JwtHttpBearerAuth::class,
        [
          'class' => HttpHeaderAuth::class,
          'header' => 'EC-AUTH-KEY',
          'except' => ['options'],
        ],
        [
          'class' => QueryParamAuth::class,
          'tokenParam' => 'tk',
          'except' => ['options'],
        ]
      ],
      'except' => ['options'],
    ];

    return  array_merge([
      'corsFilter' => \yii\filters\Cors::class,
    ], $behaviors);
  }
}
