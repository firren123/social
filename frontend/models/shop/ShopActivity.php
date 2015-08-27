<?php
/**
 * 商家活动相关
 *
 * PHP Version 5
 * 商家活动相关
 *
 * @category  I500M
 * @package   Social
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/8/26 下午2:03 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\models\shop;
use frontend\models\i500m\Product;
use yii\helpers\ArrayHelper;

/**
 * 商家活动
 *
 * @category MODEL
 * @package  Social
 * @author   renyineng <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class ShopActivity extends ShopBase
{
    /**
     * 获取正在进行的活动
     * @param int $shop_id 商家id
     * @return array
     */
    public function getCurrentActivity($shop_id)
    {
        $map['shop_id'] = $shop_id;
        $map['status'] = 1;
        $time = date("Y-m-d H:i:s", time());
        $activity = $this->find()->select('id,name,shop_id,images')
            ->where($map)
            ->andWhere(['<', 'start_time', $time])
            ->andWhere(['>', 'end_time', $time])
            ->asArray()->all();
        return $activity;
    }

    /**
     * 获取正在进行的活动
     * @param int $shop_id 商家id
     * @param int $type    1买赠 2满赠3限购
     * @return array
     */
    public function getPurchaseGoods($shop_id, $type = 1)
    {
        $map['shop_id'] = $shop_id;
        $map['status'] = 1;
        $map['type'] = $type;
        $time = date("Y-m-d H:i:s", time());
        $activity = $this->find()->select('id,name,shop_id')
            ->where($map)
            ->andWhere(['<', 'start_time', $time])
            ->andWhere(['>', 'end_time', $time])
            ->asArray()->all();
        //return $activity;
        $activity_ids = $activity_products = $product_ids = [];
        if (!empty($activity)) {
            $img_path = \Yii::$app->params['imgHost'];
            if (substr($img_path, -1) == '/') {
                $img_path = substr($img_path, 0, -1);
            }
            foreach ($activity as $k => $v) {
                $activity_ids[] = $v['id'];
            }
            $a_model = new ActivityGoods();
            $activity_products = $a_model->getList(['activity_id'=>$activity_ids, 'shop_id'=>$shop_id]);
            if (!empty($activity_products)) {
                foreach ($activity_products as $k => $v) {
                    $product_ids[] = $v['product_id'];
                }
                $g_model = new Product();
                //var_dump($product_ids);
                $goods = $g_model->getList(['id'=>$product_ids], 'id, name, image');
                //var_dump($goods);
                $goods = ArrayHelper::index($goods, 'id');
                foreach ($activity_products as $k => $v) {
                    $activity_products[$k]['name'] = ArrayHelper::getValue($goods, $v['product_id'].'.name');
                    $activity_products[$k]['image'] = $img_path . ArrayHelper::getValue($goods, $v['product_id'].'.image');
                }
            }

        }
        return $activity_products;
    }

    /**
     * 获取商家活动
     * @param int $shop_id 商家id
     * @return array
     */
    public function getActivityName($shop_id)
    {
        $type = $this->find()->select('type,name')->where(['shop_id'=>$shop_id])->groupBy('type')->asArray()->all();
        return $type;
    }
}