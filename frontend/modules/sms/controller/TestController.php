<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Social
 * @filename  TestController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/7 上午10:46
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace frontend\modules\sms\controllers;
use yii\web\Controller;

/**
 * Class TestController
 * @category  PHP
 * @package   Social
 * @filename  TestController.php
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/7 上午10:46
 * @link      http://www.i500m.com/
 */
class TestController extends Controller{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @throws Exception
     * @return string
     */
    public function actionIndex()
    {

        /**
        rabbitmq 测试环境
        地址：118.186.247.55:5672
        用户：500m
        密码：gbjY51Rpstx
        虚拟主机：500m
         */
        $config = array(
            'host' => '118.186.247.55',
            'port' => 5672,
            'vhost' => '500m',
            'login' => '500m',
            'password' => 'gbjY51Rpstx'
        );
        $conn = new AMQPConnection($config);

        if ($conn->connect()) {
            echo "Established a connection to the broker <br />";
        }
        else {
            echo "Cannot connect to the broker <br />";
        }

        $conn->setHost('www.w3hacker.com');
        var_dump($conn->getHost());
        $conn->setLogin('admin');
        var_dump($conn->getLogin());
        $conn->setPassword('www.w3hacker.com');
        var_dump($conn->getPassword());
        $conn->setPort('1234');
        var_dump($conn->getPort());
        $conn->setTimeout(3000);
        var_dump($conn->getTimeout());
        $conn->setVhost('admin');
        var_dump($conn->getVhost());
        var_dump($conn->isConnected());

        if (!$conn->disconnect()) {
            throw new Exception('Could not disconnect');
        } else {
            var_dump("disconnect");
        }

        //$conn->reconnect();
        var_dump($conn->isConnected());

    }



}
