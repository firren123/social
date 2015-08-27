<?php
/**
 * 商品
 *
 * PHP Version 5
 *
 * @category  Social
 * @package   Goods
 * @author    renyineng<renyineng@iyangpin.com>
 * @time      2015/8/25
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace frontend\modules\v1\controllers;


use frontend\models\i500m\Product;
use frontend\models\shop\ActivityGoods;
use frontend\models\shop\ShopActivity;
use frontend\models\shop\ShopProducts;
use Yii;
use common\helpers\Common;
use common\helpers\CurlHelper;
use common\helpers\SsdbHelper;
use common\helpers\RequestHelper;
use yii\helpers\ArrayHelper;

/**
 * Goods
 *
 * @category Social
 * @package  Shop
 * @author   linxinliang <renyineng@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     renyineng@iyangpin.com
 */
class GoodsController extends BaseController
{
    public $enableCsrfValidation = false;
    public $pageSize = 6;

    /**
     * 商品列表
     * @return json
     */
    public function actionIndex()
    {
        $cat_id = RequestHelper::get('cat_id', 0, 'intval');
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        if (empty($cat_id)) {
            $this->returnJsonMsg(102, [], '分类id必须!');
        }
        if (empty($this->shop_id)) {
            $this->returnJsonMsg(103, [], '商家id无效!');
        }
        $shop_models = new ShopProducts();
        $map = ['shop_id'=>$this->shop_id, 'cat_id'=>$cat_id, 'status'=>1];
        $data_list = $shop_models->getGoodsList($map, 'product_id,price,product_number,status', $this->pageSize);
        //var_dump($data_list);exit();
        $goods_id = $goods_list = $goods = $data_goods = $shop_products = [];
        if (!empty($data_list['list'])) {
            foreach ($data_list['list'] as $k => $v) {
                $goods_id[] = $v['product_id'];
                //$shop_products[$v['product_id']] = $v;
            }
            //var_dump($shop_products);
            if (!empty($goods_id)) {
                $models = new Product();
                $goods = $models->getList(['id'=>$goods_id, 'status'=>1], 'id,name,image,bar_code,attr_value');
            }
            if (!empty($goods)) {
                foreach ($goods as $k => $v) {
                    $data_goods[$v['id']] = $v;
                }
            }
            //获取商家正在进行的限购的商品
            $a_model = new ShopActivity();
            $activity_goods = $a_model->getPurchaseGoods($this->shop_id, 3);

            $activity_goods = ArrayHelper::index($activity_goods, 'product_id');
            $img_path = Yii::$app->params['imgHost'];
            if (substr($img_path, -1) == '/') {
                $img_path = substr($img_path, 0, -1);
            }

            foreach ($data_list['list'] as $k => $v) {
                $data_list['list'][$k]['name'] = ArrayHelper::getValue($goods, $k.'.name', '');
                $data_list['list'][$k]['image'] = $img_path . ArrayHelper::getValue($goods, $k.'.image', '');
                $data_list['list'][$k]['purchase_num'] = ArrayHelper::getValue($activity_goods, $k.'.day_confine_num', 0);

            }
            //var_dump($data_list);exit();
            $item = ArrayHelper::getValue($data_list, 'list', []);
            $pageCount = ArrayHelper::getValue($data_list, 'pageCount', 0);
            $count = ArrayHelper::getValue($data_list, 'count', 0);
            $this->returnJsonMsg(200, ['item'=>$item, 'pageCount'=>$pageCount, 'count'=>$count], '获取成功');

        } else {
            $this->returnJsonMsg(101, [], '无商品!');
        }

    }

    /**
     * 商品详情
     * @return array
     */
    public function actionDetail()
    {

    }

    /**
     * 获取热门推荐
     * @return json
     */
    public function actionHot()
    {
        $model = new Product();
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $list = $model->getList(['is_hot'=>1, 'single'=>1, 'status'=>1], 'id,name,image');
        $new_list = $goods_list = [];
        if (!empty($list)) {
            $goods_id = [];
            foreach ($list as $k => $v) {
                $goods_id[] = $v['id'];
                $new_list[$v['id']] = $v;
            }
            $img_path = Yii::$app->params['imgHost'];
            if (substr($img_path, -1) == '/') {
                $img_path = substr($img_path, 0, -1);
            }
            if (!empty($goods_id)) {
                $shop_model = new ShopProducts();
                $goods_list = $shop_model->getList(['product_id'=>$goods_id, 'shop_id'=>$this->shop_id, 'status'=>1], 'product_id,product_number,price');
                if (!empty($goods_list)) {
                    foreach ($goods_list as $k => $v) {
                        $goods_list[$k]['name'] =  ArrayHelper::getValue($new_list, $v['product_id'].'.name', '');
                        $goods_list[$k]['image'] = $img_path . ArrayHelper::getValue($new_list, $v['product_id'].'.image', '');
                    }
                }
            }
        }
        $this->returnJsonMsg(200, $goods_list, 'SUCCESS');
    }

    /**
     * 根据商家id 和活动类型 获取正在进行的所有活动
     * @return json
     */
    public function actionActivity()
    {
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $type = RequestHelper::get('type', 0, 'intval');
        $activity = new ShopActivity();
        if (empty($this->shop_id)) {
            $this->returnJsonMsg(101, [], '无效的商家id!');
        }
        if (empty($type)) {
            $this->returnJsonMsg(102, [], '无效的活动类型!');
        }
        $goods = $activity->getPurchaseGoods($this->shop_id, $type);
        $this->returnJsonMsg(200, $goods, 'SUCCESS');
       // var_dump($goods);
    }

    public function actionBanner()
    {
        $this->shop_id = RequestHelper::get('shop_id', 0, 'intval');
        $model = new ShopActivity();
        $data = $model->getCurrentActivity($this->shop_id);
        $img_path = Yii::$app->params['imgHost'];
        if (substr($img_path, -1) == '/') {
            $img_path = substr($img_path, 0, -1);
        }
        $banner = [];
        foreach ($data as $k => $v) {
            $banner[$k] = [
                'image'=>$img_path . $v['images']
            ];
        }

        $this->returnJsonMsg(200, $banner, 'SUCCESS');
    }
}
