<?php
/**
 * 定位
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Location
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      2015/9/28
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\modules\v1\controllers;

use common\helpers\CurlHelper;
use Yii;
use common\helpers\Common;
use common\helpers\RequestHelper;

/**
 * 定位
 *
 * @category Social
 * @package  Location
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class LocationController extends BaseController
{
    public function actionNearCommunity()
    {
        $lng = RequestHelper::get('lng', 0);
        $lat = RequestHelper::get('lat', 0);
        $dis = RequestHelper::get('dis', 3);
        $url = Common::C('channelHost').'lbs/near-community?lng='.$lng.'&lat='.$lat.'&dis='.$dis;
        $res = CurlHelper::get($url);
        if ($res['code'] == 200) {
            $this->returnJsonMsg($res['code'], $res['data'], $res['message']);
        } else {
            $this->returnJsonMsg(101, [], '获取失败');
        }


    }
}
