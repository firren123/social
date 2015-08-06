<?php
/**
 * 活动相关逻辑
 *
 * PHP Version 5
 *
 * @category  Mall
 * @package   Helper
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      15/6/4 下午3:33
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */
namespace common\helpers;

use Yii;
use common\helpers\Common;
use frontend\models\shop\ShopProducts;
use frontend\models\i500m\CrmActivity;
use frontend\models\i500m\CrmActivityProduct;
use frontend\models\i500m\CrmActivityOrderLog;
use frontend\models\i500m\Order;

/**
 * Helper
 *
 * @category Mall
 * @package  Helper
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class ActivityHelper
{
    /**
     * 通过商品id判断改商品是否参加活动
     * @param int $shop_id  店铺ID
     * @param int $goods_id 商品ID[标准库商品id]
     * @return array
     */
    public function checkGoodsJoinActivity($shop_id=0,$goods_id=0)
    {
        $shopInfo = [];
        if (!empty($shop_id) && !empty($goods_id)) {
            $m_shop_products = new ShopProducts();
            $shop_where['shop_id']    = $shop_id;
            $shop_where['product_id'] = $goods_id;
            $shop_fields = 'id,status,activity_temp';
            $shopInfo = $m_shop_products->getInfo($shop_where,true,$shop_fields);
        }
        return $shopInfo;
    }

    /**
     * 验证活动
     * @param int $type        活动类型 1=限购 2=买赠 3=加价购
     * @param int $activity_id 活动ID
     * @param int $user_id     用户ID
     * @param int $goods_id    商品ID
     * @return bool
     */
    public function checkActivity($type=0,$activity_id=0,$user_id=0,$goods_id=0) {
        switch ($type)
        {
            case '1':  //限购
                return Common::statusInfo('4013');
                break;
            case '2':  //买赠
                return $this->_checkWithGiftActivity($activity_id,$user_id,$goods_id);
                break;
            case '3':  //加价购
                return Common::statusInfo('4014');
                break;
            default :
                return Common::statusInfo('4015');
        }
    }

    /**
     * 买赠活动验证
     * @param int $activity_id 活动ID
     * @param int $user_id     用户ID
     * @param int $goods_id    商品ID
     * @return array
     */
    private function _checkWithGiftActivity($activity_id=0,$user_id=0,$goods_id=0)
    {
        if (!empty($activity_id)) {
            $activityInfo = $this->_getActivityInfo($activity_id);
            if (!empty($activityInfo)) {
                /** 验证基本信息 **/
                if ($activityInfo['status']=='2') {
                    return Common::statusInfo('4003');
                }
                if (time() < strtotime($activityInfo['start_time'])) {
                    return Common::statusInfo('4007');
                }
                if (time() > strtotime($activityInfo['end_time'])) {
                    return Common::statusInfo('4008');
                }
                if ($activityInfo['platform']!='1') {
                    return Common::statusInfo('4004');
                }
                if ($activityInfo['new_user_site'] == '1') {
                    /** 仅限新用户参加 **/
                    if (!empty($user_id)) {
                        if (!$this->_checkNewUser($user_id)) {
                            return Common::statusInfo('4005');
                        }
                    } else {
                        return Common::statusInfo('1002');
                    }
                }
                /** 验证商品信息 **/
                $activityProductInfo = $this->_getActivityProductInfo($activity_id,$goods_id);
                if (!empty($activityProductInfo)) {
                    if (time() < strtotime($activityProductInfo['start_time'])) {
                        return Common::statusInfo('4009');
                    }
                    if (time() > strtotime($activityProductInfo['end_time'])) {
                        return Common::statusInfo('4010');
                    }
                    if ($activityProductInfo['day_confine_num'] > 0) {
                        /** 限制了每日限购 **/
                        $rs_get_num = $this->_getActivityOrderLog($activity_id,$goods_id);
                        if ($rs_get_num['code']=='200') {
                            $all_num = $rs_get_num['data']['all_num'];
                            if ($all_num+1 > $activityProductInfo['day_confine_num']) {
                                return Common::statusInfo('4011');
                            }
                        } else {
                            return $rs_get_num;
                        }
                    }
                } else {
                    return Common::statusInfo('4006');
                }
            } else {
                return Common::statusInfo('4002');
            }
            return Common::statusInfo('200');
        } else {
            return Common::statusInfo('4001');
        }
    }

    /**
     * 判断是否是新用户(未下过订单的用户为新用户)
     * @param int $user_id 用户ID
     * @return bool true=新用户
     */
    private function _checkNewUser($user_id=0) {
        if (!empty($user_id)) {
            $m_order = new Order();
            $order_where = [];
            $order_where['user_id'] = $user_id;
            $order_fields = 'id';
            $orderInfo = $m_order->getInfo($order_where,true,$order_fields);
            if (empty($orderInfo)) {
                return true;
            } else {
                return false;
            }
        } else {
            return Common::statusInfo('1002');
        }
    }

    /**
     * 获取活动基本信息
     * @param int $activity_id
     * @return array
     */
    private function _getActivityInfo($activity_id=0) {
        $activityInfo = [];
        if (!empty($activity_id)) {
            $m_activity = new CrmActivity();
            $activity_where = [];
            $activity_where['id'] = $activity_id;
            $activity_fields = 'id,type,start_time,end_time,status,pay_type,platform,new_user_site';
            $activityInfo = $m_activity->getInfo($activity_where,true,$activity_fields);
        }
        return $activityInfo;
    }

    /**
     * 获取活动商品信息
     * @param int $activity_id
     * @param int $goods_id
     * @return array
     */
    private function _getActivityProductInfo($activity_id=0,$goods_id=0)
    {
        $activityProductInfo =  [];
        if (!empty($activity_id) && !empty($goods_id)) {
            $m_activity_product = new CrmActivityProduct();
            $activity_product_where = [];
            $activity_product_where['activity_id'] = $activity_id;
            $activity_product_where['product_id']  = $goods_id;
            $activity_product_fields = 'id,activity_id,product_id,start_time,end_time,day_confine_num,price';
            $activityProductInfo = $m_activity_product->getInfo($activity_product_where,true,$activity_product_fields);
        }
        return $activityProductInfo;
    }
    /**
     * 获取活动订单日志数量
     * @param int $activity_id 活动ID
     * @param int $goods_id    商品ID
     * @return array
     */
    private function _getActivityOrderLog($activity_id=0,$goods_id=0){
        if (!empty($activity_id) && !empty($goods_id)) {
            $time_start = date('Y-m-d',time()).' 00:00:00';
            $now = date('Y-m-d H:i:s',time());
            $m_activity_order_log = new CrmActivityOrderLog();
            $activity_order_log_where = [];
            $activity_order_log_where['activity_id'] = $activity_id;
            $activity_order_log_where['product_id']  = $goods_id;
            $activity_order_log_where['type']        = '0';
            $activity_order_log_and_where = 'create_time > '.$time_start.' and create_time < '.$now;
            $activity_order_log_fields = 'id,num';
            $activityOrderLogInfo = $m_activity_order_log->getInfo($activity_order_log_where,true,$activity_order_log_fields,$activity_order_log_and_where);
            if (!empty($activityOrderLogInfo)) {
                $all_num = 0;
                foreach($activityOrderLogInfo as $k => $v) {
                    $all_num += $v['num'];
                }
                $data['all_num'] = $all_num;
                return Common::statusInfo('200',$data);
            } else {
                $data['all_num'] = 0;
                return Common::statusInfo('200',$data);
            }
        } else {
            return Common::statusInfo('4012');
        }
    }
}