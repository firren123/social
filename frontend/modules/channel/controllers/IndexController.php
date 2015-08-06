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
        echo "这是通道模块，请完善。";
    }
}
