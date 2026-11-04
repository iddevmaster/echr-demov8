<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'name' => 'KKUEC Submission Online',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'th-TH',
    'sourceLanguage' => 'th-TH',
    'timeZone' => 'Asia/Bangkok',
    'controllerNamespace' => 'app\commands',
    'components' => [
//        'formatter' => [
//            'class' => 'yii\i18n\Formatter',
////            'dateFormat' => 'php:d/m/Y',
//            'dateFormat' => 'php:d/m/Y',
//            'datetimeFormat' => 'php:d/m/Y H:i',
//            'timeFormat' => 'php:H:i',
////            'timeZone' => 'Asia/Bangkok'
//            'timeZone' => 'UTC',
//            'nullDisplay' => '',
//        ],
        'util' => [
            'class' => 'app\components\Util',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => 'http://echr/echr/',
            'scriptUrl' => 'http://echr/echr/',
            'rules' => [
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'echrkku@gmail.com',
                'password' => 'zmlsofiqtkzkkclf',
                'authMode' => 'login',
                'port' => '587',
                'encryption' => 'tls',
                'StreamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => TRUE,
                        'verify_peer' => FALSE,
                    ]
                ]
            ],
        ],
        'db' => $db,
    ],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'ignoreActions' => ['*'],
            'maxAge' => 30,
//            'panels' => [
////                'audit/request',
////                'audit/error',
//                'audit/trail',
//            ],
        ],
//        'datecontrol' => [
//            'class' => '\kartik\datecontrol\Module',
//            // format settings for displaying each date attribute (ICU format example)
////            'displaySettings' => [
////                Module::FORMAT_DATE => 'php:d/m/Y',
////                Module::FORMAT_TIME => 'php:H:i:s',
////                Module::FORMAT_DATETIME => 'php:d/m/Y H:i',
////            ],
//            // format settings for saving each date attribute (PHP format example)
//            'saveSettings' => [
//                Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
//                Module::FORMAT_TIME => 'php:H:i:s',
//                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
//            ],
//            // set your display timezone
//            'displayTimezone' => 'Asia/Bangkok',
//            // set your timezone for date saved to db
//            'saveTimezone' => 'Asia/bangkok',
//            // automatically use kartik\widgets for each of the above formats
//            'autoWidget' => true,
//            // default settings for each widget from kartik\widgets used when autoWidget is true
//            'autoWidgetSettings' => [
//                Module::FORMAT_DATE => ['type' => kartik\widgets\DatePicker::TYPE_COMPONENT_APPEND, 'pluginOptions' => ['autoclose' => true]], // example
//                Module::FORMAT_DATETIME => ['type' => \kartik\datetime\DateTimePicker::TYPE_COMPONENT_APPEND, 'pluginOptions' => ['autoclose' => true]], // setup if needed
//                Module::FORMAT_TIME => [], // setup if needed
//            ],
//        // custom widget settings that will be used to render the date input instead of kartik\widgets,
//        // this will be used when autoWidget is set to false at module or widget level.
////            'widgetSettings' => [
////                Module::FORMAT_DATE => [
////                    'class' => 'yii\jui\DatePicker', // example
////                    'options' => [
////                        'dateFormat' => 'php:d/M/Y',
////                        'options' => ['class' => 'form-control'],
////                        '_container' => ['class' => 'empty'],
////                    ]
////                ]
////            ]
//        // other settings
//        ],
//        'audit' => 'bedezign\yii2\audit\Audit',
    ],
    'params' => $params,
        /*
          'controllerMap' => [
          'fixture' => [ // Fixture generation command line.
          'class' => 'yii\faker\FixtureController',
          ],
          ],
         */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
