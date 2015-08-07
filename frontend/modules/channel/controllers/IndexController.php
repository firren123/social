<?php
/**
 * Index
 *
 * PHP Version 5
 *
 * @category  Social Channel
 * @package   Index
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015/8/05 09:21
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace frontend\modules\channel\controllers;

use common\helpers\Common;
use common\helpers\RequestHelper;
use frontend\models\i500_social\User;
use frontend\models\i500_social\UserToken;
use yii\web\Controller;

/**
 * Index
 *
 * @category Social
 * @package  Index
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class IndexController extends Controller
{
    /**
     * Index
     * @return string
     */
    public function actionIndex()
    {
        echo "这是通道模块，请完善。111";
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @throws Exception
     * @return string
     */
    public function actionMq()
    {
        echo 123;
        exit;
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
        } else {
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
