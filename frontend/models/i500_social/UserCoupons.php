<?php
/**
 * 用户优惠券表
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

/**
 * 用户优惠券表
 *
 * @category MODEL
 * @package  Social
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class UserCoupons extends SocialBase
{
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%i500_user_coupons}}';
    }

    /**
     * 获取最大的没有过期的优惠劵 并且符合使用条件
     * @param string $mobile 手机号
     * @param float  $total  总金额
     * @return array
     */
    public function getMaxCoupon($mobile, $total)
    {
        $map['mobile'] = $mobile;
        $map['status'] = 0;
        $time = date("Y-m-d H:i:s");
        //$andMap = ['>', 'start_time', date("Y-m-d H:i:s")];
        $andMap = ['>', 'expired_time', date("Y-m-d H:i:s")];//过期时间大于当前时间

//        $max = $this->find()
//            ->select('serial_number,min_amount,type_name,min_amount,')
//            ->where($map)->andWhere($andMap)->andWhere(['<=', 'min_amount', $total])->asArray()->one();
//        $max = $this->find()->where($map)->andWhere($andMap)->andWhere(['<=', 'min_amount', $total])->max('par_value');
        $list = $this->find()->select('id,par_value')
            ->where($map)->andWhere($andMap)->andWhere(['<=', 'min_amount', $total])->orderBy("par_value desc")
            ->asArray()->one();

        //$max = !empty($max) ? $max : 0;
        if (!empty($list)) {
            return $list;
        }
        return [];
            //->asArray()->all();
    }
    public function checkCoupon($coupon_id, $total) {
        if (empty($coupon_id)) {
            return false;
        }
        $coupon = $this->getInfo(['id'=>$coupon_id], 'serial_number,min_amount,type_name,min_amount,expired_time');
        if (!empty($coupon)) {
            if ($coupon['expired_time'] > date("Y-m-d H:i:s") && $coupon['status'] == 0 && $coupon['min_amount'] <= $total) {
                return $coupon['par_value'];
            }
        } else {
            return false;
        }
    }


}
