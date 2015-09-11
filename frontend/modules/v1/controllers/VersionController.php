<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/9/11 上午9:59 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\modules\v1\controllers;
use frontend\models\i500m\AppLog;

/**
 * Shop
 *
 * @category Social
 * @package  Shop
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class VersionController extends BaseController
{
    public function actionIndex()
    {
        $model = new AppLog();
        $info = $model->getInfo(['type'=>0], true , 'name,explain,url,upgrade', [], 'create_time desc');
        if (!empty($info)) {
            $this->returnJsonMsg(200, $info, '有新版本了');
        } else {
            $this->returnJsonMsg(100, [], 'SUCCESS');
        }
    }
}