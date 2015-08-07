<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        //数据库配置
        'db_social'=> [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=i500_social',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','trace'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'urlManager'=> [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yo71Dop6YvUneqq-M21tan2E_cFqA9Vx',
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@frontend/views' => '@frontend/views/themes/default'],
                'baseUrl' => '@web/themes/default',
            ],
        ],
    ],
    'id' => 'app-frontend',
    'basePath' => dirname(dirname(__DIR__)) . '/frontend',
    'bootstrap' => ['log'],
    'defaultRoute'=>'index',  //设置默认路由
    'controllerNamespace' => 'frontend\controllers',
    'params' => require(__DIR__ . '/params.php'),
    'modules' => [
        'v1' => [
            'class' => 'frontend\modules\v1\Module',
        ],
        'channel' => [
            'class' => 'frontend\modules\channel\Module',
        ],
    ],
];
