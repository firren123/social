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
 * @time      15/8/26 下午3:31 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\models\shop;

/**
 * 商家活动
 *
 * @category MODEL
 * @package  Social
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class ActivityGoods extends ShopBase
{
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_activity_product}}';
    }
    /**
     * 减去库存
     * @param array  $map  查询条件
     * @param string $type 减or 加
     * @return bool
     */
    public function editNumber($map, $type = '-')
    {
        $model = $this->findOne($map);
        if ($type == '-') {
            $model->day_confine_num = $model->day_confine_num - 1;
        } elseif ($type == '+') {
            $model->day_confine_num = $model->day_confine_num + 1;
        }
        $re = $model->save();
        return $re;
    }
}