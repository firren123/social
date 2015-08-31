<?php
/**
 * 活动赠品表
 *
 * PHP Version 5
 * 活动赠品表
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/28 下午3:35 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\models\shop;

/**
 * 商家活动赠品
 *
 * @category MODEL
 * @package  Social
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class ActivityGift extends ShopBase
{
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_activity_gift}}';
    }
}