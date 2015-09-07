<?php
/**
 * 订单表
 *
 * PHP Version 5
 *
 * @category  MODEL
 * @package   Social
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015-08-25
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace frontend\models\i500_social;
use frontend\models\i500m\Shop;
use common\helpers\CurlHelper;

/**
 * 订单表
 *
 * @category MODEL
 * @package  Social
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class Order extends SocialBase
{
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%i500_order}}';
    }

    /**
     * 根据商家id，和商品金额 获取运费
     * @param int   $shop_id 商家id
     * @param float $total   商品金额
     * @return string
     */
    public function checkAddress($mobile, $address_id, $shop_id)
    {
        if (!empty($mobile) && !empty($address_id)) {
            $model = new UserAddress();
            $address = $model->getInfo(['mobile'=>$mobile, 'id'=>$address_id], 'lng,lat');
            //var_dump($address);
            if (!empty($address)) {
                $channelUrl = \Yii::$app->params['channelHost'];
                $url = $channelUrl.'lbs/check-address?shop_id='.$shop_id.'&lng='.$address['lng'].'&lat='.$address['lat'];
                $re = CurlHelper::get($url, true);
                if ($re['code'] == 200) {
                    return true;
                }
                return false;
            }
        }
        return false;


    }
    public function createSn($province_id, $mobile)
    {
        $channelUrl = \Yii::$app->params['channelHost'];
        $url = $channelUrl.'order/create-order-sn?province_id='.$province_id.'&mobile='.$mobile;
        $re = CurlHelper::get($url, true);
        if (isset($re['code']) && $re['code'] == 200) {
            return $re['data'];
        } else {
            return false;
        }
    }
}
