<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'message' => [
                        'from' => ['jperez320@gmail.com'],
                        'to' => ['jperez820@hotmail.com', 'alvarojfer_archer@hotmail.com'],
                        'subject' => 'Gestion de errores SIMWebPLUS. Produccion',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*'urlManager' => [
            'scriptUrl' => '/backend/index.php'
        ],*/
        /*'request' => [
            'baseUrl' => '/backend'
        ]*/
    ],
    //'params' => $params,
    'params' => [
        'icon-framework' => 'fa',  // Font Awesome Icon framework
    ],

];
