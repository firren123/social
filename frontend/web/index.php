<?php
//开启调试模式
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
$config = require(__DIR__ . '/../../common/config/main.php');
//error_reporting(E_ALL^E_NOTICE); //屏蔽NOTICE

$application = new yii\web\Application($config);
$application->run();