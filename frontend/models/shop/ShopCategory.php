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
 * @time      15/8/26 下午5:07 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\models\shop;
use frontend\models\i500m\Category;

/**
 * 商家活动
 *
 * @category MODEL
 * @package  Social
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class ShopCategory extends ShopBase
{
    /**
     * 设置表名称
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_category}}';
    }

    public function getCategory($shop_id)
    {
        $shop_category = $this->getList(['shop_id'=>$shop_id]);
        $category_ids = [];
        foreach ($shop_category as $k => $v) {
            $category_ids[] = $v['cate_first_id'];
        }
       // var_dump($category_ids);
        $category = new Category();
        $item = $category->getList(['id'=>$category_ids], 'id,name');
        return $item;
    }
}