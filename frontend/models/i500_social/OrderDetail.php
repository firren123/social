<?php
/**
 * 订单详情表
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
 * 订单详情表
 *
 * @category MODEL
 * @package  Social
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class OrderDetail extends SocialBase
{
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%i500_order_detail}}';
    }

    /**
     * 插入订单详情数据
     * @param array $order_detail 商品输数据
     * @return int
     */
    public function insertDetail($order_detail)
    {
        if (!empty($order_detail)) {
            $fields = [
                'order_sn',
                'mobile','shop_id',
                'product_id',
                'product_name',
                'product_img',
                'num',
                'price',
                'attribute_str',
                'total',
                'remark',
                'retread_num',
                'goods_type',
                'activity_id',
                'is_gift'
            ];

            $re = self::getDB()->createCommand()->batchInsert('order_detail', $fields, $order_detail)->execute();
            return $re;
        }
    }
}
